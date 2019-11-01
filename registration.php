<?php  include "includes/db.php"; ?>
 <?php  include "includes/header.php"; ?>
<?php
require 'vendor/autoload.php';
//$pusher= new Pusher\Pusher('key', 'secret', 'app-key', 'options');
$options = array(
    'cluster' => 'us2',
    //'encrypted' => true
    'useTLS' => true
  );

$pusher= new Pusher\Pusher('23f06d812d1b60eb383d', '94e24e2f48df4c3ebfb8', '809504', $options);

//if (isset($_POST['submit'])) {
//     $username= $_POST['username'];
//     $email= $_POST['email'];
//     $password= $_POST['password'];

//     if (username_exists($username)) {
//         $message= "User Exists";
//     }

//     if (!empty($username) && !empty($email) && !empty($password)) {
        
//         $username= mysqli_real_escape_string($connection, $username);
//         $password= mysqli_real_escape_string($connection, $password);
//         $email= mysqli_real_escape_string($connection, $email);


//         // $query= "SELECT randSalt from users";
//         // $select_randSalt_query= mysqli_query($connection, $query);
//         // if (!$select_randSalt_query) {
//         //     die("Query Failed". mysqli_error($connection));
//         // }

//                 // $hashFormat= "$2y$4$";
//                 // $salt= "iusesomecrazystrings22";
//                 // $hashF_and_salt= $hashFormat . $salt;
//                 // $2y$4$iusesomecrazystrings22
//                 // $pwhash = crypt($password, $hashF_and_salt);
        
//         //This is another way of encrypting password
//         // while($row= mysqli_fetch_array($select_randSalt_query)){
//         //     $salt = $row['randSalt'];   
//         // }

//         // $enc_password= crypt($password, $salt);

//         $password= password_hash($password, PASSWORD_BCRYPT, array('cost' =>10));

//         $query= "INSERT INTO users (username, user_email, user_password, user_role) ";
//         $query .= "VALUES('{$username}', '{$email}', '{$password}', 'subscriber' )";
//         $register_user_query= mysqli_query($connection, $query);

//         if (!$register_user_query) {
//             die("Query Failed".mysqli_error($connection));
//         }
//         $message= "Your Registration has been submitted";
//         //echo $salt;
//     }else{
//         $message= "Fields cannot be empty";
//     }
    
// }


if ($_SERVER['REQUEST_METHOD']=="POST") { 
    $username=  trim($_POST['username']);
    $email=     trim($_POST['email']);
    $password=  trim($_POST['password']);


    $error =[
        'username'=>'',
        'email'=> '',
        'password'=> ''
    ];

    
    
    if (strlen($username)<4) {
        $error['username']="Username needs to be longer";
    }

    if ($username=='') {
        $error['username']="Username cannot be empty";
    }

    if (username_exists($username)) {
        $error['username']= "Username already exists, Pick another One";
    }

    if ($email=='') {
        $error['email']="Email cannot be empty!";
    }

    if (email_exists($email)) {
        $error['email']="Email Already exist <a href='index.php'>Please Login</a>";
    }

    if ($password=='') {
        $error['password']= 'Password cannot be empty';
    }

    
    foreach ($error as $key => $value) {
        if (empty($value)) {
            unset($error[$key]);
        }
    }

    if (empty($error)) {
        register_user($username, $email, $password);

        $data['message']= $username;

        $pusher->trigger('notifications', 'new_user', $data);

        login_user($username, $password);
    }
}
?>

    <!-- Navigation -->
    
    <?php  include "includes/navigation.php"; ?>
    
 
    <!-- Page Content -->
    <div class="container">
    
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
                <div class="form-wrap">
                <h1>Register</h1>
                    <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">
                    <!--<h6><?php// echo $message; ?></h6>-->
                        <div class="form-group">
                            <label for="username" class="sr-only">username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Enter Desired Username"  autocomplete="on" value="<?php echo isset($username) ? $username: '';?>">
                        </div>

                        <p><?php echo isset($error['username']) ? $error['username']: ''; ?></p>

                         <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="somebody@example.com" autocomplete="on" value="<?php echo isset($email) ? $email: ''; ?>">
                        </div>

                        <p><?php echo isset($error['email']) ? $error['email']: ''; ?></p>
                         <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="key" class="form-control" placeholder="Password">

                            <p><?php echo isset($error['password']) ? $error['password']: ''; ?></p>
                        </div>
                
                        <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Register">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>
  <hr>



<?php include "includes/footer.php";?>
  