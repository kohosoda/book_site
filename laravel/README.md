## データ設計

books
- id
- google_id
- title
- price
- page_count
- publisher
- published_date
- description
- category
- created_at
- updated_at

rel_books_authors
- id
- book_id
- author_id

authors
- id
- name

## 機能
- 本登録バッチ
  - Google Books API にリクエストした結果を DB に保存する。
- 検索
  - キーワード検索
- レコメンデーション
  - ベクトル検索による類似判定


## 技術要素
- フロント
  - React
- バックエンド
  - Laravel
  - Postgres
- ベクトル検索
  - Solr
- Embedding
  - word2vec?

## 画面
- トップページ
  - キーワード検索用の検索ボックス
- 検索結果ページ
  - 画面上部に検索ボックス
  - 本のパネルを 20 件ずつ表示
    - 画像
    - 価格
    - タイトル
    - 出版社
- 本の詳細ページ
  - 本の詳細情報
    - タイトル
    - 出版社
    - 出版日
    - 著者
    - ページ数
    - description
  - レコメンデーション

## 検索クエリのアイディア
-IT系
Python
Javascript
PHP
React
ネットワーク
Linux
DB
エンジニア
プログラマ

-仕事
キャリア
ロジカルシンキング
デザインシンキング
経営
リーダー
転職

-生活
お金
投資
趣味
音楽
ピアノ
ギター




