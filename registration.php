<?php

if (session_status() == PHP_SESSION_NONE){
 session_start();
}
include('core.functions.php');
 $file = new mainController();

 $_SESSION['mssg']='';
 $_SESSION['coremsg']='';
 $_SESSION['errmsg']='';

//for creating new user
if(isset($_POST['user_create'])){

  if($file->usercreate($_POST)){
      $names = $_POST['user_fname'];
      $email = $_POST['user_email'];
    $output = '';
    $output = "<link rel=\"stylesheet\" href=\"css/bootstrap.min.css\" type=\"text/css\">
";
    $output .= "<div class=\"container\">";
    $output .= "<div class=\"well\">";
    $output .= "Thank you for signing up .";
    if(isset($_SESSION['mailmsg']) && $_SESSION['mailmsg']=="Y" ){
    $output .= "You need to verify your email, to complete sign up process.
     Verification email has been sent on your registered email:<strong>'".$email."'.</strong>Click here to go to <a href=\"?action=bio_login\"><u>Login</u></a>.";
      }
    else{
    $output .= "Verification mail couldn't be sent due to some problem. Contact admin.";  
    }  
    $output .= "</div>";
    $output .= "</div>";
    $_SESSION['mssg']=$output;
    //echo $output;
   //header("refresh:5;url=index.html");
   // exit();
   }
 }

//for logging user
 if(isset($_POST['user_login'])){


  //check if user logged or not
  if(isset($_SESSION['uid']) && isset($_SESSION['uname']) && isset($_SESSION['uemail'])){
     header('location:dashboard.php');
     exit();
  }

   $user_info = $file->userlogin($_POST);
  if(!$user_info){
    $_SESSION['mssg']='Wrong username or password!';
  }else{
    if(isset($_SESSION['log_msg']) && isset($user_info) && $_SESSION['log_msg']!=''){
       //echo $_SESSION['log_msg'];
       //$_SESSION['log_msg'] = '';
      //echo 'jkjbjkbkjjkj';
    }
    else{
      //set the session variable
      $_SESSION['user_info'] = $user_info;
      $_SESSION['uid'] = $user_info[0];
      $_SESSION['uname'] = $user_info[1];
      $_SESSION['uemail'] = $user_info[2];
      header('location:dashboard.php');
      exit();
    }
  }
}
 

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
 <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Page Title -->
  <title>Biosangam 2018 - International Conference on Food, Health and Environmental Biotechnology: Innovation and Translational Dimensions | MNNIT Allahabad</title>
<!-- /Page title -->

<!-- Seo Tags -->
<meta property="og:title" content="Biosangam 2018 - International Conference on Food, Health and Environmental Biotechnology: Innovation and Translational Dimensions | MNNIT Allahabad" />
<meta property="og:type" content="website" data-page-subject="true"  />

<meta charset="utf-8" />
    <meta name="description" http-equiv="description" content="Biosangam 2018 - International Conference on Food, Health and Environmental Biotechnology is to be held in 'Kumbh Nagari' Allahabad. The conference is being organized by Department of Biotechnology, Motilal Nehru National Institute of Technology (MNNIT) Allahabad. The conference will be an interactive and scientifically vibrant programme. The conference consists of various sessions including keynote, plenary and parallel sessions. " />
<meta name="keywords" http-equiv="keywords" content="Biosangam, biotechnology, international conference, Young Scientist Award, Food Biotechnology, MNNIT Allahabad, NIT allahabad, Department of biotechnology MNNIT, research, allahabad" />
<!-- /Seo Tags -->

<!-- Favicon and Touch Icons
========================================================= -->
  <link rel="shortcut icon" href="img/favicon2.ico" type="image/x-icon">
  <link rel="icon" href="img/favicon2.ico" type="image/x-icon">
<!-- /Favicon
========================================================= -->

