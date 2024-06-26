import langchain
from fastapi import APIRouter
from langchain_core.output_parsers import JsonOutputParser
from langchain_community.callbacks import get_openai_callback
from processing.chains.extraction import extract_categories_chain, extract_properties
from processing.types.requests import CategoryExtractionRequest, DataPointExtractionChainRequest

router = APIRouter()


@router.post('/categories/extract')
def extract_categories(request: CategoryExtractionRequest):
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
    with get_openai_callback() as cb:
        res = extract_chain.invoke({
            "current_context": request.current_context
        })
        print(cb)
        return res


@router.post('/extract')
def extract_data_points(request: DataPointExtractionChainRequest):
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
    extract_chain = extract_properties()
    return extract_chain.invoke({
        "topic_data_points": request.topic_data_points,
        "current_context": request.current_context
    })

