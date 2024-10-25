
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
        $sql = "UPDATE todo SET completion = :completion WHERE ID_User = :ID_User AND ID_Todo = :ID_Todo";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':completion', $new_completion, PDO::PARAM_INT);
        $stmt->bindParam(':ID_User', $id, PDO::PARAM_INT);
        $stmt->bindParam(':ID_Todo', $id_todo, PDO::PARAM_INT);
        $stmt->execute();
        header('Location: main_page.php');
        exit();
    }
}

if (isset($_POST['delete'])) {
    $id_todo = $_POST['id_todo'] ?? null;
    if ($id_todo) {
        $sql = "DELETE FROM todo WHERE ID_Todo = :ID_Todo AND ID_User = :ID_User";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':ID_User', $id, PDO::PARAM_INT);
        $stmt->bindParam(':ID_Todo', $id_todo, PDO::PARAM_INT);
        $stmt->execute();
        header('Location: main_page.php');
        exit();
    }
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
    <title>To-Do List</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script> 
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

<body class="bg-gradient-to-r from-blue-200 to-cyan-200">

    <div class="container-fluid content-center p-6 flex justify-center">

        <div class="w-full h-100 mt-3">

            <div class="flex justify-end mt-5">

                <form method="post" class="mr-4" onsubmit="return showLogoutModal(event);">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Log Out</button>
                </form>

                <form method="post" action="profile.php">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">View Profile</button>
                </form>

            </div>

            <div class="grid grid-cols-2 gap-6k">

                <form class="max-w-sm mx-auto" action="todo_add.php" method="post">

                    <div class="mb-3 mt-10">
                        <label class="mb-2 text-lg font-medium text-gray-900">Add To-Do</label>
                        <input type="text" name="todo" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required />
                    </div>

                    <div class="flex items-center mb-4">
                        <input checked type="radio" value="General" name="category" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                        <label class="ms-2 text-sm font-medium text-gray-900">General</label>
                    </div>

                    <div class="flex items-center mb-4">
                        <input type="radio" value="Shopping" name="category" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                        <label class="ms-2 text-sm font-medium text-gray-900">Shopping</label>
                    </div>

                    <div class="flex items-center mb-4">
                        <input type="radio" value="Homework" name="category" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                        <label class="ms-2 text-sm font-medium text-gray-900">Homework</label>
                    </div>

                    <div class="flex items-center mb-4">
                        <input type="radio" value="Event" name="category" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                        <label class="ms-2 text-sm font-medium text-gray-900">Event</label>
                    </div>

                    <div class="text-center">
                        <!-- <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">Add Task</button> -->
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Add Task</button>
                    </div>

                </form>

                <form class="max-w-sm mx-auto" action="todo_filter.php" method="post">

                    <div class="mb-3 mt-10">
                        <input type="text" name="search" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Search Task" />
                    </div>

                    <div class="flex items-center mb-4">
                        <input type="radio" value="all" name="filter_status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
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

                    <div class="flex justify-between">

                        <div class="text-center">
                            <!-- <button type="submit" name="reset_search" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Reset</button> -->
                            <button type="submit" name="reset_search" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Reset</button>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="todo_search" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Search</button>
                        </div>

                    </div>

                </form>

            </div>

            <?php echo "<div class='items-start grid " . ($_SESSION['filter'] === 'all' ? "grid-cols-3" : "grid-cols-1") . " text-center'>";

            if ($_SESSION['filter'] === 'all') {
                $filters = ['td', 'og', 'cp'];
            } else {
                $filters = [$_SESSION['filter']];
            }

            $search = $_SESSION['search'] ?? '';

            foreach ($filters as $filter) {

                $completionStatus = null;

                if ($filter === 'td') {
                    $completionStatus = 0;
                } elseif ($filter === 'og') {
                    $completionStatus = 1;
                } elseif ($filter === 'cp') {
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

                echo "<div class='grid grid-cols-1 items-start place-items-center justify-start shadow-2xl border-solid border-2 m-5 pt-5 pb-10 rounded-xl font-bold'><h1>" . ($filter === 'td' ? "Todo" : ($filter === 'og' ? "Ongoing" : "Completed")) . "</h1>";

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='grid shadow-2xl border-solid border-4 border-stone-700 hover:border-sky-400 rounded-lg bg-" . ($filter === 'td' ? "red" : ($filter === 'og' ? "yellow" : "green")) . "-400 mt-5 w-3/4 text-start relative py-10 px-3'>
                                
                                <button onclick='confirmDelete({$row['ID_Todo']})' class='absolute top-0 right-0 mt-2 mr-2'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24'>
                                        <path d='M 10.806641 2 C 10.289641 2 9.7956875 2.2043125 9.4296875 2.5703125 L 9 3 L 4 3 A 1.0001 1.0001 0 1 0 4 5 L 20 5 A 1.0001 1.0001 0 1 0 20 3 L 15 3 L 14.570312 2.5703125 C 14.205312 2.2043125 13.710359 2 13.193359 2 L 10.806641 2 z M 4.3652344 7 L 5.8925781 20.263672 C 6.0245781 21.253672 6.877 22 7.875 22 L 16.123047 22 C 17.121047 22 17.974422 21.254859 18.107422 20.255859 L 19.634766 7 L 4.3652344 7 z'></path>
                                    </svg>
                                </button>

                                <h1 class='font-bold absolute top-0 left-0 mt-2 ms-2 text-xs'>{$row['Category']}</h1>
                                <p class='text-xs font-medium text-gray-900 mb-2'>{$row['Description']}</p>";

                    if ($filter === 'td' || $filter === 'og') {
                        echo "<form class='max-w-sm ms-auto absolute right-0 bottom-0 mt-2 mr-2' method='post' onsubmit='return confirm(\"Are you sure you want to mark this as " . ($filter === 'td' ? "Ongoing" : "Completed") . "?\");'>
                                        <input type='hidden' name='id_todo' value='{$row['ID_Todo']}'>
                                        <button type='submit' name='" . ($filter === 'td' ? "ongoing" : "completed") . "' class=' text-xs bg-blue-500 hover:bg-blue-600 text-white font-bold p-2 md:py-2 md:px-4 mb-2 rounded'>Update</button>
                                    </form>";
                    }



                    echo "</div>";
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

    </div>

    <link href="./src/output.css" rel="stylesheet">
</body>
</html>