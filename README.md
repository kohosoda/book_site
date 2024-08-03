# book_site

## セットアップ

1. コンテナの立ち上げ
   ```bash
   cd book_site
   docker compose up -d
   ```

2. Laravel プロジェクトの composer install
   ```bash
   docker compose exec app composer install
   ```

3. データのインポート
   ```bash
   docker compose exec app php artisan migrate
   docker compose exec app php artisan app:import-books
   docker compose exec python python book_embedding.py
   ```

4. npm run dev
   ```bash
   docker compose exec app npm run dev
   ```
