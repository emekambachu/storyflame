from typing import Optional, List

from langchain_core.output_parsers import StrOutputParser, PydanticOutputParser, JsonOutputParser
from langchain_core.prompts import PromptTemplate
from langchain_openai import ChatOpenAI
from pydantic import BaseModel, Field


class OnboardingResponse(BaseModel):
    message: str = Field(
        title="Message",
        description="Encouragement message to provide to the user after they provide the information."
    )
    question: str = Field(
        title="Question", description="The question to ask the user to extract the data."
    )


class OnboardingResponseMultipleChoice(OnboardingResponse):
    options: List[str] = Field(
        title="Options", description="The options to provide to the user."
    )


def onboarding_template():
    return (
        "You are a smart helper chatbot for a writing platform that engages the user in a conversation to extract "
        "information about their writing interests and preferences for a writing platform"
        " You need to craft a {task}"
        "Inside of message field, you need to provide user your understanding of their answer to previous question."
        "Great examples of message are: 'Nice, Star Wars. So, you like sci-fi.', 'I see, you like fantasy.', "
        "'Interesting, you like to write about characters with superpowers.', etc."
        "Good examples of questions are: 'What type of characters do you like to write about?', 'What's your "
        "favorite genre?', etc."
        "# Question: {question} "
        "# User answer: {answer} "
        "# Topics to ask next: {topics} "
        "# Important instructions: message field should never include any questions. "
        "{format_instructions}"
    )


def story_template():
    return (
        "You are a smart helper chatbot for a writing platform "
        "that keeps an engaging dialog with the user to help them write a story"
        " Use chat history to craft a {task} based on chat history."
        "Inside of message field, you need to provide user your understanding of their message."
        "Great examples of message are: 'Nice, Star Wars. So, you like sci-fi.', 'I see, you like fantasy.', "
        "'Interesting, you like to write about characters with superpowers.', etc."
        "Good examples of questions are: 'What type of characters do you like to write about?', 'What's your "
        "favorite genre?', etc."
        "# Chat history: {chat_history} "
        "# Topics to ask next: {topics} "
        "# Important instructions: message field should never include any questions. "
        "{format_instructions}"
    )


def base_chain(engine: str, task: str, task_topics: str, multiple_choice: bool = False):
    parser = JsonOutputParser(
        pydantic_object=OnboardingResponseMultipleChoice if multiple_choice else OnboardingResponse)

    if engine == 'onboarding':
        template = onboarding_template()
    elif engine == 'story':
        template = story_template()
    else:
        raise ValueError("Invalid engine " + engine)

    prompt = PromptTemplate(
        template=template,
        input_variables=["chat_history", "topics"],
        partial_variables={
            "task": task,
            "format_instructions": parser.get_format_instructions(),
        },
    )

    model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=1)

    chain = prompt | model | parser

    return chain


def generate_short_open_ended_question(engine: str):
    return base_chain(
        engine,
        "short open ended question that is easy to understand based on chat that is easy to understand "
        "history to get more information about provided topics.",
        "Topics to ask next"
    )


def generate_follow_up_question(engine: str):
    return base_chain(
        engine,
        "follow-up question for the user to better understand the question they asked earlier, question should be "
        "different from previous to help user think from different perspective.",
        "Topics discussed"
    )


def generate_brainstorming_question(engine: str):
    return base_chain(
        engine,
        "brainstorming question to help the user think of new ideas about topics discussed earlier, question should "
        "be different from previous to help user think from different perspective.",
        "Topics discussed"
    )


def generate_next_question(engine: str, question_type: Optional[str]):
    print(question_type)
    if not question_type or question_type == "basic":
        return generate_short_open_ended_question(engine)
    if question_type == "follow-up":
        return generate_follow_up_question(engine)
    if question_type == "brainstorm":
        return generate_brainstorming_question(engine)
    return generate_short_open_ended_question(engine)


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
