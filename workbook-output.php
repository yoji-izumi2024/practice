<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php

	# 変数の初期化
	$workbook_id = '';

	# セッションの削除
	unset($_SESSION['message']);

	$percentage = $_REQUEST['percentage'];

	if (filter_var($percentage, FILTER_VALIDATE_INT) !== false && 
					$percentage >= 0 && $percentage <= 100) {

		if (isset($_REQUEST['workbook_id'])) {
			$workbook_id = trim($_REQUEST['workbook_id']);
		}

		if (strlen($workbook_id) > 0) {
			# 検定情報の更新
			updateWorkbook($_REQUEST['workbook_title'], $_REQUEST['percentage'], $workbook_id);

			$_SESSION['message'] = '検定情報を更新しました。';

		} else {
			# 検定情報の登録
			insertWorkbook($_REQUEST['workbook_title'], $_REQUEST['percentage']);

			$_SESSION['message'] = '検定情報を登録しました。';
		}

	} else {
		$_SESSION['message'] = '検定情報は登録できません。合格基準には、０から１００までの値を入力してください。';
	}

	# リダイレクト処理
	header('Location: workbook-list.php');
	exit();
?>
<?php require 'includes/footer.php'; ?>
