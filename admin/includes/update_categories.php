    <form action="" method="post">
      <div class="form-group">
         <label for="cat-title">Edit Category</label>
         <?php

        if (isset($_GET['edit'])) {
                $edit_id= $_GET['edit'];
                
                $query= "SELECT * FROM categories WHERE cat_id=$edit_id";
                 $select_cat= mysqli_query($connection, $query);
                 while ($row= mysqli_fetch_assoc($select_cat)){
                    $cat_id= $row['cat_id'];
                    $cat_title= $row['cat_title'];
                    ?>
                <input type="text" value="<?php if (isset($cat_title)) {echo $cat_title;} ?>" class="form-control" name="cat_title">
                <?php
                } 
            }   
         ?> 

        
         <?php
            /////////////////// Updating Categories ////////////
         // Without the prepare statement
            // if (isset($_POST['update_categories'])) {
            //     $the_cat_title= $_POST['cat_title'];
            //     $query= "UPDATE categories SET cat_title='{$the_cat_title}' WHERE cat_id={$cat_id}";
            //     $update_query= mysqli_query($connection, $query);

            // }

         if (isset($_POST['update_categories'])) {
                $the_cat_title= $_POST['cat_title'];
                $stmt= mysqli_prepare($connection, "UPDATE categories SET cat_title=? WHERE cat_id=?");
                mysqli_stmt_bind_param($stmt, 'si', $the_cat_title, $cat_id);
                mysqli_stmt_execute($stmt); 
                
                if (!$stmt) {
                   die("Query Failed".mysqli_error($connection));
                }
                //$update_query= mysqli_query($connection, $query);
                mysqli_stmt_close($stmt);
            }
         ?>         
      </div>

       <div class="form-group">
          <input class="btn btn-primary" type="submit" name="update_categories" value="Update Category">
      </div>

    </form>
