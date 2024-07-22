from fastapi import APIRouter
from langchain_core.prompts import ChatPromptTemplate
from pydantic import BaseModel

from processing.models.DynamicModel import DynamicModel
from processing.parsing.xml_tag_parser import XMLOutputParser

router = APIRouter()


class ImageRequest(BaseModel):
    name: str
    description: str
    data_points: dict


@router.post("/image")
async def generate_image(request: ImageRequest):
    prompt = ChatPromptTemplate.from_template(
        template="Create a descriptive prompt for an artist or AI image generator.  The purpose of this descriptive prompt is to create an image for {name} that is {description}. Use the following data to generate the image: {data_points}. Put the descriptive image designs instructions inside <imagePrompt></imagePrompt> tags."
    )
    model = DynamicModel.default_generation()
    xml_parser = XMLOutputParser.from_tag("imagePrompt")

    chain = prompt | model | xml_parser
    imagePrompt = chain.invoke({
        "name": request.name,
        "purpose": request.purpose,
        "example": request.example,
        "description": request.description,
        "data_points": request.data_points
    })
    print(image)
    return {
        "imagePrompt": imagePrompt
    }
