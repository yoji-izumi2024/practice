<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php
	if (isset($_SESSION['user'])) {

		$user_id = $_SESSION['user']['id'];
	
		# 受験結果情報の取得
		$answers = getAnswerDataListByUserId($user_id);

		echo '<h4>', $_SESSION['user']['user_name'], 'さんの受験履歴</h4>';
		echo '<hr>';

		if (count($answers) > 0) {

			echo '<ul>';
			echo '<li>受験履歴の詳細を確認する場合は、確認したい検定タイトルの<strong>詳細</strong>をクリックしてください。</li>';
			echo '</ul>';
			echo '<hr>';
	
			echo '<table class="table">';
	
			echo '<colgroup>';
			echo '<col class="history-exam-id">';
			echo '<col class="history-exam-title">';
			echo '<col class="history-exam-datetime">';
			echo '<col class="history-exam-result">';
			echo '<col class="history-exam-detail">';
			echo '</colgroup>';
		
			echo '<thead>';
			echo '<tr>';
			echo '<th class="text-center">受験番号</th>';
			echo '<th class="text-center">検定タイトル</th>';
			echo '<th class="text-center">受験日時</th>';
			echo '<th class="text-center">合否</th>';
			echo '<th></th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			foreach ($answers as $answer) {
		
				$answer_id = $answer['answer_id'];
		
				echo '<tr>';
				echo '<td class="text-center">', $answer_id, '</td>';
				echo '<td>', $answer['workbook_title'], '</td>';
				echo '<td class="text-center">', $answer['exam_date'], '</td>';
				echo '<td class="text-center">', $answer['exam_results'], '</td>';
				echo '<td class="text-center"><a href="history-detail.php?answer_id=', $answer_id, '" class="mx-2">詳細</a></td>';
				echo '</tr>';
			}
		
			echo '</tbody>';
			echo '</table>';
	
		} else {

			echo '受験履歴がありません。';
		}

	} else {
		echo '受験履歴を表示するには、ログインしてください。';
	}
?>
<?php require 'includes/footer.php'; ?>
