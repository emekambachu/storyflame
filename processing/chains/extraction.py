from typing import Optional, List

from langchain.output_parsers import OutputFixingParser
from langchain_anthropic import ChatAnthropic
from langchain_core.messages import AIMessage
from langchain_core.output_parsers import PydanticOutputParser, JsonOutputParser
from langchain_core.prompts import PromptTemplate
from langchain_openai import ChatOpenAI
from pydantic import create_model, Field, BaseModel

from processing.models.DynamicModel import DynamicModel
from processing.parsing.xml_tag_parser import XMLOutputParser
from processing.templates.extraction import category_extraction_template, category_criteria, \
    category_extraction_example, category_format_instructions, wrong_category_extraction_example, \
    data_point_extraction_template


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
    xml_parser = XMLOutputParser.from_tag("json_response")
    json_parser = JsonOutputParser()
    # model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=0)
    # model = ChatAnthropic(model="claude-3-haiku-20240307", temperature=0, max_tokens=4096)
    model = DynamicModel.default_extraction()

    # output_fixing_parser = OutputFixingParser.from_llm(parser=json_parser, llm=model, max_retries=3)
    prompt = PromptTemplate(
        template=category_extraction_template,
        input_variables=["current_context"],
        partial_variables={
            "category_criteria": category_criteria,
            "category_extraction_example": category_extraction_example,
            "category_format_instructions": category_format_instructions,
            "wrong_category_extraction_example": wrong_category_extraction_example
        }
    )
    return prompt | model | xml_parser | json_parser


def extract_properties():
    # output_parsing_model = create_model(
    #     "ExtractionOutput",
    #     **{
    #         data_point.name: (Optional[List[str]] if data_point.type == "array" else Optional[str],
    #                           Field(title=data_point.title, description=data_point.description))
    #         for data_point in data_points
    #     }
    # )
    # parser = PydanticOutputParser(pydantic_object=output_parsing_model)
    # prompt = PromptTemplate(
    #     template="You are an expert extraction algorithm. "
    #              "You need to extract following properties from user response on a writing platform to a question. "
    #              "Do not come up with any properties on your own. "
    #              "Only extract the properties from the user's response. "
    #              "If you can't extract the property, set it to n/a. "
    #              "#List of properties to extract: {data_points} "
    #              "#Question to the user: {question} "
    #              "#Answer from user: {answer} "
    #              "\n{format_instructions}"
    #              "Please put the formatted JSON in between <json_response></json_response>",
    #     input_variables=["data_points", "question", "answer"],
    #     partial_variables={
    #         "format_instructions": parser.get_format_instructions()
    #     }
    # )

    xml_parser = XMLOutputParser.from_tag("json_response")
    json_parser = JsonOutputParser()
    prompt = PromptTemplate(
        template=data_point_extraction_template,
        input_variables=["current_context", "topic_data_points"],
    )

    # model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=0.1)
    # model = ChatAnthropic(model="claude-3-haiku-20240307", temperature=0.1, max_tokens=4096)
    model = DynamicModel.default_extraction()

    chain = prompt | model | xml_parser | json_parser

    return chain
