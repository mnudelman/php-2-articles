<?php
/**
 * Комментарии к статье
 */
?>

   <h3 id="comments___"> ================КОММЕНТАРИИ==========</h3><br>
    <?php
    if (is_array($comments)) {
        foreach ($comments as $comment) {
            $author = $comment['author'];
            $text = $comment['text'];
            $date = $comment['date'];
            echo $author, '-' . $date . TaskStore::LINE_FEED;
            ?>
            <textarea style="width:400px;height:100px" readonly>
           <?= $text; ?>
       </textarea><br>
        <?php

        }
    }
    ?>
    <br>
    <br>
    <form action="<?=$urlNavigator?>" method="post">
        <?php
        $readOnly = ($addCommentFlag) ? '' : 'readonly' ;
        ?>
        <strong>новый комментарий(только для зарегистрированных)</strong>
        <textarea style="width:400px;height:100px" name="newComment"
                      <?=$readOnly;?>   >


            </textarea>
        <?php
         if ($addCommentFlag) {
             ?>
             <input type="submit" name="saveComment" value="сохранить">
         <?php
         }
        ?>
    </form>
    <br>&nbsp;<br>
<a href="#topArticle">Начало статьи</a>
<br>&nbsp;<br>
<br>&nbsp;<br>
<br>&nbsp;<br>
