<?php

include_once '../lib/Database.php';
include_once "./classes/PasswordChange.php";

$changePasswordObj = new PasswordChange();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

//    $email = $_POST['email'];
//    $new_password = md5($_POST['new_password']);
//    $conform_password = md5($_POST['confirm_password']);

    $changePass  = $changePasswordObj->changePassword($_POST);
    //print_r($loginUser);

}

?>


<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <title>Login Form</title>
</head>
<body>
<div class="container py-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
             <span>
                <?php
                if(isset($changePass)){
                    ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                      <strong><?php echo $changePass ;?></strong>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                </div>
                    <?php
                }
                ?>
            </span>

            <div class="card">
                <div class="card-header">Login Form</div>
                <div class="card-body">
                    <form action="" method="POST">

                        <input type="hidden" class="form-control" name="token" id="token" value="<?php if(isset($_GET['token'])){ echo $_GET['token']; } ?>">

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email"value="<?php if(isset($_GET['email'])){ echo $_GET['email']; } ?>">
                        </div>

                        <div class="form-group my-3">
                            <label for="new_password">Password</label>
                            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="New Password">
                        </div>

                        <div class="form-group my-3">
                            <label for="confirm_password">Password</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>
</html>