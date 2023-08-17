<?php

include_once '../lib/Database.php';
include_once '../helpers/Format.php';

class Category{

    public $db_connection;
    public $validate;

    public function __construct()
    {
        $this->db_connection = new Database();
        $this->validate = new Format();
    }

    public function allCategory(){
        $select_query = "SELECT * FROM categories ORDER BY id DESC";
        $select_result = $this->db_connection->select($select_query);
        if($select_query != false){
            return $select_result;
        }else{
            $error  = "No data fund!";
            return $error;
        }
    }

    public function viewCategory(){
        $select_query = "SELECT * FROM categories";
        $select_result = $this->db_connection->select($select_query);
        if($select_query != false){
            return $select_result;
        }else{
            $error  = "No data fund!";
            return $error;
        }
    }

    public function totalCategory(){
        $count_query = "SELECT COUNT(id) AS total_category_count FROM categories";
        $count_result = $this->db_connection->select($count_query);
        if($count_result){
            $row = $count_result->fetch_assoc();
            $categoryCount = $row['total_category_count'];
            return $categoryCount;
        }
    }


    public function addCategory($data){
        $name = $this->validate->validation($data['name']);
        $status = $this->validate->validation($data['status']);
        if(empty($name) || empty($status)){
            $error  = "Field must not be empty";
            header("Location: {$_SERVER['PHP_SELF']}?error=$error");
            exit;
        }else{
            $select_query = "SELECT * FROM categories where name ='$name'";
            $select_result = $this->db_connection->select($select_query);

            if($select_result){
                $error  = "This category has been inserted already";
                header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                exit;
            }else{
                if($data['id']){
                    $category_id = $data['id'];
                    $update_query = "UPDATE categories SET name = '$name' where id='$category_id'";
                    $update_result = $this->db_connection->update($update_query);
                    if($update_result){
                        $success  = "Category has been updated successfully";
                        header("Location: {$_SERVER['PHP_SELF']}?success=$success");
                        exit;
                    }else{
                        $error  = "Category has not been updated successfully";
                        header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                        exit;
                    }
                }else{
                    $insert_query = "INSERT INTO categories (name,status) values ('$name','$status')";
                    $insert_result = $this->db_connection->insert($insert_query);
                    if($insert_result){
                        $success  = "Category has been inserted successfully";
                        header("Location: {$_SERVER['PHP_SELF']}?success=$success");
                        exit;
                    }else{
                        $error  = "Category has not been inserted successfully";
                        header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                        exit;
                    }
                }

            }
        }
    }

    public function editCategory($id){
        $edit_query = "SELECT * FROM categories where id='$id'";
        $edit_result = $this->db_connection->select($edit_query);
        if($edit_result){
            $row = mysqli_fetch_assoc($edit_result);
            return $row;
        }
    }

    public function destroy($id){
        $delete_query = "DELETE FROM categories where id ='$id'";
        $delete_result = $this->db_connection->delete($delete_query);
        if($delete_result){
            $success  = "Category has been deleted successfully";
            header("Location: {$_SERVER['PHP_SELF']}?success=$success");
            exit;
        }else{
            $error  = "Category has not been deleted successfully";
            header("Location: {$_SERVER['PHP_SELF']}?error=$error");
            exit;
        }
    }




}

?>