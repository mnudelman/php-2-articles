<?php
/**
 *   страница  без подвала
 */
?>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);
?>

<html>
<?=$partHeadPart;?>
<body>
<?=$partTopMenu;?>
<div id="contentShow">
   <?=$partContent;?>
</div>
</body>
</html>