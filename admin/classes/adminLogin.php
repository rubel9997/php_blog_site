<?php

    include_once '../lib/Session.php';
        Session::loginCheck();
    include_once '../lib/Database.php';
    include_once '../helpers/Format.php';


class adminLogin{
        public $db_connection;
        public $validate;

        public function __construct()
        {
            $this->db_connection = new Database();
            $this->validate = new Format();
        }

        public function allUser(){
            $select_query ="SELECT * FROM users";
            $select_result = $this->db_connection->select($select_query);
            if($select_result){
                return $select_result;
            }
        }

        public function loginUser($email,$password){
            $email = $this->validate->validation($email);
            $password = $this->validate->validation($password);

            if(empty($email) || empty($password)){
                $error = 'Field must not be empty';
                return $error;
            }else{
                $query = "SELECT * FROM users  where email = '$email' AND password = '$password'";

                $result  = $this->db_connection->select($query);

                if($result){
                    $row = mysqli_fetch_assoc($result);
                    if($row['verified_at'] != null ){
                        $_SESSION['auth_id'] = $row['id'];
                        Session::set('login',true);
                        Session::set('name',$row['name']);
                        header('Location:index.php');

                    }else{
                        $error = 'Please verify your email first.';
                        return $error;
                    }

                }else{
                    $error = 'Email and password do not match the records';
                    return $error;
                }
            }

        }


}




?>