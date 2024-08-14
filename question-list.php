<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<h4>問題登録</h4>
<hr>
<ul>
	<li>新規に登録する場合は、<strong>新規登録</strong>ボタンをクリックしてください。</li>
	<li>変更する場合は、変更したい<strong>問題文</strong>をクリックしてください。</li>
	<li>削除する場合は、削除したい検定タイトルの<strong>削除</strong>をクリックしてください。</li>
	<li>問題の選択肢を登録する場合は、<strong>選択肢</strong>をクリックしてください。</li>
</ul>
<?php
	$message = '';
	if (isset($_SESSION['message'])) {
		$message = $_SESSION['message'];
	}

	# セッションの削除
	unset($_SESSION['message']);
	unset($_SESSION['question_id']);

	echo '<form action="question-input.php" method="post">';
	echo '<input type="submit" value="新規登録" class="my-1">';
	echo '</form>';
	echo '<hr>';

	if (isset($message)) {
		echo '<p>', $message, '</p>';
	}

	echo '<table class="table">';

	echo '<colgroup>';
	echo '<col class="question-id">';
	echo '<col class="question-text">';
	echo '<col class="question-choice">';
	echo '<col class="question-delete">';
	echo '</colgroup>';

	echo '<thead>';
	echo '<tr>';
	echo '<th class="text-center">問題番号</th>';
	echo '<th class="text-center">問題文</th>';
	echo '<th></th>';
	echo '<th></th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';

	# 問題情報の全件取得
	$questions = getQuestionDataList();

	foreach ($questions as $question) {

		$question_id = $question['question_id'];
		$question_text = nl2br(htmlspecialchars($question["question_text"], ENT_QUOTES, 'UTF-8'), false);

		echo '<tr>';
		echo '<td class="text-center">', $question_id, '</td>';
		echo '<td><a href="question-input.php?question_id=', $question_id, '">', $question_text, '</a></td>';
		echo '<td class="text-center"><a href="question-choice-list.php?question_id=', $question_id, '" class="mx-2">選択肢</a></td>';
		echo '<td class="text-center"><a href="question-delete.php?question_id=', $question_id, '" class="mx-2">削除</a></td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
?>
<?php require 'includes/footer.php'; ?>
