from typing import Dict, Optional, Any, List

from pydantic import BaseModel

from processing.types.Messages import Message
from processing.types.achievement import Achievement
from processing.types.context import CurrentContext


class BaseRequest(BaseModel):
    current_context: CurrentContext
    pass


class NextQuestionRequest(BaseRequest):
    history: List[Message]
    achievements: List[Achievement]
    type: str
    pass


class CategoryExtractionRequest(BaseRequest):
    topic_data_points: List[Achievement]
    pass


class DataPointExtractionChainRequest(BaseRequest):
    topic_data_points: List[Achievement]
    pass
