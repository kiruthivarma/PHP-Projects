<?php
include("db.php");

// Add new User
if (isset($_POST['save_newuser'])) {
    try {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $reg = mysqli_real_escape_string($conn, $_POST['reg']);

        $query = "INSERT INTO student_info(name, reg) VALUES ('$name', '$reg')";

        if (mysqli_query($conn, $query)) {
            $res = [
                'status' => 200,
                'message' => 'Details Updated Successfully'
            ];
            echo json_encode($res);
        } else {
            throw new Exception('Query Failed: ' . mysqli_error($conn));
        }
    } catch (Exception $e) {
        $res = [
            'status' => 500,
            'message' => 'Error: ' . $e->getMessage()
        ];
        echo json_encode($res);
    }
}

// Delete User
if (isset($_POST['delete_user'])) {
    $student_id = mysqli_real_escape_string($conn, $_POST['user_id']);
  
    $query = "DELETE FROM student_info WHERE id='$student_id'";
    $query_run = mysqli_query($conn, $query);
  
    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Details Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Details Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
}

// Update User
if (isset($_GET['get_user'])) {
    $student_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $query = "SELECT * FROM student_info WHERE id='$student_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run && mysqli_num_rows($query_run) > 0) {
        $user = mysqli_fetch_assoc($query_run);
        $res = [
            'status' => 200,
            'data' => $user
        ];
    } else {
        $res = [
            'status' => 500,
            'message' => 'User Not Found'
        ];
    }
    echo json_encode($res);
}

if (isset($_POST['update_user'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $reg = mysqli_real_escape_string($conn, $_POST['reg']);

        $query = "UPDATE student_info SET name='$name', reg='$reg' WHERE id='$id'";

        if (mysqli_query($conn, $query)) {
            $res = [
                'status' => 200,
                'message' => 'Details Updated Successfully'
            ];
            echo json_encode($res);
        } else {
            throw new Exception('Query Failed: ' . mysqli_error($conn));
        }
    } catch (Exception $e) {
        $res = [
            'status' => 500,
            'message' => 'Error: ' . $e->getMessage()
        ];
        echo json_encode($res);
    }
}
?>
