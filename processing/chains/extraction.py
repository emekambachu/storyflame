from typing import Optional, List

from langchain_anthropic import ChatAnthropic
from langchain_core.messages import AIMessage
from langchain_core.output_parsers import PydanticOutputParser, JsonOutputParser, XMLOutputParser
from langchain_core.prompts import PromptTemplate
from langchain_openai import ChatOpenAI
from pydantic import create_model, Field, BaseModel

from processing.templates.extraction import category_extraction_template, category_criteria


class DataPoint(BaseModel):
    name: str  # data point key
    type: str  # text, array
    title: str
    description: str
    examples: Optional[List[str]]


def get_json_property(response: dict) -> str:
    print(response)
    return response["json_response"]


def extract_categories_chain():
    xml_parser = XMLOutputParser()
    json_parser = JsonOutputParser()
    model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=0.1)
    prompt = PromptTemplate(
        template=category_extraction_template,
        input_variables=["current_context"],
        partial_variables={
            "category_criteria": category_criteria
        }
    )
    return prompt | model | xml_parser | get_json_property | json_parser


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
                 "Please put the formatted JSON in between <json_response></json_response>",
        input_variables=["data_points", "question", "answer"],
        partial_variables={
            "format_instructions": parser.get_format_instructions()
        }
    )

    # model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=0.1)
    model = ChatAnthropic(model="claude-3-haiku-20240307", temperature=0.1)

    chain = prompt | model | regex_extract_xml | parser

    return chain
