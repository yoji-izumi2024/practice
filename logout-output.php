<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php
	if (isset($_SESSION['user'])) {

		# セッションの削除
		unset($_SESSION['user']);

		# メッセージ表示
		echo 'ログアウトしました。';

	} else {

		# メッセージ表示
		echo 'すでにログアウトしています。';
	}
?>
<?php require 'includes/footer.php'; ?>
