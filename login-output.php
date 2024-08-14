<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php
	# セッション削除
	unset($_SESSION['user']);

	# ユーザー情報の取得
	$user = getUserData($_REQUEST['login']);
	
	if (count($user) > 0) {

		# パスワード検証
		if (password_verify($_REQUEST['password'], $user['password'])) {

			#　セッションの保存
			$_SESSION['user']=[
				'id'=>$user['user_id'], 
				'user_name'=>$user['user_name'], 
				'login'=>$user['login'], 
				'password'=>$user['password']
			];
		}
	}

	# メッセージ表示
	if (isset($_SESSION['user'])) {
		echo 'いらっしゃいませ、', $_SESSION['user']['user_name'], 'さん。';
	} else {
		echo 'ユーザー名またはパスワードが違います。';
	}
?>
<?php require 'includes/footer.php'; ?>
