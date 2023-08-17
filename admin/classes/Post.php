<?php



include_once '../lib/Database.php';
include_once '../helpers/Format.php';


class Post{

    public $db_connection;
    public $validate;

    public function __construct()
    {
        $this->db_connection = new Database();
        $this->validate = new Format();
    }

    public function allPost(){
        $select_query = "SELECT posts.*,categories.name FROM posts 
                         INNER JOIN categories ON posts.category_id = categories.id ORDER BY id DESC";
        $select_result = $this->db_connection->select($select_query);
        if($select_result != false){
            return $select_result;
        }else{
            $error  = "No data fund!";
            return $error;
        }
    }

    public function addPost($data,$file){
       $title  = $this->validate->validation($data['title']);
       $category_id  = $this->validate->validation($data['category_id']);
       $slide_description  = $this->validate->validation($data['slide_description']);
       $post_description  = $this->validate->validation($data['post_description']);
       $type  = $this->validate->validation($data['type']);
       $status  = $this->validate->validation($data['status']);
       $tags  = $this->validate->validation($data['tags']);
        $serializedTags = serialize($tags);


        //slide image
        $permitted = array('jpg','png','jpeg','gif');

        $slide_img_name = $file['slide_image']['name'];
        $slide_img_size = $file['slide_image']['size'];
        $slide_img_temp = $file['slide_image']['tmp_name'];

        $slide_img_separate = explode('.',$slide_img_name);
        $slide_img_file_ext = strtolower(end($slide_img_separate));
        $slide_unique_image = substr(md5(time()),0,10).'.'.$slide_img_file_ext;
        $slide_upload_image = "upload/".$slide_unique_image;

        //post image
        $post_img_name = $file['post_image']['name'];
        $post_img_size = $file['post_image']['size'];
        $post_img_temp = $file['post_image']['tmp_name'];

        $post_img_separate = explode('.',$post_img_name);
        $post_img_file_ext = strtolower(end($post_img_separate));
        $post_unique_image = substr(md5(time()),0,8).'.'.$post_img_file_ext;
        $post_upload_image = "upload/".$post_unique_image;

        if($data['id']){
            if(empty($title) || empty($category_id) || empty($slide_description) || empty($post_description) || empty($status) || empty($type)){
                $error  = "Field must not be empty";
                header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                exit;
            }else{
                if(!empty($slide_img_name) || !empty($post_img_name)){

                    if($slide_img_size > 1048567){
                        $error  = "File size must be less than 1 MB";
                        header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                        exit;
                    }
                    elseif($post_img_size > 1048567){
                        $error  = "File size must be less than 1 MB";
                        header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                        exit;
                    }
                    elseif(in_array($slide_img_file_ext,$permitted) == false){
                        $error  = "You can upload only:-".implode(',',$permitted);
                        header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                        exit;
                    }
                    elseif(in_array($post_img_file_ext,$permitted) == false){
                        $error  = "You can upload only:-".implode(',',$permitted);
                        header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                        exit;
                    }
                    else{

                        if(move_uploaded_file($slide_img_temp,$slide_upload_image) && move_uploaded_file($post_img_temp,$post_upload_image)){

                            if($data['id']){
                                $post_id = $data['id'];
                                $update_query = "UPDATE posts SET 
                                    title = '$title',
                                    category_id = '$category_id',
                                    slide_image = '$slide_upload_image',
                                    post_image = '$post_upload_image',
                                    slide_description = '$slide_description',
                                    post_description = '$post_description',
                                    status = '$status',
                                     type = '$type',
                                    tags = '$serializedTags'
                                    where id='$post_id'";
                                $update_result = $this->db_connection->update($update_query);

                                if($update_result){
                                    $error  = "Post has updated successfully";
                                    header("Location: {$_SERVER['PHP_SELF']}?success=$error");
                                    exit;
                                }else{
                                    $error  = "Post has not updated successfully";
                                    header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                                    exit;
                                }
                            }
                        }else{
                            $error  = "Image has not uploaded successfully";
                            header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                            exit;
                        }
                    }
                }
                else{
                    $post_id = $data['id'];
                    $update_query = "UPDATE posts SET 
                                    title = '$title',
                                    category_id = '$category_id',
                                    slide_description = '$slide_description',
                                    post_description = '$post_description',
                                    status = '$status',
                                    type = '$type',
                                    tags = '$serializedTags'
                                    where id='$post_id'";
                    $update_result = $this->db_connection->update($update_query);

                    if($update_result){
                        $error  = "Post has updated successfully";
                        header("Location: {$_SERVER['PHP_SELF']}?success=$error");
                        exit;
                    }else{
                        $error  = "Post has not updated successfully";
                        header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                        exit;
                    }
                }
            }
        }else{
            if(empty($title) || empty($category_id) || empty($slide_description) || empty($post_description) || empty($status) || empty($type)){
                $error  = "Field must not be empty";
                header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                exit;
            }elseif($slide_img_size > 1048567){
                $error  = "File size must be less than 1 MB";
                header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                exit;
            }
            elseif($post_img_size > 1048567){
                $error  = "File size must be less than 1 MB";
                header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                exit;
            }
            elseif(in_array($slide_img_file_ext,$permitted) == false){
                $error  = "You can upload only:-".implode(',',$permitted);
                header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                exit;
            }
            elseif(in_array($post_img_file_ext,$permitted) == false){
                $error  = "You can upload only:-".implode(',',$permitted);
                header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                exit;
            }else{
                if(move_uploaded_file($slide_img_temp,$slide_upload_image) && move_uploaded_file($post_img_temp,$post_upload_image)){
                    $insert_query = "INSERT INTO posts (title,category_id,slide_image,post_image,slide_description,post_description,type,status,tags) 
                         VALUES ('$title','$category_id','$slide_upload_image','$post_upload_image','$slide_description','$post_description','$type','$status','$serializedTags')";
                    $insert_result = $this->db_connection->insert($insert_query);

                    if($insert_result){
                        $error  = "Post has inserted successfully";
                        header("Location: {$_SERVER['PHP_SELF']}?success=$error");
                        exit;
                    }else{
                        $error  = "Post has not inserted successfully";
                        header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                        exit;
                    }
                }
            }
        }
    }

