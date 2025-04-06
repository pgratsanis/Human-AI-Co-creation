import pandas as pd

def jaccard_similarity(a, b):
    set_a = set(a.lower().split())
    set_b = set(b.lower().split())
    intersection = len(set_a & set_b)
    union = len(set_a | set_b)
    return intersection / union if union else 0

df = pd.read_csv("data/sample_questions.csv")

for col in df.columns[2:]:
    sims = []
    for i in range(len(df)):
        sim = jaccard_similarity(df['ground_truth'][i], df[col][i])
        sims.append(sim)
    df[f"{col}_jaccard"] = sims

df.to_csv("results/sample_output_jaccard.csv", index=False)
