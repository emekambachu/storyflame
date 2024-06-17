from typing import List, Optional

from pydantic import BaseModel


class DataPoint(BaseModel):
    id: str
    name: str
    extraction_description: str


class Achievement(BaseModel):
    id: str
    name: str
    category: str
    applicable_elements: Optional[List[str]]
    data_points: List[DataPoint]
