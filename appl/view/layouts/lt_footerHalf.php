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

<div id="contentShowHalf">
    <?=$partContent;?>

    <div id="footerHalf">
        <?=$partFooter;?>
    </div>
</div>
<div id="rightPanel">
   <?=$partRightPanel;?>
</div>

</body>
</html>