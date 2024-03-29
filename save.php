<?php
require("dbconnection.php");

ini_set('session_use_strict_mode', 1);  
session_set_cookie_params([
    'lifetime' => 60*60*24,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,
    'httponly' => true,
]);

session_start();
session_regenerate_id(true);


$errors = [];

$user_email = validate_data($_POST['user_email']);
$user_password = validate_data($_POST['user_password']);

$db = new db();
$result = $db->get_user_data('users', "user_email = '$user_email' AND user_password = '$user_password' ");  
$user_data = $result->fetch_assoc();


 if ( $result->num_rows > 0) {
     $_SESSION['id'] = $user_data['id'];
     $_SESSION['user_email'] = $user_data['user_email'];
     $_SESSION['role'] = $user_data['role'];
     $_SESSION['ext_number'] = $user_data['ext_number'];
     $_SESSION['user_name'] = $user_data['user_name'];
     $_SESSION['user_image'] = $user_data['user_image'];
      if ($_SESSION['role'] == 'user') {
           header("Location: user.php");
          exit;
      } elseif ($_SESSION['role'] == 'admin') {
          header("Location: admin.php");
          exit;
      }
  } else {
      $errors[] = "Invalid email or password";
      $errors = json_encode($errors);
      header("Location: login.php?errors=" . urlencode($errors));
      exit;
 
}
echo "<pre>";
var_dump(session_id());
var_dump($_SESSION);
echo "</pre>";

function validate_data($data)
{
    $data = trim($data);
    $data = addslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>