<?php

    include_once "../lib/Session.php";
    Session::init();

    include_once "../lib/Database.php";

    $db_connection = new Database();

    if(isset($_GET['token'])){
        $token = $_GET['token'];
        $query = "SELECT token,verified_at FROM users where token = '$token'";
        $result = $db_connection->select($query);
        if($result != false){
            $row = mysqli_fetch_assoc($result);
            if($row['verified_at'] == null){
                $click_token = $row['token'];
                $current_date = date('Y-m-d');
//                print_r($click_token);
                $update_query = "UPDATE users SET verified_at = '$current_date' WHERE token = '$click_token'";
                $update_result = $db_connection->update($update_query);

                if($update_result){
                    $_SESSION['status'] = 'Your account has been verified successfully.';
                    header('Location:login.php');
                }else{
                    $_SESSION['status'] = 'Verified failed';
                    header('Location:login.php');
                }


            }else{
                $_SESSION['status'] = 'This email is already verified. plseae login!';
                header('Location:login.php');
            }

        }else{
            $_SESSION['status'] = 'This token does not exist!';
            header('Location:login.php');
        }

    }else{
        $_SESSION['status'] = 'Not Allowed';
        header('Location:login.php');
    }


?>