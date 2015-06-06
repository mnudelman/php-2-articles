<?php
//session_start();
/**
 *  Меню - шапка страницы
 */
?>
<?php
 $htmlDirTop = TaskStore::$htmlDirTop ;
 $dirTop = TaskStore::$dirTop ;
?>
<div id="topMenu">
    <strong>ШП. PHP-2.Занятие-1.Систематизация статей.</strong> <br>

    <a href="<?php echo $htmlDirTop.'/index.php?cnt=cnt_topic' ?>" class="menu">
        <img src="<?php echo $htmlDirTop ?>/images/gtk-dnd-multiple.png" title="Тема статей" alt="Тема">
        <?php
        $topicName = TaskStore::getParam('topicName') ;
        echo 'тема:'. ( ( empty($topicName)) ? 'тема не выбрана' : $topicName );
        ?>
    </a>&nbsp;&nbsp;

    <a href="<?php echo $htmlDirTop.'/index.php?cnt=cnt_user' ?>" class="menu">
        <img src="<?php echo $htmlDirTop ?>/images/people.png"
             title="пользователь" alt="пользователь">
        <?php

           echo TaskStore::getParam('userName') ;
        ?>
    </a> &nbsp;&nbsp;
    <a href="<?php echo  $htmlDirTop.'/index.php?cnt=cnt_about' ?>" class="menu">
     <img src="<?php echo  $htmlDirTop ?>/images/help-about.png" title="about" alt="about"></a>

</div>
&nbsp;&nbsp;
