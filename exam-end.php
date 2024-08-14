<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php

	# 変数の初期化
	$workbook_id = '';

	if (isset($_SESSION['user'])) {

        # セッションに選択された項目を保存
        if (!isset($_SESSION['selected'])) {
            $_SESSION['selected'] = [];
        }

		# POSTされたanswersの選択肢番号を、セッションselectedに格納する
        if (isset($_POST['answers'])) {
            foreach ($_POST['answers'] as $selected_id) {
                if (!in_array($selected_id, $_SESSION['selected'])) {
                    $_SESSION['selected'][] = $selected_id;
                }
            }
        }

		# タイムゾーンを日本時間に設定
		date_default_timezone_set('Asia/Tokyo');
		
		# 終了時刻を現在の日本時刻として保存
		$end_time = date('Y-m-d H:i:s');

		# セッションから開始時刻を取得
		$start_time = $_SESSION['start_time'] ?? '不明';

		# 経過時間の計算
		$start_datetime = new DateTime($start_time);
		$end_datetime = new DateTime($end_time);
		$interval = $start_datetime->diff($end_datetime);

		# 経過時間をフォーマット
		$interval_time = $interval->format(' %i 分 %s 秒');

		if (isset($_SESSION['workbook_id'])) {
            $workbook_id = $_SESSION['workbook_id'];
        }

		# 問題情報と問題詳細情報の取得
        $questions = getQuestionDataListByWorkbookId($workbook_id);

		# 変数の初期化
		$number_question = count($questions);
		$number_correct = 0;
		$number_incorrect = 0;
		$answer_correct = '';

		$answers =[];
		foreach ($questions as $question) {

			$question_id = $question['question_id'];

			if (count($question['choices']) > 0) {

				# 選択肢文言の取得
				$choices = getChoiceTextList($_SESSION['selected'], $question_id);
				foreach ($choices as $choice) {
					$choice_text = nl2br(htmlspecialchars($choice['choice_text'], ENT_QUOTES, 'UTF-8'), false);
				}

				# 指定した問題の正解となる選択肢番号の取得
				$corrects = getChoiceCorrectListByQuestionId($question_id);

				# 選択された選択肢の問題番号と一致する選択肢番号の取得
				$correct_answer = getChoiceAnswer($_SESSION['selected'], $question_id);

				if (arraysAreEqual($corrects, $correct_answer)) {
					$answer_correct = '1';
					$number_correct += 1;
				} else {
					$answer_correct = '0';
					$number_incorrect += 1;
				}
			}

			$answers[] = [
				'question_id'=>$question['question_id'],
				'question_text'=>$question['question_text'],
				'description'=>$question['description'],
				'answer_correct'=>$answer_correct
			];
		}

		# 検定情報の取得
		$workbook = getWorkbookData($workbook_id);

		# 解答テーブルに登録
		insertAnswerRecult($_SESSION['user']['id'], 
							$_SESSION['user']['user_name'], 
							$workbook_id, 
							$workbook['workbook_title'], 
							$interval_time, 
							$workbook['percentage'], 
							$number_question, 
							$number_correct, 
							$number_incorrect, 
							$answers
						);

		# 解答番号の最大値の取得
		$answer_id = getAnswerMaxId();

		# リダイレクト処理
		header('Location: history-detail.php?answer_id=' . $answer_id);
		exit();

    } else {
		echo '受験するには、ログインしてください。';
	}
?>
<?php require 'includes/footer.php'; ?>
