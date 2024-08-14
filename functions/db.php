<?php

/**
* データベースへの接続
*  
* @param なし
* @return $pdo 接続情報
*/
function connectToDatabase() {

    # データベース接続情報
	$dsn = 'mysql:host=localhost;dbname=practice_db;charset=utf8';
	$user_name = 'practice';
	$password = 'password';

    try {

        $pdo = new PDO($dsn, $user_name, $password);

        # エラーモードを例外に設定
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        # プリペアドステートメントのエミュレーションを無効に設定（ネイティブのプリペアドステートメントを優先）
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $pdo;

    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* ユーザー情報の取得
*  
* @param $login ログイン名
* @return $stmt ユーザー情報
*/
function selectUser($login) {

    # SQL文
	$sql = 'SELECT * FROM user WHERE login=:login ';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);

        # プリペアドステートメントを実行
        $stmt->execute();

        return $stmt;

    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* ユーザー情報の取得
*  
* @param $login ログイン名
* @return array $user ユーザー情報
*/
function getUserData($login) {

    # ユーザー情報の取得
    $stmt = selectUser($login);

    $user = [];
    foreach ($stmt as $row) {
        $user['user_id'] = $row['id'];
        $user['user_name'] = htmlspecialchars_decode($row['user_name'], ENT_QUOTES);
        $user['login'] = $row['login'];
        $user['password'] = $row['password'];
    }

    return $user;
}

/**
* ユーザー情報の取得
*  
* @param $user_id ユーザーID
* @param $login ログイン名
* @return $stmt ユーザー情報
*/
function selectLoginNotId($user_id, $login) {

    # SQL文
    $sql = 'SELECT * FROM user WHERE id!=:user_id AND login=:login';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
    
        # プリペアドステートメントを実行
        $stmt->execute();
    
        return $stmt;
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* ユーザー情報の取得
*  
* @param $login ログイン名
* @return $stmt ユーザー情報
*/
function selectLogin($login) {

    # SQL文
    $sql = 'SELECT * FROM user WHERE login=:login';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
    
        # プリペアドステートメントを実行
        $stmt->execute();
    
        return $stmt;
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* ユーザー情報の登録
*  
* @param $user_name ユーザー名
* @param $login ログイン名
* @param $password パスワード
* @return boolean 登録結果
*/
function insertUser($user_name, $login, $password) {

    # XSS攻撃対策
    $user_name = htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8');

    # パスワードのハッシュ化
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    # SQL文
    $sql = 'INSERT INTO user (user_name, login, password) VALUES (:user_name, :login, :password)';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
    
        # プリペアドステートメントを実行
        return $stmt->execute();
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* ユーザー情報の更新
*  
* @param $user_name ユーザー名
* @param $login ログイン名
* @param $password パスワード
* @param $user_id ユーザーID
* @return boolean 更新結果
*/
function updateUser($user_name, $login, $password, $user_id) {

    # XSS攻撃対策
    $user_name = htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8');

    # パスワードのハッシュ化
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    # SQL文
    $sql = 'UPDATE user SET user_name=:user_name, login=:login, password=:password WHERE id=:user_id';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    
        # プリペアドステートメントを実行
        return $stmt->execute();
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 検定情報の取得
*  
* @param $workbook_id 検定番号(デフォルト値='')
* @return $stmt 検定情報
*/
function selectWorkbook($workbook_id='') {

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        if (strlen(trim($workbook_id)) > 0) {
            # SQL文
            $sql = 'SELECT * FROM workbook WHERE id=:workbook_id ORDER BY id';
    
            # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
            $stmt = $pdo->prepare($sql);
    
            # プレースホルダへ実際の値を設定
            $stmt->bindParam(':workbook_id', $workbook_id, PDO::PARAM_INT);
    
        } else {
            # SQL文
            $sql = 'SELECT * FROM workbook ORDER BY id';
    
            # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
            $stmt = $pdo->prepare($sql);
        }
    
        # プリペアドステートメントを実行
        $stmt->execute();
    
        return $stmt;
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 検定情報の取得
*  
* @param $workbook_id 検定番号
* @return array $workbook 検定情報
*/
function getWorkbookData($workbook_id) {

    # 検定情報の取得
    $stmt = selectWorkbook($workbook_id);

    $workbook = [];
    foreach ($stmt as $row) {
        $workbook['workbook_id'] = $row['id'];
        $workbook['workbook_title'] = htmlspecialchars_decode($row['workbook_title'], ENT_QUOTES);
        $workbook['percentage'] = $row['percentage'];
    }

    return $workbook;
}

/**
* 検定情報の全件取得
*  
* @param なし
* @return array $workbooks 検定情報
*/
function getWorkbookDataList() {

    # 検定情報の取得
    $stmt = selectWorkbook();

    $workbooks = [];
    foreach ($stmt as $row) {
        $workbook = [];
        $workbook['workbook_id'] = $row['id'];
        $workbook['workbook_title'] = htmlspecialchars_decode($row['workbook_title'], ENT_QUOTES);
        $workbook['percentage'] = $row['percentage'];
        $workbooks[] = $workbook;
    }

    return $workbooks;
}

/**
* 検定情報の登録
*  
* @param $workbook_title 検定タイトル
* @param $percentage 合格基準
* @return boolean 登録結果
*/
function insertWorkbook($workbook_title, $percentage) {

    # XSS攻撃対策
    $workbook_title = htmlspecialchars($workbook_title, ENT_QUOTES, 'UTF-8');

    # SQL文
    $sql = 'INSERT INTO workbook (workbook_title, percentage) VALUES (:workbook_title, :percentage)';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':workbook_title', $workbook_title, PDO::PARAM_STR);
        $stmt->bindParam(':percentage', $percentage, PDO::PARAM_INT);
    
        # プリペアドステートメントを実行
        return $stmt->execute();
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 検定情報の更新
*  
* @param $workbook_title 検定タイトル
* @param $percentage 合格基準
* @param $workbook_id 検定番号
* @return boolean 更新結果
*/
function updateWorkbook($workbook_title, $percentage, $workbook_id) {

    # XSS攻撃対策
    $workbook_title = htmlspecialchars($workbook_title, ENT_QUOTES, 'UTF-8');

    # SQL文
    $sql = 'UPDATE workbook SET workbook_title=:workbook_title, percentage=:percentage WHERE id=:workbook_id';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':workbook_title', $workbook_title, PDO::PARAM_STR);
        $stmt->bindParam(':percentage', $percentage, PDO::PARAM_INT);
        $stmt->bindParam(':workbook_id', $workbook_id, PDO::PARAM_INT);
    
        # プリペアドステートメントを実行
        return $stmt->execute();
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 検定情報の削除
*  
* @param $workbook_id 検定番号
* @return boolean 削除結果
*/
function deleteWorkbook($workbook_id) {

    # SQL文
    $sql = 'DELETE FROM workbook WHERE id=:workbook_id';

    try {
         # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':workbook_id', $workbook_id, PDO::PARAM_INT);
    
        # プリペアドステートメントを実行
        return $stmt->execute();
     
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 検定詳細情報の取得
*  
* @param $workbook_id 検定番号(デフォルト値='')
* @return $stmt 検定詳細情報
*/
function selectWorkbookDetailByWorkbookId($workbook_id='') {

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        if (strlen(trim($workbook_id)) > 0) {
            # SQL文
            $sql = 'SELECT d.id, d.workbook_id, d.question_id, q.question_text, q.description ' .
                        'FROM workbook_detail d, question q ' .
                        'WHERE d.question_id = q.id AND d.workbook_id=:workbook_id ' .
                        'ORDER BY workbook_id, question_id';
    
            # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
            $stmt = $pdo->prepare($sql);
    
            # プレースホルダへ実際の値を設定
            $stmt->bindParam(':workbook_id', $workbook_id, PDO::PARAM_INT);
    
        } else {
            # SQL文
            $sql = 'SELECT d.id, d.workbook_id, d.question_id, q.question_text, q.description ' .
                        'FROM workbook_detail d, question q ' .
                        'WHERE d.question_id = q.id ' .
                        'ORDER BY workbook_id, question_id';
    
            # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
            $stmt = $pdo->prepare($sql);
        }
    
        # プリペアドステートメントを実行
        $stmt->execute();
    
        return $stmt;
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 検定詳細情報の取得
*  
* @param $workbook_id 検定番号
* @return array $workbookdetails 検定詳細情報
*/
function getWorkbookDetailDataByWorkbookId($workbook_id) {

    # 検定詳細情報の取得
    $stmt = selectWorkbookDetailByWorkbookId($workbook_id);

    $workbookdetails = [];
    foreach ($stmt as $row) {
        $workbookdetail = [];
        $workbookdetail['detail_id'] = $row['id'];
        $workbookdetail['workbook_id'] = $row['workbook_id'];
        $workbookdetail['question_id'] = $row['question_id'];
        $workbookdetail['question_text'] = htmlspecialchars_decode($row['question_text'], ENT_QUOTES);
        $workbookdetail['description'] = htmlspecialchars_decode($row['description'], ENT_QUOTES);
        $workbookdetails[] = $workbookdetail;
    }

    return $workbookdetails;
}

/**
* 検定詳細情報の全件取得
*  
* @param なし
* @return array $workbookdetails 検定詳細情報
*/
function getWorkbookDetailDataList() {

    # 検定詳細情報の取得
    $stmt = selectWorkbookDetailByWorkbookId();

    $workbookdetails = [];
    foreach ($stmt as $row) {
        $workbookdetail = [];
        $workbookdetail['detail_id'] = $row['id'];
        $workbookdetail['workbook_id'] = $row['workbook_id'];
        $workbookdetail['question_id'] = $row['question_id'];
        $workbookdetail['question_text'] = htmlspecialchars_decode($row['question_text'], ENT_QUOTES);
        $workbookdetail['description'] = htmlspecialchars_decode($row['description'], ENT_QUOTES);
        $workbookdetails[] = $workbookdetail;
    }

    return $workbookdetails;
}

/**
* 検定詳細情報の登録
*  
* @param $workbook_id 検定番号
* @param $question_id 問題番号
* @return boolean 登録結果
*/
function insertWorkbookDetail($workbook_id, $question_id) {

    # SQL文
    $sql = 'INSERT INTO workbook_detail (workbook_id, question_id) VALUES (:workbook_id, :question_id)';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':workbook_id', $workbook_id, PDO::PARAM_INT);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
    
        # プリペアドステートメントを実行
        return $stmt->execute();
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 検定詳細情報の削除
*  
* @param $workbook_id 検定番号
* @return boolean 削除結果
*/
function deleteWorkbookDetail($workbook_id) {

    # SQL文
    $sql = 'DELETE FROM workbook_detail WHERE workbook_id=:workbook_id';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':workbook_id', $workbook_id, PDO::PARAM_INT);
    
        # プリペアドステートメントを実行
        return $stmt->execute();
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 問題情報の取得
*  
* @param $question_id 問題番号(デフォルト値='')
* @return $stmt 問題情報
*/
function selectQuestion($question_id='') {

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        if (strlen(trim($question_id)) > 0) {
            # SQL文
            $sql = 'SELECT * FROM question WHERE id=:question_id ORDER BY id';
    
            # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
            $stmt = $pdo->prepare($sql);
    
            # プレースホルダへ実際の値を設定
            $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
    
        } else {
            # SQL文
            $sql = 'SELECT * FROM question ORDER BY id';
    
            # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
            $stmt = $pdo->prepare($sql);
        }
    
        # プリペアドステートメントを実行
        $stmt->execute();
    
        return $stmt;
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 問題情報の取得
*  
* @param $question_id 問題番号
* @return array $question 問題情報
*/
function getQuestionData($question_id) {

    # 問題情報の取得
    $stmt = selectQuestion($question_id);

    $question = [];
    foreach ($stmt as $row) {
        $question['question_id'] = $row['id'];
        $question['question_text'] = htmlspecialchars_decode($row['question_text'], ENT_QUOTES);
        $question['description'] = htmlspecialchars_decode($row['description'], ENT_QUOTES);
    }

    return $question;
}

/**
* 問題情報の全件取得
*  
* @param なし
* @return array $questions 問題情報
*/
function getQuestionDataList() {

    # 問題情報の取得
    $stmt = selectQuestion();

    $questions = [];
    foreach ($stmt as $row) {
        $question = [];
        $question['question_id'] = $row['id'];
        $question['question_text'] = htmlspecialchars_decode($row['question_text'], ENT_QUOTES);
        $question['description'] = htmlspecialchars_decode($row['description'], ENT_QUOTES);
        $questions[] = $question;
    }

    return $questions;
}

/**
* 問題情報の登録
*  
* @param $question_text 問題文
* @param $description 解説
* @return boolean 登録結果
*/
function insertQuestion($question_text, $description) {

    # XSS攻撃対策
    $question_text = htmlspecialchars($question_text, ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');

    # SQL文
    $sql = 'INSERT INTO question (question_text, description) VALUES (:question_text, :description)';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':question_text', $question_text, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    
        # プリペアドステートメントを実行
        return $stmt->execute();
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 問題情報の更新
*  
* @param $question_text 問題文
* @param $description 解説
* @param $question_id 問題番号
* @return boolean 更新結果
*/
function updateQuestion($question_text, $description, $question_id) {

    # XSS攻撃対策
    $question_text = htmlspecialchars($question_text, ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');

    # SQL文
    $sql = 'UPDATE question SET question_text=:question_text, description=:description WHERE id=:question_id';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':question_text', $question_text, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
    
        # プリペアドステートメントを実行
        return $stmt->execute();
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 問題情報の削除
*  
* @param $question_id 問題番号
* @return boolean 削除結果
*/
function deleteQuestion($question_id) {

    # SQL文
    $sql = 'DELETE FROM question WHERE id=:question_id';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
    
        # プリペアドステートメントを実行
        return $stmt->execute();
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 選択肢情報の取得
*  
* @param $choice_id 選択肢番号
* @return $stmt 選択肢情報
*/
function selectChoice($choice_id) {

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # SQL文
        $sql = 'SELECT * FROM choice WHERE id=:choice_id ORDER BY question_id, id';

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':choice_id', $choice_id, PDO::PARAM_INT);
    
        # プリペアドステートメントを実行
        $stmt->execute();
    
        return $stmt;
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 選択肢情報の取得
*  
* @param $choice_id 選択肢番号
* @return array $choice 選択肢情報
*/
function getChoiceData($choice_id) {

    # 選択肢情報の取得
    $stmt = selectChoice($choice_id);

    $choice = [];
    foreach ($stmt as $row) {
        $choice['choice_id'] = $row['id'];
        $choice['question_id'] = $row['question_id'];
        $choice['choice_text']  = htmlspecialchars_decode($row['choice_text'], ENT_QUOTES);
        $choice['correct'] = $row['correct'];
    }

    return $choice;
}

/**
* 選択肢番号に紐づく問題番号の取得
*  
* @param $choice_id 選択肢番号
* @return $choice['question_id'] 問題番号
*/
function getChoiceQuestId($choice_id) {

    # 選択肢情報の取得
    $choice = getChoiceData($choice_id);

    return $choice['question_id'];
}

/**
* 選択された選択肢の問題番号と一致する選択肢番号の取得
*  
* @param $sessions セッションの選択肢番号
* @param $question_id 問題番号
* @return array $choice_answer 選択肢番号
*/
function getChoiceAnswer($sessions, $question_id) {

    $choice_answer = [];
    foreach ($sessions as $id) {
        # セッションの選択肢番号から取得した問題番号と一致する場合
        if (getChoiceQuestId($id) == $question_id) {
            # セッションの選択肢番号を格納
            $choice_answer[] = (int)$id;
        }
    }

    return $choice_answer;
}

/**
* 選択肢情報の取得
*  
* @param $question_id 問題番号
* @return $stmt 選択肢情報
*/
function selectChoiceByQuestionId($question_id) {

    # SQL文
    $sql = 'SELECT * FROM choice WHERE question_id=:question_id ORDER BY question_id, id';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
    
        # プリペアドステートメントを実行
        $stmt->execute();
    
        return $stmt;
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 選択肢情報の取得
*  
* @param $question_id 問題番号
* @return array $choices 選択肢情報
*/
function getChoiceDataListByQuestionId($question_id) {

    # 選択肢情報の取得
    $stmt = selectChoiceByQuestionId($question_id);

    $choices = [];
    foreach ($stmt as $row) {
        $choice = [];
        $choice['choice_id'] = $row['id'];
        $choice['question_id'] = $row['question_id'];
        $choice['choice_text']  = htmlspecialchars_decode($row['choice_text'], ENT_QUOTES);
        $choice['correct'] = $row['correct'];
        $choices[] = $choice;
    }

    return $choices;
}

/**
* 問題情報と問題詳細情報の取得
*  
* @param $workbook_id 検定番号
* @return array $questions 問題情報と問題詳細情報
*/
function getQuestionDataListByWorkbookId($workbook_id) {

    # 検定詳細情報の取得
    $details = getWorkbookDetailDataByWorkbookId($workbook_id);

    $questions = [];
    foreach ($details as $detail) {

        # 選択肢情報の取得
        $choices = getChoiceDataListByQuestionId($detail['question_id']);

        $questions[] = [
            'detail_id' => $detail['detail_id'],
            'workbook_id' => $detail['workbook_id'],
            'question_id' => $detail['question_id'],
            'question_text' => htmlspecialchars_decode($detail['question_text'], ENT_QUOTES),
            'description' => $detail['description'],
            'choices' => $choices
        ];
    }

    return $questions;
}

/**
* 指定した問題の正解となる選択肢番号の取得
*  
* @param $question_id 問題番号
* @return array $corrects 正解となる選択肢番号
*/
function getChoiceCorrectListByQuestionId($question_id) {

    # 選択肢情報の取得
    $choices = getChoiceDataListByQuestionId($question_id);

    $corrects = [];
    foreach ($choices as $choice) {
        if ($choice['correct'] == '1') {
            $corrects[] = $choice['choice_id'];
        }
    }

    return $corrects;
}

/**
* 選択肢文言の取得
*  
* @param $sessions セッションの選択された選択肢番号
* @param $question_id 問題番号
* @return array $choices_text 選択肢文言
*/
function getChoiceTextList($sessions, $question_id) {

    # 選択肢情報の取得
    $choices = getChoiceDataListByQuestionId($question_id);

    $choices_text = [];
    foreach ($choices as $choice) {
        foreach ($sessions as $id) {
            # セッションの選択肢番号と一致する場合
            if ($id == $choice['choice_id']) {
                # 選択肢番号と選択肢文言を格納
                $choices_text[] = [
                    'choice_id'=>$choice['choice_id'],
                    'choice_text'=>htmlspecialchars_decode($choice['choice_text'], ENT_QUOTES)
                ];
            }
        }
    }

    return $choices_text;
}

/**
* 選択肢情報の登録
*  
* @param $question_id 問題番号
* @param $choice_text 選択肢文言
* @param $correct 正解
* @return boolean 登録結果
*/
function insertChoice($question_id, $choice_text, $correct) {

    # XSS攻撃対策
    $choice_text = htmlspecialchars($choice_text, ENT_QUOTES, 'UTF-8');

    # SQL文
    $sql = 'INSERT INTO choice (question_id, choice_text, correct) VALUES (:question_id, :choice_text, :correct)';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->bindParam(':choice_text', $choice_text, PDO::PARAM_STR);
        $stmt->bindParam(':correct', $correct, PDO::PARAM_STR);
    
        # プリペアドステートメントを実行
        return $stmt->execute();
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 選択肢情報の更新
*  
* @param $question_id 問題番号
* @param $choice_text 選択肢文言
* @param $correct 正解
* @param $choice_id 選択肢番号
* @return boolean 更新結果
*/
function updateChoice($question_id, $choice_text, $correct, $choice_id) {

    # XSS攻撃対策
    $choice_text = htmlspecialchars($choice_text, ENT_QUOTES, 'UTF-8');

    # SQL文
    $sql = 'UPDATE choice SET question_id=:question_id, choice_text=:choice_text, correct=:correct WHERE id=:choice_id';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->bindParam(':choice_text', $choice_text, PDO::PARAM_STR);
        $stmt->bindParam(':correct', $correct, PDO::PARAM_STR);
        $stmt->bindParam(':choice_id', $choice_id, PDO::PARAM_INT);
    
        # プリペアドステートメントを実行
        return $stmt->execute();
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 選択肢情報の削除
*  
* @param $choice_id 選択肢番号
* @return boolean 削除結果
*/
function deleteChoice($choice_id) {

    # SQL文
    $sql = 'DELETE FROM choice WHERE id=:choice_id';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':choice_id', $choice_id, PDO::PARAM_INT);
    
        # プリペアドステートメントを実行
        return $stmt->execute();
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 解答情報の取得
*  
* @param $answer_id 解答番号
* @return $stmt 解答情報
*/
function selectAnswer($answer_id) {

    # SQL文
    $sql = 'SELECT * FROM answer WHERE id=:answer_id';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':answer_id', $answer_id, PDO::PARAM_INT);
    
        # プリペアドステートメントを実行
        $stmt->execute();
    
        return $stmt;
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 解答情報の取得
*  
* @param $answer_id 解答番号
* @return array $answer 解答情報
*/
function getAnswerData($answer_id) {

    # 解答情報の取得
    $stmt = selectAnswer($answer_id);

    $answer = [];
    foreach ($stmt as $row) {
        $answer['answer_id'] = $row['id'];
        $answer['user_id'] = $row['user_id'];
        $answer['user_name'] = $row['user_name'];
        $answer['workbook_id'] = $row['workbook_id'];
        $answer['workbook_title'] = $row['workbook_title'];
        $answer['exam_date'] = $row['exam_date'];
        $answer['interval_time'] = $row['interval_time'];
        $answer['exam_results'] = $row['exam_results'];
        $answer['number_percentage'] = $row['number_percentage'];
        $answer['number_question'] = $row['number_question'];
        $answer['number_correct'] = $row['number_correct'];
        $answer['number_incorrect'] = $row['number_incorrect'];
    }
    return $answer;
}

/**
* 解答情報の取得
*  
* @param $user_id ユーザー番号
* @return $stmt 解答情報
*/
function selectAnswerByUserId($user_id) {

    # SQL文
    $sql = 'SELECT * FROM answer WHERE user_id=:user_id ORDER BY exam_date DESC';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    
        # プリペアドステートメントを実行
        $stmt->execute();
    
        return $stmt;
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 解答情報の取得
*  
* @param $user_id ユーザー番号
* @return array $answers 解答情報
*/
function getAnswerDataListByUserId($user_id) {

    # 解答情報の取得
    $stmt = selectAnswerByUserId($user_id);

    $answers = [];
    foreach ($stmt as $row) {
        $answer = [];
        $answer['answer_id'] = $row['id'];
        $answer['user_id'] = $row['user_id'];
        $answer['user_name'] = $row['user_name'];
        $answer['workbook_id'] = $row['workbook_id'];
        $answer['workbook_title'] = $row['workbook_title'];
        $answer['exam_date'] = $row['exam_date'];
        $answer['exam_results'] = $row['exam_results'];
        $answer['number_percentage'] = $row['number_percentage'];
        $answer['number_question'] = $row['number_question'];
        $answer['number_correct'] = $row['number_correct'];
        $answer['number_incorrect'] = $row['number_incorrect'];
        $answers[] = $answer;
    }

    return $answers;
}

/**
* 解答情報の解答番号の最大値の取得
*  
* @param なし
* @return $stmt 解答番号の最大値
*/
function selectAnswerMaxId() {

    # SQL文
    $sql = 'SELECT MAX(id) as max_id FROM answer';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プリペアドステートメントを実行
        $stmt->execute();
    
        return $stmt;
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 解答情報の解答番号の最大値の取得
*  
* @param なし
* @return $max_id 解答番号の最大値
*/
function getAnswerMaxId() {

    # 解答情報の解答番号の最大値の取得
    $stmt = selectAnswerMaxId();

    $max_id = '0';
    foreach ($stmt as $row) {
        $max_id = $row['max_id'];
    }

    return (int)$max_id;
}

/**
* 合否判定結果の取得
*  
* @param $number_percentage 合格基準
* @param $number_question 問題数
* @param $number_correct 正解数
* @return 合否判定結果
*/
function getExamEesults($number_percentage, $number_question, $number_correct) {

    # 変数の初期化
    $exam_results = '';

    # データが正しいか確認
    if ($number_question > 0) {

        # 正解率を計算
        $result_percentage = ($number_correct / $number_question) * 100;

        # 合否判定
        if ($result_percentage >= $number_percentage) {
            $exam_results = '合格';
        } else {
            $exam_results = '不合格';
        }
    } else {
        $exam_results = '算定不可';
    }

    return $exam_results;
}

/**
* 解答情報の登録
*  
* @param $user_id ユーザーID
* @param $user_name ユーザー名
* @param $workbook_id 検定番号
* @param $workbook_title 検定タイトル
* @param $interval_time 受験時間
* @param $workbook_percentage 合格基準
* @param $number_question 問題数
* @param $number_correct 正解数
* @param $number_incorrect 不正解数
* @return boolean 登録結果
*/
function insertAnswer($user_id, $user_name, $workbook_id, $workbook_title, $interval_time, $number_percentage, $number_question, $number_correct, $number_incorrect) {

    # XSS攻撃対策
    $user_name = htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8');
    $workbook_title = htmlspecialchars($workbook_title, ENT_QUOTES, 'UTF-8');

    # 合否判定結果の取得
    $exam_results = getExamEesults($number_percentage, $number_question, $number_correct);

    # SQL文
    $sql = 'INSERT INTO answer (' .
                            'user_id,' . 
                            'user_name,' . 
                            'workbook_id,' . 
                            'workbook_title,' . 
                            'exam_date,' . 
                            'interval_time,' . 
                            'exam_results,' . 
                            'number_percentage,' . 
                            'number_question,' . 
                            'number_correct,' . 
                            'number_incorrect' .
                        ') VALUES (' . 
                            ':user_id,' . 
                            ':user_name,' . 
                            ':workbook_id,' . 
                            ':workbook_title,' . 
                            'now(),' . 
                            ':interval_time,' . 
                            ':exam_results,' . 
                            ':number_percentage,' . 
                            ':number_question,' . 
                            ':number_correct,' . 
                            ':number_incorrect' .
                        ')';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
        $stmt->bindParam(':workbook_id', $workbook_id, PDO::PARAM_INT);
        $stmt->bindParam(':workbook_title', $workbook_title, PDO::PARAM_STR);
        $stmt->bindParam(':interval_time', $interval_time, PDO::PARAM_STR);
        $stmt->bindParam(':exam_results', $exam_results, PDO::PARAM_STR);
        $stmt->bindParam(':number_percentage', $number_percentage, PDO::PARAM_INT);
        $stmt->bindParam(':number_question', $number_question, PDO::PARAM_INT);
        $stmt->bindParam(':number_correct', $number_correct, PDO::PARAM_INT);
        $stmt->bindParam(':number_incorrect', $number_incorrect, PDO::PARAM_INT);

        # プリペアドステートメントを実行
        return $stmt->execute();

    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 解答詳細情報の取得
*  
* @param $answer_id 解答番号
* @return $stmt 解答詳細情報
*/
function selectAnswerDetailByAnswerId($answer_id) {

    # SQL文
    $sql = 'SELECT * FROM answer_detail WHERE answer_id=:answer_id ORDER BY id';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':answer_id', $answer_id, PDO::PARAM_INT);

        # プリペアドステートメントを実行
        $stmt->execute();
    
        return $stmt;
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 解答詳細情報の取得
*  
* @param $answer_id 解答番号
* @return array $details 解答詳細情報
*/
function getAnswerDetailDataListByAnswerId($answer_id) {

    # 解答詳細情報の取得
    $stmt = selectAnswerDetailByAnswerId($answer_id);

    $details = [];
    foreach ($stmt as $row) {
        $detail = [];
        $detail['detail_id'] = $row['id'];
        $detail['answer_id'] = $row['answer_id'];
        $detail['question_text'] = $row['question_text'];
        $detail['description'] = $row['description'];
        $detail['answer'] = $row['answer'];
        $details[] = $detail;
    }

    return $details;
}

/**
* 解答詳細情報の登録
*  
* @param $answer_id 解答番号
* @param $question_text 問題文
* @param $description 解説
* @param $answer 解答
* @return boolean 登録結果
*/
function insertAnswerDetail($answer_id, $question_text, $description, $answer) {

    # XSS攻撃対策
    $question_text = htmlspecialchars($question_text, ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');

    # SQL文
    $sql = 'INSERT INTO answer_detail (answer_id, question_text, description, answer) VALUES (:answer_id, :question_text, :description, :answer)';

    try {
        # データベースに接続
        $pdo = connectToDatabase();

        # プレースホルダを用いたSQL文を生成（プリペアドステートメント）
        $stmt = $pdo->prepare($sql);

        # プレースホルダへ実際の値を設定
        $stmt->bindParam(':answer_id', $answer_id, PDO::PARAM_INT);
        $stmt->bindParam(':question_text', $question_text, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':answer', $answer, PDO::PARAM_STR);
    
        # プリペアドステートメントを実行
        return $stmt->execute();
    
    } catch (PDOException $e) {
        echo 'PDOException:', __FILE__, ':', __LINE__, ':', __FUNCTION__, ':', $e->getMessage();
        exit;
    }
}

/**
* 解答情報と解答詳細情報の登録
*  
* @param $user_id ユーザーID
* @param $user_name ユーザー名
* @param $workbook_id 検定番号
* @param $workbook_title 検定タイトル
* @param $workbook_percentage 合格基準
* @param $number_question 問題数
* @param $number_correct 正解数
* @param $number_incorrect 不正解数
* @param $answers 解答詳細情報
* @return なし
*/
function insertAnswerRecult($user_id, $user_name, $workbook_id, $workbook_title, $interval_time, $workbook_percentage, $number_question, $number_correct, $number_incorrect, $answers) {

    # 解答情報の登録
    insertAnswer(
        $user_id, 
        $user_name, 
        $workbook_id, 
        $workbook_title, 
        $interval_time,
        $workbook_percentage,
        $number_question, 
        $number_correct, 
        $number_incorrect
    );
    
    # 解答番号の最大値の取得
    $answer_id = getAnswerMaxId();

    # 解答詳細情報の登録
    foreach($answers as $answer) {
        insertAnswerDetail(
            $answer_id,
            $answer['question_text'], 
            $answer['description'], 
            $answer['answer_correct']
        );
    }
}

/**
* 文字列の比較
*  
* @param $array1 文字列の比較対象１
* @param $array2 文字列の比較対象２
* @return boolean 比較結果
*/
function arraysAreEqual($array1, $array2) {

    # 配列の長さが異なる場合は一致しない
    if (count($array1) !== count($array2)) {
        return false;
    }

    # 配列の要素が全て一致するかをチェック
    for ($i = 0; $i < count($array1); $i++) {
        if ($array1[$i] !== $array2[$i]) {
            return false;
        }
    }

    # 全ての要素が一致する場合はtrueを返す
    return true;
}

?>
