-- Replace CHANGE_ME with the same password you put in config.php
CREATE USER IF NOT EXISTS 'cat2user'@'localhost' IDENTIFIED BY 'CHANGE_ME';
GRANT SELECT, INSERT, UPDATE ON CAT2.* TO 'cat2user'@'localhost';
FLUSH PRIVILEGES;
