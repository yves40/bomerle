START TRANSACTION;
SET time_zone = "+01:00";

SELECT COUNT (*) from dblog;

SELECT message, logtime 
    from dblog 
    where logtime > (curdate() - interval 2 day)
    order by logtime desc;

delete from dblog where logtime < (curdate() - interval 40 day);

SELECT COUNT (*) from dblog;

ROLLBACK;
