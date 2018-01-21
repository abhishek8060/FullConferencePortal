<?php

if(isset($_GET) && $_GET['verifyuser']){
  include_once('core.functions.php'); //print_r($_GET);die();

  $code = $_GET['verifyuser'];
  //sanitize user code
  $connx = new dbase();
  $conn = $connx->connect();
  $code = htmlspecialchars(mysqli_real_escape_string($conn,$code));

  //check this code into the database
  $file = new mainController();
  $check_code = $file->verifyuser($code); //var_dump($check_code);die();
  if(isset($check_code) && count($check_code)!=0 && !$check_code === false){
  	  //then update user account status to updated
  	  $userid = $check_code[0];
  	  $updt_acc = $file->update_user_status($userid);

  	  //display the successful message
	  $output = '';
	  $output = "<link rel=\"stylesheet\" href=\"css/bootstrap.min.css\" type=\"text/css\">
";
	  $output .= "<div class=\"container\">";
	  $output .= "<div class=\"well\">";
	  $output .= "Successfully verified.You can login and submit abstract.Click here to <a href=\"?action=bio_login\">login</a>.You will be redirected in 3 seconds.";
	  $output .= "</div>";
	  $output .= "</div>";

	  echo $output;
	  header("refresh:5;url=index.html");
  }else{
     $_SESSION['lgn_msg'] = "Wrong link, please try again";
     header('location:index.html');
  }

}else{
	header('location:index.html');
	exit();
}



 ?>