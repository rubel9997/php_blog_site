<?php

include_once '../lib/Session.php';
include_once '../lib/Database.php';
include_once '../helpers/Format.php';
include_once "../PHPmailer/PHPMailer.php";
include_once "../PHPmailer/SMTP.php";
include_once "../PHPmailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class User{
    public $db_connection;
    public $validate;

    public function __construct()
    {
        $this->db_connection = new Database();
        $this->validate = new Format();
    }

    public function allUser(){
        $select_query ="SELECT * FROM users ORDER BY id DESC";
        $select_result = $this->db_connection->select($select_query);
        if($select_result){
            return $select_result;
        }
    }

    public function totalUser(){
        $count_query = "SELECT COUNT(id) AS total_user_count FROM users";
        $count_result = $this->db_connection->select($count_query);
        if($count_result){
            $row = $count_result->fetch_assoc();
            $userCount = $row['total_user_count'];
            return $userCount;
        }
    }


    public function addUser($data){

        function sendMail($name,$email,$token){

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
            $email_template   = "<h4> You have register with Ui Barn.</h4>
                                        <h5>Verify your email address to login. please click the link below.</h5> 
                                        <a href='http://localhost/php-blog-site/admin/verify-email.php?token=$token'>Click here</a>";
            $mail->Body       = $email_template;
            $mail->send();
        }

        $name = $this->validate->validation($data['name']);
        $email = $this->validate->validation($data['email']);
        $phone = $this->validate->validation($data['phone']);
        $password = $this->validate->validation(md5($data['password']));
        $status = $this->validate->validation($data['status']);
        $token = md5(rand());

        if(empty($name) || empty($email) || empty($phone) || empty($status)){
            $error  = "Field must not be empty";
            header("Location: {$_SERVER['PHP_SELF']}?error=$error");
            exit;
        }else{
            if($data['id']){
                $category_id = $data['id'];
                $update_query = "UPDATE users SET name = '$name',email='$email', phone ='$phone', status='$status' where id='$category_id'";
                $update_result = $this->db_connection->update($update_query);
                if($update_result){
                    $success  = "User has been updated successfully";
                    header("Location: {$_SERVER['PHP_SELF']}?success=$success");
                    exit;
                }else{
                    $error  = "User has not been updated successfully";
                    header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                    exit;
                }
            }else{
                $query = "SELECT * FROM users where email = '$email'";
                $email_check = $this->db_connection->select($query);
                if($email_check > '0') {
                    $error = 'This email already exist!';
                    header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                }else{
                    $insert_query = "INSERT INTO users (name,email,phone,password,token,status) VALUES ('$name','$email','$phone','$password','$token','$status')";
                    $insert_result = $this->db_connection->insert($insert_query);
                    if($insert_result){
                        sendMail($name,$email,$token);
                        $success  = "User has been inserted successfully. Please check your email for verify your account.";
                        header("Location: {$_SERVER['PHP_SELF']}?success=$success");
                        exit;
                    }else{
                        $error  = "User has not been inserted successfully";
                        header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                        exit;
                    }
                }
            }
        }

    }

    public function editUser($id){
        $edit_query = "SELECT * FROM users where id='$id'";
        $edit_result = $this->db_connection->select($edit_query);
        if($edit_result){
            $row = mysqli_fetch_assoc($edit_result);
            return $row;
        }
    }

    public function viewUser(){
        $select_query ="SELECT * FROM users";
        $select_result = $this->db_connection->select($select_query);
        if($select_result){
            return $select_result;
        }
    }

    public function destroy($id){
        $delete_query = "DELETE FROM users where id = '$id'";
        $delete_result = $this->db_connection->delete($delete_query);
        if($delete_result){
            $success  = "User has been deleted successfully";
            header("Location: {$_SERVER['PHP_SELF']}?error=$success");
            exit;
        }else{
            $error  = "User has not been deleted successfully";
            header("Location: {$_SERVER['PHP_SELF']}?error=$error");
            exit;
        }
    }


}




?>