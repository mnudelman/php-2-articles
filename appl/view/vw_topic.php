<?php
/**
 * Форма выбора текущей галереи
 * Date: 25.05.15
 */
?>
<form action="<?php echo $urlToTopic?>" method="post">
    <label>
        <span class="label">текущий режим:</span>
        <input type="text" readonly="readonly" name="galleryStatName" class="field"
               value="<?php echo $topicStatName ?>">
    </label>&nbsp;&nbsp;
    <input type="hidden"  name="galleryEditStat" class="field"
           value="<?php echo $topicEditStat ?>">


    <button name="changeStat" class="btGal">изменить режим</button>
    <br>
    <label>
        <span class="label">выбрать тему</span>
        <select name="currentTopicId" class="field">
            <?php
            foreach($topicList as $topic) {
                $topicid  = $topic['topicid'] ;
                $topicName= $topic['topicname'] ;
                $selected = ( $topicid == $currentTopicId ) ? 'selected' : '' ;
                echo '<option value="'.$topicid.'"  '.$selected.' >'.$topicid.'-'.$topicName.'</option>'."\n" ;
            }
            ?>
        </select>
    </label>&nbsp;&nbsp;
    <button name="goShow" class="bt btGal">Просмотр</button>&nbsp;&nbsp;
    <?php
    if ($editFlag) {
        ?>
        <button name="editArticle" class="btGal">Редактировать</button><br>
        <?php
         if ($addTopicFlag) {          // можно добавлять новые рубрики
        ?>
        <label>
            <span class="label">Новая тема:</span>
            <input type="text" name="addTopic" class="field">
        </label>&nbsp;&nbsp;
        <button name="addTopicExec" class="btGal">Добавить</button>

    <?php
         }
    }
    ?>
    <br>
    <div style="margin-left:451px;">
        <button name="exit" class="btGal">Прервать</button
    </div>
</form
