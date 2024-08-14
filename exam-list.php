<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php
	if (isset($_SESSION['user'])) {

		echo '<h4>受験</h4>';
		echo '<hr>';
		echo '<ul>';
		echo '<li>受験したい検定の<strong>受験</strong>をクリックしてください。</li>';
		echo '</ul>';
		echo '<hr>';

		echo '<table class="table">';

		echo '<colgroup>';
		echo '<col class="exam-id">';
		echo '<col class="exam-title">';
		echo '<col class="exam-percentage">';
		echo '<col class="exam-start">';
		echo '</colgroup>';
	
		echo '<thead>';
		echo '<tr>';
		echo '<th class="text-center">検定番号</th>';
		echo '<th class="text-center">検定タイトル</th>';
		echo '<th class="text-center">合格基準</th>';
		echo '<th></th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';

		# 検定情報の全件取得
		$workbooks = getWorkbookDataList();

		foreach ($workbooks as $workbook) {

			$workbook_id = $workbook['workbook_id'];

			echo '<tr>';
			echo '<td class="text-center">', $workbook_id, '</td>';
			echo '<td>', $workbook['workbook_title'], '</td>';
			echo '<td class="text-center">', $workbook['percentage'], '%以上</td>';
			echo '<td class="text-center"><a href="exam-start.php?workbook_id=', $workbook_id, '" class="mx-2">受験</a></td>';
			echo '</tr>';
		}

		echo '</tbody>';
		echo '</table>';
		echo '<hr>';

	} else {
		echo '受験するには、ログインしてください。';
	}
?>
<?php require 'includes/footer.php'; ?>
