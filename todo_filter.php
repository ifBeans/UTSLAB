<?php

session_start();

if(isset($_POST['filter_status'])){

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

}

if (isset($_POST['search'])) {
    $_SESSION['search'] = $_POST['search'];
}

if (isset($_POST['reset_search'])) {
    unset($_SESSION['search']);
    $_SESSION['filter'] = "all";
}

header('Location: main_page.php');
exit();
