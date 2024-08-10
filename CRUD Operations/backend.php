<?php
include('db.php');
echo('Connection Found');
// Add new user
if (isset($_POST['save_newuser'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $reg = mysqli_real_escape_string($conn, $_POST['reg']);

    $query = "INSERT INTO student_info (name, reg) VALUES ('$name', '$reg')";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $response = [
            'status' => 200,
            'message' => 'User added successfully!'
        ];
    } else {
        $response = [
            'status' => 500,
            'message' => 'User addition failed!'
        ];
    }

    echo json_encode($response);
    exit();
}

// Delete user
if (isset($_POST['delete_user'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);

    $query = "DELETE FROM student_info WHERE id='$user_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $response = [
            'status' => 200,
            'message' => 'User deleted successfully!'
        ];
    } else {
        $response = [
            'status' => 500,
            'message' => 'User deletion failed!'
        ];
    }

    echo json_encode($response);
    exit();
}

// Get user data for editing
if (isset($_GET['get_user'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);

    $query = "SELECT * FROM student_info WHERE id='$user_id'";
    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) == 1) {
        $user = mysqli_fetch_array($query_run);

        $response = [
            'status' => 200,
            'data' => $user
        ];
    } else {
        $response = [
            'status' => 404,
            'message' => 'User not found!'
        ];
    }

    echo json_encode($response);
    exit();
}

// Update user data
if (isset($_POST['update_user'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $reg = mysqli_real_escape_string($conn, $_POST['reg']);

    $query = "UPDATE student_info SET name='$name', reg='$reg' WHERE id='$user_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $response = [
            'status' => 200,
            'message' => 'User updated successfully!'
        ];
    } else {
        $response = [
            'status' => 500,
            'message' => 'User update failed!'
        ];
    }

    echo json_encode($response);
    exit();
}
?>
