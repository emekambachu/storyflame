from typing import List, Dict

import langchain
from langchain_core.messages import AIMessage
from langchain_core.output_parsers import StrOutputParser
from langchain_core.output_parsers.openai_tools import PydanticToolsParser
from langchain_core.prompts import PromptTemplate, ChatPromptTemplate
from langchain_openai import ChatOpenAI
from pydantic import BaseModel, validator


def validate_format(field: str) -> str:
    if field not in ["unlikely", "unsure", "likely", "very likely"]:
        raise ValueError("Field should be one of [unlikely, unsure, likely, very likely]")
    return field


class AnswerEvaluation(BaseModel):
    """
    Evaluation of the answer to the question.
    """

    answer_rating: str
    topic_change: str
    is_skipped: str
    user_not_understand: str
    user_dont_know: str

    _validated_answer_rating = validator("answer_rating", allow_reuse=True)(validate_format)
    _validated_topic_change = validator("topic_change", allow_reuse=True)(validate_format)
    _validated_is_skipped = validator("is_skipped", allow_reuse=True)(validate_format)
    _validated_user_understood = validator("user_not_understand", allow_reuse=True)(validate_format)
    _validated_user_dont_know = validator("user_dont_know", allow_reuse=True)(validate_format)


def rate_response():
    """
    Rate a response based on the question and answer.
    Get following outputs:
    - answer_rating: rating of the answer to the question
    - topic_change: whether the answer changes the topic
    - is_skipped: whether user wants to skip the question
    """
    prompt = PromptTemplate(
        template="Act as evaluator and rate the user's response to the question."
                 "Based on the question and answer, give a rating to the answer for following criteria:"
                 "- Answer rating: rating of the answer to the question"
                 "- Topic change: whether the answer changes the topic"
                 "- Is skipped: whether user wants to skip the question"
                 "- User not understand: whether the user indicates that they don't understand the question"
                 "- User don't know: whether the user indicates that they don't know the answer"
                 "All ratings should be on a scale [unlikely, unsure, likely, very likely]."
                 "Question: {question}"
                 "Answer: {answer}",
        input_variables=["question", "answer"],
    )

    parser = PydanticToolsParser(tools=[AnswerEvaluation])
    # temperature=0 for deterministic outputs
    model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=0).bind_tools([AnswerEvaluation])

    chain = prompt | model | parser

    return chain


def parse_ratings(ai_message: AIMessage) -> List[str]:
    """
    Split the output into a dictionary.
    """
    print(ai_message.content)
    return [
        line.split(":")[0].strip()
        for line in ai_message.content.split("\n")
        if line.strip() and ":" in line and line.split(":")[1].strip().lower() in ["likely", "very likely"]
    ]


def rate_topics(limit: int = 3):
    """
    Rate how likely a topic is being discussed in the conversation.
    """
    prompt = PromptTemplate(
        template="You are an expert analysis and you are evaluating the conversation with a writer."
                 "Based on the response, rate how likely the topic is being discussed in the conversation."
                 "All ratings should be on a scale [unlikely, unsure, likely, very likely]. "
        # +
        # (
        #     f"Only rate the top {limit} topics that are most likely being discussed."
        #     if limit is not None
        #     else ""
        # ) +
                 "#Example: If the user mentioned anything about the topic 'plot' with high confidence, "
                 "set 'plot' to most likely. If the user did not mention the topic 'plot', "
                 "set 'plot' to unlikely. If the user mentioned the topic 'plot' but you are not sure, "
                 "set 'plot' to unsure."
                 "#Question: {question} "
                 "#Answer: {answer} "
                 "#List of topics to rate: {topics} "
                 "#Output Format: Property name: Confidence level\nOther property name: Confidence level",
        input_variables=["topics", "question", "answer"],
    )

    model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=0.1)

    # temperature=0 for deterministic outputs
    chain = prompt | model | parse_ratings

    return chain


def rate_data_points(limit: int = 6):
    prompt = PromptTemplate(
        template="You are an expert analysis algorithm. "
                 # "From the user response to a question, tell us how likely the user provided the information we need. "
                 "For each data point in the list below, rate how likely the user provided the information from the. "
                 "topics in the list."
                 "All ratings should be on a scale [unlikely, unsure, likely, very likely]. "
        # +
        # (
        #     f"Only rate the top {limit} data points that are most likely being discussed."
        #     if limit is not None
        #     else ""
        # ) +
        #          "#Example: If the user mentioned the property 'name' with high confidence, set 'name' to most "
        #          "likely. If the user did not mention the property 'name', set 'name' to unlikely. If the user "
        #          "mentioned the property 'name' but you are not sure, set 'name' to unsure."
        #          "#Question: {question} "
                 "#User message: {answer} "
                 "#List of data points to rate: {data_points} "
                 "#Output Format: Property name: Confidence level\nOther property name: Confidence level",
        input_variables=["data_points", "question", "answer"],
    )

    model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=0.1)

    chain = prompt | model | parse_ratings

    return chain


def rate_data_points_next():
    """
    Rate how likely a data point should be discussed next from the conversation history and available data points.
    """
    prompt = PromptTemplate(
        template="You are an expert analysis algorithm. "
                 "Based on the conversation history and the available topics to discuss, rate how likely the topic "
                 "should be discussed next."
                 "For each data point in the list below, rate how likely the data point should be discussed next. "
                 "All ratings should be on a scale [unlikely, unsure, likely, very likely]. "
                 "#Chat history: {chat_history} "
                 "#Available data points: {available_data_points} "
                 "#Output Format: Property name: Confidence level\nOther property name: Confidence level",
        input_variables=["chat_history", "available_data_points"],
    )

    model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=0.2)

    chain = prompt | model | parse_ratings

    return chain


def rate_generated_data(data_type: str):
    """
    Rate how likely the generated data (e.g. title, description, logline, etc.) is an accurate representation of the
    extracted data points.
    """
    prompt = PromptTemplate(
        template="You are an expert analysis algorithm. "
                 "Based on the extracted data points, rate how likely the generated {data_type} is an accurate "
                 "representation of the extracted data points."
                 "Rating should be on a scale [unlikely, unsure, likely, very likely], where 'unlikely' means the "
                 "generated {data_type} is not an accurate representation or contains invalid information,"
                 "'very likely' means the generated {data_type} is an accurate representation of the extracted data "
                 "points."
                 "#Extracted data points: {extracted} "
                 "#Generated {data_type}: {generated} "
                 "#Output Format: Confidence level",
        input_variables=["extracted", "generated"],
        partial_variables={"data_type": data_type}
    )

    model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=0.2)

    chain = prompt | model | StrOutputParser()

    return chain


def rate_same_value_probability():
    """
    Rate how likely the two values are the same.
    """
    prompt = PromptTemplate(
        template="You are an expert analysis algorithm. "
                 "Based on the two values, rate how likely the two values are the same but written differently."
                 "Rating should be on a scale [unlikely, unsure, likely, very likely], where 'unlikely' means the "
                 "two values are not the same,"
                 "'very likely' means the two values are the same but written differently."
                 "#Value 1: {value1} "
                 "#Value 2: {value2} "
                 "#Output Format: Confidence level",
        input_variables=["value1", "value2"]
    )

    model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=0.2)

    chain = prompt | model | StrOutputParser()

    return chain
