<?php
/**
 * Форма задания login, password
 * Date: 23.05.15
 *
 */
?>

<form action="<?=$urlToUser ?>" method="post">
    <div>
        ВХОД. Войдите под своим login,password или
        <button name="registration">пройдите регистрацию</button>
    </div>

    <label><span class="label"><strong>Имя:</strong></span>
        <input class="field" type="text" name="login"
               value="<?= $login; ?>"> </label> </br>

    <label><span class="label"> <strong>Пароль:</strong></span>
        <span> <input class="field" type="password" name="password"
                      value="<?= $password ?>"></label> </br><br>
    <button name="enter" class="bt">ВОЙТИ</button>
    <?php
    if ($profileIsPossible) {
        ?>
        <button name="profile">ПРОФИЛЬ</button>
    <?php
    }
    ?>
    <button name="exit">НА ГЛАВНУЮ</button>

</form>
