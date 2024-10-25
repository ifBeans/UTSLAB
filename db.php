
<?php

define('DSN', 'mysql:host=sql303.infinityfree.com;dbname=if0_37550332_utslab');
define('DBUSER', 'if0_37550332');
define('DBPASS', 'Ajikan2005');

$db = new PDO(DSN, DBUSER, DBPASS);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);