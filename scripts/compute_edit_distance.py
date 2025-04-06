import pandas as pd
import numpy as np

def edit_distance_similarity(a, b):
    dp = np.zeros((len(a)+1, len(b)+1))
    for i in range(len(a)+1):
        dp[i][0] = i
    for j in range(len(b)+1):
        dp[0][j] = j
    for i in range(1, len(a)+1):
        for j in range(1, len(b)+1):
            if a[i-1] == b[j-1]:
                dp[i][j] = dp[i-1][j-1]
            else:
                dp[i][j] = 1 + min(dp[i-1][j], dp[i][j-1], dp[i-1][j-1])
    max_len = max(len(a), len(b))
    return 1 - dp[len(a)][len(b)] / max_len if max_len > 0 else 1

df = pd.read_csv("data/sample_questions.csv")

for col in df.columns[2:]:
    sims = []
    for i in range(len(df)):
        sim = edit_distance_similarity(df['ground_truth'][i], df[col][i])
        sims.append(sim)
    df[f"{col}_edit"] = sims

df.to_csv("results/sample_output_edit.csv", index=False)
