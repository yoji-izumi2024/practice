<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<h4>ログイン</h4>
<hr>
<?php
	if (isset($_SESSION['user'])) {
		echo '<p>', $_SESSION['user']['user_name'], 'さんがログイン中です。</P><br>';
	}
?>
<ul>
	<li>ログイン名とパスワードを入力し、<strong>ログイン</strong>ボタンをクリックしてください。</li>
</ul>
<hr>
<form action="login-output.php" method="post">
	<table class="table">
		<colgroup>
			<col class="user-item">
			<col class="user-input">
		</colgroup>
		<thead>
			<tr>
				<th>ログイン名</th>
				<td class="full-width-input"><input type="text" name="login"></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>パスワード</th>
				<td class="full-width-input"><input type="password" name="password"></td>
			</tr>
		</tbody>
	</table>
	<div class="user-submit"><input type="submit" value="ログイン"></div>
</form>
<?php require 'includes/footer.php'; ?>
