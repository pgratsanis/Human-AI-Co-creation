import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

df = pd.read_csv("data/sample_questions.csv")
vectorizer = TfidfVectorizer()

for col in df.columns[2:]:
    sims = []
    for i in range(len(df)):
        vecs = vectorizer.fit_transform([df['ground_truth'][i], df[col][i]])
        sim = cosine_similarity(vecs[0:1], vecs[1:2])[0][0]
        sims.append(sim)
    df[f"{col}_cosine"] = sims

df.to_csv("results/sample_output.csv", index=False)
