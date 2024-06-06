from fastapi import FastAPI
import spacy
from pydantic import BaseModel
from fuzzywuzzy import fuzz

nlp = spacy.load("en_core_web_lg")
app = FastAPI()


class SimilarityRequest(BaseModel):
    text1: str
    text2: str


@app.post("/similarity")
def similarity(request: SimilarityRequest):
    # doc1 = nlp(request.text1)
    # doc2 = nlp(request.text2)
    # sim = doc1.similarity(doc2) * 100
    sim = fuzz.partial_ratio(request.text1, request.text2)
    print(f"Similarity between {request.text1} and {request.text2} is {sim}")
    return {
        "similarity": sim
    }
