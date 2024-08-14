<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php

	# セッションの削除
	unset($_SESSION['message']);

	# 検定情報の削除
	if (deleteWorkbook($_REQUEST['workbook_id'])) {
		$_SESSION['message'] = '検定情報の削除に成功しました。';
	} else {
		$_SESSION['message'] = '検定情報の削除に失敗しました。';
	}

	# リダイレクト処理
	header('Location: workbook-list.php');
	exit();
?>
<?php require 'includes/footer.php'; ?>
