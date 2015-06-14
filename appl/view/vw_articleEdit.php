<?php
/**
 * Форма редактирования альбома
 * Date: 26.05.15
 */
?>
<?php

?>

<form action="<?=$urlArticleEdit;?>" method="post"
      enctype="multipart/form-data">
    <div id="contentShowHalfEdit">
    <table border="4"
           cellspacing="1"
           cellpadding=“1” class="galFformEdit">

        <tr>
            <th>Статья</th>
            <th>Заголовок</th>
            <th>Тема</th>
            <th>файл</th>
            <th>отметка</th>
        </tr>
        <?php
        if (!empty($articles)) {

            foreach ($articles as $artId => $article) {
                $file = $article['file'];
                $title = $article['title'];
                $articleTopics = $article['topics'];
                $fText = $dirArticle . '/' . $file;
                $fhandle = fopen($fText, 'r');
        ?>
            <tr>
            <td>
                <textarea readonly name="articleText" style="width:300px;height:200px;">
        <?php
                    if (false === $fhandle) {
                        echo 'ERROR:файл:' . $fText . '  недоступен!';
                    } else {
                        $maxLength = 1000;
                        $len = 0;
                        while (false !== ($line = fgets($fhandle, 200) ) ) {
                            echo $line;
                        }
                    }

                echo '</textarea>';
                    fclose($fhandle);
                    echo '</td>';
                    echo '<td>';
                    echo '<textarea name="title#' . $artId .
                        '" style="width:200px;height:100px;">';
                    echo $title;
                    echo '</textarea>';
                    echo '</td>';
                    echo '<td>';
                    // темы-рубрики статьи
                    foreach ($topicList as $tid => $topic) {   // полный список
                        $checked = (isset($articleTopics[$tid])) ? 'checked' : '';
                        $tName = $topic['topicname'];
                        echo '<label>';
                        $inp = '<input type="checkbox"  name="topic#%d#%d" %s >' ;
                        echo sprintf($inp,$tid,$artId,$checked) ;
                        echo $tName;
                        echo '</label><br>';
                    }
                    echo '</td>';
                    echo '<td>';
                    echo $file;         // файл статьи
                    echo '</td>';
                    echo '<td>';
                   $inp = '<input type="checkbox"  name="check#%d">';
                   echo sprintf($inp,$artId) ;
            echo '</td>' ."\n" ;
            echo '</tr>';
            }
        }
        ?>

    </table>
    </div>
    <div id="footerHalfEdit">
    <label>
        Выбор новых статей
        <input type="file" name="articleFile[]" multiple>
    </label>
        <span style="margin-left:52px">
        <button class="btGalEdit" name="add">Добавить на сайт</button>
        </span>
    <br>

    <button class="btGalEdit" name="save">Сохранить изменения</button>
    <button class="btGalEdit" name="del">Удалить отмеченные</button>
    <button class="btGalEdit" name="show">В просмотр</button>
    </div>
</form>
