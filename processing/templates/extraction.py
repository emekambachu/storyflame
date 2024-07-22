data_point_extraction_template = (
    "Acting as an Expert Story & Writing Analyst & Story Consultant, please help [a] determine if this writer's "
    "response (current_context.writer_response) contains information for any of these Topic Data Points in the list "
    "below, and if so, [b] include just the necessary information. Each Topic / Achievement Data Point includes an "
    "Extraction Description and Purpose to help with evaluation."

    "Loop through each Category in the current_context.focus.current_data."

    "For the transcribed Writer message below, check each data point from the provided Topic Data Point list and rate "
    "your confidence level. Please rate the confidence level that the Category was discussed in the "
    "CurrentContext.writer_response between 0.0-0.1 = no_confidence, 0.2-0.5 = low_confidence, 0.6-0.8 = "
    "high_confidence, and 0.9-1.0 for absolute confidence."

    "Please return the data in an XML of <json_response> as an JSON array of \"achievements\".  Only include "
    "data_points with high_confidence or absolute_confidence (0.6+)."

    "If the data_point_value already exists within the current_context.current_data, then merge that information with "
    "the new information from the current_context.writer_response. For example, if the Occupation is already filled "
    "in the current_context, and another Occupation is given from the writer_response, merge the two."

    "Only include the \"data_point.value\" when the data_point.confidence_level is high or absolute confidence that "
    "the response contains information pertinent to the data point. When writing the the value, please do so as a "
    "more professional version of the writer writing a treatment pitch in a professional way. This should be written "
    "as coming from the writer, instead of from another source. Only rewrite what the writer has said to make it "
    "sound more professional, but do not infer, and do not come up with new data. Only include necessary information "
    "for each data point value."

    "Use the DataValueInstructions to fill out the applicable data_point_value."
    "#CurrentContext: {current_context}"
    "#List of topic data points to rate: {topic_data_points}"
    "#json_response format: [achievements: [{{achievement_id: <achievement_id>, achievement_name:<achievement_name>, "
    "data_points: [{{category: <category_type>, element_name:<element_name>, data_point_id: <data_point_id>, "
    "data_point_name: <data_point_name>, confidence_level:<selected_confidence_level>}}]}}]]]"
    "#Output format: <json_response>[json_response]</json_response>"
    "#Note: Do not include any additional response other than what is in <json_response></json_response>"
    # "#Example element:"
)

category_extraction_example = {
    "categories": {
        "Story": [
            {
                "name": "Galactic Space Adventure",
                "usage": "The writer mentions a story called Galactic Space Adventure and describes it's main plot and characters.",
                "confidence": 1.0,
                "categories": {
                    "Character": [
                        {
                            "name": "Captain Brown",
                            "related": "Galactic Space Adventure",
                            "usage": "Captain Brown is the main character of Galactic Space Adventure. He is described as a brave and daring space captain who leads his crew on dangerous missions.",
                            "confidence": 0.9
                        },
                        {
                            "name": "Jenny",
                            "related": "Galactic Space Adventure",
                            "usage": "Jenny is Captain Brown's loyal first mate. She is a skilled pilot and mechanic who helps Captain Brown navigate the dangers of space.",
                            "confidence": 0.8
                        }
                    ],
                    "Plot": [
                        {
                            "name": "The Quest for the Crystal Star",
                            "related": "Galactic Space Adventure",
                            "usage": "The main plot of Galactic Space Adventure is the crew's quest to find the legendary Crystal Star, a powerful artifact that can save the galaxy from destruction.",
                            "confidence": 0.9
                        }
                    ]
                }
            }
        ]
    }
}

wrong_category_extraction_example = {
    "categories": {
        "Story": [
            {
                "name": "Galactic Space Adventure",
                "usage": "The writer mentions a story called Galactic Space Adventure and describes it's main plot and characters.",
                "confidence": 1.0,
            }
        ],
        "Character": [
            {
                "name": "Captain Brown",
                "usage": "Captain Brown is the main character of Galactic Space Adventure. He is described as a brave and daring space captain who leads his crew on dangerous missions.",
                "confidence": 0.9
            },
            {
                "name": "Jenny",
                "related": "Galactic Space Adventure",
                "usage": "Jenny is Captain Brown's loyal first mate. She is a skilled pilot and mechanic who helps Captain Brown navigate the dangers of space.",
                "confidence": 0.8
            }
        ],
        "Plot": [
            {
                "name": "The Quest for the Crystal Star",
                "usage": "The main plot of Galactic Space Adventure is the crew's quest to find the legendary Crystal Star, a powerful artifact that can save the galaxy from destruction.",
                "confidence": 0.9
            }
        ]
    }
}

