-- CREATE DATABASE IF NOT EXISTS articles ;
-- show tables ;
-- show triggers ;
-- строка в userprofile появляется вместе с users
--CREATE TRIGGER insert_user AFTER INSERT ON users
--FOR EACH ROW
  --INSERT INTO userprofile (userid) VALUES (new.userId);
-- --------------------------------------
--INSERT INTO TABLE users (login,password) VALUES  ('mnudelman','12345') ;
-- articles -Список статей
--CREATE TRIGGER  insert_user AFTER INSERT ON users
--FOR EACH ROW
--  INSERT INTO userprofile (userid) VALUES (new.userId);


--  commentarticle - комментарии к статье
SELECT * FROM users ;
SELECT * FROM commentarticle ;
SELECT users.login,
        commentarticle.comment,
       commentarticle.date
       FROM commentarticle,users
       WHERE commentarticle.articleid = 8 AND
       commentarticle.authorid = users.userid
       ORDER BY commentarticle.date DESC  ;