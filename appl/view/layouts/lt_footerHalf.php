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
<?php
$htmlDirTop = TaskStore::$htmlDirTop ;
$dirTop = TaskStore::$dirTop ;
?>

<html>

<html>
<?php
include_once TaskStore::$dirView . '/headPart.php';
?>
<body><body>
<?php
include_once TaskStore::$dirView . '/topMenu.php';
?>
<div id="contentShowHalph">

    <?php
    include_once TaskStore::$dirView .'/messageForm.php' ;
    ?>
    <?php
    include_once $content ;
    ?>

</div>
<div id="footerHalph">
    <?php
    if (!empty($footer)) {
        include_once $footer;
    }
    ?>

</div>
</body>
</html>