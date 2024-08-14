<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php
	# 変数の初期化
	$user_name = '';
	$login = '';
	$password = '';

	if (isset($_SESSION['user'])) {
		# セッションの各値を変数に代入
		$user_name = $_SESSION['user']['user_name'];
		$login = $_SESSION['user']['login'];
		$password = $_SESSION['user']['password'];
	}
?>
<h4>受験生登録</h4>
<hr>
<ul>
	<li>名前、ログイン名、パスワードを入力し、<strong>登録</strong>ボタンをクリックしてください。</li>
</ul>
<hr>
<form action="user-output.php" method="post">
	<table class="table">
		<colgroup>
			<col class="user-item">
			<col class="user-input">
		</colgroup>
		<thead>
		<tr>
			<th>名前</th>
			<td class="full-width-input"><input type="text" name="user_name" value="<?php echo $user_name; ?>" required></td>
		</tr>
		<tr>
			<th>ログイン名</th>
			<td class="full-width-input"><input type="text" name="login" pattern="[a-zA-Z0-9]{4,}" title="ログイン名は4文字以上の英数字のみです。" value="<?php echo $login; ?>" required></td>
		</tr>
		<tr>
			<th>パスワード</th>
			<td class="full-width-input"><input type="password" name="password" pattern="[a-zA-Z0-9]{8,}" title="パスワードは8文字以上の英数字のみです。"  value="<?php echo $password; ?>" required></td>
		</tr>
	</table>
	<div class="user-submit"><input type="submit" value="登録"></div>
</form>
<?php require 'includes/footer.php'; ?>
