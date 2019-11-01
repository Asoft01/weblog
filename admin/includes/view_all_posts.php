<?php
//include('delete_modal.php');
    if (isset($_POST['checkBoxArray'])) {
        foreach ($_POST['checkBoxArray'] as $postValueId) {
           //echo $checkBoxValue;
            $bulk_options= $_POST['bulk_options'];
            switch ($bulk_options) {
                case 'published':
                    $query= "UPDATE posts SET post_status='{$bulk_options}' WHERE post_id={$postValueId}";
                    $update_to_published_status= mysqli_query($connection, $query);
                    confirm($update_to_published_status);
                    break;

                case 'draft':
                    $query= "UPDATE posts SET post_status='{$bulk_options}' WHERE post_id={$postValueId}";
                    $update_to_draft_status= mysqli_query($connection, $query);
                    confirm($update_to_draft_status);
                    break;

                case 'delete':
                    //$query= "DELETE * FROM posts WHERE post_id = {$postValueId}";
                    $query= "DELETE FROM posts WHERE post_id={$postValueId}";
                    $update_to_delete_status= mysqli_query($connection, $query);
                    confirm($update_to_delete_status);
                    break;


                case 'clone':
                    //$query= "DELETE * FROM posts WHERE post_id = {$postValueId}";
                   $query= "SELECT * from posts WHERE post_id='{$postValueId}'";
                   $select_post_query = mysqli_query($connection, $query);

                   while ($row= mysqli_fetch_array($select_post_query)) {
                       $post_title= $row['post_title'];
                       $post_category_id= $row['post_category_id'];
                       $post_date= $row['post_date'];
                       $post_author= $row['post_author'];
                       $post_user= $row['post_user'];
                       $post_status= $row['post_status'];
                       $post_image= $row['post_image'];
                       $post_tags= $row['post_tags'];
                       $post_content= $row['post_content'];
                       if (empty($post_tags)) {
                           $post_tags="No tags";
                       }

                   }

                   $query= "INSERT INTO posts(post_category_id, post_title, post_author, post_user, post_date, post_image, post_content, post_tags, post_status) ";
                   $query.= "VALUES('{$post_category_id}', '{$post_title}', '{$post_author}', '{$post_user}', now(), '{$post_image}', '{$post_content}', '{$post_tags}', '{$post_status}')";
                   $copy_query= mysqli_query($connection, $query);
                   if (!$copy_query) {
                       die("Query Failed".mysqli_error($connection));
                   }

                    break;

                case 'reset':
                    //$query= "DELETE * FROM posts WHERE post_id = {$postValueId}";
                    $query= "UPDATE posts SET post_views_count=0 WHERE post_id=".mysqli_real_escape_string($connection, $postValueId)."";
                    $reset_query= mysqli_query($connection, $query);
                    header("location: posts.php");

                    break;


                default:
                    # code...
                    break;
            }

        }
    }

?>

