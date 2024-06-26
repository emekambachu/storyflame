from pydantic import BaseModel, field_validator

from processing.validators.rating_validator import validate_rating_format


class AnswerEvaluation(BaseModel):
    """
    Evaluation of the answer to the question.
    """

    answered_correctly: float
    topic_change: float
    is_skipped: float
    user_does_not_understand: float
    user_does_not_know: float
    we_do_not_understand: float

    @field_validator("answered_correctly", "topic_change", "is_skipped", "user_does_not_understand",
                     "user_does_not_know", "we_do_not_understand")
    def validate_rating_format(cls, field: float) -> float:
        if field < 0.0 or field > 1.0:
            raise ValueError(
                "Field value must be between 0.0 and 1.0, but got value: " + str(field))
        return field
