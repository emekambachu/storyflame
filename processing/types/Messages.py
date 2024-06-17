from pydantic import BaseModel


class Message(BaseModel):
    agent: str
    content: str
