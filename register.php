<?php
// register.php

// Add your database connection and other necessary functions here
$conn = mysqli_connect('localhost', 'root', '', 'userdb1');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $name = $_POST['name'];
   $email = $_POST['email'];
   $pass = $_POST['password'];
   $cpass = $_POST['cpassword'];
   $age = $_POST['age'];
   $dob = $_POST['dob'];
   $pno = $_POST['pno'];

   // Check if user already exists
   $select = "SELECT * FROM user_form WHERE email = '$email'";
   $result = mysqli_query($conn, $select);
   if (mysqli_num_rows($result) > 0) {
      echo json_encode(array('success' => false, 'error' => 'User already exists!'));
      exit;
   }

   // Check if password and confirm password match
   if ($pass !== $cpass) {
      echo json_encode(array('success' => false, 'error' => 'Passwords do not match!'));
      exit;
   }
   $message=array('name'=>$_POST['name'],
   'email'=>$_POST['email'],
    'password'=>$_POST['password'],
    'age'=>$_POST['age'],
    'dob'=>$_POST['dob'],
     'pno'=>$_POST['pno']
   );
   if(filesize("message.json")==0){
      $first_record=array($message);
      $data_to_save=$first_record;
   }
   else{
      $old_records=json_decode(file_get_contents("message.json"));
      array_push($old_records,$message);
      $data_to_save=$old_records;
   }
   file_put_contents("message.json",json_encode($data_to_save,JSON_PRETTY_PRINT),LOCK_EX);
   // Insert user data into the database
   $insert = "INSERT INTO user_form (name, email, password, age, dob, pno) 
              VALUES ('$name', '$email', '$pass', '$age', '$dob', '$pno')";
   if (mysqli_query($conn, $insert)) {
      echo json_encode(array('success' => true));
      exit;
   } else {
      echo json_encode(array('success' => false, 'error' => 'An error occurred during registration.'));
      exit;
   }
}
?>
