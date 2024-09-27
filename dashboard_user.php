<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../config/db.php'; // Ensure the correct path to db.php

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You are not logged in!";
    exit();
}

$user_id = $_SESSION['user_id']; // Logged-in user's ID

// Handle adding a new task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $created_at = trim($_POST['date']);

    // Insert the new task into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, created_at, user_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $created_at, $user_id]);

        // Redirect to the user dashboard after adding the task
        header('Location: /connex_codeworks_task_platform_dynamic/connex_codeworks_task_platform_dynamic/views/user_dashboard.php');
        exit();
    } catch (PDOException $e) {
        echo "Error inserting task: " . $e->getMessage();
    }
}

// Handle task editing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_task'])) {
    $task_id = $_POST['task_id'];
    $edit_title = trim($_POST['edit_title']);
    $edit_description = trim($_POST['edit_description']);
    $edit_date = trim($_POST['edit_date']);

    // Update the task in the database
    try {
        $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, created_at = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$edit_title, $edit_description, $edit_date, $task_id, $user_id]);

        // Redirect to the user dashboard after editing the task
        header('Location: /connex_codeworks_task_platform_dynamic/connex_codeworks_task_platform_dynamic/views/user_dashboard.php');
        exit();
    } catch (PDOException $e) {
        echo "Error updating task: " . $e->getMessage();
    }
}

// Fetch all tasks for the logged-in user
$stmt = $pdo->prepare("SELECT id, title, description, created_at FROM tasks WHERE user_id = ?");
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Dashboard</title>
</head>
<body>
    <h1>Your Tasks</h1>

    <!-- Add New Task Form -->
    <h2>Add a New Task</h2>
    <form method="POST" action="">
        <input type="hidden" name="add_task" value="1">
        <label>Title:</label>
        <input type="text" name="title" required><br>

        <label>Description:</label>
        <textarea name="description" required></textarea><br>

        <label>Date:</label>
        <input type="date" name="date" required><br>

        <button type="submit">Add Task</button>
    </form>

    <h2>Your Tasks</h2>
    <?php if (!empty($tasks)): ?>
    <table border="1">
        <tr>
            <th>Task Title</th>
            <th>Description</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($tasks as $task): ?>
        <tr>
            <td><?= htmlspecialchars($task['title']); ?></td>
            <td><?= htmlspecialchars($task['description']); ?></td>
            <td><?= htmlspecialchars($task['created_at']); ?></td>
            <td>
                <!-- Edit Task Button -->
                <a href="javascript:void(0);" onclick="document.getElementById('edit_task_<?= $task['id']; ?>').style.display='block';">Edit</a>
            </td>
        </tr>

        <!-- Edit Task Form (Hidden by Default) -->
        <tr id="edit_task_<?= $task['id']; ?>" style="display: none;">
            <td colspan="4">
                <form method="POST" action="">
                    <input type="hidden" name="edit_task" value="1">
                    <input type="hidden" name="task_id" value="<?= $task['id']; ?>">

                    <label>Title:</label>
                    <input type="text" name="edit_title" value="<?= htmlspecialchars($task['title']); ?>" required><br>

                    <label>Description:</label>
                    <textarea name="edit_description" required><?= htmlspecialchars($task['description']); ?></textarea><br>

                    <label>Date:</label>
                    <input type="date" name="edit_date" value="<?= htmlspecialchars($task['created_at']); ?>" required><br>

                    <button type="submit">Update Task</button>
                    <button type="button" onclick="document.getElementById('edit_task_<?= $task['id']; ?>').style.display='none';">Cancel</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
    <p>No tasks found.</p>
    <?php endif; ?>
</body>
</html>
