import pprint
from typing import Optional, List

from langchain_core.output_parsers import StrOutputParser, PydanticOutputParser, JsonOutputParser
from langchain_core.prompts import PromptTemplate, ChatPromptTemplate, SystemMessagePromptTemplate
from langchain_openai import ChatOpenAI
from pydantic import BaseModel, Field

from processing.models.DynamicModel import DynamicModel
from processing.parsing.xml_tag_parser import XMLOutputParser
from processing.templates.next_question import onboarding_engine_prompt, story_engine_prompt, basic_question_task, \
    followup_question_task, brainstorming_task, onboarding_engine_system_message, story_engine_system_message, \
    next_question_prompt
from processing.types.Messages import Message


class OnboardingResponse(BaseModel):
    brief_affirming_summarizing_active_listening_statement: str = Field(
        title="Brief Affirming Summarizing Active Listening Statement",
        description="A brief, affirming, summarizing, and active listening statement to show understanding and "
                    "encouragement to the user.",
    )
    question: str = Field(
        title="Question", description="The question to ask the user."
    )
    tooltip: str = Field(
        title="Tooltip", description="A tooltip to provide additional information or context to the user."
    )
    data_points: List[str] = Field(
        title="Data Points", description="Data points ids used to generate the question."
    )
    examples: List[str] = Field(
        title="Examples", description="Examples of the type of response expected from the user."
    )


class OnboardingResponseMultipleChoice(OnboardingResponse):
    options: List[str] = Field(
        title="Options", description="The options to provide to the user."
    )


def remap_response(response: dict):
    return {
        "title": response["brief_affirming_summarizing_active_listening_statement"],
        "question": response["question"],
        "tooltip": response["tooltip"],
        "examples": response["examples"],
        "data_points": response["data_points"],
    }


def base_chain(engine: str, task: str, history: List[Message], multiple_choice: bool = False):
    xml_parser = XMLOutputParser.from_tag("json_response")
    parser = JsonOutputParser(
        pydantic_object=OnboardingResponseMultipleChoice if multiple_choice else OnboardingResponse)

    if engine == 'Writer':
        template = onboarding_engine_system_message
    else:
        template = story_engine_system_message

    messages = [
        ("user", template),
        *[
            ("user" if message.agent == "user" else "assistant", message.content)
            for message in history[-10:]
        ],
        ("user", next_question_prompt)
    ]
    pprint.pprint(messages)

    prompt = ChatPromptTemplate.from_messages(
        messages
    )

    prompt = prompt.partial(task=task)
    prompt = prompt.partial(format_instructions=parser.get_format_instructions())
    prompt.input_variables = ["topics"]

    model = DynamicModel.default_generation()

    chain = prompt | model | xml_parser | parser | remap_response

    return chain


def generate_short_open_ended_question(engine: str, history: List[Message]):
    return base_chain(
        engine,
        basic_question_task,
        history
    )


def generate_follow_up_question(engine: str, history: List[Message]):
    return base_chain(
        engine,
        followup_question_task,
        history
    )


def generate_brainstorming_question(engine: str, history: List[Message]):
    return base_chain(
        engine,
        brainstorming_task,
        history
    )


def generate_next_question(engine: str, history: List[Message], question_type: Optional[str]):
    print(question_type)
    if not question_type or question_type == "basic":
        return generate_short_open_ended_question(engine, history)
    if question_type == "follow-up":
        return generate_follow_up_question(engine, history)
    if question_type == "brainstorm":
        return generate_brainstorming_question(engine, history)
    return generate_short_open_ended_question(engine, history)


def generate_title_chain():
    """
    Chain that generates a title for story based on the extracted data points.
    """
    prompt = PromptTemplate(
        template="You are given a list of key elements from a story, including characters, settings, main events, "
                 "and themes. Based on this data, create a compelling and intriguing title for the story. Title should "
                 "start from 'Untitled' and show the essence of the story. "
                 "Ensure the title reflects the essence and core of the narrative. Here are the extracted data points: "
                 "{extracted}."
                 "#Output Format: Title",
        input_variables=["extracted"],
    )

    model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=1)

    chain = prompt | model | StrOutputParser()

    return chain


def generate_bio_chain():
    """
    Chain that generates a bio for a user based on the extracted data points.
    """
    prompt = PromptTemplate(
        template="You are given a list of key elements from a user's profile for a writing platform, including "
                 "interests, writing style, and preferences. Based on this data, create a short bio for the user. "
                 "Bio should be engaging and informative, reflecting the user's personality and writing style. "
                 "Write from the perspective of the writer, in the first person."
                 "The bio should be 3-4 sentences long and be useful for producers to understand who the writer is."
                 "Do not make up any information, only use the information provided in the conversation."
                 "Do not embellish the information, only use the information provided."
                 "Do not discuss the quality of their writing."
                 "Here are the extracted data points: {extracted}."
                 "#Output Format: Bio",
        input_variables=["extracted"],
    )

    model = ChatOpenAI(model_name="gpt-4o", temperature=0.3)

    chain = prompt | model | StrOutputParser()

    return chain
