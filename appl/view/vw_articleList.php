<?php
/**
 * форма списокЗаголовковСтатей
 * Date: 10.06.15
 * Time: 19:47
 */
?>
<ul>
    <?php
    if (is_array($articles)) {
        foreach ($articles as $article) {
            $aid = $article['articleid'];
            $title = $article['title'];
            $file = $article['file'];
            $topics = $article['topics'];
            ?>
            <li>
                <a class="rightMenu" href="<?= $htmlDirTop; ?>/index.php?cnt=Cnt_navigator&articleid=<?= $aid; ?>">
                    <strong> <?= $title; ?> </strong>
                </a>
            </li>
        <?php
        }
    }
    ?>
</ul>