# embedding_script.py

from sentence_transformers import SentenceTransformer

# モデルのロード
model = SentenceTransformer('stsb-xlm-r-multilingual')

# エンベディングする文章のリスト
sentences = [
    "今日はいい天気です。",
    "私は昨日映画を見ました。",
    "Pythonは素晴らしいプログラミング言語です。"
]

# エンベディングの計算
embeddings = model.encode(sentences)

# 結果の表示
for sentence, embedding in zip(sentences, embeddings):
    print(f"Sentence: {sentence}")
    print(f"Embedding: {embedding}\n")
