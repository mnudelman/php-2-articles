<?php
/**
 * Форма страничного навигатора
 * Date: 28.05.15
 * Time: 21:28
 */
?>
<div align="center" class="navigator">
<form method="POST"  action="<?=$urlNavigator;?>">
    <a href="<?=$urlNavigator.'/page/first' ?>" readonly="readonly">
            <img src="<?=$dirImages;?>/go-first.png"
                     title="первая страница" alt="|<" >
    </a>
    <a href="<?=$urlNavigator.'/page/prev' ?>">
        <img src="<?=$dirImages;?>/go-previous.png"
                     title="предыдущая страница" alt="<" >
    </a>
    <?php

    for ($i=$navPageMin; $i <= $navPageMax; $i++) {
        echo '<a href="'.$urlNavigator.'/page/'.$i.'">' ;
        if ($currentPage == $i) {
            echo '<span  class="navPageCurrent">' . $i . '</span></a>' . "\n";
        }else {
            echo '<span  class="navPageNum">' . $i . '</span></a>' .  "\n" ;
        }
    }

    ?>
    <a href="<?=$urlNavigator.'/page/next';?>">
        <img src="<?=$dirImages;?>/go-next.png"
                     title="следующая страница" alt=">" >
    </a>
    <a href="<?=$urlNavigator.'/page/last';?>">
        <img src="<?=$dirImages;?>/go-last.png"
                     title="последняя страница" alt=">|" >
    </a>

    <br><br>
    <button name="topicSelect">Выбрать тему</button>

        <select name="currentTopicId" class="field">
            <?php
            foreach($topicList as $topic) {
                $topicid  = $topic['topicid'] ;
                $topicName= $topic['topicname'] ;
                $text = $topicName ;
                $selected = ( $topicid == $currentTopicId ) ? 'selected' : '' ;
                $opt = '<option value="%d"  %s >%s</option>' ;
                echo sprintf($opt,$topicid,$selected,$text)."\n" ;
            }
            ?>
        </select>
    &nbsp;&nbsp;&nbsp;

</form>
</div>
