<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php

	# 変数の初期化
	$workbook_id = '';
	$workbook_title = '';
	$percentage = '';

	if (isset($_REQUEST['workbook_id'])) {

		$workbook_id = $_REQUEST['workbook_id'];

		# 検定情報の取得
		$workbook = getWorkbookData($workbook_id);
		if (count($workbook) > 0) {
			$workbook_title = $workbook['workbook_title'];
			$percentage = $workbook['percentage'];			
		}
	}
?>
<h4>検定登録</h4>
<hr>
<ul>
	<li>検定タイトルを入力し、<strong>登録</strong>ボタンをクリックしてください。</li>
</ul>
<hr>
<form action="workbook-output.php" method="post">
	<input type="hidden" name="workbook_id" value="<?php echo $workbook_id; ?>">
	<table class="table">
		<colgroup>
			<col class="input-item">
			<col class="input-value">
		</colgroup>
		<tr>
			<th>検定タイトル</th>
			<td class="full-width-input"><input type="text" name="workbook_title" value="<?php echo $workbook_title; ?>" required></td>
		</tr>
		<tr>
			<th>合格基準(%)</th>
			<td><input type="number" name="percentage" min="0" max="100" value="<?php echo $percentage; ?>" required></td>
		</tr>
	</table>
	<input type="submit" value="登録" class="my-1">
</form>
<?php require 'includes/footer.php'; ?>
