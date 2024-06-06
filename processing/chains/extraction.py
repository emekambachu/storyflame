from typing import Optional, List

from langchain_anthropic import ChatAnthropic
from langchain_core.messages import AIMessage
from langchain_core.output_parsers import PydanticOutputParser
from langchain_core.prompts import PromptTemplate
from langchain_openai import ChatOpenAI
from pydantic import create_model, Field, BaseModel


class DataPoint(BaseModel):
    name: str  # data point key
    type: str  # text, array
    title: str
    description: str
    examples: Optional[List[str]]


def extract_properties(data_points: List[DataPoint]):
    output_parsing_model = create_model(
        "ExtractionOutput",
        **{
            data_point.name: (Optional[List[str]] if data_point.type == "array" else Optional[str],
                              Field(title=data_point.title, description=data_point.description))
            for data_point in data_points
        }
    )

    print(output_parsing_model)

    parser = PydanticOutputParser(pydantic_object=output_parsing_model)

    print(parser.get_format_instructions())

    prompt = PromptTemplate(
        template="You are an expert extraction algorithm. "
                 "You need to extract following properties from user response on a writing platform to a question. "
                 "Do not come up with any properties on your own. "
                 "Only extract the properties from the user's response. "
                 "If you can't extract the property, set it to n/a. "
                 "#List of properties to extract: {data_points} "
                 "#Question to the user: {question} "
                 "#Answer from user: {answer} "
                 "\n{format_instructions}"
                 "Please put the formatted JSON in between <formatted_json></formatted_json>",
        input_variables=["data_points", "question", "answer"],
        partial_variables={
            "format_instructions": parser.get_format_instructions()
        }
    )

    # model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=0.1)
    model = ChatAnthropic(model="claude-3-haiku-20240307", temperature=0.1)

    chain = prompt | model | regex_extract_xml | parser

    return chain


import re


def regex_extract_xml(message: AIMessage):
    match = re.search(r"<formatted_json>(.*)</formatted_json>", message.content, re.DOTALL)
    if match:
        return match.group(1)
    return message.content
