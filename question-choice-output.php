<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php

	# セッションの削除
	unset($_SESSION['message']);

	$correct = '0';
	if (isset($_REQUEST['correct'])) {
        $correct = '1';
    }

	if (!empty($_REQUEST['choice_id'])) {
		# 選択肢情報の更新
		updateChoice($_REQUEST['question_id'], $_REQUEST['choice_text'], $correct, $_REQUEST['choice_id']);

		$_SESSION['message'] = '選択肢情報を更新しました';

	} else {
		# 選択肢情報の登録
		insertChoice($_REQUEST['question_id'], $_REQUEST['choice_text'], $correct);

		$_SESSION['message'] = '選択肢情報を登録しました。';
	}

	# リダイレクト処理
	header('Location: question-choice-list.php');
	exit();
?>
<?php require 'includes/footer.php'; ?>
