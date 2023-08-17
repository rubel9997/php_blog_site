<?php

    include_once "../lib/Database.php";
    include_once "../helpers/Format.php";
    include_once "../PHPmailer/PHPMailer.php";
    include_once "../PHPmailer/SMTP.php";
    include_once "../PHPmailer/Exception.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;


class ResendEmail{

    public $db_connection;
    public $validate;

    public function __construct()
    {
        $this->db_connection = new Database();
        $this->validate = new Format();
    }

    public function resendEmail($email){

        function  resendEmailVerify($name,$email,$token){
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = 'a6aa178ce61857';
            $mail->Password = 'dc2b021fd067a0';


//                 $mail = new PHPMailer(true);
//                 $mail->isSMTP();
//                 $mail->SMTPAuth   = true;
//                 $mail->Host       = 'smtp.gmail.com';
//                 $mail->Username   = 'admin@gmail.com';
//                 $mail->Password   = 'pass';
//                 $mail->SMTPSecure = 'tls';
//                 $mail->Port       = 587;
            $mail->setFrom('a6aa178ce61857', $name);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification Mail';
            $email_template   = "<h4> You have register with Ui Barn.</h4>
                                        <h5>Verify your email address to login. please click the link below.</h5> 
                                        <a href='http://localhost/php-blog-site/admin/verify-email.php?token=$token'>Click here</a>";
            $mail->Body       = $email_template;
            $mail->send();
        }


        $email = $this->validate->validation($email);
        $email = mysqli_real_escape_string($this->db_connection->link,$email);

        if(empty($email)){
            $error = 'Email field must not be empty';
            return $error;
        }else{
            $query = "SELECT * FROM users where email = '$email'";
            $result = $this->db_connection->select($query);
            if($result){

                $row = mysqli_fetch_assoc($result);
                if($row['verified_at'] == null){
                    $name = $row['name'];
                    $email = $row['email'];
                    $token = $row['token'];

                    resendEmailVerify($name,$email,$token);

                    $success = 'Verification email link is send your email.';
                    return $success;

                }else{
                    $error = 'Email already verified.please login. ';
                    return $error;
                }

            }else{
                $error = 'This email is not registered.Please registration first.';
                return $error;
            }
        }



    }



}

?>