from typing import Dict, List, Optional

from dotenv import load_dotenv
from fastapi import FastAPI
import langchain
from langchain_openai import ChatOpenAI
from pydantic import BaseModel
from langchain_core.prompts import PromptTemplate, ChatPromptTemplate
from langchain_core.pydantic_v1 import Field, BaseModel as LangChainBaseModel
from langchain_core.messages import AIMessage
from langchain_core.runnables import RunnablePassthrough
from openai import OpenAI

app = FastAPI()

load_dotenv()

# os.environ["OPENAI_API_KEY"] = getpass.getpass()


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


class ExtractRequest(BaseModel):
    question: str
    message: str
    data: dict
    available_properties: dict


class OutputFormatModel(LangChainBaseModel):
    pass
    # error: Optional[str] = Field(
    #     default=None, title="Error", description="If you cant extract the property, create a question to ask the user to provide the information.")


@app.post("/onboarding/extract")
def extract(params: ExtractRequest):
    langchain.debug = True
    llm = ChatOpenAI(model="gpt-3.5-turbo")

    print(params.message)

    dict_prop_name_to_key = {
        value['name']: key for key, value in params.available_properties.items()}

    prompt = PromptTemplate(
        template="You are an expert analysis algorithm. "
        "From the user response to a question, tell us how likely the user provided the information we need. "
        "For each property in the list below, provide the confidence level of the user providing the information on the scale [unlikely, unsure, likely, most likely]."
        "\n#List of properties: {properties}."
        "\n#Question: {question}."
        "\n#User answer: {message}."
        "\n#Example: If the user mentioned the property 'name' with high confidence, set 'name' to most likely. If the user did not mention the property 'name', set 'name' to unlikely. If the user mentioned the property 'name' but you are not sure, set 'name' to unsure."
        "\n#Output Format: Property name: Confidence level\nOther property name: Confidence level",
        input_variables=["properties", "message"]
    )

    def parse(ai_message: AIMessage):
        properties = ai_message.content.split("\n")
        # Use generator expression instead of list comprehension
        properties = (prop.split(":") for prop in properties)
        properties = {
            dict_prop_name_to_key[prop[0]]: prop[1].strip()
            for prop in properties
        }
        return properties

    def top_k(properties: Dict[str, str]):
        return [
            {
                "name": params.available_properties[key]['name'],
                "examples": params.available_properties[key]['examples'] if 'examples' in params.available_properties[key] else [],
                "description": params.available_properties[key]['description'],
            }
            for key, value in properties.items()
            if value.lower() == 'most likely' or value.lower() == 'likely'
        ]

    def check_number_of_properties(properties: Dict[str, str]):
        if len(properties) == 0:
            # llm found no properties to extract
            raise ValueError("No properties found to extract.")
        return properties

    rating_chain = prompt | llm | parse | top_k | check_number_of_properties

    # response = rating_chain.invoke({
    #     "properties": [value['name'] for key, value in params.available_properties.items()],
    #     "question": params.question,
    #     "message": params.message,
    # })

    def extract_parse(ai_message: AIMessage):
        properties = ai_message.content.split("\n")
        # Use generator expression instead of list comprehension
        properties = (prop.split(":") for prop in properties)
        properties = {
            dict_prop_name_to_key[prop[0]]: prop[1].strip().replace("'", "")
            if not prop[1].strip().startswith("[")
            else [
                val.strip() for val in prop[1].strip().replace("[", "").replace("]", "").split(",")
            ]
            for prop in properties
        }
        return properties

    extraction_prompt = PromptTemplate(
        template="You are an expert extraction algorithm. "
        "You need to extract following properties from user response on a writing platform to a question. "
        "All properties should be extracted from the user response. "
        "If you can't extract the property, set it to null."
        "\n#Properties to extract: {properties}."
        "\n#What user been asked: {question}."
        "\n#User answer: {message}."
        "\n#Output Format: Property name: Value\nMulti value property name: [Value1, Value2]",
        input_variables=["properties", "question", "message"],
    )

    full_chain = RunnablePassthrough.assign(
        properties=rating_chain) | extraction_prompt | llm | extract_parse

    # extraction_response = extraction_chain.invoke({
    #     "properties": [
    #         params.available_properties[key]['name']
    #         for key, value in response.items() if value >= 5],
    #     "question": params.question,
    #     "message": params.message,
    # })

    try:
        response = full_chain.invoke({
            "properties": [value['name'] for key, value in params.available_properties.items()],
            "question": params.question,
            "message": params.message,
        })
    except ValueError as e:
        response = {}

    return response


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


class OnboardingResponseMultipleChoise(OnboardingResponse):
    options: List[str] = Field(
        title="Options", description="The options to provide to the user."
    )


@app.post("/onboarding/question")
def onboarding(params: OnboardingRequest):
    llm = ChatOpenAI(model="gpt-3.5-turbo", temperature=0.2)

    prompt = ChatPromptTemplate.from_messages(
        [
            (
                "system",
                "You are an expert in onboarding new users." +
                (
                    (
                        "You need to ask the user a question based on data we need to extract from them"
                        "and provide them with 4 options to choose from."
                        "Make sure you provide them options that are relevant to the user."
                        "Important that you dont use repetitive options or options like 'I don't know', 'Other', 'None of the above'."
                    )
                    if params.type == "multiple_choice"
                    else ("You need to ask the user open ended question based on data we need to extract from them."
                          "Group the data into one open ended question, keep it short and simple."
                          "While you will be looking to craft a question that will get the user to provide the information we need, "
                          "do not ask them directly for the list of data we need."
                          "Try to ask them a creative question that will get them to provide the information we need.")
                ) +
                "Do not ask them same question as before."
                "Use chat history to make creative and relevant questions."
                "#Data to extract: {data}."
            ),
            *[
                (
                    history.agent,
                    history.text
                )
                for history in params.history[-10:]
            ]
        ]
    )

    # prompt = PromptTemplate(
    #     template="You are an expert in onboarding new users."
    #     "You need to ask the user open ended question based on data we need to extract from them."
    #     "Group the data into one open ended question, keep it short and simple."
    #     "While you will be looking to craft a question that will get the user to provide the information we need, "
    #     "do not ask them directly for the list of data we need."
    #     "Try to ask them a creative question that will get them to provide the information we need."
    #     "Do not ask them same question as before."
    #     "#Previous question: {previous_question}."
    #     "#User information: {user}."
    #     "#Data to extract: {data}.",
    #     input_variables=["user", "data"]
    # )

    runnable = prompt | llm.with_structured_output(
        schema=OnboardingResponseMultipleChoise if params.type == "multiple_choice" else OnboardingResponse)

    response = runnable.invoke({
        "data": params.data
    })

    return response
