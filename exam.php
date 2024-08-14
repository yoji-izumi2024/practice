<?php require 'includes/session.php'; ?>
<?php require 'includes/header.php'; ?>
<?php require 'includes/menu.php'; ?>
<?php require 'functions/db.php'; ?>
<?php

    /**
    * ページング処理
    *  
    * @param $items 全ページ情報
    * @param $page 指定するページ
    * @param $itemsPerPage １ページあたりの個数(デフォルト値 1)
    * @return ページ情報
    */
    function paginate($items, $page, $itemsPerPage=1) {

        $totalItems = count($items);
        $totalPages = ceil($totalItems / $itemsPerPage);
        
        # 1ページ目と総ページ数の範囲内であるか確認
        if ($page < 1) {
            $page = 1;
        } elseif ($page > $totalPages) {
            $page = $totalPages;
        }
        
        $offset = ($page - 1) * $itemsPerPage;
        $pagedItems = array_slice($items, $offset, $itemsPerPage);
        
        return [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'items' => $pagedItems
        ];
    }

    if (isset($_SESSION['user'])) {

        if (isset($_SESSION['workbook_id'])) {
            $workbook_id = $_SESSION['workbook_id'];
        }

        if (isset($_REQUEST['workbook_id'])) {
            $workbook_id = $_REQUEST['workbook_id'];
            $_SESSION['workbook_id'] = $workbook_id;
        }

        # 問題情報と問題詳細情報の取得
        $questions = getQuestionDataListByWorkbookId($workbook_id);

        # 現在のページと1ページあたりの項目数を設定
        $current_page = isset($_POST['page']) ? (int)$_POST['page'] : 1;

        # セッションに選択された項目を保存
        if (!isset($_SESSION['selected'])) {
            $_SESSION['selected'] = [];
        }

        # POSTされた選択された選択肢番号をセッションに格納する
        if (isset($_POST['answers'])) {
            foreach ($_POST['answers'] as $selected_id) {
                if (!in_array($selected_id, $_SESSION['selected'])) {
                    $_SESSION['selected'][] = $selected_id;
                }
            }
        }

        # 最終ページに到達した場合の処理
        $total_pages = count($questions);

        # ページング処理を実行
        $pagedData = paginate($questions, $current_page);

        if ($current_page < $total_pages) {
            echo '<form method="post">';
        } else {
            echo '<form method="post" action="exam-end.php">';
        }

        foreach ($pagedData['items'] as $question) {

            $question_text = nl2br(htmlspecialchars($question["question_text"], ENT_QUOTES, 'UTF-8'), false);

            echo "<h6>問題", $current_page, ". " . $question_text . "</h6><br>";

            if (count($question['choices']) > 0) {
                echo '<ul>';
                foreach ($question['choices'] as $choice) {

                    $choice_text = nl2br(htmlspecialchars($choice['choice_text'], ENT_QUOTES, 'UTF-8'), false);

                    echo '<li>';
                    echo '<label class="m-1">';
                    echo '<input type="checkbox" name="answers[]" value="', $choice['choice_id'], '" class="mx-2"';
                    echo in_array($choice['choice_id'], $_SESSION['selected']) ? 'checked' : '', '>';
                    echo $choice_text, '<br>';
                    echo '</label>';
                    echo '</li>';
                }
                echo '</ul>';

            } else {
                echo '<p class="ms-2">選択肢が未登録です</p>';
            }
        }

        echo '<input type="hidden" name="page" value="', $current_page + 1, '">';
        if ($current_page < $total_pages) {
            echo '<input type="submit" value="次へ">';
        } else {
            echo '<input type="submit" value="採点結果">';
        }
        echo '</form>';

    } else {
		echo '受験するには、ログインしてください。';
	}
?>
<?php require 'includes/footer.php'; ?>