<form action="" method="POST">
<table class="table table-bordered table-hover">

        <div id="bulkOptionContainer" class="col-xs-4">
            <select class="form-control" name="bulk_options" id="">
                <option value="">Select Options</option>
                <option value="published">Publish</option>
                <option value="draft">Draft</option>
                <option value="delete">Delete</option>
                <option value="clone">Clone</option>
                <option value="reset">Reset</option>
            </select>
        </div>

        <div class="col-xs-4">
            <input type="submit" name="submit" class="btn btn-success" value="Apply">
            <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>
        </div>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAllBoxes"></th>
                        <th>ID</th>
                        <th>Users</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Image</th>
                        <th>Tags</th>
                        <th>Comments</th>
                        <th>Date</th>
                        <th>View Post</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        <th>Views</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $user= currentUser();
                        // $query= "SELECT * FROM posts ORDER BY post_id DESC";
                        // Using Left Join
                        $query= "SELECT posts.post_id, posts.post_author, posts.post_user, posts.post_title, posts.post_category_id, posts.post_status, posts.post_image, ";

                        $query .="posts.post_tags, posts.post_comment_count, posts.post_date, posts.post_views_count, categories.cat_id, categories.cat_title ";

                        $query .=" FROM posts";
                        $query .=" LEFT JOIN categories ON posts.post_category_id=categories.cat_id ORDER BY posts.post_id DESC";

                       // Displaying information according to the user session
                        // $query .=" LEFT JOIN categories ON posts.post_category_id=categories.cat_id WHERE post_user='$user' ORDER BY posts.post_id DESC";



                        $select_post= mysqli_query($connection, $query);
                        while ($row= mysqli_fetch_assoc($select_post)) {
                            $post_id=           $row['post_id'];
                            $post_author=       $row['post_author'];
                            $post_user=         $row['post_user'];
                            $post_title=        $row['post_title'];
                            $post_category_id=  $row['post_category_id'];
                            $post_status=       $row['post_status'];
                            $post_image=        $row['post_image'];
                            $post_tags=         $row['post_tags'];
                            $post_comment_count=$row['post_comment_count'];
                            $post_date=         $row['post_date'];
                            $post_views_count= $row['post_views_count'];
                            // So far Left Join is used, we can now access the categories table
                            $cat_title=    $row['cat_title'];
                            $cat_id=       $row['cat_id'];

                            echo "<tr>";
                            ?>

                                <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value="<?php echo $post_id; ?>"></td>


                            <?php
                                echo "<td>$post_id</td>";

                                if (!empty($post_author)) {
                                    echo "<td>$post_author</td>";
                                }elseif (!empty($post_user)) {
                                    echo "<td>$post_user</td>";
                                }

                                 //echo "<td>$post_user</td>";


                                echo "<td>$post_title</td>";
                                //echo "<td>dddd$post_category_id</td>";
                                
                                // This code pull out category data without left join on line 116

                                // $query= "SELECT * FROM categories where cat_id='$post_category_id'";
                                // $select_categories_id= mysqli_query($connection, $query);
                                // while ($row= mysqli_fetch_assoc($select_categories_id)) {
                                //     $cat_id= $row['cat_id'];
                                //     $cat_title= $row['cat_title'];
                                //     echo "<td>{$cat_title}</td>";
                                // }   
                                echo "<td>$cat_title</td>";

                                //echo "<td>$post_status</td>";
                                echo "<td>$post_status</td>";
                                echo "<td><img src='../images/$post_image' height='50px' height='50px'></td>";
                                echo "<td>$post_tags</td>";

                                $query= "SELECT * FROM comments WHERE comment_post_id=$post_id";
                                $send_comment_query= mysqli_query($connection, $query);
                                $row= mysqli_fetch_array($send_comment_query);
                                $comment_id= $row['comment_id'];

                                $count_comments= mysqli_num_rows($send_comment_query);

                                echo "<td><a href='post_comments.php?id=$post_id'>$count_comments</a></td>";



                                echo "<td>$post_date</td>";
                                echo "<td><a class='btn btn-primary' href='../post.php?p_id={$post_id}'>View Post</td>";
                                echo "<td><a class='btn btn-info' href='posts.php?source=edit_post&p_id={$post_id}'>Edit</td>";

                                ?>

                                <form method="POST">
                                    <input type="hidden"name="post_id" value="<?php echo $post_id; ?>">
                                    <?php
                                        echo "<td><input class='btn btn-danger' type='submit' name='delete' value='Delete'></td>";
                                    ?>
                                </form>
                                <?php 

                                // Here is the normal way of deleting via a get request on line 208 which works in relation with line 219; however to make it more secure; the above delete code uses POST request according to ed_lec277


                                //echo "<td><a onclick=\"javascript: return confirm('Are you sure you want to delete?'); \" href='posts.php?delete={$post_id}'>Delete</td>";
                                // echo "<td><a rel='$post_id' href='javascript:void(0)' class='delete_link'>Delete</a></td>";


                                echo "<td><a href='posts.php?reset={$post_id}'>{$post_views_count}</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
 </form>           
           <?php
           // First method of deleting using a get Request
                // if (isset($_GET['delete'])) {
                //     $the_delete_id= $_GET['delete'];
                //     $query= "DELETE FROM posts WHERE post_id='{$the_delete_id}'";
                //     $delete_query= mysqli_query($connection, $query);
                //     header("location: posts.php");

                // }

                // Second method of deleting using a Post Request working with line 197
                 if (isset($_POST['delete'])) {
                    $the_delete_id= $_POST['post_id'];
                    $query= "DELETE FROM posts WHERE post_id='{$the_delete_id}'";
                    $delete_query= mysqli_query($connection, $query);
                    header("location: posts.php");

                }

                if (isset($_GET['reset'])) {
                    $the_post_id= $_GET['reset'];
                    $query= "UPDATE posts SET post_views_count=0 WHERE post_id=".mysqli_real_escape_string($connection, $_GET['reset'])."";
                    $reset_query= mysqli_query($connection, $query);
                    header("location: posts.php");

                }

            ?>
<script>
$(document).ready(function(){
    $('.delete_link').on('click', function(){
       // alert('It Works!');
       var id= $(this).attr('rel');

       var delete_url= "posts.php?delete="+id+" ";
       $(".modal_delete_link").attr("href", delete_url);

       $("#myModal").modal('show');
    });
});
</script>