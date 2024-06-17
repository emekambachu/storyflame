from fastapi import APIRouter

from processing.chains.rating import rate_response_chain
from processing.types.requests import BaseRequest

router = APIRouter()


@router.post('/rate')
def rate(request: BaseRequest):
    rating_chain = rate_response_chain()
    return rating_chain.invoke({
        "current_context": request.current_context.dict()
    })
