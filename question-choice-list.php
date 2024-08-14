<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php
	if (isset($_REQUEST['question_id'])) {
		$question_id = $_REQUEST['question_id'];
		$_SESSION['question_id'] = $question_id;

	} elseif (isset($_SESSION['question_id'])) {
		$question_id = $_SESSION['question_id'];
	}

	# 問題情報の取得
	$question = getQuestionData($question_id);
	$question_text = $question['question_text'];
?>
<h4>問題選択肢一覧</h4>
<hr>
<p>問題<?php echo $question_id; ?>. <?php echo $question_text; ?></p>
<hr>
<ul>
	<li>新規に登録する場合は、<strong>新規登録</strong>ボタンをクリックしてください。</li>
	<li>変更する場合は、変更したい<strong>選択肢文言</strong>をクリックしてください。</li>
	<li>削除する場合は、削除したい選択肢の<strong>削除</strong>をクリックしてください。</li>
</ul>
<?php
	$message = '';
	if (isset($_SESSION['message'])) {
		$message = $_SESSION['message'];
	}

	# セッションの削除
	unset($_SESSION['message']);

	echo '<form action="question-choice-input.php" method="post">';
	echo '<input type="submit" value="新規登録">';
	echo '</form>';
	echo '<hr>';

	if (isset($message)) {
		echo '<p>', $message, '</p>';
	}

	echo '<table class="table">';

	echo '<colgroup>';
	echo '<col class="choice-no">';
	echo '<col class="choice-text">';
	echo '<col class="choice-correct">';
	echo '<col class="choice-delete">';
	echo '</colgroup>';

	echo '<thead>';
	echo '<tr>';
	echo '<th class="text-center">番号</th>';
	echo '<th class="text-center">選択肢文言</th>';
	echo '<th class="text-center">正解</th>';
	echo '<th></th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';

	# 選択肢情報の取得
	$choices = getChoiceDataListByQuestionId($question_id);

	$no = 1;
	foreach ($choices as $choice) {

		$choice_id = $choice["choice_id"];
		$choice_text = nl2br(htmlspecialchars($choice["choice_text"], ENT_QUOTES, 'UTF-8'), false);
		$correct = $choice["correct"];

		echo '<tr>';
		echo '<td class="text-center">', $no++, '<input type="hidden" name="choice_id" value="', $choice_id, '"></td>';
		echo '<td><a href="question-choice-input.php?choice_id=', $choice_id, '">', $choice_text, '</a></td>';
		echo '<td class="text-center">', $correct == "1" ? '〇' : '', '</td>';				
		echo '<td class="text-center"><a href="question-choice-delete.php?choice_id=', $choice_id, '" class="mx-2">削除</a></td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';	
?>
<?php require 'includes/footer.php'; ?>
