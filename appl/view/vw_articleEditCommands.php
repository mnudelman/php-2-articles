<?php
/**
 * Форма редактирования статей. Команды
 *
 */



?>
<div id="footerHalfEdit">
    <?php
    if ($addFlag) {
        ?>
        <label>
            Выбор новых статей
            <input type="file" name="articleFile[]" multiple>
        </label>
        <span style="margin-left:52px">
        <button class="btGalEdit" name="add">Добавить на сайт</button>
        </span>
        <br>
    <?php
    }
    ?>
    <?php
    if ($editFlag) {
    ?>
    <button class="btGalEdit" name="save">Сохранить изменения</button>
    <?php
    }
    ?>
    <?php
    if ($delFlag) {
    ?>
    <button class="btGalEdit" name="del">Удалить отмеченные</button>
    <?php
    }
    ?>
    <button class="btGalEdit" name="show">В просмотр</button>
</div>
