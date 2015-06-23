-- Создание схемы БД articles
-- --------------------------------------
-- CREATE DATABASE IF NOT EXISTS articles ;
-- --------------------------------------
-- users - список пользователей
CREATE TABLE IF NOT EXISTS users (
  userid   INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  login    VARCHAR(20) UNIQUE,
  password CHAR(32),
  roleid INTEGER REFERENCES taskroles (roleid)
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
-- -----------------------------------------------
--  commentarticle - комментарии к статье
CREATE TABLE IF NOT EXISTS commentarticle (
id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
articleid INTEGER
          REFERENCES articles (articleid) ON DELETE CASCADE ,
authorid INTEGER
         REFERENCES users (userid) ON DELETE CASCADE,
comment VARCHAR (400),
date    DATE
) ;
--  --------------------------------------
-- строка в userprofile появляется вместе с users
CREATE TRIGGER  insert_user AFTER INSERT ON users
FOR EACH ROW
  INSERT INTO userprofile (userid) VALUES (new.userId);
-- --------------------------------------

-- подсхема  permisions - определяет возможности доступа к операциям
--  taskobject - объекты задачи
CREATE TABLE IF NOT EXISTS taskobjects (
objectid INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
objectname VARCHAR (20) UNIQUE
) ;
-- taskroles - субъекты задачи
CREATE TABLE IF NOT EXISTS taskroles (
roleid INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
rolename VARCHAR (20) UNIQUE ,
facultative INT DEFAULT 0
) ;
-- taskdoing - действия
CREATE TABLE IF NOT EXISTS taskdoings (
doingid INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
doingname VARCHAR (20) UNIQUE ,
rang INT
) ;
-- permissions - возможности
CREATE TABLE IF NOT EXISTS permissions (
id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
objectid INTEGER
        REFERENCES taskobjects (objextid),
roleid INTEGER
       REFERENCES taskroles (roleid),
totalrang  INTEGER,
UNIQUE (objectid, roleid)   -- строка объект-испольнитель единственная
) ;

-- sessions - сессии пользователей
CREATE TABLE IF NOT EXISTS sessions (
id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
sid VARCHAR (30) ,
userid  INTEGER
       REFERENCES users (userid),
begtime TIMESTAMP,
endtime TIMESTAMP,
passwordsave INTEGER default  0
) ;