<!-- >> CSS
============================================================================== -->

  <!-- Bootstrap styles -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- /Bootstrap Styles -->
  <!-- Google Web Fonts --> 
  <link href='https://fonts.googleapis.com/css?family=Hind:400,700' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
  <!-- /google web fonts -->
  <!-- owl carousel -->
  <link href="vendor/owl.carousel/owl-carousel/owl.carousel.css" rel="stylesheet">
  <link href="vendor/owl.carousel/owl-carousel/owl.theme.css" rel="stylesheet">
  <!-- /owl carousel -->
  <!-- fancybox.css -->
  <link href="vendor/fancybox/jquery.fancybox.css" rel="stylesheet">
  <!-- /fancybox.css -->
  <!-- Font Awesome -->
  <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
  <!-- /Font Awesome -->
  <!-- Main Styles -->
  <link href="css/styles.css" rel="stylesheet">
  <style type="text/css">
    td, th{
      padding: 10px 20px;
    }
    .cmte{
      font-size: 15px;
    }
    .cmte tr, .cmte th, .cmte td{
      border: 1px solid black;
    }
  </style>
  <!-- /Main Styles -->
<!-- >> /CSS
============================================================================== -->
<script src="./js/jquery.js"></script>
<!-- Main JS -->
<script src="./js/main.js"></script>
<!-- /Main JS -->
<script type="text/javascript">
  function disp(argument){
  if(argument == 'conf'){
    document.getElementById("about-text-title").innerHTML = "About the Conference";
    document.getElementById("about-text-content").innerHTML = "<p style='line-height:1.5' align='justify'>It gives us immense pleasure to invite you to join BioSangam 2018, <b>“International Conference on Innovations and Translational Dimensions: Food, Health, and Environmental Biotechnology”</b>, to be held at one of the most ancient and culturally rich cities of India “Kumbh Nagari”, Allahabad from <b>March, 9-11 2018</b>. The conference is being organized by Department of Biotechnology, Motilal Nehru National Institute of Technology (MNNIT) Allahabad with an aim to promote excellence in scientific knowledge and innovation in biotechnology and related disciplines to motivate young researchers. <br><br>The conference also envisages providing a forum to researchers around the globe to explore and discuss on various aspects on recent advances in field of food, health, and environment that has dynamically opened up new avenues of research. It will provide deep insights into innovations, challenges and growth opportunities in diversified domains of Biotechnology. The conference consists of various sessions including keynote, plenary and parallel sessions. Each session will be addressed by outstanding experts who will highlight recent advances in various facets of biotechnology. It will also offer budding scientist an opportunity to present their work in front of eminent experts of their field and compete for various awards like BioSangam Young Scientist Awards-2018.</p>";
  }
  if(argument == 'dept'){
  document.getElementById("about-text-title").innerHTML = "About the Department";
    document.getElementById("about-text-content").innerHTML = "<p style='line-height:1.5' align='justify'>Biotechnology at MNNIT Allahabad was established as a new academic unit under Applied Mechanics in 2006, with the objective of integrating life sciences with engineering and to develop cutting-edge technology through research, training and technological innovation. An independent Department of Biotechnology was established in the April, 2012. <br><br>Since its inception, the department has witnessed a consistent rise in the students demand for the subject. Keeping a beat to the global demands for researchers in this field, B.Tech, M.Tech and PhD programmes are being run by the department. The department has a team of young enthusiastic and well qualified faculty actively involved in research and training.</p>";
  }
  if(argument == 'mnnit'){
    document.getElementById("about-text-title").innerHTML = "About MNNIT Allahabad";
    document.getElementById("about-text-content").innerHTML = "<p style='line-height:1.5' align='justify'>Motilal Nehru National Institute of Technology (MNNIT) Allahabad, MNNIT is an institute with total commitment to quality and excellence in academic pursuits. It was established as one of the seventeen Regional Engineering Colleges of India in the year 1961 as a joint enterprise of Government of India and Government of Uttar Pradesh, and was an associated college of University of Allahabad, which is the third oldest university in India. <br><br>With more than 50 years of experience and achievements in the field of technical education, having traversed a long way, on June 26, 2002 MNREC was transformed into National Institute of Technology fully funded by Government of India. With the enactment of national Institutes of Technology Act-2007, the Institute has been granted the status of institution of national importance w.e.f. 15.08.2007.</p>";
  }

}
</script>
<style type="text/css">
  #google_translate_element {
  /*position: absolute;*/
  /*top: 10px;*/
  z-index: 99999;
  /*right: 15px;*/
  font-family: 'Montserrat', sans-serif;
  font-style: none;

}
</style>
</head>

