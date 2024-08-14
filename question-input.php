<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php

	# 変数の初期化
	$question_id = '';
	$question_text = '';
	$description = '';

	if (isset($_REQUEST['question_id'])) {

		$question_id = $_REQUEST['question_id'];

		# 問題情報の取得
		$question = getQuestionData($question_id);
		if (count($question) > 0) {
			$question_text = $question['question_text'];
			$description = $question['description'];
		}
	}
?>
<h4>問題登録</h4>
<hr>
<ul>
	<li>問題文、解説を入力し、<strong>登録</strong>ボタンをクリックしてください。</li>
</ul>
<hr>
<form action="question-output.php" method="post">
	<input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
	<table class="table">
		<colgroup>
			<col class="user-item">
			<col class="user-input">
		</colgroup>
		<tr>
			<td>問題文</td>
			<td><textarea id="question_text" name="question_text" rows="6"><?php echo $question_text; ?></textarea></td>
		</tr>
		<tr>
			<td>解説</td>
			<td><textarea id="description" name="description" rows="6"><?php echo $description; ?></textarea></td>
		</tr>
	</table>
	<input type="submit" value="登録">
</form>
<?php require 'includes/footer.php'; ?>
