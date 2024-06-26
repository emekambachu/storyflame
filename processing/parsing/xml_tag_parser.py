import re

from langchain_core.output_parsers import BaseOutputParser
from langchain_core.output_parsers.base import T


class XMLOutputParser(BaseOutputParser[str]):
    tag = "json_output"

    def parse(self, text: str) -> str:
        match = re.search(rf"<{self.tag}>(.*)</{self.tag}>", text, re.DOTALL)
        if match:
            return match.group(1)
        return ""

    @staticmethod
    def from_tag(tag: str) -> 'XMLOutputParser':
        return XMLOutputParser(tag=tag)
