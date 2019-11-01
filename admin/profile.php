<?php include "includes/admin_header.php" ?>
    
<?php 
if (isset($_SESSION['username'])) {
    $username= $_SESSION['username'];
    $query= "SELECT * FROM users WHERE username='{$username}'";
    $select_user_profile_query= mysqli_query($connection, $query);
    while ($row= mysqli_fetch_array($select_user_profile_query)) {
        $user_id=            $row['user_id'];
        $username=           $row['username'];
        $user_password=      $row['user_password'];
        $user_firstname=     $row['user_firstname'];
        $user_lastname=      $row['user_lastname'];
        $user_email=         $row['user_email'];
        $user_image=         $row['user_image'];
        $user_role=          $row['user_role'];
    }

}

?>

<?php
    if (isset($_POST['edit_user'])) {
            $user_firstname        = $_POST['user_firstname'];
            $user_lastname         = $_POST['user_lastname'];
            //$user_role             = $_POST['user_role'];
            $username              = $_POST['username'];
    
            // $post_image        = $_FILES['post_image']['name'];
            // $post_image_temp   = $_FILES['post_image']['tmp_name'];
    
            $user_email             = $_POST['user_email'];
            $user_password           = $_POST['user_password'];

            // move_uploaded_file($post_image_temp, "../images/$post_image");
            // if (empty($post_image)) {
            //   $query= "SELECT * FROM posts WHERE post_id= $query_id";
            //   $select_img= mysqli_query($connection, $query);
            //   while ($row= mysqli_fetch_assoc($select_img)) {
            //     $post_image= $row['post_image'];
            //   }
            // }

            //$query= "UPDATE posts SET post_title='{$post_title}' WHERE post_id={$query_id}";

             $user_password= password_hash($user_password, PASSWORD_BCRYPT, array('cost' =>10));

            //  $query="UPDATE users SET user_firstname='{$user_firstname}', user_lastname='{$user_lastname}', username='{$username}' WHERE username='$username'";

            // $query= "UPDATE users SET ";
            // $query .= "user_firstname ='{$user_firstname}', ";
            // $query .= "user_lastname ='{$user_lastname}', ";
            // $query .= "username ='{$username}', ";
            // $query .= "user_email ='{$user_email}', ";
            // $query .= "user_password ='{$user_password}' ";
            // $query .= "WHERE username= '{$username}' ";

            $query= "UPDATE users SET ";
            $query .= "user_firstname ='{$user_firstname}', ";
            $query .= "user_lastname ='{$user_lastname}', ";
            $query .= "username ='{$username}', ";
            $query .= "user_email ='{$user_email}', ";
            $query .= "user_password ='{$user_password}' ";
            $query .= "WHERE username= '{$username}' || user_id={$user_id}";



            $edit_user_query= mysqli_query($connection, $query);
            confirm($edit_user_query);


}
?>

    <div id="wrapper">

        <!-- Navigation -->
 
        <?php include "includes/admin_navigation.php" ?>
        
        
    

<div id="page-wrapper">

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">


            <h1 class="page-header">
                Welcome to admin
                <small>Author</small>
            </h1>
            
            <form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
      <label for="post_author">Firstname</label>
      <input type="text" class="form-control" name="user_firstname" value="<?php echo $user_firstname; ?>">
    </div>


    <div class="form-group">
      <label for="post_author">Lastname</label>
      <input type="text" class="form-control" name="user_lastname" value="<?php echo $user_lastname; ?>">
    </div>

    
   
    <!-- <div class="form-group">
      <label for="post_image">Post Image</label>
      <input type="file" name="post_image">
    </div> -->

    <div class="form-group">
      <label for="post_tags">Username</label>
      <input type="text" class="form-control" name="username" value="<?php echo $username; ?>">
    </div>

    <div class="form-group">
      <label for="post_content">Email</label>
      <input type="email" class="form-control" name="user_email" value="<?php echo $user_email; ?>">
    </div>

    <div class="form-group">
      <label for="post_content">Password</label>
      <input autocomplete="off" type="password" class="form-control" name="user_password">
    </div>

    <div class="form-group">
      <input class="btn btn-primary" type="submit" name="edit_user" value="Update Profile">
    </div>

  </form>
            </div>
        </div>
        <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

</div>



<?php 

//deleteCategories();

 ?>

  
        
     
        
        <!-- /#page-wrapper -->
        
    <?php include "includes/admin_footer.php" ?>
