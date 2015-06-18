<?php
/**
 * ПОказать результаты тестирования
 */
?>
<head xmlns="http://www.w3.org/1999/html">
    <style>
        .current {
            color:blue;
        }
        .comp {
            color:greenyellow;
        }
        .senior {
            color:yellow;
        }
        .norm {
            color:#000000;
        }
    </style>
</head>
<h2>Тестирование компонент представления</h2>
<table border="4"
       cellspacing="1"
       cellpadding=“1” class="galFformEdit">
    <caption><strong>Контроллер:<?=$controllerName;?><br>
             Компоненты представлений:<?=$controllerViewPart;?>
        </strong>
    </caption>
    <tr>
        <th>o</th>
        <th>1</th>
        <th>2</th>
        <th>3</th>

    </tr>
    <?php
    foreach ($components as $name=>$component) {
        echo '<tr>'."\n"  ;
        $stat = $component['status'] ;
        $styleClass = 'norm' ;
        $styleClass = (TestView::$STAT_COMPONENT == $stat) ? 'comp' :$styleClass ;
        $styleClass = (TestView::$STAT_SENIOR == $stat) ? 'senior' :$styleClass ;
        $styleClass = (TestView::$STAT_CURRENT == $stat) ? 'current' :$styleClass ;
        $path = $component['path'] ;
        $level = sizeof($path) ;
        $isOk = $component['ok'] ;
        for ($i = 0; $i <=$maxLevel ; $i++) {
            echo '<td  style="background-color: #c3c3af">';
            if ($i == $level) {
                $pictOk = (true === $isOk) ? 'dialog-ok.png' : 'gtk-cancel.png' ;
            ?>
                <a href="<?=$urlTest.'?comp='.$name.'&up=1';?>" name="up"><img src="<?=$dirImg?>/go-up.png"
                        title="senior" alt="senior"> </a>
                <img src="<?=$dirImg.'/'.$pictOk;?>">
                <span class="<?=$styleClass;?>">
                     <?=$name;?>
                </span>
                <a href="<?=$urlTest.'?comp='.$name.'&down=1';?>"><img src="<?=$dirImg?>/go-down.png"
                                         title="components" alt="components"> </a>
            <?php
            }else {
                echo '&nbsp' ;
            }
            echo '</td>' ;
        }
        echo '</tr>'."\n"  ;
    }
    ?>

</table>