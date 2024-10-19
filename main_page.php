<?php

session_start();
require_once('db.php');
$id = $_SESSION['id_user'];

if (!isset($_SESSION['id_user']) && !isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['ongoing']) || isset($_POST['completed'])) {
    $id_todo = $_POST['id_todo'] ?? null;
    if ($id_todo) {
        $new_completion = isset($_POST['ongoing']) ? 1 : 2; // 1 for ongoing, 2 for completed
        $sql = "UPDATE todo SET completion = ? WHERE ID_User = ? AND ID_Todo = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$new_completion, $id, $id_todo]);
        header('Location: main_page.php');
        exit();
    }
}

if (isset($_POST['delete'])) {
    $id_todo = $_POST['id_todo'] ?? null;
    if ($id_todo) {
        $sql = "DELETE FROM todo WHERE ID_Todo = ? AND ID_User = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_todo, $id]);
        header('Location: main_page.php');
        exit();
    }
}

if (isset($_POST['todo_search'])) {
    $_SESSION['search'] = $_POST['todo_search'];
    header('Location: main_page.php');
    exit();
}

if (isset($_POST['reset_search'])) {
    unset($_SESSION['search']);
    header('Location: main_page.php');
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function confirmDelete(id) {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            document.getElementById('deleteTodoId').value = id;
        }
        
        function closeModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
        }

        function showLogoutModal(event) {
            event.preventDefault(); 
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('hidden');
            document.getElementById('logoutForm').action = event.target.form.action; 
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('hidden');
        }
    </script>
</head>

<body>

    <div class="container-fluid content-center p-6 flex justify-center">

        <div class="w-full h-100 mt-3">

            <form class="max-w-sm mx-auto" action="todo_add.php" method="post">

                <div class="mb-3 mt-10">
                    <label class="mb-2 text-lg font-medium text-gray-900">Add To-Do</label>
                    <input type="text" name="todo" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required />
                </div>

                <div class="text-center">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">Add Task</button>
                </div>

            </form>

            <form class="max-w-sm mx-auto" method="post">

                <div class="mb-3 mt-10">
                    <label class="mb-2 text-lg font-medium text-gray-900">Search To-Do</label>
                    <input type="text" name="todo_search" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" />
                </div>

                <div class="text-center">
                    <button type="submit" name="search" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">Search</button>
                </div>

            </form>

            <form class="max-w-sm mx-auto" method="post">

                <div class="text-center">
                    <button type="submit" name="reset_search" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Reset</button>
                </div>

            </form>

            <form class="max-w-sm mx-auto" action="todo_filter.php" method="post">

                <div class="flex items-center mb-4">
                    <input checked type="radio" value="all" name="filter_status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                    <label class="ms-2 text-sm font-medium text-gray-900">All</label>
                </div>

                <div class="flex items-center mb-4">
                    <input type="radio" value="td" name="filter_status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                    <label class="ms-2 text-sm font-medium text-gray-900">To Do</label>
                </div>

                <div class="flex items-center mb-4">
                    <input type="radio" value="og" name="filter_status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                    <label class="ms-2 text-sm font-medium text-gray-900">Ongoing</label>
                </div>

                <div class="flex items-center mb-4">
                    <input type="radio" value="cp" name="filter_status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                    <label class="ms-2 text-sm font-medium text-gray-900">Completed</label>
                </div>

                <div class="text-center">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Filter</button>
                </div>

            </form>

            <div class="items-start grid grid-cols-3 text-center">

                <?php

                    $filters = ['all', 'td', 'og', 'cp'];
                    $search = $_SESSION['search'] ?? '';
                    foreach ($filters as $filter) {

                        $completionStatus = null;

                        if ($filter === 'td') {
                            $completionStatus = 0;
                        }
                        elseif ($filter === 'og') {
                            $completionStatus = 1;
                        }
                        elseif ($filter === 'cp') {
                            $completionStatus = 2;
                        }

                        $sql = "SELECT * FROM todo WHERE ID_User = ?";
                        $params = [$id];

                        if ($completionStatus !== null) {
                            $sql .= " AND completion = ?";
                            $params[] = $completionStatus;
                        }
                        
                        if (!empty($search)) {
                            $sql .= " AND description LIKE ?";
                            $params[] = "%{$search}%";
                        }

                        $stmt = $db->prepare($sql);
                        $stmt->execute($params);

                        echo "<div class='grid grid-cols-1 items-start place-items-center'><h1>" . ucfirst($filter) . "</h1>";

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<div class='grid shadow-2xl rounded-lg bg-" . ($filter === 'td' ? "red" : ($filter === 'og' ? "yellow" : "green")) . "-100 mt-5 p-10 w-3/4 text-start relative'>
                                    <button onclick='confirmDelete({$row['ID_Todo']})' class='absolute top-0 right-0 mt-2 mr-2 text-red-500 hover:text-red-700'>X</button>
                                    <h1>{$row['category']}</h1>
                                    <p class='text-md font-medium text-gray-900'>{$row['description']}</p>
                                    <form class='max-w-sm ms-auto' method='post' onsubmit='return confirm(\"Are you sure you want to mark this as " . ($filter === 'td' ? "Ongoing" : "Completed") . "?\");'>
                                        <input type='hidden' name='id_todo' value='{$row['ID_Todo']}'>
                                        <button type='submit' name='" . ($filter === 'td' ? "ongoing" : "completed") . "' class='mt-2 text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5'>" . ucfirst($filter) . "</button>
                                    </form>
                                  </div>";
                        }
                        echo "</div>";

                    }
                    ?>
                </div>

                <div id="logoutModal" class="hidden fixed z-10 inset-0 overflow-y-auto">

                    <div class="flex items-center justify-center min-h-screen">

                        <div class="fixed inset-0 bg-gray-500 opacity-75"></div>

                        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-lg">

                            <div class="px-4 py-3">

                                <h2 class="text-lg font-semibold">Confirm Logout</h2>
                                <p>Are you sure you want to log out?</p>

                                <form method="post" id="logoutForm">

                                    <div class="flex justify-end mt-4">
                                        <button type="button" onclick="closeLogoutModal()" class="text-gray-500 hover:text-gray-800">Cancel</button>
                                        <button type="submit" name="logout" class="ml-2 text-white bg-red-600 hover:bg-red-700 rounded px-4 py-2">Log Out</button>
                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="flex justify-end mt-5">

                    <form method="post" class="mr-4" onsubmit="return showLogoutModal(event);">
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Log Out</button>
                    </form>

                    <form method="post" action="profile.php">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">View Profile</button>
                    </form>

                </div>

                <div id="deleteModal" class="hidden fixed z-10 inset-0 overflow-y-auto">

                    <div class="flex items-center justify-center min-h-screen">

                        <div class="fixed inset-0 bg-gray-500 opacity-75"></div>

                        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-lg">

                            <div class="px-4 py-3">

                                <h2 class="text-lg font-semibold">Confirm Delete</h2>
                                <p>Are you sure you want to delete this To-Do?</p>

                                <form method="post">

                                    <input type="hidden" id="deleteTodoId" name="id_todo" value="">

                                    <div class="flex justify-end mt-4">
                                        <button type="button" onclick="closeModal()" class="text-gray-500 hover:text-gray-800">Cancel</button>
                                        <button type="submit" name="delete" class="ml-2 text-white bg-red-600 hover:bg-red-700 rounded px-4 py-2">Delete</button>
                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>
                    
                </div>


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

                                <h1><?php echo $row['category'] ?></h1>
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

                                <h1><?php echo $row['category'] ?></h1>
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

                                <h1><?php echo $row['category'] ?></h1>
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