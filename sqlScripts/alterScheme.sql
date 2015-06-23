-- подсхема  permisions - определяет возможности доступа к операциям
--  taskobject - объекты задачи
CREATE TABLE IF NOT EXISTS taskobjects (
objectid INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
objectname VARCHAR (20) UNIQUE
) ;
-- taskroles - субъекты задачи
CREATE TABLE IF NOT EXISTS taskroles (
roleid INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
rolename VARCHAR (20)  UNIQUE ,
facultative INT DEFAULT 0
) ;
-- taskdoing - действия
CREATE TABLE IF NOT EXISTS taskdoings (
doingid INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
doingname VARCHAR (20)  UNIQUE ,
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

ALTER TABLE users ADD COLUMN
roleid INTEGER REFERENCES taskroles (roleid) ;