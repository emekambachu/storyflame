from typing import List, Optional, Any, Dict

from langchain_core.callbacks import CallbackManagerForLLMRun
from langchain_core.language_models import BaseChatModel
from langchain_core.messages import BaseMessage
from langchain_core.outputs import ChatResult
from pydantic import model_validator

from processing.config import model_stats


class UsageLoggingChatModel(BaseChatModel):
    model: str

    @classmethod
    @model_validator(mode="before")
    def validate_attrs(cls, data: Dict[str, Any]) -> Dict[str, Any]:
        model = data.get("model", None)

        if not model:
            raise ValueError("The 'model' attribute must be set.")

        if model not in model_stats.keys():
            raise ValueError(f"The 'model' attribute '{model}' must exist in the 'model_stats' dict.")

        return data

    @property
    def _llm_type(self) -> str:
        return "usage-logging-model"

    def generate_openai(
        self,
        messages: List[BaseMessage],
        stop: Optional[List[str]] = None,
        run_manager: Optional[CallbackManagerForLLMRun] = None,
        **kwargs: Any
    ) -> ChatResult:
        pass

    def generate_google(
        self,
        messages: List[BaseMessage],
        stop: Optional[List[str]] = None,
        run_manager: Optional[CallbackManagerForLLMRun] = None,
        **kwargs: Any
    ) -> ChatResult:
        pass

    def generate_anthropic(
        self,
        messages: List[BaseMessage],
        stop: Optional[List[str]] = None,
        run_manager: Optional[CallbackManagerForLLMRun] = None,
        **kwargs: Any
    ) -> ChatResult:
        pass

    def _generate(
        self,
        messages: List[BaseMessage],
        stop: Optional[List[str]] = None,
        run_manager: Optional[CallbackManagerForLLMRun] = None,
        **kwargs: Any
    ) -> ChatResult:
        family = model_stats[self.model]["family"]
        if family == "openai":
            return self.generate_openai(messages, stop, run_manager, **kwargs)
        elif family == "google":
            return self.generate_google(messages, stop, run_manager, **kwargs)
        elif family == "anthropic":
            return self.generate_anthropic(messages, stop, run_manager, **kwargs)
        pass
