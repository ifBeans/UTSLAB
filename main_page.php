<?php

session_start();
require_once('db.php');
$id = $_SESSION['id_user'];

if (!isset($_SESSION['id_user']) && !isset($_SESSION['username']) && !isset($_SESSION['password'])) {
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <div class="container-fluid content-center p-6 flex justify-center">



        <div class="w-full h-100 mt-3">

            <form class="max-w-sm mx-auto" action="todo_add.php" method="post">

                <div class="mb-3 mt-10">
                    <label class="mb-2 text-lg font-medium text-gray-900">Add To-Do</label>
                    <input type="text" name="todo" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required />
                </div>

            </form>

            <form class="max-w-sm mx-auto" method="post">

                <div class="mb-3 mt-10">
                    <label class="mb-2 text-lg font-medium text-gray-900">Search To-Do</label>
                    <input type="text" name="todo_search" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" />
                </div>

            </form>

            <form class="max-w-sm mx-auto" method="post">

                <div class="text-center">
                    <button type="submit" name="reset_search" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Reset</button>
                </div>

            </form>

            <form class="max-w-sm mx-auto" action="todo_filter.php" method="post">

                <div class="flex items-center mb-4">
                    <input checked type="radio" value="all" name="filter_status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">All</label>
                </div>

                <div class="flex items-center mb-4">
                    <input type="radio" value="td" name="filter_status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">To Do</label>
                </div>

                <div class="flex items-center mb-4">
                    <input type="radio" value="og" name="filter_status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ongoing</label>
                </div>

                <div class="flex items-center mb-4">
                    <input type="radio" value="cp" name="filter_status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Completed</label>
                </div>

                <div class="text-center">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Filter</button>
                </div>

            </form>

            <div class="items-start grid grid-cols-3 text-center">

                <?php if ($_SESSION['filter'] == 'all' or $_SESSION['filter'] == 'td') { ?>

                    <div class="grid grid-cols-1 items-start place-items-center">

                        <h1>Todo</h1>
                        <?php

                        if (isset($_SESSION['search'])) {
                            $search = $_SESSION['search'];
                            $sql = "SELECT * FROM todo WHERE completion = ? AND description LIKE ? AND id_user = ?";
                            $result = $db->prepare($sql);
                            $result->execute([0, "%{$search}%", $id]);
                        } else {
                            $sql = "SELECT * from todo WHERE completion = ? AND id_user = ?";
                            $result = $db->prepare($sql);
                            $result->execute([0, $id]);
                        }

                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                        ?>

                            <div class="grid shadow-2xl rounded-lg bg-red-400 mt-5 p-10 w-3/4 text-start">

                                <h1><?php echo $row['kategori'] ?></h1>
                                <p class="text-md font-medium text-gray-900"><?php echo $row['description'] ?></p>

                                <form class="max-w-sm ms-auto" method="post">

                                    <input type="hidden" name="id_todo" value=<?= $row['id_todo'] ?> />
                                    <button type="submit" name="ongoing" class="bg-transparent hover:bg-yellow-400 text-amber-200 font-semibold hover:text-white py-2 px-4 border border-yellow-400 hover:border-yellow-500 rounded">
                                        Ongoing
                                    </button>

                                </form>

                            </div>

                        <?php
                        }
                        ?>

                    </div>

                <?php } ?>

                <?php if ($_SESSION['filter'] == 'all' or $_SESSION['filter'] == 'og') { ?>

                    <div class="grid grid-cols-1 items-start place-items-center">

                        <h1>Ongoing</h1>
                        <?php

                        if (isset($_SESSION['search'])) {
                            $search = $_SESSION['search'];
                            $sql = "SELECT * FROM todo WHERE completion = ? AND description LIKE ? AND id_user = ?";
                            $result = $db->prepare($sql);
                            $result->execute([1, "%{$search}%", $id]);
                        } else {
                            $sql = "SELECT * from todo WHERE completion = ? AND id_user = ?";
                            $result = $db->prepare($sql);
                            $result->execute([1, $id]);
                        }

                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                        ?>

                            <div class="grid shadow-2xl rounded-lg bg-yellow-100 mt-5 p-10 w-3/4 text-start">

                                <h1><?php echo $row['kategori'] ?></h1>
                                <p class="text-md font-medium text-gray-900"><?php echo $row['description'] ?></p>

                                <form class="max-w-sm ms-auto" method="post">

                                    <input type="hidden" name="id_todo" value=<?= $row['id_todo'] ?> />
                                    <button type="submit" name="completed" class="bg-transparent hover:bg-green-400 text-emerald-400 font-semibold hover:text-white py-2 px-4 border border-emerald-400 hover:border-emerald-500 rounded">
                                        Completed
                                    </button>

                                </form>

                            </div>

                        <?php
                        }
                        ?>

                    </div>

                <?php } ?>

                <?php if ($_SESSION['filter'] == 'all' or $_SESSION['filter'] == 'cp') { ?>

                    <div class="grid grid-cols-1 items-start place-items-center">

                        <h1>Completed</h1>
                        <?php

                        if (isset($_SESSION['search'])) {
                            $search = $_SESSION['search'];
                            $sql = "SELECT * FROM todo WHERE completion = ? AND description LIKE ? AND id_user = ?";
                            $result = $db->prepare($sql);
                            $result->execute([2, "%{$search}%", $id]);
                        } else {
                            $sql = "SELECT * from todo WHERE completion = ? AND id_user = ?";
                            $result = $db->prepare($sql);
                            $result->execute([2, $id]);
                        }

                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                        ?>

                            <div class="grid shadow-2xl rounded-lg bg-green-100 mt-5 p-10 w-3/4 text-start">

                                <h1><?php echo $row['kategori'] ?></h1>
                                <p class="text-md font-medium text-gray-900"><?php echo $row['description'] ?></p>

                            </div>

                        <?php
                        }
                        ?>

                    </div>

            </div>

        <?php } ?>


        <form class="max-w-sm mx-auto mt-5" method="post">

            <div class="text-center">
                <button type="submit" name="logout" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Log Out</button>
            </div>

        </form>

        </div>

    </div>

</body>

</html>

<?php

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
}

if (isset($_POST['ongoing'])) {
    $id_todo = $_POST['id_todo'];

    $sql = "UPDATE todo SET completion = 1 WHERE id_user = ? AND id_todo = ?";

    $result = $db->prepare($sql);
    $result->execute([$id, $id_todo]);

    header('Location: main_page.php');
}

if (isset($_POST['completed'])) {
    $id_todo = $_POST['id_todo'];

    $sql = "UPDATE todo SET completion = 2 WHERE id_user = ? AND id_todo = ?";

    $result = $db->prepare($sql);
    $result->execute([$id, $id_todo]);

    header('Location: main_page.php');
}

if (isset($_POST['todo_search'])) {

    $description = $_POST['todo_search'];

    $_SESSION['search'] = $description;

    header('Location: main_page.php');
}

if (isset($_POST['reset_search'])) {

    unset($_SESSION['search']);

    header('Location: main_page.php');
}

?>