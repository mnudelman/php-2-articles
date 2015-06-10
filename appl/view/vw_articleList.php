<?php
/**
 * форма списокЗаголовковСтатей
 * Date: 10.06.15
 * Time: 19:47
 */
?>
<ul>
<?php
foreach($articles as  $article ){
    $aid = $article['articleid'];
    $title = $article['title'];
    $file = $article['file'];
    $topics = $article['topics'];
    ?>
    <li>
        <a class="rightMenu" href="<?=$htmlDirTop;?>/index.php?cnt=cnt_navigator&articleid=<?=$aid;?>" >
       <strong> <?=$title;?> </strong>
        </a>
    </li>
<?php
}
?>
</ul>