<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<h4>選択肢登録</h4>
<hr>
<ul>
	<li>選択肢文言、選択を入力し、<strong>登録</strong>ボタンをクリックしてください。</li>
</ul>
<?php

	# 変数の初期化
	$question_id = '';
	$choice_id = '';
	$choice_text = '';
	$correct = '';

	if (isset($_SESSION['question_id'])) {
		$question_id = $_SESSION['question_id'];
	}

	if (isset($_REQUEST['choice_id'])) {

		$choice_id = $_REQUEST['choice_id'];

		# 選択肢情報の取得
		$choice = getChoiceData($choice_id);

		$question_id = $choice['question_id'];
		$choice_text = $choice['choice_text'];
		$correct = $choice['correct'];
	}

	echo '<form action="question-choice-output.php" method="post">';
	echo '<input type="hidden" name="choice_id" value="', $choice_id, '">';
	echo '<input type="hidden" name="question_id" value="', $question_id, '">';

	echo '<table class="table">';
	echo '<colgroup>';
	echo '<col class="user-item">';
	echo '<col class="user-input">';
	echo '</colgroup>';
	echo '<tr>';
	echo '<th>選択肢文言</th>';
	echo '<td><textarea id="choice_text" name="choice_text" rows="4">', $choice_text, '</textarea></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th>正解</th>';
	echo '<td class="full-width-input"><input type="checkbox" name="correct" value="' . $correct . '"' . ($correct == '1' ? ' checked' : '') . '></td>';
	echo '</tr>';

	echo '</table>';

	echo '<input type="submit" value="登録" class="my-1">';
	echo '</form>';
?>
<?php require 'includes/footer.php'; ?>
