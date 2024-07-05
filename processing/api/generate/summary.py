from fastapi import APIRouter
from langchain_core.prompts import ChatPromptTemplate
from pydantic import BaseModel

from processing.models.DynamicModel import DynamicModel
from processing.parsing.xml_tag_parser import XMLOutputParser

router = APIRouter()


class SummaryRequest(BaseModel):
    name: str
    length: str
    purpose: str
    example: str
    data_points: dict


@router.post("/summary")
async def generate_summary(request: SummaryRequest):
    prompt = ChatPromptTemplate.from_template(
        template="Act as a human summarizer and create a summary for {name} that is {length} in length."
                 " The purpose of the summary is to {purpose}." +
                 (len(request.example) > 0 and " An example of the summary is: {example}." or "")
                 + " Use the following data to generate the summary: {data_points}."
                   " Put the generated summary inside <summary></summary> tags."
    )
    model = DynamicModel.default_generation()
    xml_parser = XMLOutputParser.from_tag("summary")

    chain = prompt | model | xml_parser
    sum = chain.invoke({
        "name": request.name,
        "length": request.length,
        "purpose": request.purpose,
        "example": request.example,
        "data_points": request.data_points
    })
    print(sum)
    return {
        "summary": sum
    }
