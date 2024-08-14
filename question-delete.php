<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php

	# セッションの削除
	unset($_SESSION['message']);

	# 問題情報の削除
	if (deleteQuestion($_REQUEST['question_id'])) {
		$_SESSION['message'] = '問題情報の削除に成功しました。';
	} else {
		$_SESSION['message'] = '問題情報の削除に失敗しました。';
	}

	# リダイレクト処理
	header('Location: workbook-list.php');
	exit();
?>
<?php require 'includes/footer.php'; ?>
