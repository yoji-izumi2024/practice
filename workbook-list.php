<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<h4>検定登録</h4>
<hr>
<ul>
	<li>新規に登録する場合は、<strong>新規登録</strong>ボタンをクリックしてください。</li>
	<li>変更する場合は、変更したい<strong>検定タイトル</strong>をクリックしてください。</li>
	<li>削除する場合は、削除したい検定タイトルの<strong>削除</strong>をクリックしてください。</li>
	<li>検定タイトルに問題を紐づける場合は、<strong>詳細</strong>をクリックしてください。</li>
</ul>
<?php
	$message = '';
	if (isset($_SESSION['message'])) {
		$message = $_SESSION['message'];
	}
	unset($_SESSION['message']);

	echo '<form action="workbook-input.php" method="post">';
	echo '<input type="submit" value="新規登録" class="my-1">';
	echo '</form>';
	echo '<hr>';

	if (isset($message)) {
		echo '<p>', $message, '</p>';
	}

	echo '<table class="table">';

	echo '<colgroup>';
	echo '<col class="workbook-id">';
	echo '<col class="workbook-title">';
	echo '<col class="workbook-percentage">';
	echo '<col class="workbook-detail">';
	echo '<col class="workbook-delete">';
	echo '</colgroup>';

	echo '<thead>';
	echo '<tr>';
	echo '<th class="text-center">検定番号</th>';
	echo '<th class="text-center">検定タイトル</th>';
	echo '<th class="text-center">合格基準</th>';
	echo '<th></th>';
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
		echo '<td><a href="workbook-input.php?workbook_id=', $workbook_id, '">', $workbook['workbook_title'], '</a></td>';
		echo '<td class="text-center">', $workbook['percentage'], '%以上</td>';
		echo '<td class="text-center"><a href="workbook-detail-input.php?workbook_id=', $workbook_id, '" class="mx-2">詳細</a></td>';
		echo '<td class="text-center"><a href="workbook-delete.php?workbook_id=', $workbook_id, '" class="mx-2">削除</a></td>';
		echo '</tr>';
	}

	echo '</tbody>';
	echo '</table>';
?>
<?php require 'includes/footer.php'; ?>
