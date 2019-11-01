<?php include'includes/db.php'; ?>
<?php include'includes/header.php'; ?>
    
<!--Navigation -->

<?php include'includes/navigation.php'; ?>
<?php
  if (isset($_POST['liked'])) {
    //echo "<h1>It Works</h1>";
    //1= FETCHING THE RIGHT POST

    // The $_POST request is coming from AJAX request in line 273
    $post_id= $_POST['post_id'];
    $user_id= $_POST['user_id'];
    $query= "SELECT * FROM posts WHERE post_id=$post_id";
    $postResult= mysqli_query($connection, $query);
    $post= mysqli_fetch_array($postResult);
    $likes= $post['likes'];
    if (mysqli_num_rows($postResult)>=1) {
      echo $post['post_id'];
    }

    mysqli_query($connection, "UPDATE posts SET likes=$likes+1 WHERE post_id=$post_id");
    // UPDATE POST WITH LIKES
    mysqli_query($connection, "INSERT INTO likes(user_id, post_id) VALUES($user_id, $post_id)");
    exit();
    // CREATE LIKES FOR POST
  }


  if (isset($_POST['unliked'])) {
   // echo "<h1>It is Unliked</h1>";
    //1= FETCHING THE RIGHT POST

   // The $_POST request is coming from AJAX request in line 293
    $post_id= $_POST['post_id'];
    $user_id= $_POST['user_id'];
    $query= "SELECT * FROM posts WHERE post_id=$post_id";
    $postResult= mysqli_query($connection, $query);
    $post= mysqli_fetch_array($postResult);
    $likes= $post['likes'];

    //2. DELETE LIKES

    mysqli_query($connection, "DELETE FROM likes WHERE post_id=$post_id AND user_id=$user_id");

    // 3 UPDATE LIKES WITH DECREMENTING LIKES 
    mysqli_query($connection, "UPDATE posts SET likes=$likes-1 WHERE post_id=$post_id");
    exit();
  //   mysqli_query($connection, "UPDATE posts SET likes=$likes+1 WHERE post_id=$post_id");
  //   // UPDATE POST WITH LIKES
  //   mysqli_query($connection, "INSERT INTO likes(user_id, post_id) VALUES($user_id, $post_id)");
  //   exit();
  //   // CREATE LIKES FOR POST
   
  }
