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


def extract_categories():
    model = ChatOpenAI(model_name="gpt-3.5-turbo", temperature=0.1)
    prompt = PromptTemplate(
        template='Please check the CurrentContext.writer_response below, and see if it mentions any Categories in the '
                 'CategoryCriteria.  The criteria will include positive notes about what may constitute the category '
                 'having been discussed, as well as negative notes about what does not constitute the category being '
                 'discussed.  Please rate the confidence level that the Category was discussed in the '
                 'CurrentContext.writer_response between 0.0-0.1 = no_confidence, 0.2-0.5 = low_confidence, '
                 '0.6-0.9 = high_confidence, and 0.9-1.0 for absolute confidence.  Then add each potential Category '
                 'Element mentioned in an array for that Category with information about the element and a confidence '
                 'rating for each. Only include elements with high or absolute confidence.'
                 '#Example for Character'
                 '{{"type": "Character","confidence": 0.8,"elements": [{{"name": "Sam","usage": "The writer mentions '
                 'Sam\'s demographics, physical appearance, motivation, and friends.","confidence": 0.8}}, '
                 '{{"name": "Sam\'s Sister","usage": "While not named, Sam\'s sister is mentioned as a supporting '
                 'character who helps Sam.","confidence": 0.8}}]}'
                 '#CurrentContext: {current_context}'
                 '#CategoryCriteria: {category_criteria}'
                 '#OutputInstructions: Please provide a rating for each element, and return in a JSON format inside '
                 '<json_response></json_response> XML tags.  The JSON should be in {{categories:[{{type:category_type, '
                 'confidence: confidence_level, elements:[{{"name":name_if_found, "usage": '
                 'summary_sentences_of_how_it_was_mentioned, "confidence": confidence_rating}}]}}]}} format.  Only '
                 'include the <json_response> content, no other context is needed.'
    )
    return prompt | model | regex_extract_xml


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


import re


def regex_extract_xml(message: AIMessage):
    match = re.search(r"<json_response>(.*)</json_response>", message.content, re.DOTALL)
    if match:
        return match.group(1)
    return message.content
