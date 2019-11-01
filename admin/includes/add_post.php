<?php
if(isset($_POST['create_post'])) {
   
            $post_title        = escape($_POST['title']);
            $post_user         = escape($_POST['post_user']);
            $user_id           = escape($_SESSION['user_id']);
            $post_category_id  = escape($_POST['post_category']);
            $post_status       = escape($_POST['post_status']);
    
            $post_image        = escape($_FILES['post_image']['name']);
            $post_image_temp   = escape($_FILES['post_image']['tmp_name']);
    
    
            $post_tags         = escape($_POST['post_tags']);
            $post_content      = escape($_POST['post_content']);
            $post_date         = escape(date('d-m-y'));
           // $post_comment_count      = 4;
            //This is updated from the post.php line 97

           
            move_uploaded_file($post_image_temp, "../images/$post_image" );

            $query= "INSERT INTO posts(post_category_id, user_id, post_title, post_user, post_date, post_image, post_content, post_tags, post_status) ";
            $query.= "VALUES('{$post_category_id}', {$user_id}, '{$post_title}', '{$post_user}', now(), '{$post_image}', '{$post_content}', '{$post_tags}', '{$post_status}')";

            $create_post =mysqli_query($connection, $query);
            // $query .= "values('{$cat_title}')";
            confirm($create_post);
            $query_id= mysqli_insert_id($connection);
            echo "<p class='bg-success'>Post Created! <a href='../post.php?p_id={$query_id}'>View Post</a> or <a href='posts.php'>View All Post</a></p>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
  <form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label for="title">Post Title</label>
      <input type="text" class="form-control" name="title">
    </div>

   <div class="form-group">
      <label for="category">Category</label>
      <select name="post_category" id="post_category">

        <?php
          $query= "SELECT * FROM categories";
          $select_categories = mysqli_query($connection, $query);
         //confirm($select_categories);
          while ($row= mysqli_fetch_assoc($select_categories)) {
            $cat_id= $row['cat_id'];
            $cat_title= $row['cat_title'];
            echo "<option value='$cat_id'>{$cat_title}</option>";
          }
        ?>

      </select>
    </div>

    <div class="form-group">
      <label for="users">Users</label>
      <select name="post_user" id="post_user">

        <?php
          $users_query= "SELECT * FROM users";
          $select_users = mysqli_query($connection, $users_query);
         //confirm($select_categories);
          while ($row= mysqli_fetch_assoc($select_users)) {
            $user_id= escape($row['user_id']);
            $username= escape($row['username']);
            echo "<option value='$username'>{$username}</option>";
          }
        ?>

      </select>
    </div>



    <div class="form-group">
      <label for="post_status">Post Status</label>
      <select name="post_status" id="">
        <option value="draft">Select Options</option>
        <option value="published">Publish</option>
        <option value="draft">Draft</option>
      </select>
    </div>


   <!--  <div class="form-group">
      <label for="post_author">Post Author</label>
      <input type="text" class="form-control" name="post_author">
    </div> -->

    
    <div class="form-group">
      <label for="post_image">Post Image</label>
      <input type="file" name="post_image">
    </div>

    <div class="form-group">
      <label for="post_tags">Post Tags</label>
      <input type="text" class="form-control" name="post_tags">
    </div>

    <div class="form-group">
      <label for="post_content">Post Content</label>
      <textarea class="form-control" name="post_content" id="" cols="30" rows="10"></textarea> 
    </div>

    <div class="form-group">
      <input class="btn btn-primary" type="submit" name="create_post" value="Publish Post">
    </div>

  </form>
</body>
</html>