<body>

<!-- Page Loader
========================================================= -->
<div class="loader-container" id="page-loader"> 
  <div class="loading-wrapper loading-wrapper-hide">
    <div class="loader-animation" id="loader-animation">
      <svg class="svg-loader" width=100 height=100>
      <circle cx=50 cy=50 r=25 />
    </svg>
    </div>    
    <!-- Edit With Your Name -->
    <div class="loader-name" id="loader-name">
      <img width="240" src="img/loader-logo.png" alt="">
    </div>
    <!-- /Edit With Your Name -->
    <!-- Edit With Your Job -->
    <p class="loader-job" id="loader-job">Mar 9-11 2018 @ MNNIT Allahabad</p>
    <!-- /Edit With Your Job -->
  </div>   
</div>
<!-- /End of Page loader
========================================================= -->

<!-- Header
================================================== -->
<header id="header" class="">
  <div class="container">
    <!-- logo -->
    <div class="header-logo" id="header-logo">
      <img src="img/logo.png" alt=""/>
    </div>
    <!-- /logo -->
    <!-- MAIN MENU -->
      <nav class="">
        <ul class="hd-list-menu">
          <li class="active"><a href="index.html#main-carousel">Home</a></li>
          <li><a href="index.html#section-event-infos">About </a></li>
          <li><a href="index.html#section-schedule">Dates</a></li>
          <li><a href="index.html#section-speakers">Committees</a></li>                 
          <li><a href="index.html#section-testimonials">Thrust Areas</a></li>
          <li><a href="index.html#section-sponsors">Sponsors</a></li> 
          <li><a href="index.html#section-faq">FAQ</a></li>
          <li><a target="_blank" href="http://allahabad.nic.in/tourist_place.html">Tourism</a></li>
          <li><a href="index.html#section-prices">Fees</a></li>
          <li><a href="index.html#section-register">Register</a></li>
          <li>
        </li>
        </ul> 
      </nav>
      <!-- /MAIN MENU -->
  </div>  
  
</header>
<!-- /Header
================================================== -->


