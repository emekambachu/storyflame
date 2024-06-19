from fastapi import APIRouter, Request, Depends
from langchain_core.prompts import ChatPromptTemplate, PromptTemplate

from processing.chains.generation import generate_next_question
from processing.models.DynamicModel import DynamicModel
from processing.types.requests import NextQuestionRequest

router = APIRouter()


@router.post("/next")
async def next(request: NextQuestionRequest):
    generate_next_question_chain = generate_next_question(
        engine=request.current_context.focus.type,
        history=request.history,
        question_type=request.type
    )

    return generate_next_question_chain.invoke({
        "chat_history": request.history,
        "topics": request.achievements
    })
