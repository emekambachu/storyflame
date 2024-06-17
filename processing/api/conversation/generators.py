
from fastapi import APIRouter

from processing.chains.generation import generate_next_question
from processing.types.requests import NextQuestionRequest

router = APIRouter()

@router.post("/next")
def next(request: NextQuestionRequest):
    generate_next_question_chain = generate_next_question(
        'story',
        request.type
    )

    return generate_next_question_chain.invoke({
        "chat_history": request.history,
        "topics": request.achievements
    })
