<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php

	# 変数の初期化
	$workbook_title = '';

	# セッションの削除
	unset($_SESSION['selected']);
	unset($_SESSION['start_time']);
	unset($_SESSION['end_time']);

	if (isset($_SESSION['user'])) {

		# タイムゾーンを日本時間に設定
		date_default_timezone_set('Asia/Tokyo');

		# フォームの表示時にセッションに現在の日本時刻を保存
		if (!isset($_SESSION['start_time'])) {
			$_SESSION['start_time'] = date('Y-m-d H:i:s');
		}

        $workbook_id = $_REQUEST['workbook_id'];

		# 検定情報の取得
		$workbook = getWorkbookData($workbook_id);
		if (count($workbook) > 0) {
			$workbook_title = $workbook['workbook_title'];
		}

		echo '<h4>検定 「', $workbook_title, '」</h4>';
		echo '<hr>';
	
		echo '<form action="exam.php" method="post">';
		echo '<input type="hidden" name="workbook_id" value="', $workbook_id, '">';
		echo '<p>試験を開始する場合は、開始ボタンをクリックしてください</p>';
		echo '<input type="submit" value="開始">';
		echo '</form>';
    } else {
		echo '受験するには、ログインしてください。';
	}
?>
<?php require 'includes/footer.php'; ?>