<div class="page-wrapper">
  
  <div id="body-content">

  
    <div class="section-register inverted-section2 section-padding" id="section-register">
      <div class="container">
        <!-- Section title -->
        <div class="section-title-wrapper">
         

 
  <?php
      
    

  //if action is login the we display login form
   if(isset($_GET['action']) && $_GET['action'] == 'bio_login'){

   ?>
      
       <h2 class="title-section">Login</h2>
          <center></center>
        </div>

    
    <div class="container" id="register-form">
     <div class="form-title">
        <h3 class="brdr">Please Login</h3>
     </div>
     <?php echo '<h2 align="center">'.$_SESSION['mssg'].'</h2>'; ?>
     <?php
     if(isset($_SESSION['log_msg'])) echo '<h3 align="center"><font color="#eae236">'.$_SESSION['log_msg'].'</font></h3>'; 
        $_SESSION['log_msg'] = '';?>

     <div class="form-reg brdr">
      <form class="form-horizontal" name="Form" method="post" action="#" onsubmit="return validateLoginForm()" id="reg-form">
           <div class="form-group">
             <label class="control-label col-sm-4" for="fname">*Registered Email:</label>
             <div class="col-sm-7"> 
               <input type="text" name="user_email" class="form-control textInput" id="fname" placeholder="Enter email">
             </div>
           </div>
           <div class="form-group">
             <label class="control-label col-sm-4" for="email">*Password:</label>
             <div class="col-sm-7"> 
               <input type="password" name="user_pass" class="form-control textInput" id="email" placeholder="Password">
             </div>

             
           </div>
           <div align="center" style="margin-right:185px;">
          <a href="forgot.php"><font color="yellow">Forgot Password?</font></a>
           </div>
           <br>
         <div class="form-group"> 
              <div class="aab controls col-md-4"></div>
                 <div class="controls col-md-8 ">
                      <input type="submit" name="user_login" value="Login" class="btn btn-primary btn" id="submit-id-signup"/>
                       New User?<a href="?action=bio_register" id="login-btn"><font color="yellow">Sign Up</font></a>
               </div>
         </div> 
          <p style="text-align: center;">Fields with Asterisk(*) are required fields</p>
      </form>
    </div>

    </div><!-- /.container -->

  <?php 
     }elseif(isset($_GET['action']) && $_GET['action'] == 'bio_register'){
     //if actio is bio_register then we display registration form
   ?>
    
    <h2 class="title-section">Registration</h2>
          <center></center>
        </div>

     <?php   if(isset($_SESSION['coremsg']))
       echo '<font size="5" color="#eae236"><center>'.$_SESSION['coremsg'].'</center></font>'; 
    

       echo $_SESSION['mssg']; ?>
    <div class="container" id="register-form">
     <div class="form-title">
        <h3 class="brdr">Please register</h3>
     </div>
     <div class="form-reg brdr">
      <form class="form-horizontal" name="Form" method="post" action="#" onsubmit="return validateForm()" id="reg-form">
           
            <div class="form-group">
             <label class="control-label col-sm-4" for="pass"><font color="#ffcc00">*</font>Salutation:</label>
             <div class="col-sm-7"> 
               <select name="user_salute" style="color:black; height:44px; width:100px;">
               <option value="Mr.">Mr.</option>
               <option value="Mrs.">Mrs.</option>
               <option value="Dr.">Dr.</option>
               <option value="Prof.">Prof.</option>
               <option value="Miss.">Miss.</option>
               
               </select>
              </div>
           </div>

           <div class="form-group">
            <?php if(isset($_SESSION['errmsg']['user_fname']))
                     echo '<font color="#eae236"><center>'.$_SESSION['errmsg']['user_fname'].'</center></font>';?>
             <label class="control-label col-sm-4" for="fname"><font color="#ffcc00">*</font>Full Name:</label>
             <div class="col-sm-7"> 
               <input type="text" name="user_fname" class="form-control textInput" id="fname" placeholder="Enter Full name">
             </div>
           </div>
           <div class="form-group">
            <?php if(isset($_SESSION['errmsg']['user_email']))
                     echo '<font color="#eae236"><center>'.$_SESSION['errmsg']['user_email'].'</center></font>';?>
             <label class="control-label col-sm-4" for="email"><font color="#ffcc00">*</font>Email Address:</label>
             <div class="col-sm-7"> 
               <input type="text" name="user_email" class="form-control textInput" id="email" placeholder="Enter email">
             </div>
           </div>
           <div class="form-group">
            <?php if(isset($_SESSION['errmsg']['user_pass']))
                     echo '<font color="#eae236"><center>'.$_SESSION['errmsg']['user_pass'].'</center></font>';?>
             <label class="control-label col-sm-4" for="pass"><font color="#ffcc00">*</font>Password:</label>
             <div class="col-sm-7"> 
               <input type="password" name="user_pass" class="form-control textInput" id="pass" placeholder="Enter Password">
             </div>
           </div>
           <div class="form-group">
             <label class="control-label col-sm-4" for="cpass"><font color="#ffcc00">*</font>Confirm Password:</label>
             <div class="col-sm-7"> 
               <input type="password" name="user_cpass" class="form-control textInput" id="cpass" placeholder="Confirm your password">
             </div>
           </div>
           
           <div class="form-group">
             <label class="control-label col-sm-4" for="addr"><font color="#ffcc00">*</font>Address:</label>
             <div class="col-sm-7"> 
               <input type="text" name="user_addr" class="form-control textInput" id="addr" placeholder="Enter address">
             </div>
           </div>

           <div class="form-group">
            <?php if(isset($_SESSION['errmsg']['user_mobile']))
                     echo '<font color="#eae236"><center>'.$_SESSION['errmsg']['user_mobile'].'</center></font>';?>
             <label class="control-label col-sm-4" for="mobl"><font color="#ffcc00">*</font>Mobile Number:</label>
             <div class="col-sm-7"> 
               <input type="text" maxlength="20" name="user_mobile" class="form-control textInput" id="mobl" placeholder="Enter Mobile number">
             </div>
           </div>
           <div class="form-group">
             <label class="control-label col-sm-4" for="pwd" style="margin-right: 15px;"><font color="#ffcc00">*</font>Account type:</label>
             <label class="radio-inline" for="id_As_1"> <input type="radio" name="acctype" id="id_As_1" value="AT" >Author </label>
             <label class="radio-inline" for="id_As_2"> <input type="radio" name="acctype" id="id_As_2" value="NAT">Non-Author</label>
           </div>

          <div class="form-group">
              <div class="controls col-md-offset-4 col-md-8 ">
                <div id="div_id_terms" class="checkbox required">
                   <label for="id_terms" class=" requiredField">
                       <input class="input-ms checkboxinput" id="id_terms" name="terms" style="margin-bottom: 10px" type="checkbox" />
                              Agree with the terms and conditions
                   </label>
               </div> 
           </div>
          </div> 
         <div class="form-group"> 
              <div class="aab controls col-md-4"></div>
                 <div class="controls col-md-8 ">
                      <input type="submit" name="user_create" value="Now, Register" class="btn btn-primary btn" disabled="disabled" id="submit-id-signup" onclick="return validate_acctype()"/>
                       Already have an account?<a href="?action=bio_login" id="login-btn"><font color="yellow">Log In</font></a>
               </div>
         </div> 
          <p style="text-align: center;">Fields with Asterisk(*) are required fields</p>
      </form>
    </div>

    </div><!-- /.container -->

   <?php 
     }else{
     //if anything we navigate to index page
      $_SESSION['url_error'] = "Invalid URL";
      include('index.html');
      exit();
     }
   ?>


      </div>  
    </div>
  
  </div>
