<?php
/**
 * ПОказать результаты тестирования
 */
?>
<head xmlns="http://www.w3.org/1999/html">
    <style>
        .partBt {
            background-color: #c3c3af;
            border-radius: 10px;
        }

        .current {
            color: blue;
        }

        .comp {
            color: greenyellow;
        }

        .senior {
            color: yellow;
        }

        .norm {
            color: #000000;
        }

        form input {
            width: 300px;
            margin-left: 200px;
        }

        .label {
            width: 100px; /* Ширина блока с текстом */
            float: left; /* Расположение в одну строку с полем */
            text-align: right; /* Выравнивание по правому краю */
        }

        .labelForm {
            font-weight: bold;
        }

        .field {
            width: 250px; /* Ширина поля */
            margin-left: 10px; /* Расстояние между полем и текстом */
            /* border: 1px solid #abadb3; */
            /* Рамка вокруг текстового поля */
            padding: 2px; /* Поля вокруг текста */
            /** text-transform: capitalize; */
            /* каждое слово с большой буквы */
        }

        /* для пароля, почты, сайта */
        .field1 {
            width: 500px; /* Ширина поля */
            margin-left: 10px; /* Расстояние между полем и текстом */
            padding: 2px; /* Поля вокруг текста */
        }

    </style>
</head>
<h2>Тестирование компонент представления</h2>
<form action="<?= $urlTest; ?>" method="post">
    <table border="4"
           cellspacing="1"
           cellpadding=“1” class="galFformEdit">
        <caption><strong>Контроллер:<?= $controllerName; ?><br>
                Компоненты представлений:<?= $controllerViewPart; ?>
            </strong>
        </caption>
        <tr>
            <?php
            for ($i = 0; $i < $maxLevel; $i++) {
                echo '<th title="view component level">' . $i . '</th>';
            }
            ?>


        </tr>
        <?php
        foreach ($components as $name => $component) {
            echo '<tr>' . "\n";
            $stat = $component['status'];
            $styleClass = 'norm';
            $styleClass = (TestView::$STAT_COMPONENT == $stat) ? 'comp' : $styleClass;
            $styleClass = (TestView::$STAT_SENIOR == $stat) ? 'senior' : $styleClass;
            $styleClass = (TestView::$STAT_CURRENT == $stat) ? 'current' : $styleClass;
            $path = $component['path'];
            $level = sizeof($path);
            $isOk = $component['ok'];
            for ($i = 0; $i < $maxLevel; $i++) {
                echo '<td  style="background-color: #c3c3af">';
                if ($i == $level) {
                    $pictOk = (true === $isOk) ? 'dialog-ok.png' : 'gtk-cancel.png';
                    ?>
                    <a href="<?= $urlTest . '?comp=' . $name . '&up=1'; ?>" name="up"><img
                            src="<?= $dirImg ?>/go-up.png"
                            title="senior component" alt="senior"> </a>
                    <img src="<?= $dirImg . '/' . $pictOk; ?>">
                    <span class="<?= $styleClass; ?>">
                <button name="click_<?= $name; ?>" class="partBt <?= $styleClass; ?>"
                         title="Описатель">
                    <?= $name; ?>
                </button>
                </span>
                    <a href="<?= $urlTest . '?comp=' . $name . '&down=1'; ?>"><img src="<?= $dirImg ?>/go-down.png"
                                                                                   title="child components"
                                                                                   alt="components"> </a>
                <?php
                } else {
                    echo '&nbsp';
                }
                echo '</td>';
            }
            echo '</tr>' . "\n";
        }
        ?>

    </table>
    <div>
        контроллер:
        <select name="cntName">
            <?php
            foreach ($controllers as $cntName) {
                $selected = ($cntName == $selectName) ? 'selected' : '';
                echo '<option value="' . $cntName . '"  ' . $selected . '>' . $cntName . '</option>';
            }
            ?>
        </select>
        <button name="'select">
            Выбрать
        </button>
    </div>
</form>



<?php
if ($compClick) {
    ?>
    <br>
    <h3> Описатель компоненты:<?= $compName ?></h3>
    <form>

        <label>
            <span class="label">
            name:
            </span>
            <input type="text" readonly value="<?= $compName ?>" class="field">
        </label><br>

        <label>
        <span class="label">
        parameters:
        </span>
            <input type="text" readonly value="<?= $compParameters ?>" class="field1">
        </label><br>
        <label>
        <span class="label">
            components:
         </span>
            <input type="text" readonly value="<?= $compComponets ?>" class="field1">
        </label><br>
        <label>
        <span class="label">
        dir:
        </span>
            <input type="text" readonly value="<?= $compDir ?>" class="field1">

        </label><br>
        <label>
      <span class="label">
        file:
       </span>
            <input type="text" readonly value="<?= $compFile ?>" class="field">
        </label><br>
        <label>
        <span class="label">
        path:
        </span>
            <input type="text" readonly value="<?= $compPath ?>" class="field1">
        </label><br>
        <label>
        <span class="label">
        oK:
        </span>
            <input type="text" readonly value="<?= $compOk ?>" class="field">
        </label><br>
    </form>
<?php
}
?>
