from fastapi import APIRouter

from processing.chains.extraction import extract_categories_chain
from processing.types.requests import CategoryExtractionRequest

router = APIRouter()


@router.post('/categories/extract')
def extract(request: CategoryExtractionRequest):
    # return {
    #     "categories": {
    #         "Writer": [
    #             {
    #                 "name": "John Doe",
    #             }
    #         ]
    #     }
    # }
    extract_chain = extract_categories_chain()
    return extract_chain.invoke({
        "topic_data_points": request.topic_data_points,
        "current_context": request.current_context
    })


@router.post('/extract')
def extract_data_points(request: CategoryExtractionRequest):
    langchain.debug = True
    # return {
    #     "categories": {
    #         "Writer": [
    #             {
    #                 "name": "John Doe",
    #             }
    #         ]
    #     }
    # }
    extract_chain = extract_categories_chain()
    return extract_chain.invoke({
        "topic_data_points": request.topic_data_points,
        "current_context": request.current_context
    })
