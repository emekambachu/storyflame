import re

from langchain_core.output_parsers import BaseOutputParser
from langchain_core.output_parsers.base import T


class XMLOutputParser(BaseOutputParser[str]):
    tag = ""

    def __init__(self, tag: str):
        self.tag = tag
        super().__init__()

    def parse(self, text: str) -> str:
        match = re.search(rf"<{self.tag}>(.*)</{self.tag}>", text, re.DOTALL)
        if match:
            return match.group(1)
        return text
