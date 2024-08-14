<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php

	# セッションの削除
	unset($_SESSION['message']);

	if (!empty($_REQUEST['question_id'])) {
		# 問題情報の更新
		updateQuestion($_REQUEST['question_text'], $_REQUEST['description'], $_REQUEST['question_id']);

		$_SESSION['message'] = '問題情報を更新しました。';

	} else {
		# 問題情報の登録
		insertQuestion($_REQUEST['question_text'], $_REQUEST['description']);

		$_SESSION['message'] = '問題情報を登録しました。';
	}

	# リダイレクト処理
	header('Location: question-list.php');
	exit();
?>
<?php require 'includes/footer.php'; ?>
