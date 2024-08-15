<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php
	$message = '';
	if (isset($_SESSION['message'])) {
		$message = $_SESSION['message'];
	}
	$workbook_id = '';
	$workbook_title = '';

	# セッションの削除
	unset($_SESSION['message']);

	if (isset($_REQUEST['workbook_id'])) {
		$workbook_id = $_REQUEST['workbook_id'];
		$_SESSION['workbook_id'] = $workbook_id;

	} elseif (isset($_SESSION['workbook_id'])) {
		$workbook_id = $_SESSION['workbook_id'];
	}	

	# 検定情報の取得
	$workbook = getWorkbookData($workbook_id);
	if (count($workbook) > 0) {
		$workbook_title = $workbook['workbook_title'];
	}
	# 検定詳細情報の取得
	$details = getWorkbookDetailDataByWorkbookId($workbook_id);

	# 選択された項目を配列に保存
	$selectedItems = [];
	if (count($details) > 0) {
		foreach ($details as $detail) {
			$selectedItems[] = $detail["question_id"];
		}
	}

	# 問題情報の全件取得
	$questions = getQuestionDataList();
?>
<p>検定<?php echo $workbook_id; ?>. <?php echo $workbook_title; ?></p>
<hr>
<ul>
	<li>検定に紐づける問題を選択し、<strong>登録</strong>ボタンをクリックしてください。</li>
</ul>
<?php
	if (isset($message)) {
		echo '<p>', $message, '</p>';
	}
?>
<table class="table">
	<colgroup>
		<col class="workbook-question-checkbox">
		<col class="workbook-question-text">
	</colgroup>
	<tr>
		<th class="text-center">選択</th>
		<th class="text-center">問題文</th>
	</tr>
	<form action="workbook-detail-output.php" method="post">
		<input type="hidden" name="workbook_id" value="<?php echo $workbook_id; ?>">
		<?php foreach ($questions as $question): ?>
		<tr>
			<td class="text-center">
				<input type="checkbox" name="items[]" value="<?php echo $question['question_id']; ?>" id="<?php echo $question['question_id']; ?>" <?php echo in_array($question['question_id'], $selectedItems) ? 'checked' : ''; ?>>
			</td>
			<td>
				<label for="<?php echo $question['question_id']; ?>">
				<?php echo $question['question_text']; ?>
				</label>
			</td>
		</tr>
		<?php endforeach; ?>
		<input type="submit" value="登録">
		<hr>
	</form>
</table>
<br>
<br>
<?php require 'includes/footer.php'; ?>
