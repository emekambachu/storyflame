from pydantic import BaseModel, validator

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

    _validated_answer_rating = validator("answered_correctly", allow_reuse=True)(validate_rating_format)
    _validated_topic_change = validator("topic_change", allow_reuse=True)(validate_rating_format)
    _validated_is_skipped = validator("is_skipped", allow_reuse=True)(validate_rating_format)
    _validated_user_understood = validator("user_does_not_understand", allow_reuse=True)(validate_rating_format)
    _validated_user_dont_know = validator("user_does_not_know", allow_reuse=True)(validate_rating_format)
    _validated_we_dont_understand = validator("we_do_not_understand", allow_reuse=True)(validate_rating_format)
