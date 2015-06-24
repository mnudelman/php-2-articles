<?php
/**
 * Комментарии к статье
 */
?>

   <h3 id="comments___"> ================КОММЕНТАРИИ==========</h3><br>
<form action="<?= $urlNavigator ?>" method="post">
    <?php
    if (is_array($comments)) {
        foreach ($comments as $comment) {
            $commentId = $comment['commentId'] ;
            $author = $comment['author'];
            $text = $comment['text'];
            $date = $comment['date'];
            $permissions = $comment['permissions'];
            $addCommentFlag = (in_array('create', $permissions));
            $editFlag = (in_array('edit', $permissions));
            $delFlag = (in_array('delete', $permissions));
            echo $author, '-' . $date . TaskStore::LINE_FEED;
            $readOnly = ($editFlag) ? '' : 'readonly';
            ?>
            <textarea style="width:400px;height:100px" <?= $readOnly;?>
                     name="commentText_<?=$commentId;?>">
           <?= $text; ?>
       </textarea><br>
            <?php
            if ($editFlag) {
                ?>
                <button name="editComment_<?=$commentId;?>">сохранить изменения</button>&nbsp;

            <?php
            }
            ?>
            <?php
            if ($delFlag) {
                ?>
                <button name="delComment_<?=$commentId;?>">удалить</button>&nbsp;

            <?php
            }
            echo '<br>' ;
        }
    }
   ?>

</form>

<br>
<br>
<form action="<?= $urlNavigator ?>" method="post">
    <?php
    $readOnly = ($addCommentFlag) ? '' : 'readonly';
    ?>
    <strong>новый комментарий(только для зарегистрированных)</strong><br>
        <textarea style="width:400px;height:100px" name="newComment"
            <?= $readOnly; ?>   >
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