?>
 
    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            
            <div class="col-md-8">

             <?php
             if (isset($_GET['p_id'])) {

                  $the_post_id= $_GET['p_id'];
                  //Creating views for the functionality to posts
                  $view_query= "UPDATE posts SET post_views_count= post_views_count+1 WHERE post_id=$the_post_id";
                  $send_query = mysqli_query($connection, $view_query);
                  if (isset($_SESSION['user_role']) && $_SESSION['user_role']=='admin') {
                    $query= "SELECT * FROM posts WHERE post_id= $the_post_id";
                  }else{
                    $query= "SELECT * FROM posts WHERE post_id= $the_post_id AND post_status='published' ";
                  }

                    $select_post= mysqli_query($connection, $query);

                    if (mysqli_num_rows($select_post)<1) {
                      echo "<h1 class='text-center'>No Posts Available</h1>";
                    }else{
                    
                    while ($row = mysqli_fetch_assoc($select_post)) {
                      $post_title= $row['post_title'];
                      $post_author= $row['post_author'];
                      $post_date= $row['post_date'];
                      $post_image= $row['post_image'];
                      $post_content= $row['post_content'];
                  }

                  ?>
                   <h1 class="page-header">
                  Posts
                <small>Secondary Text</small> 
            </h1>

            <!-- First Post -->
            <h2>
                <a href="#"><?php echo $post_title;?></a>
            </h2>
            <p class="lead">
                by <a href="index.php"><?php echo $post_author;?></a>
            </p>
            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date;?></p>

            <img class="img-responsive" src="images/<?php echo imagePlaceholder($post_image);?>" alt="">
            <hr>
            <p><?php echo $post_content;?></p>

            <hr/>

            <?php
              if (isLoggedIn()) { ?>
                 <div class="row">
                    <p class="pull-right">
                      <a class="<?php echo userLikedThisPost($the_post_id) ? 'unlike': 'like'; ?>" href=""><span class="glyphicon glyphicon-thumbs-up"></span><?php echo userLikedThisPost($the_post_id) ? 'Unlike': 'Like'; ?>
                      </a>

                    </p>
                  </div>
              
            <?php
              }else{?>
                   <div class="row">
                    <p class="pull-right login-to-post">
                      You need to Login to like <a href="/blog/login.php">Login</a> to Like
                    </p>
                  </div>
             <?php
              }
            ?> 
           

            <!-- <div class="row">
              <p class="pull-right"><a class="like" href="#"><span class="glyphicon glyphicon-thumbs-up"></span>Like</a></p>
            </div> -->

           <!-- 
            Before updating the likes button to be dynamic
            <div class="row">
              <p class="pull-right"><a class="unlike" href="#"><span class="glyphicon glyphicon-thumbs-down"></span>Unlike</a></p>
            </div>
 -->
            <div class="row">
              <p class="pull-right likes">Likes: <?php getPostLikes($the_post_id); ?></p>
            </div>

            <div class="clearfix">
              
            </div>

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
               
              ?>
  <!-- Comments Form -->
        <?php
        
         if (isset($_POST['create_comment'])) {

             $the_post_id= $_GET['p_id'];
             $comment_author= $_POST['comment_author'];
             $comment_email= $_POST['comment_email'];
             $comment_content= $_POST['comment_content'];

             if (!empty($comment_author) && !empty($comment_email) && !empty($comment_content)) {   
               $query= "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) ";
               $query.=  "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'unapproved', now())";
               $create_comment_query= mysqli_query($connection, $query);
               
               if (!$create_comment_query) {
                   die('QUERY FAILED'.mysqli_error($connection));
               }

               // $query= "UPDATE posts SET post_comment_count= post_comment_count+1 ";
               // $query .= "WHERE post_id= $the_post_id";

               // $update_query= mysqli_query($connection, $query);
             }else{
              echo "<script>alert('Fields cannot be empty!')</script>";
             }
         }
        ?>

        <div class="well">

            <h4>Leave a Comment:</h4>
            <form action="" method="post" role="form">

                <div class="form-group">
                    <label for="Author">Author</label>
                    <input type="text" name="comment_author" class="form-control" name="comment_author">
                </div>

                <div class="form-group">
                    <label for="Author">Email</label>
                    <input type="email" name="comment_email" class="form-control" name="comment_email">
                </div>

                <div class="form-group">
                    <label for="comment">Your Comment</label>
                    <textarea name="comment_content" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
            </form>
        </div>

        <hr>
        <!-- Posted Comments-->
        <?php
            $query= "SELECT * FROM comments WHERE comment_post_id={$the_post_id} ";
            $query .= "AND comment_status='approved' ";
            $query .= "ORDER BY comment_id DESC";
            $select_comment_query= mysqli_query($connection, $query);
            if (!$select_comment_query) {
                die("Query FAILED".mysqli_error());
            }
            while ($row= mysqli_fetch_array($select_comment_query)) {
                $comment_date= $row['comment_date'];
                $comment_content= $row['comment_content'];
                $comment_author= $row['comment_author'];
            ?>
             <!--Comment-->
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="www.facebook.com" alt="">
                    </a>
                    <div class="media-object">
                        <h4 class="media-heading"><?php echo $comment_author; ?>
                            <small><?php echo $comment_date; ?></small>
                        </h4>
                           <?php echo $comment_content; ?>
                    </div>
                </div>

        <!-- End of Comment-->      
   
        <?php
            } 
          }
        }else{
                header("Location:index.php");
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
  
<script>
  $(document).ready(function(){
    var post_id= <?php echo $the_post_id;?>;
    var user_id= <?php echo loggedInUserId();?>

    //Liking
    $('.like').click(function(){
      //console.log("It works");
      $.ajax({
        url: "/blog/post.php?p_id=<?php echo $the_post_id; ?>",
        type: 'post',
        data: {
          liked: 1,
          post_id: post_id,
          user_id: user_id

        }
      })
    });

    //Unliking

    $('.unlike').click(function(){
      $.ajax({
        url: "/blog/post.php?p_id=<?php echo $the_post_id; ?>",
        type: 'post',
        data:{
          unliked:1,
          post_id: post_id,
          user_id: user_id
        }
      })
    })
  });
</script>
