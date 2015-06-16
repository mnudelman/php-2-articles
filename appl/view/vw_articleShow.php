<?php
/**
 * Форма вывода текста статьи
 * Date: 26.05.15
 */
?>
        <div style="border-bottom: 1px solid;">
 <?php
        if (is_array($topics)) {             // перечень тем
            foreach ($topics as $topic) {
                $tName = $topic['topicname'];
                ?>
                <img src="<?=$dirImages;?>/dialog-ok.png" alt="oK!"> <?= $tName; ?>&nbsp;
<?php
            }
        }
?>
        </div>
        <?=$title;?>                            <!-- заголовок статьи -->
        <?=$partArticleText;?>                  <!-- текст статьи -->
        <?$errorMessage?>                       <!-- сообщение в случае отсутствия -->




