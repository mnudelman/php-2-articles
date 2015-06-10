<?php
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
<?=$partHeadPart?>
<body>
<?=$partTopMenu;?>

<div id="contentShowHalph">
    <?=$partContent;?>
</div>
<div id="footerHalph">
    <?=$partFooter;?>
</div>
</body>
</html>