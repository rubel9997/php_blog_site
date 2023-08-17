<?php

include_once "../lib/Database.php";
include_once "../helpers/Format.php";
include_once "../PHPmailer/PHPMailer.php";
include_once "../PHPmailer/SMTP.php";
include_once "../PHPmailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class ResetPassword{

    public $db_connection;
    public $validate;

    public function __construct()
    {
        $this->db_connection = new Database();
        $this->validate = new Format();
    }

    public function resetPassword($email){

        function  passwordChangeVerify($name,$email,$token){
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = 'a6aa178ce61857';
            $mail->Password = 'dc2b021fd067a0';
            $mail->setFrom('a6aa178ce61857', $name);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification Mail';
            $email_template   = "<h4> Password reset link.</h4>
                                        <h5>your password reset link is send.</h5> 
                                        <a href='http://localhost/php-blog-site/admin/password-change.php?token=$token&email=$email'>Click here</a>";
            $mail->Body       = $email_template;
            $mail->send();
        }


        $email = $this->validate->validation($email);
        $email = mysqli_real_escape_string($this->db_connection->link,$email);
        $token = md5(rand());

        if(empty($email)){
            $error = 'Email field must not be empty';
            return $error;

        }else{
            $query = "SELECT * FROM users where email = '$email'";
            $result = $this->db_connection->select($query);
            if($result){

                $row = mysqli_fetch_assoc($result);
                $name = $row['name'];
                $email = $row['email'];

                $update_query = "UPDATE users SET token='$token' where email='$email' LIMIT 1";
                $update_result = $this->db_connection->update($update_query);

                if($update_result){

                    passwordChangeVerify($name,$email,$token);
                    $success = 'Password reset verification link send.';
                    return $success;
                }else{
                    $error = 'Something went wrong. Token does not update. ';
                    return $error;
                }

            }else{
                $error = 'This email is not found.';
                return $error;
            }
        }



    }



}

?>