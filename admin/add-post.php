<?php
include_once "inc/header.php";
include_once "inc/sidebar.php";
include_once "./classes/Post.php";
include_once "./classes/Category.php";


$category = new Category();
$all_category  = $category->allCategory();

//print_r($all_category);

$post = new Post();
$all_post  = $post->allPost();


//if($_SERVER['REQUEST_METHOD'] == 'POST'){
//
//    $add_post  = $post->addPost($_POST,$_FILES);
//
//}

if(isset($_GET['id'])){
    $id = base64_decode($_GET['id']);
    $edit_post = $post->editPost($id);
}

if(isset($_GET['delete_post_id'])){
    $id = base64_decode($_GET['delete_post_id']);
    $delete_post = $post->destroy($id);
}

if(isset($_GET['success'])){
    $success = $_GET['success'];
}

if(isset($_GET['error'])){
    $error = $_GET['error'];
}


?>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->

    <div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-10">
                        <span>
                            <?php
                            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                                $add_post  = $post->addPost($_POST,$_FILES);
                                print_r($add_post);

                            }

                            if(isset($success)){
                                ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                  <strong><?php echo $success ;?></strong>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                 </div>
                                <?php
                            }
                            elseif(isset($error)){
                                ?>
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                  <strong><?php echo $error ;?></strong>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                 </div>
                                <?php
                            }
                            ?>
                        </span>

                    <div class="card">
                        <div class="card-header"><h4 class="mb-0"><?php if(isset($edit_post['id'])) { echo 'Post Update' ;}else{ echo 'Post Add'; }  ?></h4></div>
                        <div class="card-body">
                            <form class="custom-validation" action="" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" id="id" value="<?php if(isset($edit_post['id'])) { echo $edit_post['id'] ;} ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="title" class="form-control" value="<?php if(isset($edit_post['title'])) { echo $edit_post['title'] ;} ?>" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select class="form-select select2" name="category_id" id="">
                                                <option value="">Select One</option>
                                                <?php
                                                while ($row = mysqli_fetch_assoc($all_category)){
                                                     ?>
                                                    <option <?php echo isset($edit_post['category_id']) && $edit_post['category_id'] == $row['id'] ? 'selected':'' ?> value="<?php echo isset($row['id']) ? $row['id']:'' ?>"><?php echo isset($row['name']) ? $row['name']:'' ?></option>
                                                    <?php
                                                }

                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Type</label>
                                            <select class="form-select select2" name="type" id="">
                                                <option value="">Select one</option>
                                                <option <?php echo isset($edit_post['type'])  && $edit_post['type'] == 'Slide' ? 'selected':''; ?> value="Slide">Slide</option>
                                                <option <?php echo isset($edit_post['type'])  && $edit_post['type'] == 'Post' ? 'selected':''; ?> value="Post">Post</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select class="form-select select2" name="status" id="">
                                                <option value="">Select one</option>
                                                <option <?php echo isset($edit_post['status'])  && $edit_post['status'] == 'Y' ? 'selected':''; ?> value="Y">Active</option>
                                                <option <?php echo isset($edit_post['status'])  && $edit_post['status'] == 'N' ? 'selected':''; ?> value="N">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tags</label>
                                            <select class="form-control select2" multiple="multiple" name="tags[]" id="">
                                                <?php
                                                $tags = $post->tagRetrieve($edit_post['id']);
                                                if($tags){
                                                    foreach ($tags as $tag){
                                                        ?>
                                                        <option value="<?php echo $tag ;?>" selected><?php echo $tag;?></option>
                                                        <?php
                                                    }
                                                }

                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Slide Image</label>
                                            <input type="file" name="slide_image"  class="form-control slide_image">
                                            <?php
                                            if(isset($edit_post['id'])) {
                                                ?>
                                                <img style="width: 200px;" class="mt-2" src="<?php echo $edit_post['slide_image'] ;?>" class="slide_img_preview" alt="">
                                                <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Post Image</label>
                                            <input type="file" name="post_image"  class="form-control">
                                            <?php
                                            if(isset($edit_post['id'])) {
                                                ?>
                                                <img style="width: 200px;" class="mt-2" src="<?php echo $edit_post['post_image'] ;?>" alt="">
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Slide Description</label>
                                            <textarea name="slide_description" id="classic-editor" class="form-control" cols="30" rows="10">
                                                <?php if(isset($edit_post['slide_description'])) { echo $edit_post['slide_description'] ;} ?>
                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Post Description</label>
                                            <textarea name="post_description" id="classic-editor-2" class="form-control" name="area" cols="30" rows="10">
                                                <?php if(isset($edit_post['post_description'])) { echo $edit_post['post_description'] ;} ?>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div>
                                        <div>
                                            <button type="submit" class="btn btn-primary waves-effect float-end waves-light me-1">
                                                <?php if(isset($edit_post['id'])) { echo 'Update' ;}else{ echo 'Submit'; }  ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

<!--          post list start-->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4 class="mb-0">Post List</h4></div>
                        <div class="card-body">
                            <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="dataTables_length" id="datatable_length">
                                            <label>Show <select name="datatable_length" aria-controls="datatable" class="custom-select custom-select-sm form-control form-control-sm form-select form-select-sm">
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option></select> entries</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <form action="" method="POST">
                                            <div id="datatable_filter" class="dataTables_filter"><label>Search:<input type="search" name="search"  class="form-control form-control-sm search" placeholder="" aria-controls="datatable"></label></div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline" style="border-collapse: collapse; border-spacing: 0px; width: 100%;" role="grid" aria-describedby="datatable_info">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 141.333px;" aria-sort="ascending" aria-label="Name: activate to sort column descending">ID</th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 215.333px;" aria-label="Position: activate to sort column ascending">Slide Image</th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 215.333px;" aria-label="Position: activate to sort column ascending">Post Image</th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 215.333px;" aria-label="Position: activate to sort column ascending">Title</th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 215.333px;" aria-label="Position: activate to sort column ascending">Category</th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 215.333px;" aria-label="Position: activate to sort column ascending">Tags</th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 215.333px;" aria-label="Position: activate to sort column ascending">Type</th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 215.333px;" aria-label="Position: activate to sort column ascending">Status</th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 215.333px;" aria-label="Position: activate to sort column ascending">Created At</th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 215.333px;" aria-label="Position: activate to sort column ascending">Action</th>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $i = 1;
                                            if(isset($all_post)){
                                                while ($row = mysqli_fetch_assoc($all_post)){
                                                    ?>
                                                    <tr role="row">
                                                        <td class="sorting_1 dtr-control"><?php echo $i ;?></td>
                                                        <td><img style="width: 100px" src="<?php echo $row['slide_image'] ;?>" alt=""></td>
                                                        <td><img style="width: 100px" src="<?php echo $row['post_image'] ;?>" alt=""></td>
                                                        <td><?php echo isset($row['title']) ? $row['title']:'' ;?></td>
                                                        <td><?php echo isset($row['name'])  ? $row['name']:'' ;?></td>
                                                        <td>
                                                            <?php
                                                            $tags =unserialize($row['tags']);
                                                            if($tags !== false){
                                                                $tagsData = implode(', ',$tags);
                                                                echo ucwords($tagsData);
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo isset($row['type'])  ? $row['type']:'' ;?></td>
                                                        <td><?php echo isset($row['status']) && $row['status'] =='Y' ? 'Active':'Inactive' ;?></td>
                                                        <td><?php echo isset($row['created_at']) ? date('Y-m-d', strtotime($row['created_at'])) : ''; ?></td>
                                                        <td>
                                                            <a href="add-post.php?id=<?php echo base64_encode($row['id']) ;?>" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                                            <a href="?delete_post_id=<?php echo base64_encode($row['id']); ?>" onclick="return confirm('Are you sure to delete - <?php echo $row['title']; ?>?')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                                            <a href="javascript:void(0);" class="btn btn-info btn-sm"  data-bs-toggle="modal" data-bs-target="#myModal<?php echo  $row['id'];?>"><i class="fa fa-eye" aria-hidden="true"></i></a>

                                                        </td>
                                                    </tr>

                                                    <?php
                                                    $i++;
                                                }
                                            }
                                            ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="dataTables_info" id="datatable_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div>
                                    </div>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="dataTables_paginate paging_simple_numbers" id="datatable_paginate">
                                            <ul class="pagination">
                                                <li class="paginate_button page-item previous disabled" id="datatable_previous">
                                                    <a href="#" aria-controls="datatable" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                                                </li>
                                                <li class="paginate_button page-item active">
                                                    <a href="#" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                                                </li>
                                                <li class="paginate_button page-item ">
                                                    <a href="#" aria-controls="datatable" data-dt-idx="2" tabindex="0" class="page-link">2</a>
                                                </li>
                                                <li class="paginate_button page-item ">
                                                    <a href="#" aria-controls="datatable" data-dt-idx="3" tabindex="0" class="page-link">3</a>
                                                </li>
                                                <li class="paginate_button page-item ">
                                                    <a href="#" aria-controls="datatable" data-dt-idx="4" tabindex="0" class="page-link">4</a>
                                                </li>
                                                <li class="paginate_button page-item ">
                                                    <a href="#" aria-controls="datatable" data-dt-idx="5" tabindex="0" class="page-link">5</a>
                                                </li>
                                                <li class="paginate_button page-item ">
                                                    <a href="#" aria-controls="datatable" data-dt-idx="6" tabindex="0" class="page-link">6</a>
                                                </li>
                                                <li class="paginate_button page-item next" id="datatable_next">
                                                    <a href="#" aria-controls="datatable" data-dt-idx="7" tabindex="0" class="page-link">Next</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!--          post list end-->
        </div>
    </div>
<!--    modal show start-->
    <?php

    $view_post = $post->viewPost();
    if(isset($view_post)){
        while ($modalRow = mysqli_fetch_assoc($view_post)){
            ?>
            <div id="myModal<?php echo $modalRow['id']?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel">User Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <tr>
                                    <td class="h6">Title</td>
                                    <td class="h6"><?php echo isset($modalRow['title']) ? $modalRow['title'] :'' ;?></td>
                                </tr>
                                <tr>
                                    <td class="h6">Category</td>
                                    <td class="h6"><?php echo isset($modalRow['name']) ? $modalRow['name'] :'' ;?></td>
                                </tr>
                                <tr>
                                    <td class="h6">Created At</td>
                                    <td class="h6"><?php echo isset($modalRow['created_at']) ? date('Y-m-d',strtotime($modalRow['created_at'])) :'' ;?></td>
                                </tr>
                                <tr>
                                    <td class="h6">Status</td>
                                    <td class="h6"><?php echo isset($modalRow['status']) && $modalRow['status'] == 'Y' ? 'Active' :'Inactive' ;?></td>
                                </tr>
                                <tr>
                                    <td class="h6">Tags</td>
                                   <td class="h6">
                                       <?php
                                       $tags =unserialize($modalRow['tags']);
                                       if($tags !== false){
                                           $tagsData = implode(', ',$tags);
                                           echo $tagsData;
                                       }
                                       ?>
                                   </td>
                                </tr>
                                <tr>
                                    <td class="h6">Slide Description</td>
                                    <td class="h6"><?php echo isset($modalRow['slide_description']) ? $modalRow['slide_description']:'' ;?></td>
                                </tr>
                                <tr>
                                    <td class="h6">Post Description</td>
                                    <td class="h6"><?php echo isset($modalRow['post_description']) ? $modalRow['post_description']:'' ;?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    ?>
<!--    modal show end-->

    <!-- End Page-content -->
<?php

include_once "inc/footer.php";

?>