<?php include'includes/db.php'; ?>
<?php include'includes/header.php'; ?>
<?php
session_start();
//echo password_hash('secret', PASSWORD_DEFAULT, array('cost' =>10));
echo loggedInUserId();
if (userLikedThisPost(4)){
	echo "User liked this";
}else{
	echo "User did not like this";
}
?>

