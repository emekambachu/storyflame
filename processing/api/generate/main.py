from fastapi import APIRouter
from processing.api.generate.summary import router as summary_router

generation_router = APIRouter()

generation_router.include_router(summary_router)
