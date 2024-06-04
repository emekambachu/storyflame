from typing import List, Optional

from fastapi import APIRouter, Response
from pydantic import BaseModel

from processing.chains.extraction import extract_properties, DataPoint
from processing.chains.generation import generate_next_question, generate_title_chain, generate_bio_chain
from processing.chains.rating import rate_response, rate_topics, rate_data_points, rate_data_points_next, \
    rate_generated_data
from processing.pusher_client import pusher_client

router = APIRouter()


class ExtractionGroup(BaseModel):
    name: str  # key of the group
    title: str
    description: str
    data_points: List[DataPoint]


class DialogRequest(BaseModel):
    question: str
    answer: str


class ExtractionRequest(DialogRequest):
    groups: List[ExtractionGroup]
    pass


class ChatMessage(BaseModel):
    agent: str
    content: str


class NextRequest(BaseModel):
    type: Optional[str]
    engine: str
    chat_history: List[ChatMessage]
    available_data_points: List[DataPoint]


@router.post('/rate')
def rate(request: DialogRequest):
    print(request.dict())
    rate_response_chain = rate_response()
    ratings = rate_response_chain.invoke({
        "question": request.question,
        "answer": request.answer
    })[0]

    print(ratings)

    # replace ratings with boolean values
    return {
        key: value in ["likely", "very likely"]
        for key, value in ratings.dict().items()
    }


@router.post("/extract")
def extract(request: ExtractionRequest, response: Response):
    groups = request.groups

    all_data_points = [
        data_point
        for group in groups
        for data_point in group.data_points
    ]

    # if no data points are provided
    if len(all_data_points) == 0:
        print("No data points provided")
        return dict()

    # if there are more than 5 data points and more that 2 topics, rate topics first
    if len(all_data_points) > 5 and len(groups) > 2:
        print("Rating topics length: ", len(groups), " and data points length: ", len(all_data_points))
        rate_topics_chain = rate_topics()
        rated_topics = rate_topics_chain.invoke({
            "topics": [group.title + " (Description: " + group.description + ")" for group in groups],
            "question": request.question,
            "answer": request.answer
        })

        print(rated_topics)
        if len(rated_topics) == 0:
            return dict()

        available_data_points = [
            data_point for group in groups
            if group.title in rated_topics
            for data_point in group.data_points
        ]
    else:
        available_data_points = all_data_points

    print("Rating data points length: ", len(available_data_points))
    rate_data_points_chain = rate_data_points()
    rated_data_points = rate_data_points_chain.invoke({
        "data_points": [
            # data_point.title
            data_point.title + " (Description: " + data_point.description + ")"
            for data_point in available_data_points
        ],
        "question": request.question,
        "answer": request.answer
    })

    print(rated_data_points)
    if len(rated_data_points) == 0:
        print("No rated data points found.")
        return dict()

    data_points_for_extraction = [
        data_point for data_point in available_data_points
        if data_point.title in rated_data_points
    ]

    print("Extracting data points")
    extract_data_chain = extract_properties(data_points_for_extraction)
    extracted = extract_data_chain.invoke({
        "data_points": [
            data_point.name + ": " + data_point.description
            for data_point in data_points_for_extraction
        ],
        "question": request.question,
        "answer": request.answer
    })

    if len(extracted.dict().keys()) == 0:
        print("No extracted data points found.")
        return dict()

    print(extracted.dict())

    return extracted


@router.post("/next")
def generate_response(request: NextRequest):
    if len(request.available_data_points) > 3 and (not request.type or request.type == "basic"):
        print("Rating data points for next question")

        rate_data_points_chain = rate_data_points_next()
        rated_data_points = rate_data_points_chain.invoke({
            "available_data_points": [
                group.title + " (Description: " + group.description + ")"
                for group in request.available_data_points
            ],
            "chat_history": request.chat_history
        })
    else:
        rated_data_points = [
            group.title
            for group in request.available_data_points
        ]

    rated_data_points = rated_data_points[0:1]
    print(rated_data_points)

    print("Generating next question")
    generate_next_question_chain = generate_next_question(request.engine, request.type)
    print([
        group.title + " (Description: " + group.description + ")"
        for group in request.available_data_points
        if group.title in rated_data_points
    ])

    next_question = None
    # stream to pusher
    for chunk in generate_next_question_chain.stream({
        "chat_history": request.chat_history,
        "question": request.chat_history[-2].content,
        "answer": request.chat_history[-1].content,
        "topics": [
            group.title + " (Description: " + group.description + ")"
            for group in request.available_data_points
            if group.title in rated_data_points
        ]
    }):
        # print('chunk:', chunk)
        next_question = chunk
        # pusher_client.trigger("conversation", "next_question", {
        #     "question": chunk
        # })

    # next_question = "".join(chunks)

    # next_question = generate_next_question_chain.invoke({
    #     "chat_history": request.chat_history,
    #     "topics": [
    #         group.title + " (Description: " + group.description + ")"
    #         for group in request.available_data_points
    #         if group.title in rated_data_points
    #     ]
    # })

    print(next_question)

    return {
        "question": next_question['question'],
        "message": next_question['message'],
        "data_points": [
            group.name
            for group in request.available_data_points
            if group.title in rated_data_points
        ]
    }


class GenerateRequest(BaseModel):
    type: str
    extracted: dict


@router.post("/generate")
def generate(request: GenerateRequest, response: Response):
    print(request.dict())
    for attempt in range(2):
        print("Attempt: ", attempt)

        if request.type == 'summary':
            raise NotImplementedError("Summary generation not implemented yet.")
        elif request.type == 'title':
            chain = generate_title_chain()
        elif request.type == 'bio':
            chain = generate_bio_chain()

        generated = chain.invoke({
            "extracted": request.extracted
        })

        if generated:
            print("Generated: ", generated)
            rate_chain = rate_generated_data(request.type)
            rating = rate_chain.invoke({
                "extracted": request.extracted,
                "generated": generated
            })
            print("Rating: ", rating)
            if rating.lower() in ["likely", "very likely"]:
                return {
                    "generated": generated,
                    "rating": rating
                }
        else:
            print("Retrying generation")

    raise Exception("Failed to generate a valid response.")


class ConflictRequest(BaseModel):
    data_point: DataPoint
    old_value: str
    new_value: str


@router.post("/conflict")
def resolve_conflict(request: ConflictRequest):
    print(request.dict())

    same_value_probability_chain = rate_same_value_probability()
