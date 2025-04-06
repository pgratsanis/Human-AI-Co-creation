# Data Description

This page documents the structure of the dataset used in the experiments.

## Dataset Fields

| Field Name               | Description                                                  |
|--------------------------|--------------------------------------------------------------|
| Chapter                  | Book chapter number from which the question originates       |
| Exercise                 | Number of the exercise within the chapter                    |
| Question                 | The biology question presented to the model                  |
| Answer-Ground Truth      | The textbook-based reference answer                          |
| Hint                     | A contextual hint provided in some prompting cases           |
| gpt3.5_no_hint           | Response by GPT-3.5 without hint                             |
| gpt3.5_with_hint         | Response by GPT-3.5 with contextual hint                     |
| gptevalReplyNoHint       | Self-evaluation score provided by GPT-3.5                    |
| gptevalReplyWithHint     | Self-evaluation score provided by GPT-3.5 (with hint)        |
| gpt4onohint              | Response by GPT-4o without hint                              |
| gpt4owithhint            | Response by GPT-4o with hint                                 |
| gptevalReplyNoHint4o     | Self-evaluation score by GPT-4o (no hint)                    |
| gptevalReplyWithHint4o   | Self-evaluation score by GPT-4o (with hint)                  |

## Notes

- All question-answer pairs are anonymized.
- The full dataset is not public due to copyright restrictions.
- Sample data included in `/data/sample_questions.csv`.

## Licensing

These data samples are provided under a permissive license for research purposes only.
