<?php

include 'config.php';

if(isset($_POST['submit'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);


   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $message[] = 'user already exists'; 
   } else {
      // Email validation
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $message[] = 'invalid email address';
      } elseif($pass != $cpass){
         $message[] = 'confirm password not matched!';
      } elseif($image_size > 2000000){
         $message[] = 'image size is too large!';
      } 
      // Length validation
   elseif(strlen($name) < 2 || strlen($name) > 50) {
      $message[] = 'Name should be between 2 and 50 characters long';
   }

   // Character validation
   elseif(!preg_match('/^[a-zA-Z\s-]+$/', $name)) {
      $message[] = 'Name should only contain alphabetical characters, hyphens, and spaces';
   }

   // Number validation (optional)
   elseif(preg_match('/[0-9]/', $name)) {
      $message[] = 'Name should not contain numbers';
   }  
      
      
      else {
         $insert = mysqli_query($conn, "INSERT INTO `user_form`(name, email, password, image) VALUES('$name', '$email', '$pass', '$image')") or die('query failed');

         if($insert){
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'registered successfully!';
            header('location:login.php');
         } else {
            $message[] = 'registration failed!';
         }
      }
   }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <?php require("header.php");?>
</head>
<body>

<div class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Register Now</h3>
      <?php
      if(isset($message)){
         foreach($message as $message){
            echo '<div class="message">'.$message.'</div>';
         }
      }
      ?>
      <input type="text" name="name" placeholder="Enter Username" class="box" required>
      <input type="email" name="email" placeholder="Enter Email" class="box" required>
      <input type="password" name="password" placeholder="Enter password" class="box" required>
      <input type="password" name="cpassword" placeholder="Confirm password" class="box" required>
      <p>
      <label>Upload your Profile Picture </label>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png" >
      </p>
      <input type="submit" name="submit" value="register now" class="btn">
      <p>Already have an Account? <a href="login.php">Login Now</a></p>
   </form>

</div>
<?php require("footer.php");?>


</body>
</html>