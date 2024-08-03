import json
import requests

# 対象ドキュメントのベクトルを取得
# curl -X GET 'http://solr:8983/solr/books/select?q=id:1&fl=embedding&wt=json'
solr_url = 'http://solr:8983/solr/books/select'
params = {'q': 'id:2', 'fl': 'description_vector', 'wt': 'json'}
response = requests.get(solr_url, params=params)
data = response.json()
vector = data['response']['docs'][0]['description_vector']

# 類似ドキュメントの検索
payload = {
    "query": f'{{!knn f=description_vector topK=10}}{vector}',
    "fields": ["id", "score"]
}
headers = {
    "Content-Type": "application/json"
}
response = requests.post(solr_url, headers=headers, data=json.dumps(payload))
simlar_books = response.json()['response']['docs']

# 検索結果を出力
print(simlar_books)
