<?php include "includes/admin_header.php" ?>
    
<?php
// Only admin can view all users not with the subscriber role 
    if (!is_admin($_SESSION['username'])) {
        header("Location: index.php");
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
                    case 'add_user':
                        include 'includes/add_user.php';
                        break;

                    case 'edit_user':
                        include 'includes/edit_user.php';
                        break;

                    case '200':
                        echo "Nice 200";
                        break;
                    
                    default:
                        include 'includes/view_all_users.php';
                        break;
                }
            ?>
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
