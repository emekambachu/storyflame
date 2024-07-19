from fastapi import APIRouter
from processing.api.generate.summary import router as summary_router
from processing.api.generate.image import router as image_router

generation_router = APIRouter()

generation_router.include_router(summary_router)
generation_router.include_router(image_router)
