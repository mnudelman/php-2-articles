<?php
/**
 *  Вывод всех сообщений
 */
    if (!empty($messages)) {
        ?>
        <form>
            <h3><?php echo $title?></h3>
            <textarea name="sqlText" readonly="readonly"
                      style="width:620px;height:200px;font-size:15px ;background-color: background-color: #F5E8B8; color:blue">
        <?php
        echo chr(10);
        foreach ($messages as $erTxt) {
            echo $erTxt . CHR(10);
            echo '' . CHR(10);
        }
        ?>
    </textarea><br>
        </form> <br>
    <?php
    }
?>



