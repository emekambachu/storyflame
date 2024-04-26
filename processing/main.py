import os
from typing import Union

from fastapi import FastAPI
from faster_whisper import WhisperModel
from pydantic import BaseModel

app = FastAPI()


@app.get("/")
def read_root():
    return {"foo": "baz"}


class TranscribeRequest(BaseModel):
    path: str


@app.post("/transcribe")
def transcribe(params: TranscribeRequest):
    # check if file exists

    model = WhisperModel("tiny.en", device="cpu", compute_type="float32")

    print(f"Transcribing the audio file at: {params.path}")
    segments, info = model.transcribe(params.path, beam_size=5)
    print("Detected language '%s' with probability %f" % (info.language, info.language_probability))

    return [
        {
            "start": segment.start,
            "end": segment.end,
            "text": segment.text
        }
        for segment in segments
    ]
