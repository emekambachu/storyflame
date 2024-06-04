from fastapi import APIRouter
from processing.api.conversation.main import router as conversation_router

api_router = APIRouter()

api_router.include_router(conversation_router, prefix="/conversation")
