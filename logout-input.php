<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<h4>ログアウト</h4>
<?php
	if (isset($_SESSION['user'])) {
        echo '<p>', $_SESSION['user']['user_name'], 'さん ログアウトしますか？</p>';
        echo '<form action="logout-output.php" method="POST">';
        echo '<input type="submit" value="ログアウト">';
        echo '</form>';
    } else {
		echo 'ログインしていません。';
    }
?>
<?php require 'includes/footer.php'; ?>
