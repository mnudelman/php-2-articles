-- Создание схемы БД articles
-- --------------------------------------
-- CREATE DATABASE IF NOT EXISTS articles ;
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
