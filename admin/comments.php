<?php include "includes/admin_header.php" ?>
    
<?php 


    
    
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
            <?php
                if (isset($_GET['source'])) {
                    $source= $_GET['source'];
                }else{
                    $source= '';
                }

                /*if ($source=='24') {
                    echo "This is 24";
                    # code...
                }else if($source=='30'){
                    echo "This is 30";
                }else{
                    include 'includes/view_all_posts.php';
                }*/
             
                
                switch ($source) {
                    case 'add_post':
                        include 'includes/add_post.php';
                        break;

                    case 'edit_post':
                        include 'includes/edit_post.php';
                        break;

                    case '200':
                        echo "Nice 200";
                        break;
                    
                    default:
                        include 'includes/view_all_comments.php';
                        break;
                }
            ?>
            </div>
        </div>
        <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

</div>

<?php include "includes/admin_footer.php" ?>
