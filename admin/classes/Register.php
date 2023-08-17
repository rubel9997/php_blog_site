<?php
     include_once "../lib/Database.php";
     include_once "../helpers/Format.php";
     include_once "../PHPmailer/PHPMailer.php";
     include_once "../PHPmailer/SMTP.php";
     include_once "../PHPmailer/Exception.php";

     use PHPMailer\PHPMailer\PHPMailer;
     use PHPMailer\PHPMailer\SMTP;
     use PHPMailer\PHPMailer\Exception;

class Register{

        public $db_connection;
        public $form_data_validate;

        public function __construct()
        {
            $this->db_connection = new Database();
            $this->form_data_validate = new Format();
        }

        public function addUser($data){
//            return $data;

             function sendMail($name,$email,$token){

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

            $name = $this->form_data_validate->validation($data['name']);
            $email = $this->form_data_validate->validation($data['email']);
            $phone = $this->form_data_validate->validation($data['phone']);
            $password = $this->form_data_validate->validation(md5($data['password']));
            $token = md5(rand());



            if(empty($name) || empty($email) || empty($phone) ||empty($password)){
                    $error = "Field must not be empty!";
                    return $error;
            }
            else{

                $query = "SELECT * FROM users where email = '$email'";
                $email_check = $this->db_connection->select($query);
                if($email_check > '0'){
                $error = 'This email already exist!';
                return $error;
                header('Location:register.php');

                }
                else{
                    $insert_query = "INSERT INTO users (name,email,phone,password,token) 
                                        VALUES ('$name','$email','$phone','$password','$token')";

                    $result = $this->db_connection->insert($insert_query);

                    if($result){
                        sendMail($name,$email,$token);
                        $success = 'Registration successfully. Please check your email for verify your account.';
                        return $success;
                    }else{
                        $error = 'Registration failed.';
                        return $error;
                    }
                }
            }



        }
}

?>