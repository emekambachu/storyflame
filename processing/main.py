from typing import Dict, List, Optional

from dotenv import load_dotenv
from fastapi import FastAPI
import langchain
from langchain_core.output_parsers import PydanticOutputParser
from langchain_openai import ChatOpenAI
from pydantic import BaseModel, create_model
from langchain_core.prompts import PromptTemplate, ChatPromptTemplate
from langchain_core.pydantic_v1 import Field, BaseModel as LangChainBaseModel
from langchain_core.messages import AIMessage
from langchain_core.runnables import RunnablePassthrough
from openai import OpenAI

from processing.parser.convert import convert

app = FastAPI()

load_dotenv()


@app.get("/")
def read_root():
    return {"foo": "baz"}


class TranscribeRequest(BaseModel):
    path: str


@app.post("/transcribe")
def transcribe(params: TranscribeRequest):
    audio_file = open(params.path, "rb")
    client = OpenAI()
    transcription = client.audio.transcriptions.create(
        model="whisper-1",
        file=audio_file
    )
    return transcription.text

    # # check if file exists

    # model = WhisperModel("tiny.en", device="cpu", compute_type="float32")

    # print(f"Transcribing the audio file at: {params.path}")
    # segments, info = model.transcribe(params.path, beam_size=5)
    # print("Detected language '%s' with probability %f" %
    #       (info.language, info.language_probability))

    # return [
    #     {
    #         "start": segment.start,
    #         "end": segment.end,
    #         "text": segment.text
    #     }
    #     for segment in segments
    # ]


class OutputFormatModel(LangChainBaseModel):
    pass
    # error: Optional[str] = Field(
    #     default=None, title="Error", description="If you cant extract the property, create a question to ask the user to provide the information.")


def evaluate_answers(
    properties: dict, question: str, answer: str
) -> List[str]:
    """
    Function to evaluate the user's answers to the question
    and provide the confidence level of the user providing the information
    on the scale [unlikely, unsure, likely, most likely].
    """
    dict_prop_name_to_key = {
        value['name']: key for key, value in properties.items()}

    llm = ChatOpenAI(model="gpt-3.5-turbo", temperature=0)

    prompt = PromptTemplate(
        template="You are an expert analysis algorithm. "
                 "From the user response to a question, tell us how likely the user provided the information we need. "
                 "For each property in the list below, provide the confidence level of the user providing the "
                 "information on the scale [unlikely, unsure, likely, most likely]."
                 "\n#List of properties: {properties}."
                 "\n#Question to user: {question}."
                 "\n#User answer: {message}."
                 "\n#Example: If the user mentioned the property 'name' with high confidence, set 'name' to most "
                 "likely. If the user did not mention the property 'name', set 'name' to unlikely. If the user "
                 "mentioned the property 'name' but you are not sure, set 'name' to unsure."
                 "\n#Output Format: Property name: Confidence level\nOther property name: Confidence level",
        input_variables=["properties", "message"]
    )

    chain = prompt | llm

    print(
        "Evaluating response",
        question,
        answer,
    )

    response = chain.invoke({
        # give the list of property names only
        "properties": [
            value['name'] + (' (Examples: ' + ', '.join(value['examples']) + ')' if 'examples' in value else '')
            for key, value
            in properties.items()],
        "question": question,
        "message": answer,
    })

    # if content is not string, return empty dict
    if not isinstance(response.content, str):
        print("Ai gave invalid response", response.content)
        return []

    if response.content == "":
        print("Ai gave empty response")
        return []

    print("Ai response", response.content)

    # parse the response
    return [
        dict_prop_name_to_key[prop[0]]
        for prop in (prop.split(":") for prop in response.content.split("\n") if prop.strip() != "" and ":" in prop)
        if prop[0].strip() in dict_prop_name_to_key and
           prop[1].strip().lower() in ["likely", "most likely"]
    ]


def extract_properties(
    evaluated_properties: List[str], question: str, answer: str, properties_config: dict
) -> Dict[str, List[str] or str]:
    """
    Function to extract the properties from the user response to question
    """
    if len(evaluated_properties) == 0:
        print("No properties found to extract.")
        return {}

    evaluated_properties_config = {
        key: value for key, value in properties_config.items() if key in evaluated_properties
    }
    print("Evaluated properties", evaluated_properties_config)

    # create new pydantic model for the output parsing
    output_parsing_model = create_model(
        "ExtractionOutput",
        **{
            key: (Optional[List[str]] if value['type'] == "array" else Optional[str],
                  Field(title=value['name'], description=value['description']))
            for key, value in evaluated_properties_config.items()
        }
    )

    parser = PydanticOutputParser(pydantic_object=output_parsing_model)

    extraction_prompt = PromptTemplate(
        template="You are an expert extraction algorithm. "
                 "You need to extract following properties from user response on a writing platform to a question. "
                 "All properties should be extracted from the user response. "
                 "If you can't extract the property, set it to null."
                 "\n#Properties to extract: {properties}."
                 "\n#What user been asked: {question}."
                 "\n#User answer to question: {message}."
                 "\n{format_instructions}",
        input_variables=["properties", "question", "message"],
        partial_variables={
            "format_instructions": parser.get_format_instructions()
        }
    )

    llm = ChatOpenAI(model="gpt-3.5-turbo", temperature=0)

    runnable = extraction_prompt | llm | parser

    return runnable.invoke({
        "properties": [value['name'] for key, value in evaluated_properties_config.items()],
        "question": question,
        "message": answer,
    })


