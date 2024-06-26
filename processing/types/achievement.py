from typing import List, Optional

from pydantic import BaseModel


class DataPoint(BaseModel):
    id: str
    name: str
    extraction_description: str
    extraction_examples: Optional[List[str]] = None
    purpose: Optional[str] = None
    data_value_instructions: Optional[str] = None


class Achievement(BaseModel):
    id: str
    name: str
    category: str
    applicable_elements: Optional[List[str]] = None
    data_points: List[DataPoint]
