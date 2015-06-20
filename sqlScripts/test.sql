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


SELECT * from users ;
