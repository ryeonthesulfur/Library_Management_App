# 書籍管理アプリ テーブル設計


ER図：
![ER図](image-1.png)


## membersテーブル (メンバー情報)
| カラム名   | 型       | NULL | Key | 備考           |     概要
| :--------- | :------- | :--- | :-- | :------------- |
| id         | bigint   | NO   | PK  |                |
| name       | string   | NO   |     |                |氏名
| name_kana  | string   | YES  |     |                |氏名（かな）
| email      | string   | NO   |     | unique         |メルアド
| password   | string   | NO   |     |                |パスワード
| prefecture | string   | YES  |     |                |都道府県名
| city       | string   | YES  |     |                |市町村名
| other      | string   | YES  |     |                |番地など
| dm         | boolean  | NO   |     | default: false |広告メールを受け取るか
| roles      | json     | YES  |     |                |ロール名
| info       | json     | YES  |     |                |そのほかの情報（性別、言語、SNS）
| created_at | datetime | NO   |     |                |
| updated_at | datetime | NO   |     |                |

### アソシエーション
hasMany: reviews
hasOne: author

---

## authorsテーブル (著者情報)
| カラム名   | 型       | NULL | Key | 備考       |     概要
| :--------- | :------- | :--- | :-- | :--------- |
| id         | bigint   | NO   | PK  |            |
| member_id  | bigint   | NO   | FK  | members.id |membersテーブルと紐づく外部キー
| pen_name   | string   | NO   |     |            |著者名（ペンネーム）
| debut      | date     | YES  |     |            |初出版日
| created_at | datetime | NO   |     |            |
| updated_at | datetime | NO   |     |            |

### アソシエーション
belongsTo: member
belongsToMany: books

---

## booksテーブル (書籍情報)
| カラム名   | 型       | NULL | Key | 備考           |     概要
| :--------- | :------- | :--- | :-- | :------------- |
| id         | bigint   | NO   | PK  |                |
| isbn       | string   | NO   |     | unique         |ISBNコード
| title      | string   | NO   |     |                |書名
| price      | integer  | NO   |     |                |価格
| publisher  | string   | YES  |     |                |出版社
| published  | date     | YES  |     |                |刊行日
| sample     | boolean  | NO   |     | default: false |サンプルダウンロードの有無
| created_at | datetime | NO   |     |                |
| updated_at | datetime | NO   |     |                |

### アソシエーション
belongsToMany: authors
hasMany: reviews
morphToMany: tags (taggables)
morphOne: memos (memoable)

---

## author_bookテーブル (著者/書籍の中間テーブル)
| カラム名  | 型      | NULL | Key    | 備考           |     概要
| :-------- | :------ | :--- | :----- | :------------- |
| author_id | bigint  | NO   | PK, FK | authors.id     |authorsテーブルと紐づく外部キー
| book_id   | bigint  | NO   | PK, FK | books.id       |booksテーブルと紐づく外部キー
| hidden    | boolean | NO   |        | default: false |著者名を非表示にするか（既定はfalse）
| order     | integer | NO   |        | default: 0     |著者名の表示順（既定は0）

### アソシエーション
（モデルは作成しないことが多い）

---

## reviewsテーブル (レビュー情報)
| カラム名   | 型       | NULL | Key | 備考                    |     概要
| :--------- | :------- | :--- | :-- | :---------------------- |
| id         | bigint   | NO   | PK  |                         |
| book_id    | bigint   | NO   | FK  | books.id                |booksテーブルと紐づく外部キー
| member_id  | bigint   | NO   | FK  | members.id              |membersテーブルと紐づく外部キー
| status     | string   | NO   |     | draft/published/deleted |レビューのステータス
| rate       | integer  | NO   |     |                         |星の数
| body       | text     | NO   |     |                         |レビュー本文
| created_at | datetime | NO   |     |                         |
| updated_at | datetime | NO   |     |                         |

