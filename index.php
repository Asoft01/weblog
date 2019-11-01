
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
                $per_page=2;
                if (isset($_GET['page'])) {
                    $page= $_GET['page'];

                }else{
                    $page= "";
                }

                if ($page == "" || $page == 1) {
                    $page_1 = 0;
                }else{
                    $page_1 = ($page* $per_page)-$per_page;
                }

                
                if (isset($_SESSION['user_role']) && $_SESSION['user_role']=='admin') {
                    $post_query_count= "SELECT * FROM posts";
                  }else{
                    
                    $post_query_count= "SELECT * FROM posts WHERE post_status='published'";
                  }  
            
                $find_count= mysqli_query($connection, $post_query_count);
                $count= mysqli_num_rows($find_count);
                
                if ($count<1) {
                    echo "<h1 class='text-center'>No Posts Available</h1>";
                }else{
                //echo $count;
                $count= ceil($count / $per_page);
                //echo $count;

                $query= "SELECT * FROM posts LIMIT $page_1, $per_page";
                //$query= "SELECT * FROM posts";
                $select_post= mysqli_query($connection, $query);
                while ($row = mysqli_fetch_assoc($select_post)) {
                  $post_id= $row['post_id'];
                  $post_title= $row['post_title'];
                  $post_author= $row['post_user'];
                  $post_date= $row['post_date'];
                  $post_image= $row['post_image'];
                  $post_content= substr($row['post_content'], 0, 200);
                  $post_status= $row['post_status'];

                  //if ($post_status =="published") {
                  ?>
                   <h1 class="page-header">
                Page Header
                <small>Secondary Text</small> 
            </h1>

            <!-- First Post -->
            <h2>
            <!-- Before using the rewrite rule on the .htaccess line 3-->
                <a href="post.php?p_id=<?echo $post_id;?>"><?php echo $post_title;?></a>
            <!-- <a href="post/<?php //echo $post_id;?>"><?php //echo $post_title;?></a> -->
            </h2>
            <p class="lead">
                by <a href="author_posts.php?author=<?php echo $post_author?>&p_id=<?php echo $post_id;?>"><?php echo $post_author;?></a>
            </p>
            <p><?php if (isset($_SESSION['user_role'])) {
              echo $_SESSION['lastname'];
            } ?></p>
            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date;?></p>
            <!-- Works correctly without the function image on line 85-->
            <!-- <a href="post.php?p_id=<?//echo $post_id;?>"><img class="img-responsive" src="images/<?php //echo $post_image;?>" alt="">
            </a> -->

            <a href="post.php?p_id=<?echo $post_id;?>"><img class="img-responsive" src="images/<?php echo imagePlaceholder($post_image);?>" alt="">
            </a>
            <hr>
            <p><?php echo $post_content;?></p>
            <a class="btn btn-primary" href="post.php?p_id=<?echo $post_id;?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

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
            <?php
                for($i=1; $i<$count; $i++){
                    if ($i== $page) {
                        echo "<li><a class='active_link' href='index.php?page={$i}'>{$i}</a></li>";
                    }else{
                        echo "<li><a href='index.php?page={$i}'>{$i}</a></li>";
                    }
                    
                }
            ?>
        </ul>
<?php include'includes/footer.php'; ?>
  

 