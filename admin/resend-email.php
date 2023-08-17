<?php

include_once "./classes/ResendEmail.php";

$resendEmailObject = new ResendEmail();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $email = $_POST['email'];

    $resendEmail  = $resendEmailObject->resendEmail($email);
    //print_r($resendEmail);

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
    <title>Resend Email</title>
</head>
<body>
<div class="container py-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
             <span>
                <?php
                if(isset($resendEmail)){
                    ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                      <strong><?php echo $resendEmail ;?></strong>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                </div>
                    <?php
                }
                ?>
            </span>

            <div class="card">
                <div class="card-header">Resend Email Form</div>
                <div class="card-body">
                    <form action="" method="POST">

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">Resend Email</button>
                            <a href="login.php" class="btn btn-primary">Login</a>
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