</div>




<!-- Footer
================================================== -->
<footer id="footer" class="jumb-footer">
  <div class="container">
    <!-- row -->
    <div class="row">
      <!-- col -->
      <div class="col-sm-6">
        "If only the human body could handle trauma as well as biotechnology stocks do." <br><i>- Alex Berenson</i>
      </div>
      <!-- /col -->
      <!-- col -->
      <div class="col-sm-6 text-right">
        <!-- Social Icons -->
        <div class="footer-social-icons">
          <a href="https://www.facebook.com/Biosangam/"><i class="fa fa-facebook"></i></a>
          <!-- <a href="index.html#"><i class="fa fa-facebook"></i></a>
          <a href="index.html#"><i class="fa fa-twitter"></i></a>
          <a href="index.html#"><i class="fa fa-linkedin"></i></a>
          <a href="index.html#"><i class="fa fa-youtube"></i></a> -->
        </div>
        <!-- /Social Icons -->
      </div>
      <!-- /col -->
    </div>
    <!-- /row -->
  </div>  
</footer>

</body>
</html>
<script type="text/javascript">
 //enable submit button only when toc is agreed
  id_terms.onclick = function(){
    var a = document.getElementById('id_terms');
    if(a.checked == true){
      document.getElementById('submit-id-signup').disabled = false;
    }else{
      document.getElementById('submit-id-signup').disabled = true;
    }
 }

//for account type
function validate_acctype(){
  var check_user_type = document.getElementsByName('acctype');
  var ischecked_acctype = false;
  for ( var i = 0; i < check_user_type.length; i++) {
      if(check_user_type[i].checked == true) {
          ischecked_acctype = true;
          break;
      }
  }
  if(!ischecked_acctype){ 
      alert("Please choose your account type");
      return false;
  }
}

//check for empty fields in the registration form
function validateForm(){
    var a=document.forms["Form"]["user_fname"].value;
    var b=document.forms["Form"]["user_email"].value;
    var c=document.forms["Form"]["user_pass"].value;
    var d=document.forms["Form"]["user_cpass"].value;
    var e=document.forms["Form"]["user_mobile"].value;
    var f=document.forms["Form"]["user_addr"].value;
    if (a==null || a=="",b==null || b=="",c==null || c=="",d==null || d=="" , e=="" || e==null,f==null || f==""){
      alert("Please Fill Required Fields, fields with Asterisk(*) are required ones");
      return false;
    }
    if(c.localeCompare(d)!=0){
      alert("Passwords don't match");
      return false;
    }
}

//check for empty fields in the login form
function validateLoginForm(){
    var a=document.forms["Form"]["user_pass"].value;
    var b=document.forms["Form"]["user_email"].value;
    if (a==null || a=="",b==null || b==""){
      alert("Please Fill Required Fields, fields with Asterisk(*) are required ones");
      return false;
    }
}


</script>
</html>
