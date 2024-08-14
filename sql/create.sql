# 存在する場合、データベース削除
drop database if exists practice_db;

# データベース作成
create database practice_db default character set utf8 collate utf8_general_ci;

# 存在する場合、ユーザー削除
drop user if exists 'practice'@'localhost';

# ユーザー作成
create user 'practice'@'localhost' identified by 'password';

# ユーザーに権限付与
grant all on practice_db.* to 'practice'@'localhost';

# データベースの指定
use practice_db;

# ユーザーテーブル作成
DROP TABLE IF EXISTS user;
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,  # ユーザーID
    user_name VARCHAR(255),             # ユーザー名
    login VARCHAR(255) UNIQUE,          # ログイン名
    password VARCHAR(255)               # パスワード
);

# 検定テーブル作成
DROP TABLE IF EXISTS workbook;
CREATE TABLE workbook (
    id INT AUTO_INCREMENT PRIMARY KEY,  # 検定番号
    workbook_title VARCHAR(255),        # 検定タイトル
    percentage INT                      # 合格基準
);

# 検定詳細テーブル作成
DROP TABLE IF EXISTS workbook_detail;
CREATE TABLE workbook_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,  # 検定詳細番号
    workbook_id INT,                    # 検定番号
    question_id INT                     # 問題番号
);

# 問題テーブル作成
DROP TABLE IF EXISTS question;
CREATE TABLE question (
    id INT AUTO_INCREMENT PRIMARY KEY,  # 問題番号
    question_text VARCHAR(255),         # 問題文
    description TEXT                    # 解説
);

# 選択肢テーブル作成
DROP TABLE IF EXISTS choice;
CREATE TABLE choice (
    id INT AUTO_INCREMENT PRIMARY KEY,  # 選択肢番号
    question_id INT,                    # 問題番号
    choice_text VARCHAR(255),           # 選択肢文言
    correct VARCHAR(1)                  # 正解 '0':不正解、'1':正解
);

# 解答テーブル作成
DROP TABLE IF EXISTS answer;
CREATE TABLE answer (
    id INT AUTO_INCREMENT PRIMARY KEY,  # 解答番号
    user_id INT,                        # ユーザーID
    user_name VARCHAR(255),             # ユーザー名
    workbook_id INT,                    # 検定番号
    workbook_title VARCHAR(255),        # 検定タイトル
    exam_date DATETIME,                 # 受験日時
    interval_time VARCHAR(255),         # 受験時間
    exam_results VARCHAR(255),          # 受験結果
    number_percentage INT,              # 合格基準（％）
    number_question INT,                # 問題数
    number_correct INT,                 # 正解数
    number_incorrect INT                # 不正解数
);

# 解答詳細テーブル作成
DROP TABLE IF EXISTS answer_detail;
CREATE TABLE answer_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,  # 解答詳細番号
    answer_id INT,                      # 解答番号
    question_text VARCHAR(255),         # 問題文
    description TEXT,                   # 解説
    answer VARCHAR(1)                   # 解答 '0':未選択、'1':選択
);
