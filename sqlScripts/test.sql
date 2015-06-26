-- UPDATE taskdoings SET doingname = 'create' WHERE doingname = 'creat' ;
-- DELETE FROM taskdoings ;

-- SELECT * FROM commentarticle ;
--
-- SELECT * FROM taskobjects ;
--
-- SELECT * FROM taskroles ;
--
-- SELECT * FROM taskdoings ;
--
SELECT * FROM permissions ;
SELECT *FROM sessions ;

SELECT taskobjects.objectname,
       taskroles.rolename,
       permissions.totalrang
       FROM permissions,taskobjects,taskroles
       WHERE permissions.roleid = taskroles.roleid AND
             permissions.objectid = taskobjects.objectid
       ORDER BY objectname,rolename ;


