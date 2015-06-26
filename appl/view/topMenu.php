<?php
/**
 *  Меню - шапка страницы
 */
?>
<?php
?>
<div id="topMenu">
    <strong>ШП. PHP-2.Занятие-1.Систематизация статей.</strong> <br>

    <a href="<?=$htmlDirTop.'/Cnt_topic';?>" class="menu">
        <img src="<?=$htmlDirTop;?>/images/gtk-dnd-multiple.png" title="Тема статей" alt="Тема">
        <?='тема:'. $topicName ;?>
    </a>&nbsp;&nbsp;

    <a href="<?php echo $htmlDirTop.'/Cnt_user' ?>" class="menu">
        <img src="<?=$htmlDirTop ?>/images/people.png"
             title="пользователь" alt="пользователь">
        <?=$userName;?>
    </a> &nbsp;&nbsp;
    <a href="<?=$htmlDirTop;?>/Cnt_about" class="menu">
     <img src="<?=$htmlDirTop;?>/images/help-about.png" title="about" alt="about"></a>

</div>
&nbsp;&nbsp;
