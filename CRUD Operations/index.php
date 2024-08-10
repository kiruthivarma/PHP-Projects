<?php
include("db.php");
$sql = "SELECT * FROM student_info";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP - CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5em 1em;
        }

        /* Hide the default search bar */
        .dataTables_wrapper .dataTables_filter {
            display: none;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">Student Information</h2>
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#adduser">Add User</button>
        </div>

        <!-- Search Bar -->
        <div class="mb-3">
            <div class="input-group">
                <input type="text" id="customSearch" class="form-control" placeholder="Search...">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="user">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">S.No</th>
                        <th scope="col">Name</th>
                        <th scope="col">Reg No.</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $s = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                    <tr>
                        <td><?php echo $s; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['reg']; ?></td>
                        <td>
                            <button type="button" value="<?php echo $row['id']; ?>" class="btn btn-warning btnuseredit">Edit</button>
                            <button type="button" value="<?php echo $row['id']; ?>" class="btn btn-danger btnuserdelete">Delete</button>
                        </td>
                    </tr>
                    <?php
                    $s++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="adduser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addnewuser">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter name" required>
                        </div>
                        <div class="mb-3">
                            <label for="reg" class="form-label">Reg No.</label>
                            <input type="text" name="reg" class="form-control" placeholder="Enter Reg No." required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="edituser" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editform">
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_reg" class="form-label">Reg No.</label>
                            <input type="text" name="reg" id="edit_reg" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function () {
            var table = $('#user').DataTable();

            // Custom search functionality
            $('#customSearch').on('keyup', function () {
                table.search(this.value).draw();
            });

            $(document).on('submit', '#addnewuser', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append("save_newuser", true);
                $.ajax({
                    type: "POST",
                    url: "backend.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        var res = jQuery.parseJSON(response);
                        if (res.status == 200) {
                            $('#adduser').modal('hide');
                            $('#addnewuser')[0].reset();
                            table.ajax.reload(); // Reload table data
                            alert(res.message);
                        } else if (res.status == 500) {
                            $('#adduser').modal('hide');
                            $('#addnewuser')[0].reset();
                            console.error("Error:", res.message);
                            alert("Something went wrong. Please try again.");
                        }
                    }
                });
            });

            $(document).on('click', '.btnuserdelete', function (e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this data?')) {
                    var user_id = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "backend.php",
                        data: {
                            'delete_user': true,
                            'user_id': user_id
                        },
                        success: function (response) {
                            var res = jQuery.parseJSON(response);
                            if (res.status == 500) {
                                alert(res.message);
                            } else {
                                table.ajax.reload(); // Reload table data
                            }
                        }
                    });
                }
            });

            // Edit user
            $(document).on('click', '.btnuseredit', function (e) {
                e.preventDefault();
                var user_id = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "backend.php",
                    data: { 'get_user': true, 'user_id': user_id },
                    success: function (response) {
                        var res = jQuery.parseJSON(response);
                        if (res.status == 200) {
                            $('#edit_id').val(res.data.id);
                            $('#edit_name').val(res.data.name);
                            $('#edit_reg').val(res.data.reg);
                            $('#edituser').modal('show');
                        } else {
                            alert(res.message);
                        }
                    }
                });
            });

            $(document).on('submit', '#editform', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append("update_user", true);
                $.ajax({
                    type: "POST",
                    url: "backend.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        var res = jQuery.parseJSON(response);
                        if (res.status == 200) {
                            $('#edituser').modal('hide');
                            $('#editform')[0].reset();
                            table.ajax.reload(); // Reload table data
                            alert(res.message);
                        } else if (res.status == 500) {
                            console.error("Error:", res.message);
                            alert("Something went wrong. Please try again.");
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
