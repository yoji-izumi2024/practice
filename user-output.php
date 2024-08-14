<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php

	if (isset($_SESSION['user'])) {

		# ユーザー情報の取得
		$stmt = selectLoginNotId($_SESSION['user']['id'], $_REQUEST['login']);

	} else {

		# ユーザー情報の取得
		$stmt = selectLogin($_REQUEST['login']);
	}

	if (empty($stmt->fetchAll())) {

		if (isset($_SESSION['user'])) {

			# ユーザー情報の更新
			updateUser($_REQUEST['user_name'], $_REQUEST['login'], $_REQUEST['password'], $_SESSION['user']['id']);

			# セッションの保存
			$_SESSION['user']=[
				'id'=>$_SESSION['user']['id'], 
				'user_name'=>$_REQUEST['user_name'], 
				'login'=>$_REQUEST['login'], 
				'password'=>$_REQUEST['password']
			];

			# メッセージ表示
			echo 'ユーザー情報を更新しました。';

		} else {

			# ユーザー情報の登録
			insertUser($_REQUEST['user_name'], $_REQUEST['login'], $_REQUEST['password']);

			# メッセージ表示
			echo 'ユーザー情報を登録しました。';
		}
	} else {

		# メッセージ表示
		echo 'ログイン名がすでに使用されていますので、変更してください。';
	}
?>
<?php require 'includes/footer.php'; ?>
