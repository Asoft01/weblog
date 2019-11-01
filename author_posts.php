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
            
             if (isset($_GET['p_id'])) {
                  $the_post_id= $_GET['p_id'];
                  $the_post_author= $_GET['author'];
                  $query= "SELECT * FROM posts WHERE post_user='{$the_post_author}' ";
                    $select_post= mysqli_query($connection, $query);
                    while ($row = mysqli_fetch_assoc($select_post)) {
                      $post_title= $row['post_title'];
                      $post_author= $row['post_user'];
                      $post_date= $row['post_date'];
                      $post_image= $row['post_image'];
                      $post_content= $row['post_content'];
             }

                  ?>
                   <h1 class="page-header">
                Page Header
                <small>Secondary Text</small> 
            </h1>

            <!-- First Post -->
            <h2>
                <a href="#"><?php echo $post_title;?></a>
            </h2>
            <p class="lead">
                All Post by<a href="index.php"><?php echo $post_author;?></a>
            </p>
            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date;?></p>

            <img class="img-responsive" src="images/<?php echo $post_image;?>" alt="">
            <hr>
            <p><?php echo $post_content;?></p>

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
  

