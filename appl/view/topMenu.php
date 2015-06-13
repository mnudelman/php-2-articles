<?php
/**
 *  Меню - шапка страницы
 */
?>
<?php
?>
<div id="topMenu">
    <strong>ШП. PHP-2.Занятие-1.Систематизация статей.</strong> <br>

    <a href="<?=$htmlDirTop.'/index.php?cnt=Cnt_topic';?>" class="menu">
        <img src="<?=$htmlDirTop;?>/images/gtk-dnd-multiple.png" title="Тема статей" alt="Тема">
        <?php
        echo 'тема:'. ( ( empty($topicName)) ? 'тема не выбрана' : $topicName );
        ?>
    </a>&nbsp;&nbsp;

    <a href="<?php echo $htmlDirTop.'/index.php?cnt=Cnt_user' ?>" class="menu">
        <img src="<?=$htmlDirTop ?>/images/people.png"
             title="пользователь" alt="пользователь">
        <?php

           echo $userName ;
        ?>
    </a> &nbsp;&nbsp;
    <a href="<?php echo  $htmlDirTop.'/index.php?cnt=Cnt_about' ?>" class="menu">
     <img src="<?php echo  $htmlDirTop ?>/images/help-about.png" title="about" alt="about"></a>

</div>
&nbsp;&nbsp;
