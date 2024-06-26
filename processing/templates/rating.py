confidence_template = (
    "You should rate the following on a scale of [absolute confidence, high confidence, low confidence, no confidence]."
    "If you are absolutely sure that the topic is being discussed, rate it as 'absolute confidence'."
    "If there are strong indications that the topic is being discussed, rate it as 'high confidence'."
    "If you are not sure, rate it as 'low confidence'."
    "If you are sure that the topic is not being discussed, rate it as 'no confidence'.")

answer_rating_template = (
    "As a conversational evaluator, please rate the user's response to the question to help us best determine the "
    "content of their response."

    "- Answered question: confidence they answered the question correctly"
    "- Topic change: whether the answer changes the topic"
    "- Is skipped: whether user wants to skip the question"
    "- User does not understand: whether the user indicates that they don't understand the question"
    "- User does not know: whether the user indicates that they don't know the answer"
    "- We do not understand: the response is difficult to interpret"
    "All ratings should be between 0.0-0.1 = no_confidence, 0.2-0.5 = low_confidence, 0.6-0.9 = high_confidence, "
    "and 0.9-1.0 for absolute confidence."
    "#Example"
    "<json_response>{{ \"answered_correctly\": 0.8, \"topic_change\": 0.0, \"is_skipped\": 0.0, "
    "\"user_does_not_understand\": 0.0, \"user_does_not_know\": 0.0, \"we_do_not_understand\": 0.0}}</json_response>"
    "#CurrentContext: {current_context}"
    "#OutputInstructions: Please provide a rating for each element, and return in a JSON format inside "
    "<json_response></json_response> XML tags.  Only include the <json_response> content, no other context is needed."
)
