<?php
/**
 * * Форма редактирования статей. Таблица-список статей
 */
?>
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
        $permissions = $article['permissions'] ;
        $addFlag = (in_array('create',$permissions)) ;
        $editFlag = (in_array('edit',$permissions)) ;
        $delFag = (in_array('delete',$permissions)) ;
        $checkFlag = ($addFlag || $editFlag || $delFag) ;
        ?>
        <tr>
            <td>
                <textarea readonly name="articleText" style="width:300px;height:200px;">
        <?php
        if (false === $fhandle) {
            echo 'ERROR:файл:' . $fText . '  недоступен!';
        } else {
            $maxLength = 2000;
            $len = 0;
            while (false !== ($line = fgets($fhandle, 200) ) ) {
                echo $line;
            }
        }

        echo '</textarea>';
        fclose($fhandle);
        echo '</td>';
        echo '<td>';


        $readOnly = (!$editFlag) ? 'readonly' : '' ;
        echo '<textarea name="title#' . $artId .'" '.$readOnly.' '.
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

            $readOnly = (!$editFlag) ? 'readonly' : '' ;

            $inp = '<input type="checkbox"  name="topic#%d#%d" %s  %s>' ;
            echo sprintf($inp,$tid,$artId,$checked,$readOnly) ;
            echo $tName;
            echo '</label><br>';
        }
        echo '</td>';
        echo '<td>';
        echo $file;         // файл статьи
        echo '</td>';
        echo '<td>';
        if ($checkFlag) {
            $inp = '<input type="checkbox"  name="check#%d">';
            echo sprintf($inp, $artId);
        }else {
            echo '&nbsp' ;
        }
            echo '</td>' ."\n" ;
        echo '</tr>';
        }
        }
        ?>

    </table>
</div>
