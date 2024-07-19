from fastapi import APIRouter
from langchain_core.prompts import ChatPromptTemplate

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
        template="Create an image for {name} that is {description}. Use the following data to generate the image: {data_points}. Put the generated image inside <image></image> tags."
    )
    model = DynamicModel.default_generation()
    xml_parser = XMLOutputParser.from_tag("image")

    chain = prompt | model | xml_parser
    image = chain.invoke({
        "name": request.name,
        "description": request.description,
        "data_points": request.data_points
    })
    print(image)
    return {
        "image": image
    }
