<?php
require_once 'config/database.php';

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    header("Location: /login");
    die();
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

function login ($postData) {
  $postData = $_POST;
  global $conn;
  if ( ! empty( $_POST ) ) {
    if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) {
      // Getting submitted user data from database
      $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
      $stmt->bind_param('s', $_POST['username']);
      $stmt->execute();
      $result = $stmt->get_result();
      $user = $result->fetch_object();
        
      // Verify user password and set $_SESSION
      if (password_verify($_POST['password'], $user->password)) {
        echo 'du er inne brir'; 
        $_SESSION['user_id'] = $user->uid;
        header("Location: /dashboard");
        die();
      }
    }
  }
}

function changeCredentials () {
  $newPassword = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
  $stmt = $conn->prepare("UPDATE users SET password = ? WHERE uid = ?");
  $stmt->bind_param('ss', $newPassword, $_SESSION['user_id']);
  $stmt->execute();
  //$result = $stmt->get_result();


  
}

//   var_dump($postData);
//   if ($postData['username'] === 'admin' && $postData['password'] === 'admin') {
//     header("Location: /dashboard");
//     die();
//   } else { return; }
// }


?>