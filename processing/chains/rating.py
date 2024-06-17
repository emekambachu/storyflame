from typing import List, Dict

import langchain
from langchain_anthropic import ChatAnthropic
from langchain_core.messages import AIMessage
from langchain_core.output_parsers import StrOutputParser
from langchain_core.output_parsers.openai_tools import PydanticToolsParser
from langchain_core.prompts import PromptTemplate, ChatPromptTemplate
from langchain_openai import ChatOpenAI
from pydantic import BaseModel, validator

from processing.templates.rating import answer_rating_template
from processing.types.answer_rating import AnswerEvaluation


def rate_response_chain():
    """
    Rate a response based on the question and answer.
    """
    prompt = PromptTemplate(
        template=answer_rating_template,
        input_variables=["current_context"],
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
        if line.strip() and ":" in line and line.split(":")[1].strip().lower() in ["high confidence",
                                                                                   "very high confidence"]
    ]


def parse_ratings_new(ai_message: AIMessage) -> List[str]:
    print(ai_message.content)
    return [
        line.split(":")[0].strip()
        for line in ai_message.content.split("\n")
        if (line.strip()
            and ":" in line
            and line.split(":")[1].strip().lower() in ["high confidence",
                                                       "absolute confidence"])
    ]


def rate_topics(limit: int = 3):
    """
    Rate how high confidence a topic is being discussed in the conversation.
    """
    prompt = PromptTemplate(
        template="You are an expert analysis and you are evaluating the conversation with a writer."
                 "Based on the response, rate how high confidence the topic is being discussed in the conversation."
                 "{confidence_template}"
        #          "All ratings should be on a scale [no confidence, low confidence, high confidence, very high confidence]. "
        # # +
        # # (
        # #     f"Only rate the top {limit} topics that are absolute confidence being discussed."
        # #     if limit is not None
        # #     else ""
        # # ) +
        #          "#Example: If the user mentioned anything about the topic 'plot' with high confidence, "
        #          "set 'plot' to absolute confidence. If the user did not mention the topic 'plot', "
        #          "set 'plot' to no confidence. If the user mentioned the topic 'plot' but you are not sure, "
        #          "set 'plot' to low confidence."
                 "#Question: {question} "
                 "#Answer: {answer} "
                 "#List of topics to rate: {topics} "
                 "#Output Format: Property name: Confidence level\nOther property name: Confidence level",
        input_variables=["topics", "question", "answer"],
        partial_variables={"confidence_template": confidence_template}
    )

    model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=0.1)
    # model = ChatAnthropic(model="claude-3-haiku-20240307", temperature=0.1)

    # temperature=0 for deterministic outputs
    chain = prompt | model | parse_ratings_new

    return chain


def rate_data_points(limit: int = 6):
    prompt = PromptTemplate(
        template="You are an expert analysis algorithm. "
        # "From the user response to a question, tell us how high confidence the user provided the information we need. "
                 "For each data point in the list below, rate how high confidence the user provided the information from the. "
                 "topics in the list."
                 "{confidence_template}"
        # "All ratings should be on a scale [no confidence, low confidence, high confidence, very high confidence]. "
        # +
        # (
        #     f"Only rate the top {limit} data points that are absolute confidence being discussed."
        #     if limit is not None
        #     else ""
        # ) +
        #          "#Example: If the user mentioned the property 'name' with high confidence, set 'name' to most "
        #          "high confidence. If the user did not mention the property 'name', set 'name' to no confidence. If the user "
        #          "mentioned the property 'name' but you are not sure, set 'name' to low confidence."
        #          "#Question: {question} "
                 "#User message: {answer} "
                 "#List of data points to rate: {data_points} "
                 "#Output Format: Property name: Confidence level\nOther property name: Confidence level",
        input_variables=["data_points", "question", "answer"],
        partial_variables={"confidence_template": confidence_template}
    )

    model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=0.1)
    # model = ChatAnthropic(model="claude-3-haiku-20240307", temperature=0.1)
    # model = ChatGoogleGenerativeAI(model="gemini-1.0-pro")

    chain = prompt | model | parse_ratings_new

    return chain


def rate_data_points_next():
    """
    Rate how high confidence a data point should be discussed next from the conversation history and available data points.
    """
    prompt = PromptTemplate(
        template="You are an expert analysis algorithm. "
                 "Based on the conversation history and the available topics to discuss, rate how high confidence the topic "
                 "should be discussed next."
                 "For each data point in the list below, rate how high confidence the data point should be discussed next. "
                 "All ratings should be on a scale [no confidence, low confidence, high confidence, very high confidence]. "
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
    Rate how high confidence the generated data (e.g. title, description, logline, etc.) is an accurate representation of the
    extracted data points.
    """
    prompt = PromptTemplate(
        template="You are an expert analysis algorithm. "
                 "Based on the extracted data points, rate how high confidence the generated {data_type} is an accurate "
                 "representation of the extracted data points."
                 "Rating should be on a scale [no confidence, low confidence, high confidence, very high confidence], where 'no confidence' means the "
                 "generated {data_type} is not an accurate representation or contains invalid information,"
                 "'very high confidence' means the generated {data_type} is an accurate representation of the extracted data "
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
    Rate how high confidence the two values are the same.
    """
    prompt = PromptTemplate(
        template="You are an expert analysis algorithm. "
                 "Based on the two values, rate how high confidence the two values are the same but written differently."
                 "Rating should be on a scale [no confidence, low confidence, high confidence, very high confidence], where 'no confidence' means the "
                 "two values are not the same,"
                 "'very high confidence' means the two values are the same but written differently."
                 "#Value 1: {value1} "
                 "#Value 2: {value2} "
                 "#Output Format: Confidence level",
        input_variables=["value1", "value2"]
    )

    model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=0.2)

    chain = prompt | model | StrOutputParser()

    return chain
