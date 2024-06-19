import os
import time
from typing import List, Optional, Any, Dict

from langchain_anthropic import ChatAnthropic
from langchain_core.callbacks import CallbackManagerForLLMRun
from langchain_core.language_models import BaseChatModel
from langchain_core.messages import BaseMessage
from langchain_core.outputs import ChatResult
from langchain_google_genai import ChatGoogleGenerativeAI
from langchain_openai import ChatOpenAI
from pydantic import model_validator

from processing.config import model_stats


class DynamicModel(BaseChatModel):
    """Chat model abstraction that dynamically selects model at runtime."""
    models: Dict[str, BaseChatModel]
    default_model: str
    fails: int = 0

    @classmethod
    @model_validator(mode="before")
    def validate_attrs(cls, data: Dict[str, Any]) -> Dict[str, Any]:
        """Validate class attributes."""
        models = data.get("models", {})
        default_model = data.get("default_model", None)

        if not models or len(models) == 0:
            raise ValueError(
                "The 'models' attribute must have a size greater than 0."
            )

        if default_model not in models:
            raise ValueError(
                f"The 'default_model' attribute '{default_model}' must exist "
                "in the 'models' dict."
            )

        print(models[0])

        return data

    @property
    def _llm_type(self) -> str:
        """Return type of chat model."""
        return "dynamic-chat"

    @staticmethod
    def default_extraction():
        return DynamicModel(
            models={
                "claude-3-haiku-20240307": ChatAnthropic(
                    model_name="claude-3-haiku-20240307",
                    temperature=0,
                    max_tokens_to_sample=4096
                ),
                "gemini-1.5-flash": ChatGoogleGenerativeAI(
                    model="gemini-1.5-flash",
                    temperature=0,
                    max_output_tokens=4096
                ),
                "gpt-3.5-turbo": ChatOpenAI(
                    name="gpt-3.5-turbo",
                    temperature=0,
                    max_tokens=4096
                ),
            },
            default_model="claude-3-haiku-20240307",
        )

    @staticmethod
    def default_generation():
        return DynamicModel(
            models={
                "claude-3-haiku-20240307": ChatAnthropic(
                    model_name="claude-3-haiku-20240307",
                    temperature=0,
                ),
                "gemini-1.5-flash": ChatGoogleGenerativeAI(
                    model="gemini-1.5-flash",
                    temperature=0,
                ),
                "gpt-3.5-turbo": ChatOpenAI(
                    name="gpt-3.5-turbo",
                    temperature=0,
                ),
            },
            default_model="claude-3-haiku-20240307",
        )

    @staticmethod
    def default_rating():
        return DynamicModel(
            models={
                "claude-3-haiku-20240307": ChatAnthropic(
                    model_name="claude-3-haiku-20240307",
                    temperature=0,
                ),
                "gemini-1.5-flash": ChatGoogleGenerativeAI(
                    model="gemini-1.5-flash",
                    temperature=0,
                ),
                "gpt-3.5-turbo": ChatOpenAI(
                    name="gpt-3.5-turbo",
                    temperature=0,
                ),
            },
            default_model="claude-3-haiku-20240307",
        )

    def get_available_model(self) -> Optional[str]:
        """Return available model."""
        # loop through all models and select first one that is available
        for model_name, model in self.models.items():
            if model_name not in model_stats:
                raise ValueError(f"Model {model_name} not found in models config.")
            if ((model_stats[model_name]["status"] == "online")
                or (
                    model_stats[model_name]["status"] == "limited"
                    and model_stats[model_name]["retry_at"] < time.time())):
                print('Using model:', model_name)
                return model_name
        return None

    def try_generate(
        self,
        model: str,
        messages: List[BaseMessage],
        stop: Optional[List[str]] = None,
        run_manager: Optional[CallbackManagerForLLMRun] = None,
        **kwargs: Any,
    ) -> ChatResult:
        current_model = self.models[model]
        res = current_model._generate(
            messages=messages,
            stop=stop,
            run_manager=run_manager,
            **kwargs,
        )
        model_stats[model]["status"] = "online"
        model_stats[model]["retry_at"] = 0
        return res

    def _generate(
        self,
        messages: List[BaseMessage],
        stop: Optional[List[str]] = None,
        run_manager: Optional[CallbackManagerForLLMRun] = None,
        **kwargs: Any,
    ) -> ChatResult:
        """Select chat model from environment variable configuration."""

        model = self.get_available_model()
        if model is None:
            raise ValueError("No available models found.")
        try:
            return self.try_generate(
                model,
                messages,
                stop=stop,
                run_manager=run_manager,
                **kwargs,
            )
        except Exception as e:
            print(f"Failed to generate with model {model}: {str(e)}")
            self.fails += 1
            if self.fails >= 3:
                raise e
            model_stats[model]["status"] = "limited"
            model_stats[model]["retry_at"] = time.time() + 60 * 5  # 5 minutes
            # try other model
            return self._generate(
                messages,
                stop=stop,
                run_manager=run_manager,
                **kwargs,
            )
