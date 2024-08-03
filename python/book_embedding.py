import json
import requests
import mysql.connector
from sentence_transformers import SentenceTransformer

def get_books():
    """
    MySQLデータベースから本の説明を取得する
    """
    try:
        # MySQLデータベースに接続
        connection = mysql.connector.connect(
            host='mysql',
            user='book_user',
            password='passwd',
            database='book_db'
        )

        cursor = connection.cursor()
        cursor.execute("SELECT id, title, description FROM books")
        books = cursor.fetchall()

        return books

    except mysql.connector.Error as err:
        print(f"Error: {err}")
        return None

    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

def get_embedding(books):
    """
    本の description をエンベディングする
    出力されるベクトルの次元数は 768
    """
    book_embeddings = []
    for book in books:
        book_id = book[0]
        book_title = book[1]
        book_description = book[2]
        embedding = model.encode(book_description).tolist()
        book_embeddings.append({
            "id": book_id,
            "title": book_title,
            "description": book_description,
            "description_vector": embedding
            })
    return book_embeddings


# モデルのロード
print("モデルのロードを開始します。")
# 日本語を扱えるモデル see: https://tech.yellowback.net/posts/sentence-transformers-japanese-models
model = SentenceTransformer('stsb-xlm-r-multilingual')
print("モデルのロードが完了しました。")

# 本の情報を取得 [id, title, description] のリスト
books = get_books()

# 本の description をエンベディング
print("エンベディングを開始します。")
# エンベディングを取得 [id, title, description, description_vector] のリスト
book_embeddings = get_embedding(books)

# solr への取り込み
solr_url = "http://solr:8983/solr/books/update?commit=true"
headers = {
    "Content-Type": "application/json"
}
response = requests.post(solr_url, headers=headers, data=json.dumps(book_embeddings))
print("solrへのPOSTの結果:", response.json())
