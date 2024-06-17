def validate_rating_format(field: float) -> float:
    if field < 0.0 or field > 1.0:
        raise ValueError(
            "Field value must be between 0.0 and 1.0, but got value: " + str(field))
    return field