class ExtractRequest(BaseModel):
    question: str
    message: str
    data: dict
    available_properties: dict


@app.post("/onboarding/extract")
def extract(params: ExtractRequest):
    langchain.debug = True

    evaluated = evaluate_answers(
        params.available_properties,
        params.question,
        params.message
    )

    return extract_properties(
        evaluated,
        params.question,
        params.message,
        params.available_properties
    )


class ChatHistoryMessage(BaseModel):
    agent: str
    text: str


class OnboardingRequest(BaseModel):
    type: str
    history: List[ChatHistoryMessage]
    data: List[dict]


class OnboardingResponse(BaseModel):
    question: str = Field(
        title="Question", description="The question to ask the user to extract the data."
    )
    message: str = Field(
        title="Message",
        description="Encouragement message to provide to the user after they provide the information."
    )


class OnboardingResponseMultipleChoice(OnboardingResponse):
    options: List[str] = Field(
        title="Options", description="The options to provide to the user."
    )


@app.post("/onboarding/question")
def onboarding(params: OnboardingRequest):
    llm = ChatOpenAI(model="gpt-3.5-turbo", temperature=0.1)
    langchain.debug = True

    print(params.history[-10:])
    print("Type of question", params.type)
    print("Generating question for data", params.data)

    prompt = ChatPromptTemplate.from_messages(
        [
            (
                "system",
                "You are onboarding chat-bot for a writing platform "
                "that keeps an engaging dialog with the user to get the information we need."
                "Inside of message field, you need to provide user your understanding of their message."
                "Great examples of message are: 'Nice, Star Wars. So, you like sci-fi.', 'I see, you like fantasy.', "
                "'Interesting, you like to write about characters with superpowers.', etc."
                "Good examples of questions are: 'What type of characters do you like to write about?', 'What's your "
                "favorite genre?', etc."
                "You never ask the same question twice."
                "You need to create creative and engaging responses to the user's message."
            ),
            *[
                (
                    history.agent,
                    history.text
                )
                for history in params.history[-6:]
            ],
            (
                "system",
                "Ask the user a question based on the data we need to extract from them."
                "Data to ask for: {data}."
                "Instructions: {question_instructions}."
                "Output Format: {format_instructions}."
            )
        ]
    )

    runnable = prompt | llm.with_structured_output(
        schema=OnboardingResponseMultipleChoice if params.type == "multiple_choice" else OnboardingResponse)

    response = runnable.invoke({
        "data": params.data,
        "question_instructions": "You need to ask the user a question based on data we need to extract from them"
                                 "and provide them with 4 options to choose from."
                                 "Make sure you provide them options that are relevant to the user."
                                 "Important that you dont use repetitive options or options "
                                 "like 'I don't know', 'Other', "
                                 "'None of the above'."
        if params.type == "multiple_choice"
        else "You need to ask the user open ended question based on data we need to extract from them."
             "Group the data into one open ended question, keep it short and simple."
             "While you will be looking to craft a question that will get the user to provide the "
             "information we need,"
             "do not ask them directly for the list of data we need."
             "Try to ask them a creative question that will get them to provide the information we need.",
        "format_instructions": "{'question': string,"
                               " 'message': string, "
                               "'options': array of strings}"
        if params.type == "multiple_choice"
        else "{'question': string, "
             "'message': string}"
    })

    print(response)

    return response


class GenerateDetailsRequest(BaseModel):
    history: List[ChatHistoryMessage]


class GenerateDetailsResponse(BaseModel):
    details: str = Field(
        title="Details", description="The generated details for the user."
    )


@app.post("/onboarding/generate/details")
def generate_details(request: GenerateDetailsRequest):
    llm = ChatOpenAI(model="gpt-4o", temperature=0.1)
    langchain.debug = True

    prompt = ChatPromptTemplate.from_messages(
        [
            *[
                (
                    history.agent,
                    history.text
                )
                for history in request.history[-6:]
            ],
            (
                "system",
                "Based on the conversation, between the writer and the chat-bot, "
                "create a writer's bio for the writer's platform."
                "Write from the perspective of the writer, in the first person."
                "The bio should be 3-4 sentences long and be useful for producers to understand who the writer is."
                "Do not make up any information, only use the information provided in the conversation."
                "Do not embellish the information, only use the information provided."
                "Do not discuss the quality of their writing."
                "Output Format: {{'details': string}}"
            )
        ]
    )

    runnable = prompt | llm.with_structured_output(schema=GenerateDetailsResponse)

    response = runnable.invoke({})

    return response


@app.post("/script/parse")
def parse_script(request: dict):
    try:
        with open(request['path'], 'rb') as file:
            return convert(file, 0)
    except Exception as e:
        return {"error": str(e)}
