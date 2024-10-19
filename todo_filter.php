<?php

session_start();

switch ($_POST['filter_status']) {
    case 'all':
        $status = 'all';
        break;
    case 'td':
        $status = 'td';
        break;
    case 'og':
        $status = 'og';
        break;
    case 'cp':
        $status = 'cp';
        break;
}

$_SESSION['filter'] = $status;

header('Location: main_page.php');
exit();