category_criteria = (
    {
        "categories": [
            {
                "category": "Series",
                "purpose": "Identifies if the writer is discussing a TV series, Graphic Novel series, or Novel series they are actively creating or developing. Not for an individual story.",
                "confidence_rules": {
                    "absolute": "Explicitly mentions they are creating a TV series, Graphic Novel series or Novel series and provides the series name or title. Clearly indicates the discussion is about an overarching series and not just an individual story.",
                    "high": "Makes unambiguous references to developing a TV series, Graphic Novel series or Novel series they are creating, and discusses elements relevant to an overarching series like multiple seasons, story arcs, or a series of interconnected books or volumes. Context strongly indicates a series and not an individual story.",
                    "low": "Expressly says it is a story. Mentions or alludes to a larger series or collection of stories they might be creating or developing, but without providing clear details or context to confirm it is definitely a TV, Graphic Novel or Novel series and not just an individual story.",
                    "no": "Expressly says 'happening in the story' somewhere. Focuses solely on an individual story, or does not mention or reference a TV series, Graphic Novel series or Novel series they are creating or developing."
                },
            },
            {
                "category": "Season",
                "purpose": "Identifies if the writer is discussing a season within a series they are actively creating or developing.",
                "confidence_rules": {
                    "absolute": "Explicitly mentions a season number or title within a series they are creating, or provides clear details about a season's specific story arc, episode count, or unique themes and developments.",
                    "high": "Makes clear references to a specific season within a series they are creating without explicitly naming it, or discusses distinct narrative phases or chapters within the series they are developing.",
                    "low": "Mentions or alludes to a season within a series they might be creating or developing, but without providing clear details or context to confirm it.",
                    "no": "Does not mention or reference a season within a series they are creating or developing."
                },
                "relatable_to": ["Series"]
            },
            {
                "category": "Story",
                "purpose": "Identifies if the writer is discussing a specific story they are actively creating or developing.",
                "confidence_rules": {
                    "absolute": "Provides a clear and detailed description of a specific story they are creating, including key plot points, character developments, and resolution.",
                    "high": "Discusses a specific story they are creating and its narrative elements, but may not provide a comprehensive summary.",
                    "low": "Mentions or alludes to a story they might be creating or developing, but without providing clear details or context to confirm it.",
                    "no": "Does not mention or reference a specific story they are creating or developing."
                }
            },
            {
                "category": "Plot",
                "purpose": "Identifies if the writer is discussing the plot of a story they are actively creating or developing.",
                "confidence_rules": {
                    "absolute": "Provides a clear and detailed description of the plot of a story they are creating, including key events, twists, and cause-and-effect relationships.",
                    "high": "Discusses the plot and its key developments in a story they are creating, but may not provide a comprehensive summary.",
                    "low": "Mentions or alludes to the plot of a story they might be creating or developing, but without providing clear details or context to confirm it.",
                    "no": "Does not mention or reference the plot of a story they are creating or developing."
                },
                "relatable_to": ["Story", "Series", "Season", "Character"]
            },
            {
                "category": "Theme",
                "purpose": "Identifies if the writer is discussing the themes of a story they are actively creating or developing.",
                "confidence_rules": {
                    "absolute": "Explicitly identifies and analyzes the central themes of a story they are creating, providing clear examples and interpretations.",
                    "high": "Discusses the themes of a story they are creating, but may not provide a comprehensive analysis or interpretation.",
                    "low": "Mentions or alludes to themes in a story they might be creating or developing, but without providing clear details or context to confirm it.",
                    "no": "Does not mention or reference any themes in a story they are creating or developing."
                },
                "relatable_to": ["Story", "Series", "Season"]
            },
            {
                "category": "Character",
                "purpose": "Identifies if the writer is discussing one or more characters in a story they are actively creating or developing.",
                "confidence_rules": {
                    "absolute": "Expressly mentions one or more characters in the story they are creating. Includes their name and at least one thing about them as a character like: age, personality traits, motivations, role in the story, or appearance.",
                    "high": "Mentions one or more characters in a story they are creating, but limited details about them.",
                    "low": "Mentions or alludes to characters in a story they might be creating or developing, but without providing clear details or context to confirm it.",
                    "no": "Does not mention or reference any characters in a story they are creating or developing."
                },
                "relatable_to": ["Story", "Series", "Season"]
            },
            {
                "category": "Setting",
                "purpose": "Identifies if the writer is discussing the setting of a story they are actively creating or developing.",
                "confidence_rules": {
                    "absolute": "Provides a clear and detailed description of the setting in a story they are creating, including specific locations, time periods, and how the environment impacts the narrative.",
                    "high": "Discusses the setting and its key features in a story they are creating, but may not provide a comprehensive description.",
                    "low": "Mentions or alludes to the setting of a story they might be creating or developing, but without providing clear details or context to confirm it.",
                    "no": "Does not mention or reference the setting of a story they are creating or developing."
                },
                "relatable_to": ["Story", "Series", "Season"]
            },
            {
                "category": "World Mechanic",
                "purpose": "Identifies if the writer is discussing the rules, laws, or systems governing the fictional world of a story they are actively creating or developing.",
                "confidence_rules": {
                    "absolute": "Explicitly identifies and describes specific rules, laws, or systems governing the fictional world of a story they are creating, and how they impact the characters and narrative.",
                    "high": "Discusses unique features or mechanics of the fictional world in a story they are creating, but may not provide a comprehensive explanation of the rules or their implications.",
                    "low": "Mentions or alludes to world rules in a story they might be creating or developing, but without providing clear details or context to confirm it.",
                    "no": "Does not mention or reference any world rules in a story they are creating or developing."
                },
                "relatable_to": ["Story", "Series", "Season"]
            },
            {
                "category": "Writer",
                "purpose": "Identifies if the writer is discussing their own writing process, preferences, experiences, or goals.",
                "confidence_rules": {
                    "absolute": "The subject is on them as a writer and *not* on an individual story or series they are working on. Provides clear and detailed insights into their own writing process, preferences, experiences, or goals.",
                    "high": "The subject is on them as a writer and *not* on an individual story or series they are working on. Discusses their perspective as a writer, but may not provide comprehensive details about their writing process or goals.",
                    "low": "Mentions or alludes to their role or experiences as a writer, but without providing clear details or context to confirm it.",
                    "no": "Does not mention or reference their own writing perspective, process, experiences, or goals."
                }
            },
            {
                "category": "Reference",
                "purpose": "Identifies if the writer is making a reference to an existing story, character, setting, or other fictional element that is not part of the story they are actively creating or developing.",
                "confidence_rules": {
                    "absolute": "Explicitly names or describes an existing fictional element and draws a clear comparison or connection to an element in the story they are creating.",
                    "high": "Makes a clear reference or allusion to an existing fictional element in relation to their own story, but may not provide the name or specific details.",
                    "low": "Mentions an existing fictional element in passing, but does not clearly connect it to their own story or provides limited context for the reference.",
                    "no": "Does not mention or reference any existing fictional elements in relation to the story they are creating or developing."
                },
                "relatable_to": ["Story", "Series", "Season", "Character", "Setting", "World Mechanic"]
            }
        ]
    }
)

