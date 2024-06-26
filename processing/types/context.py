from typing import List, Dict, Any, Optional

from pydantic import BaseModel


class DataObject(BaseModel):
    name: str
    type: Optional[str]
    existing_data_points: Dict[str, Any]


class ContextFocus(BaseModel):
    type: str
    name: str
    # current_data: List[Dict[str, List[DataObject]]]
    current_data: dict
    pass


class CurrentContext(BaseModel):
    processing_type: str
    conversation_mode: str
    focus: ContextFocus
    goal: str
    question_asked: Optional[str] = None
    writer_response: Optional[str] = None
    pass
