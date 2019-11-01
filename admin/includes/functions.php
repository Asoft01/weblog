<?php
//======DATABASE HELPER FUNCTIONS========//

function imagePlaceholder($image=''){
    if (!$image) {
        return 'image1.jpg';
    }else{
        return $image;
    }
}

function currentUser(){
    if (isset($_SESSION['username'])) {
        return $_SESSION['username'];
    }

    return false;
}

function redirect($location){
     header("Location:".$location);
     exit;
}

function query($query){
    global $connection;
    $result= mysqli_query($connection, $query);
    confirm($result);
    return $result;
}

function fetchRecords($result){
    return mysqli_fetch_array($result);
}


function count_records($result){
    return mysqli_num_rows($result);
}
//=====END DATABASE HERE======//

//=======GENERAL HELPERS======//

function get_user_name(){
    // for dashboard
    return isset($_SESSION['username']) ? $_SESSION['username'] : null;

    // if (isset($_SESSION['username'])) {
    //     echo $_SESSION['username'];
    // }
}

//=======END GENERAL HELPERS=========//


//=======USERS SPECIFIC HELPERS==========//
function get_all_users_post(){
    return query("SELECT * FROM posts WHERE user_id=".loggedInUserId()."");
}

function get_all_posts_user_comments(){
    return query("SELECT * FROM posts 
        INNER JOIN comments ON posts.post_id=comments.comment_post_id
        WHERE user_id=".loggedInUserId()."");
}

function get_all_user_categories(){
    return query("SELECT * FROM categories WHERE user_id=".loggedInUserId()."");
}

function get_all_user_published_posts(){
    return query("SELECT * FROM posts WHERE user_id=".loggedInUserId()." AND post_status='published'");
}

function get_all_user_draft_posts(){
    return query("SELECT * FROM posts WHERE user_id=".loggedInUserId()." AND post_status='draft'");
}

function get_all_user_approved_posts_comments(){
    return query("SELECT * FROM posts 
        INNER JOIN comments ON posts.post_id=comments.comment_post_id
        WHERE user_id=".loggedInUserId()." AND comment_status='approved'");
}

function get_all_user_unapproved_posts_comments(){
    return query("SELECT * FROM posts 
        INNER JOIN comments ON posts.post_id=comments.comment_post_id
        WHERE user_id=".loggedInUserId()." AND comment_status='unapproved'");
}
//=======END USERS SPECIFIC HELPERS====//



//=====AUTHENTICATION HELPER FUNCTION=====//
function is_admin(){
    if (isLoggedIn()) {
        $result= query("SELECT user_role FROM users WHERE user_id=".$_SESSION['user_id']."");
       // $result= mysqli_query($connection, $query);
        //$row=mysqli_fetch_array($result);
        $row= fetchRecords($result);
        if ($row['user_role']=='admin') {
            return true;
        }else{
            return false;
        }   
    }
    return false; 
}

//=====END AUTHENTICATION HELPER FUNCTION=====//



function ifItIsMethod($method=null){
    if ($_SERVER['REQUEST_METHOD']==strtoupper($method)) {
        return true;
    }
        return false;
}


function isLoggedIn(){
    if (isset($_SESSION['user_role'])) {
        return true;
    }
    return false;
}


function loggedInUserId(){
    if (isLoggedIn()) {
        $result= query("SELECT * FROM users WHERE username='" .$_SESSION['username']. "'");
        confirm($result);
        $user= mysqli_fetch_array($result);
        // if (mysqli_num_rows($result)>=1) {
        //     return $user['user_id'];
        // }
        return mysqli_num_rows($result)>=1 ? $user['user_id'] : false;
    }
    return false;
}

function userLikedThisPost($post_id){
    $result= query("SELECT * FROM likes WHERE user_id=" .loggedInUserId(). " AND post_id={$post_id}");
    confirm($result);
    return mysqli_num_rows($result) >=1 ? true: false;
}


function checkIfUserIsLoggedInAndRedirect($redirectLocation=null){
    if (isLoggedIn()) {
        redirect($redirectLocation); 
    }
}

function getPostLikes($post_id){
    $result= query("SELECT * FROM likes WHERE post_id=$post_id");
    confirm($result);
    echo mysqli_num_rows($result);
}
function escape($string){
    global $connection;
    return mysqli_real_escape_string($connection, trim($string));
}


function users_online(){
    global $connection;
    $session= session_id();
    $time= time();
    $time_out_in_seconds= 60;
    $time_out= $time- $time_out_in_seconds;

    $query= "SELECT * FROM users_online WHERE session='$session'";
    $send_query= mysqli_query($connection, $query);
    $count= mysqli_num_rows($send_query);

    if ($count== NULL) {
        mysqli_query($connection, "INSERT INTO users_online(session, time) VALUES('$session', '$time')");
    }else{
        mysqli_query($connection, "UPDATE users_online SET time='$time' WHERE session='$session'");
    }
    $users_online_query= mysqli_query($connection, "SELECT * FROM users_online WHERE time > '$time_out'");
    return $count_user=mysqli_num_rows($users_online_query);

}

function confirm($result){
    global $connection;
    if (!$result) {
        die("QUERY Failed".mysqli_error($connection));
    }
}

function insert_categories(){
    // Perfectly working without the prepared statement
    // global $connection;
    //  if (isset($_POST['submit'])) {
    //     $cat_title= $_POST['cat_title'];
    //     if ($cat_title=="" || empty($cat_title)) {
    //         echo "This string should not be empty";
    //     }else{
    //         $query= "INSERT INTO categories(cat_title)";
    //         $query .= "values('{$cat_title}')";

    //         $create_cat= mysqli_query($connection, $query);
    //         if (!$create_cat) {
    //             die('Query Failed'.mysqli_error($connection));
    //         }
    //     }
    // }

    global $connection;
     if (isset($_POST['submit'])) {
        $cat_title= $_POST['cat_title'];
        $user_id=   $_SESSION['user_id'];
        if ($cat_title=="" || empty($cat_title)) {
            echo "This string should not be empty";
        }else{
            $stmt= mysqli_prepare($connection, "INSERT INTO categories(user_id, cat_title) VALUES (?, ?) ");
            mysqli_stmt_bind_param($stmt, "is", $user_id, $cat_title);
            mysqli_stmt_execute($stmt);

            //$query .= "values('{$cat_title}')";

            if (!$stmt) {
                die('Query Failed'.mysqli_error($connection));
            }
        }
        mysqli_stmt_close($stmt);
    }
}


function findAllCategories(){
            global $connection;
             $query= "SELECT * FROM categories";
             $select_cat= mysqli_query($connection, $query);
             while ($row= mysqli_fetch_assoc($select_cat)){
                $cat_id= $row['cat_id'];
                $cat_title= $row['cat_title'];
                echo "<tr>";
                echo "<td>{$cat_id}</td>";
                echo "<td>{$cat_title}</td>";
                echo "<td><a href='categories.php?delete={$cat_id}'>Delete</td>";
                echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
                echo "</tr>";
            }

}

function deleteCategories(){
    global $connection;
     if (isset($_GET['delete'])) {
                $the_cat_id= $_GET['delete'];
                $query= "DELETE FROM categories WHERE cat_id={$the_cat_id}";
                $delete_query= mysqli_query($connection, $query);
                header("Location: categories.php");

            }
}

/************** CODE FOR REFACTORING THE TABLE IN THE ADMIN INDEX user_count, comment_count, post_count and category_count**********************/
function recordCount($table){
    global $connection;
    $query = "SELECT * FROM ".$table;
    $select_all_post = mysqli_query($connection,$query);
    $result= mysqli_num_rows($select_all_post);
    confirm($result);
    return $result;
}


function checkStatus($table, $column, $status){
    global $connection;
    $query = "SELECT * FROM $table  WHERE $column = '$status' ";
    $result = mysqli_query($connection,$query);
    confirm($result);
    return mysqli_num_rows($result);
}

function checkUserRole($table, $column, $role){
    global $connection;
    $query = "SELECT * FROM $table WHERE $column = '$role'";
    $result = mysqli_query($connection,$query);
    confirm($result);
    return mysqli_num_rows($result);
}



// function is_admin($username){
//     global $connection;
//     $query= "SELECT user_role FROM users WHERE username='$username'";
//     $result= mysqli_query($connection, $query);
//     confirm($result);
//     $row=mysqli_fetch_array($result);
//     if ($row['user_role']=='admin') {
//         return true;
//     }else{
//         return false;
//     }
// }


function username_exists($username){
    global $connection;
    $query= "SELECT username from users WHERE username='$username'";
    $result= mysqli_query($connection, $query);
    confirm($result);
    if (mysqli_num_rows($result)>0) {
        return true;
    }else{
        return false;
    }
}


function email_exists($email){
    global $connection;
    $query= "SELECT user_email from users WHERE user_email='$email'";
    $result= mysqli_query($connection, $query);
    confirm($result);
    if (mysqli_num_rows($result)>0) {
        return true;
    }else{
        return false;
    }
}

function register_user($username, $email, $password){
        global $connection;
        $username= $_POST['username'];
        $email= $_POST['email'];
        $password= $_POST['password'];
            
            $username= mysqli_real_escape_string($connection, $username);
            $password= mysqli_real_escape_string($connection, $password);
            $email= mysqli_real_escape_string($connection, $email);

            $password= password_hash($password, PASSWORD_BCRYPT, array('cost' =>10));

            $query= "INSERT INTO users (username, user_email, user_password, user_role) ";
            $query .= "VALUES('{$username}', '{$email}', '{$password}', 'subscriber' )";
            $register_user_query= mysqli_query($connection, $query);

            confirm($register_user_query);
}

function login_user($username, $password){
     global $connection;
     $username= trim($username);
     $password= trim($password);

     $username= mysqli_real_escape_string($connection, $username);
     $password= mysqli_real_escape_string($connection, $password);

     $query= "SELECT * FROM users WHERE username='{$username}'";
     $select_user_query= mysqli_query($connection, $query);
     if (!$select_user_query) {
        die("QUERY FAILED".mysqli_error($connection));
     }
     
     while ($row= mysqli_fetch_array($select_user_query)) {
        $db_user_id=         $row['user_id'];
        $db_username=        $row['username'];
        $db_user_password=   $row['user_password'];
        $db_user_firstname=  $row['user_firstname'];
        $db_user_lastname=   $row['user_lastname'];
        $db_user_role=       $row['user_role'];

        if (password_verify($password, $db_user_password)) {
        $_SESSION['user_id']=       $db_user_id;
        $_SESSION['username']=      $db_username;
        $_SESSION['firstname']=     $db_user_firstname;
        $_SESSION['lastname']=      $db_user_lastname;
        $_SESSION['user_role']=     $db_user_role;

     // header("Location: ../admin");   
       redirect("/blog/admin");
     }else{
        return false;
        //redirect("/blog/index.php");
     }

    }
     return true;
}









// function login_user($username, $password){
// This login is working perfectly with the sidebar login blog index.php
//      global $connection;
//      $username= trim($username);
//      $password= trim($password);

//      $username= mysqli_real_escape_string($connection, $username);
//      $password= mysqli_real_escape_string($connection, $password);

//      $query= "SELECT * FROM users WHERE username='{$username}'";
//      $select_user_query= mysqli_query($connection, $query);
//      if (!$select_user_query) {
//         die("QUERY FAILED".mysqli_error($connection));
//      }
     
//      while ($row= mysqli_fetch_array($select_user_query)) {
//         $db_user_id=         $row['user_id'];
//         $db_username=        $row['username'];
//         $db_user_password=   $row['user_password'];
//         $db_user_firstname=  $row['user_firstname'];
//         $db_user_lastname=   $row['user_lastname'];
//         $db_user_role=       $row['user_role'];

//         if (password_verify($password, $db_user_password)) {

//         $_SESSION['username']=      $db_username;
//         $_SESSION['firstname']=     $db_user_firstname;
//         $_SESSION['lastname']=      $db_user_lastname;
//         $_SESSION['user_role']=     $db_user_role;

//        // header("Location: ../admin");
//             redirect("/blog/admin");
//          }else{
//             //header("Locaton: ../index.php");
//            redirect("/blog/index.php");
//             //return false;
//          }

//      }
//      return true;
// }
?>