### アソシエーション
belongsTo: book
belongsTo: member
hasMany: comments

---

## commentsテーブル (コメント情報)
| カラム名   | 型       | NULL | Key | 備考       |     概要
| :--------- | :------- | :--- | :-- | :--------- |
| id         | bigint   | NO   | PK  |            |
| review_id  | bigint   | NO   | FK  | reviews.id |reviewsテーブルと紐づく外部キー
| body       | text     | NO   |     |            |コメント本文
| created_at | datetime | NO   |     |            |
| updated_at | datetime | NO   |     |            |

### アソシエーション
belongsTo: review

---

## articlesテーブル (記事情報)
| カラム名   | 型       | NULL | Key | 備考 |     概要
| :--------- | :------- | :--- | :-- | :--- |
| id         | bigint   | NO   | PK  |      |
| subject    | string   | NO   |     |      |記事名
| body       | text     | NO   |     |      |記事本文
| summary    | string   | YES  |     |      |記事の要約
| created_at | datetime | NO   |     |      |
| updated_at | datetime | NO   |     |      |

### アソシエーション
morphToMany: tags (taggables)
morphOne: memos (memoable)

---

## tagsテーブル (タグ情報)
| カラム名   | 型       | NULL | Key | 備考   |     概要
| :--------- | :------- | :--- | :-- | :----- |
| id         | bigint   | NO   | PK  |        |
| name       | string   | NO   |     | unique |タグ名
| created_at | datetime | NO   |     |        |
| updated_at | datetime | NO   |     |        |

### アソシエーション
morphedByMany: books (taggables)
morphedByMany: articles (taggables)

---

## taggablesテーブル (タグ用中間テーブル - ポリモーフィック)
| カラム名      | 型     | NULL | Key    | 備考    |     概要
| :------------ | :----- | :--- | :----- | :------ |
| tag_id        | bigint | NO   | PK, FK | tags.id |tagsテーブルへの外部キー
| taggable_id   | bigint | NO   | PK     |         |関連先と紐づく外部キー
| taggable_type | string | NO   | PK     |         |関連先を表すモデル

### アソシエーション
（モデルは作成しない）

---

## memosテーブル (メモ情報 - ポリモーフィック)
| カラム名      | 型       | NULL | Key | 備考 |     概要
| :------------ | :------- | :--- | :-- | :--- |
| id            | bigint   | NO   | PK  |      |
| body          | text     | NO   |     |      |メモ本文
| memoable_id   | bigint   | NO   |     |      |関連先と紐づく外部キー
| memoable_type | string   | NO   |     |      |関連先を表すモデル
| created_at    | datetime | NO   |     |      |
| updated_at    | datetime | NO   |     |      |

### アソシエーション
morphTo: memoable

---

## photosテーブル (写真情報)
| カラム名      | 型       | NULL | Key | 備考 |     概要
| :------------ | :------- | :--- | :-- | :--- |
| id            | bigint   | NO   | PK  |      |
| original_name | string   | NO   |     |      |オリジナルの名前
| mime_type     | string   | NO   |     |      |MIMEタイプ
| file_size     | integer  | NO   |     |      |ファイルのサイズ
| file_content  | binary   | NO   |     |      |ファイル本体
| created_at    | datetime | NO   |     |      |
| updated_at    | datetime | NO   |     |      |

### アソシエーション
（関連先が不明なため未定義）

---

## logsテーブル (ログ情報)
| カラム名   | 型       | NULL | Key | 備考 |     概要
| :--------- | :------- | :--- | :-- | :--- |
| id         | bigint   | NO   | PK  |      |
| level      | string   | NO   |     |      |ログレベル
| message    | text     | NO   |     |      |ログメッセージ
| context    | json     | YES  |     |      |コンテキスト情報
| extra      | json     | YES  |     |      |その他の情報
| created_at | datetime | NO   |     |      |
| updated_at | datetime | NO   |     |      |

### アソシエーション
（なし）
