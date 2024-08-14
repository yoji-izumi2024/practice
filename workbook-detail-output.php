<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php

    if (isset($_SESSION['workbook_id'])) {

        $workbook_id = $_SESSION['workbook_id'];

        # 選択されたアイテムの配列を取得
        $selectedItems = isset($_POST['items']) ? $_POST['items'] : [];

        # 既存の選択を削除
        deleteWorkbookDetail($workbook_id);

        # 新しい選択を挿入
        foreach ($selectedItems as $question_id) {
            # 検定詳細情報の登録
            insertWorkbookDetail($workbook_id, $question_id);
        }

        $_SESSION['message'] = '検定詳細情報を登録しました。';

    } else {
        $_SESSION['message'] = '不正なアクセスが発生しました。';
    }

    # リダイレクト処理
	header('Location: workbook-detail-input.php');
	exit();
?>
<?php require 'includes/footer.php'; ?>
