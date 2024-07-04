from fastapi import APIRouter
from processing.api.conversation.main import router as conversation_router
from processing.config import model_stats
from processing.api.generate.main import generation_router

api_router = APIRouter()

api_router.include_router(conversation_router, prefix="/conversation")
api_router.include_router(generation_router, prefix="/generate")


@api_router.get("/stats")
def stats():
    return model_stats
