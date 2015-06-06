<?php
//session_start();
/**
 *   страница  с подвалом для вывода форм
 */
?>
<?php
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
?>

<html>
<?php
include_once TaskStore::$dirView . '/headPart.php';
?>
<body><body>
<?php
include_once TaskStore::$dirView . '/topMenu.php';
?>
<div id="contentShow">
    <?php
    include_once TaskStore::$dirView .'/messageForm.php' ;
    ?>
    <?php
    if (!empty($content)) {
        include_once $content;
    }
    ?>
</div>
</body>
</html>