    public function editPost($id){
        $edit_query = "SELECT * FROM posts where id='$id'";
        $edit_result = $this->db_connection->select($edit_query);
        if($edit_result){
            $row = mysqli_fetch_assoc($edit_result);
            return $row;
        }
    }

    public function tagRetrieve($id){
        $select_tag_query = "SELECT * FROM posts where id = '$id'";
        $select_tag_result = $this->db_connection->select($select_tag_query);
        if($select_tag_result != false){
            $row = mysqli_fetch_assoc($select_tag_result);
            $tags = unserialize($row['tags']);
            return $tags;
        }
    }

    public function viewPost(){
        $select_query = "SELECT posts.*,categories.name FROM posts 
                         INNER JOIN categories ON posts.category_id = categories.id ORDER BY id DESC";
        $select_result = $this->db_connection->select($select_query);
        if($select_result != false){
            return $select_result;
        }else{
            $error  = "No data fund!";
            return $error;
        }
    }

    public function totalPost(){
        $count_query = "SELECT COUNT(id) AS total_post_count FROM posts";
        $count_result = $this->db_connection->select($count_query);
        if($count_result){
            $row = $count_result->fetch_assoc();
            $countPost = $row['total_post_count'];
            return $countPost;
        }
    }


    public function destroy($id){
        // First, retrieve the image filename from the database
        $image_query = "SELECT * FROM posts WHERE id = '$id'";
        $image_result = $this->db_connection->select($image_query);

        if ($image_result) {
            $image_row = mysqli_fetch_assoc($image_result);
            $slide_img = $image_row['slide_image'];
            $post_img = $image_row['post_image'];

            // Delete the post from the database
            $delete_query = "DELETE FROM posts WHERE id = '$id'";
            $delete_result = $this->db_connection->delete($delete_query);

            if ($delete_result) {
                // Delete the image file from the upload folder

                if (file_exists($slide_img)) {
                    unlink($slide_img); // Delete the image file
                }
                if (file_exists($post_img)) {
                    unlink($post_img); // Delete the image file
                }

                $success = "Post and image have been deleted successfully";
                header("Location: {$_SERVER['PHP_SELF']}?success=$success");
                exit;
            } else {
                $error = "Post has not been deleted successfully";
                header("Location: {$_SERVER['PHP_SELF']}?error=$error");
                exit;
            }
        } else {
            $error = "Post not found or image filename missing";
            header("Location: {$_SERVER['PHP_SELF']}?error=$error");
            exit;
        }
    }


}



?>