# "{{categories:[{{type:category_type, confidence: "
# "confidence_level, elements:[{{\"name\":name_if_found, \"usage\": summary_sentences_of_how_it_was_mentioned, "
# "\"confidence\": confidence_rating}}]}}]}}"
category_format_instructions = (
    {
        "categories": {
            "category_type": [
                {
                    "name": "name_if_found",
                    "usage": "summary_sentences_of_how_it_was_mentioned",
                    "confidence": "confidence_level",
                    "categories": {
                        "inner_category_type": [
                            {
                                "name": "name_if_found",
                                "related": "related_category_name",
                                "usage": "summary_sentences_of_how_it_was_mentioned",
                                "confidence": "confidence_level"
                            }
                        ]
                    }
                }
            ],
            "other_category_type": [
                {
                    "name": "name_if_found",
                    "usage": "summary_sentences_of_how_it_was_mentioned",
                    "confidence": "confidence_level"
                }
            ]
        }
    }
)

category_extraction_template = (
    "Please check the CurrentContext.writer_response below, and see if it mentions any Categories in the "
    "CategoryCriteria."
    "The criteria will include positive notes about what may constitute the category having been discussed, "
    "as well as negative notes about what does not constitute the category being discussed."
    "Please rate the confidence level that the Category was discussed in the CurrentContext.writer_response between "
    "0.0-0.1 = no_confidence, 0.2-0.5 = low_confidence, 0.6-0.9 = high_confidence, and 0.9-1.0 for absolute confidence."
    "If the category is mentioned to be within other category, please include it in the other's category list."
    "Then add each potential Category Element mentioned in an array for that Category with information about the "
    "element and a confidence rating for each."
    "Only include elements with high or absolute confidence. If writer does not mention a category, do not include it."

    # "For story_breakdowns, please include a story object for each story found in the writer's response.  And for "
    # "story_breakdowns.story[].categories_and_elements in the output format, please include paragraph(s) about the "
    # "categories and elements found in the writers response for that story."

    "#Correct Example for Story"
    "{category_extraction_example}"
    "In the example for Story, the writer mentions a story called Galactic Space Adventure and describes characters "
    "and plot elements of that story. So Character and Plot categories are related to Galactic Space Adventure and"
    "placed under the Story categories."
    "#Wrong Example for Story"
    "{wrong_category_extraction_example}"

    "#CurrentContext: {current_context}"
    "#CategoryCriteria: {category_criteria}"
    "#OutputInstructions: Please provide a rating for each element, and return in a minified JSON format inside "
    "<json_response></json_response> XML tags.  The minified JSON should be in {category_format_instructions} format.  Only include the <json_response> content, no other context is "
    "needed. JSON output should be a valid minified JSON object."
)
