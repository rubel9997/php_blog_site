<?php

include_once "../lib/Database.php";
include_once "../helpers/Format.php";

class PasswordChange{

    public $db_connection;
    public $validate;

    public function __construct()
    {
        $this->db_connection = new Database();
        $this->validate = new Format();
    }

    public function changePassword($data){
        $email = $this->validate->validation($data['email']);
        $new_password = $this->validate->validation(md5($data['new_password']));
        $confirm_password = $this->validate->validation(md5($data['confirm_password']));
        $token = $this->validate->validation($data['token']);


        if(!empty($token)){

            if(!empty($email) || !empty($new_password) || !empty($confirm_password)){

                $query = "SELECT token FROM users where token ='$token'";
                $result = $this->db_connection->select($query);
                if(!empty($result) && $result !== true){
                    if($result){

                        if($new_password == $confirm_password){
                            $update_password = "UPDATE users SET password = '$new_password' where token = '$token'";
                            $udpate_result = $this->db_connection->update($update_password);
                            if($udpate_result){
                                $new_token = md5(rand());
                                $update_token = "UPDATE users SET token = '$new_token' where token ='$token'";
                                $update_token_result = $this->db_connection->update($update_token);
                                $success  = "Password change successfully";
                                return $success;

                            }else{
                                $error  = "Password does not change";
                                return $error;
                            }

                        }else{
                            $error  = "Password and Confirm password does not match";
                            return $error;
                        }

                    }else{
                        $error  = "Invalid token";
                        return $error;
                    }
                }else{
                    $error  = "Invalid token";
                    return $error;
                }


            }else{
                $error  = "Field must not be empty";
                return $error;
            }

        }else{
            $error  = "Token is not available";
            return $error;
        }
    }


}

?>