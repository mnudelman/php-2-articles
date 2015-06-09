<?php
/**
 * Форма вывода текста статьи
 * Date: 26.05.15
 */
?>
<?php
    if (!isset($artMin)) {
        $artMin = 0 ;
        $artMax = count($articles) - 1 ;

    }
    for ($i=$artMin ; $i <= $artMax; $i++) {
        $article = $articles[$i] ;
        $title =  $article['title'];
        $file = $article['file'];
        $topics = $article['topics'];

 ?>
        <div style="border-bottom: 1px solid;">
 <?php
        if (is_array($topics)) {
            foreach ($topics as $topic) {
                $tName = $topic['topicname'];
                ?>
        <img src="<?= $dirImg; ?>/dialog-ok.png" alt="oK!"> <?= $tName; ?>&nbsp;
<?php
            }
        }

        if (!empty($file) && file_exists($dirArticle .'/'. $file)) {
            echo '</div>';
            echo '<strong>';
            echo $title;
            echo '</strong>';

            echo '<article>' . "\n";
            include_once $dirArticle . '/' . $file;
            echo '</article>';
        }else {
            echo '<h2>Статья отсутствует</h2>' ;
        }
    }


