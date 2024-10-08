<?php

define('DSN', 'mysql:host=localhost;dbname=utslab');
define('DBUSER', 'root');
define('DBPASS', '');

$db = new PDO(DSN, DBUSER, DBPASS);
