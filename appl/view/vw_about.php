<?php
/**
 * текст - описание сайта
 * Date: 06.06.15
 */
?>
<div class="comment">
    <p><strong>Систематизация статей</strong></p>

    <p>
        Имеются следующие виды объектов: пользователи, темы(рубрики) статей, статьи.
    </p>
    <p>
        Каждый пользователь после регистрации может загружать на сайт произвольное число статей.
        Пользователя, загрузившего статью назовем <strong>владельцем</strong> статьи.
        Владелец, по своему усмотрению, может устанавливать/менять заголовок статьи и
        отмечать темы, которым статья соответствует.
    </p>
    <p>
        Вводить новые рубрики может пользователь-администратор.
    </p>
    <p>
        Добавленные на сайт тексты статей хранятся в одной директории ./articleHeap .
    </p>


    <h3>Реализация  MVC</h3>
    <p><strong>Контроллеры:</strong>  <i>index.php -> </i> <br>
        <i>(cnt_user,cnt_topic,cnt_article,cnt_topic,cnt_navigator)</i> <br>
        <i>cnt_user</i>(регистрация пользователей) ->  <i>cnt_profile</i>(редактирование профиляПользователя)<br>
        <i>cnt_topic</i>( ведение списка тем) -> <i>cnt_article</i>(редактирование атрибутов статей) <br>
        <i>cnt_navigator</i>(управление выводом текста).<br>
        Диспетчерезацию контроллеров выполняет объект класса Router.<br>
        Router  передает контроллеру массив данных из формы(форма предает имя своего контроллера),
        контороллер обрабатывает полученные данные и  формирует новый массив.
        Router, получив массив данных от контроллера, вместе с именем контроллера
        передает их в <i>ViewDriver</i> - управление представлениями.

    </p>
    <p>
        <strong> Модели -</strong> это два класса - набора функций, обеспечивающих взаимодействие с БД. <br>
        - <i>mod_article</i> - класс, обслуживающий  cnt_topic,cnt_article,cnt_navigator <br>
        - <i>mod_user</i>    - класс, обслуживающий  cnt_user,cnt_profile <br>
    </p>
    <p>
        <strong>Представления</strong> состоят из двух частей: <br>
        - шаблоны расположения (layouts) - это html файлы с префиксом lt_, которые подгружают формы контроллеров.<br>
        - формы контроллеров .<br>
        Диспетчеризацию представлений выполняет объект класса <i>ViewDriver</i>.<br>
        По имени котроллера определяет соответствующие форму, шаблон расположения и
        выводит в окно браузера.
    </p>
    <p>
        Статический класс <i>TaskStore</i> используется для хранения общих параметров задачи.

    </p>

    <h3> Схема БД</h3>
    <p >

        <pre class="programText">

-- Создание схемы БД articles
-- --------------------------------------
 CREATE DATABASE IF NOT EXISTS articles ;
-- --------------------------------------
-- users - список пользователей
CREATE TABLE IF NOT EXISTS users (
  userid   INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  login    VARCHAR(20) UNIQUE,
  password CHAR(32)
);
-- --------------------------------------
-- userprofile - Профиль пользователя
CREATE TABLE IF NOT EXISTS userprofile (
  id         INTEGER NOT NULL  AUTO_INCREMENT PRIMARY KEY,
  userid     INTEGER
             REFERENCES users (userid)
             ON DELETE CASCADE,
  firstname  VARCHAR(40),
  middlename VARCHAR(40),
  lastname   VARCHAR(40),
  fileFoto   VARCHAR(100), -- файл с фотографией
  tel        VARCHAR(15),
  email      VARCHAR(40),
  sex        CHAR(1)           DEFAULT 'm',
  birthday   DATE,
  CHECK (sex IN ('m', 'w'))
);
-- --------------------------------------
-- topics - Список тем  статей
CREATE TABLE IF NOT EXISTS topics (
  topicid INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
  topicname  VARCHAR (60) UNIQUE
);
-- --------------------------------------
-- articles -Список статей
CREATE TABLE IF NOT EXISTS articles (
  articleid INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
  userid INTEGER                          -- пользователь, загрузивший статью
         REFERENCES users (userid),
  title VARCHAR (100),
  annotation VARCHAR (200),
  file   VARCHAR(100) UNIQUE               -- файл с текстом статьи
);
-- --------------------------------------
-- topicarticle - распределение статей по темам
CREATE TABLE IF NOT EXISTS topicarticle (
id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
articleid INTEGER
          REFERENCES articles (articleid) ON DELETE CASCADE ,
topicid INTEGER
        REFERENCES topics (topicid) ON DELETE CASCADE,
UNIQUE (articleid, topicid)   -- строка статья - тема единственная
) ;
--  --------------------------------------
-- authorarticle - связь автор - статья
CREATE TABLE IF NOT EXISTS authorarticle (
id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
articleid INTEGER
          REFERENCES articles (articleid) ON DELETE CASCADE ,
authorid INTEGER
         REFERENCES users (userid) ON DELETE CASCADE,
UNIQUE (articleid, authorid)   -- строка статья - владелец единственная
) ;
--  --------------------------------------
-- строка в userprofile появляется вместе с users
CREATE TRIGGER  insert_user AFTER INSERT ON users
FOR EACH ROW
  INSERT INTO userprofile (userid) VALUES (new.userId);
-- --------------------------------------

    </pre>
    </p>


    <h3>Тексты, которые могут оказаться полезными</h3>

    <ul>
        <li>
            Функция, приводящая  структуру $_FILES в "человеческий вид". Работать с $_FILES
            непосредственно, особенно если загружаются сразу несколько файлов, мягко говоря
            не удобно. Следующий тест трансформирует $_FILES  к нормальному виду
            <p>
                <pre class="programText">
          /**
 * преобразует  $_FILES в нормальную форму
 * @param $topName - этот атрибут name = "..." из input type="file" name="topname[]".....
 * @return array
 */
function filesTransform($topName)
{
    /** переведем $_FILES в нормальную форму */
    $filesNorm = [];
    $names = $_FILES[$topName]['name'];
    $n = count($names);      // количество файлов
    for ($i = 0; $i < $n; $i++) {
        $fName = $_FILES[$topName]['name'][$i];
        $fType = $_FILES[$topName]['type'][$i];
        $fTmpName = $_FILES[$topName]['tmp_name'][$i];
        $fError = $_FILES[$topName]['error'][$i];
        $fSize = $_FILES[$topName]['size'][$i];
        $filesNorm[] = [
            'name' => $fName,
            'type' => $fType,
            'tmp_name' => $fTmpName,
            'error' => $fError,
            'size' => $fSize

        ];
    }
    return $filesNorm;
}

            </pre>
            </p>
        </li>
        <li>
            <p>
                Простой интерпритатор sql запросов, записанных в текстовый файл.
                Можно использовать для отладки sql-запросов.
                Как работает можно посмотреть <a href="./sqlExecute.php">здесь.</a>
            </p>
        </li>
    </ul>

</div>
