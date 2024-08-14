<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php

	if (isset($_SESSION['user'])) {

        $answer_id = $_REQUEST['answer_id'];

		# 受験結果情報の取得
        $answer = getAnswerData($answer_id);

		# 正解率を計算
        $result_percentage = round(($answer['number_correct'] / $answer['number_question']) * 100);

		echo '受験者 ', $answer['user_name'], ' 様<br>';
        echo '検定 「', $answer['workbook_title'], '」 (合格基準:', $answer['number_percentage'], '%）<br>';
        echo '受験日時 ', $answer['exam_date'], '  （経過時間 ', $answer['interval_time'], '）<br>';
        echo '合否結果 <strong>', $answer['exam_results'], '</strong>';
        echo '<small> (正解率:', $result_percentage, '%  問題数:', $answer['number_question'], '  正解数:', $answer['number_correct'], '  不正解数:', $answer['number_incorrect'], ')</small><br>';
        echo '<hr>';

		echo '<table class="table">';

		echo '<colgroup>';
		echo '<col class="history-exam-detail-id">';
		echo '<col class="history-exam-question">';
		echo '<col class="history-exam-detail">';
		echo '</colgroup>';
	
		echo '<thead>';
        echo '<tr>';
		echo '<th class="text-center">番号</th>';
		echo '<th class="text-center">問題 / 解説</th>';
		echo '<th></th>';
		echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
		# 受験結果詳細情報の取得
		$details = getAnswerDetailDataListByAnswerId($answer_id);
	
        $no = 1;
		foreach ($details as $detail) {
	
			if ($detail['answer'] == '1') {
                $answer_correct = '正解';

			} elseif ($detail['answer'] == '0')  {
                $answer_correct = '不正解';

			} else {
                $answer_correct = '不明';
            }

			$question_text = nl2br($detail["question_text"], false);
			$description = nl2br($detail["description"], false);

			echo '<tr>';
			echo '<td class="text-center align-middle" rowspan="2">', $no++, '</td>';
			echo '<td>', $question_text, '</a></td>';
			echo '<td class="text-center align-middle" rowspan="2">', $answer_correct, '</a></td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td>', $description, '</a></td>';
			echo '</tr>';		
		}
	
        echo '</tbody>';
		echo '</table>';

    } else {
		echo '受験履歴を表示するには、ログインしてください。';
	}
?>
<?php require 'includes/footer.php'; ?>
