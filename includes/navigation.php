<!-- Navigation -->
<?php session_start(); ?>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
           
           
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/blog">blog Front</a>
            </div>
            
            
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                        <?php
                            $query= "SELECT * FROM categories";
                            $result= mysqli_query($connection, $query);
                            while ($row= mysqli_fetch_assoc($result)) {
                                $cat_id= $row['cat_id'];
                               $cat_title= $row['cat_title'];

                               $category_class= '';
                               $registration= '';
                               $registration= 'registration.php';

                               $pageName= basename($_SERVER['PHP_SELF']);
                               // Making the menu item active
                               if (isset($_GET['category']) && $_GET['category']==$cat_id) {
                                   $category_class= 'active';
                               }elseif ($pageName==$registration) {
                                   $registration_class='active';
                               }

                               //echo "<li><a href='#'>{$cat_title}</a></li>";
                               
                               //This works before the use of rewrite rule
                               echo "<li class='$category_class'><a href='/blog/category.php?category=$cat_id'>{$cat_title}</a></li>";

                               //echo "<li class='$category_class'><a href='/blog/category/{$cat_id}'>{$cat_title}</a></li>";
                            }
                        ?>
                        
                        <?php if(isLoggedIn()): ?>
                             <li>
                                <a href="/blog/admin">Admin</a>
                            </li>

                            <li>
                                <a href="/blog/includes/logout.php">Logout</a>
                            </li>
                        <?php else:?>

                            <li>
                                <a href="/blog/login.php">Login</a>
                            </li>

                         <?php endif; ?>
                        
                        <!--
                            Works fine before the use of edlec 291 while importing login.php in the root directory
                             <li>
                                <a href="/blog/admin">Admin</a>
                            </li>
                        
                         <li>
                            <a href="/blog/login.php">Login</a>
                        </li> -->

                        <li class="<?php echo $registration_class; ?>">
                            <a href="/blog/registration.php">Registration</a>
                        </li>

                        <li>
                            <a href="/blog/contact.php">Contact</a>
                        </li>
                        <?php
                            if (isset($_SESSION['lastname'])) {
                                 if (isset($_GET['p_id'])) {
                                    $the_post_id = $_GET['p_id'];
                                    // echo "<li>
                                    //         <a href='admin/posts.php?source=edit_post&p_id={$the_post_id}'>Edit Post</a>
                                    //     </li>";
                                    echo "<li> <a href='/blog/admin/posts.php?source=edit_post&p_id={$the_post_id}'>Edit Post</a></li>";
                                }
                            }

                        ?>  
                        <!--
                        <li>
                            <a href="/blog/includes/logout.php">Logout</a>
                        </li>


                  


                        <li>
                            <a href="/blog/login.php">Login</a>
                        </li>
                    

                  



                                 
                     <li>
                        <a href="/blog/registration">Registration</a>
                    </li>
                    -->
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
