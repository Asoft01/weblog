<?php include'includes/db.php'; ?>
<?php include'includes/header.php'; ?>
    
<!--Navigation -->

<?php include'includes/navigation.php'; ?>

 
    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            
            <div class="col-md-8">

             <?php
             // Coming from SideBar Line 83
             if (isset($_GET['category'])) {
                 $post_category_id= $_GET['category'];

                 // Using this Feature allows the admin to view all category posts at index without published
                 // if (isset($_SESSION['user_role']) && $_SESSION['user_role']=='admin') {

                 //This method work perfectly without a prepared statement
                //  if (is_admin($_SESSION['username'])) {
                   
                //     $query= "SELECT * FROM posts WHERE post_category_id='$post_category_id'";
                //   }else{
                //     $query= "SELECT * FROM posts WHERE post_category_id='$post_category_id' AND post_status='published' ";
                //   }  

                // $select_post= mysqli_query($connection, $query);

                //   if (mysqli_num_rows($select_post)<1) {
                //   echo "<h1 class='text-center'>No Posts Available</h1>";
                // }else{

                // while ($row = mysqli_fetch_assoc($select_post)) {
                //   $post_id= $row['post_id'];
                //   $post_title= $row['post_title'];
                //   $post_author= $row['post_author'];
                //   $post_date= $row['post_date'];
                //   $post_image= $row['post_image'];
                //   $post_content= substr($row['post_content'], 0, 200);     


                if (is_admin($_SESSION['username'])) {  
                  $stmt1= mysqli_prepare($connection, "SELECT post_id, post_title, post_author, post_date, post_image, post_content FROM posts WHERE post_category_id=?" );
                }else{

                  $stmt2= mysqli_prepare($connection, "SELECT post_id, post_title, post_author, post_date, post_image, post_content FROM posts WHERE post_category_id=? AND post_status= ?");

                  $published= 'published';
                }  

                if (isset($stmt1)) {
                  mysqli_stmt_bind_param($stmt1, "i", $post_category_id);
                  mysqli_stmt_execute($stmt1);
                  mysqli_stmt_bind_result($stmt1, $post_id, $post_title, $post_author, $post_date, $post_image, $post_content);
                  $stmt= $stmt1;

                }else{
                  mysqli_stmt_bind_param($stmt2, "is", $post_category_id, $published);
                  mysqli_stmt_execute($stmt2);
                  mysqli_stmt_bind_result($stmt2, $post_id, $post_title, $post_author, $post_date, $post_image, $post_content);
                  $stmt= $stmt2;
                }

                if (mysqli_stmt_num_rows($stmt)===0) {
                  echo "<h1 class='text-center'>No Posts Available</h1>";
                }

                while (mysqli_stmt_fetch($stmt)) :
                  
                  ?>
                   <h1 class="page-header">
                Page Header
                <small>Secondary Text</small> 
            </h1>

            <!-- First Post -->
            <h2>
                <a href="post.php?p_id=<?echo $post_id;?>"><?php echo $post_title;?></a>
            </h2>
            <p class="lead">
                by <a href="index.php"><?php echo $post_author;?></a>
            </p>
            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date;?></p>

            <a href="post.php?p_id=<?echo $post_id;?>"><img class="img-responsive" src="images/<?php echo $post_image;?>" alt="">
            </a>
            <hr>
            <p><?php echo $post_content;?></p>
            <a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

            <hr/>

            <!-- End of first Post-->
                <!--<h2>
                    <a href="post/<?php //echo $post_id; ?>"></a>
                </h2>
                <p class="lead">
                    by <a href="author_posts.php"> </a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> </p>
                <hr>
                
                
                <a href="post.php">
                <img class="img-responsive" src="images" alt="">
                </a>
                
                
                
                <hr>
                <p> </p>
                <a class="btn btn-primary" href="post.php">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                -->
                <hr>
             
            <?php   
                endwhile;  mysqli_stmt_close($stmt);  
                } else{
                  header("Locations: index.php");
                }
              ?>
              


                
                
                
                
                

              
    

            </div>
            
            
            <!-- Blog Sidebar Widgets Column -->
            <?php include 'includes/sidebar.php';?>
                  
                <!-- Side Widget Well -->

            </div>
            

        </div>
        <!-- /.row -->

        <hr>


        <ul class="pager">

        </ul>
<?php include'includes/footer.php'; ?>
  

 