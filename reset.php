<?php

if(isset($_GET) && $_GET['verifyuser']){
  include_once('core.functions.php'); //print_r($_GET);die();

  $code = $_GET['verifyuser'];
  //sanitize user code
  $connx = new dbase();
  $conn = $connx->connect();
  $code = htmlspecialchars(mysqli_real_escape_string($conn,$code));
  //echo $code;
  //check this code into the database
    
    if(isset($_POST['pass']) && isset($_POST['cpass']))
    {
    $file = new mainController();
    
    if($_POST['pass']==$_POST['cpass'])
    {
      
      if($file->has_min_length($_POST['pass'],8))
       {

        if($file->check_pass($_POST['pass']) == true)
         {
              $pass=$file->hash_password($_POST['pass']); 
              
              $sql='UPDATE user SET user_pass="'.$pass.'" WHERE user_acc_temp_token="'.$code.'"';
                 
              //echo $sql; die();
              $query = mysqli_query($file->myconx,$sql);
              if($query){

            	  //display the successful message
          	  $output = '';
          	  $output = "<link rel=\"stylesheet\" href=\"css/bootstrap.min.css\" type=\"text/css\">
          ";
          	  $output .= "<div class=\"container\">";
          	  $output .= "<div class=\"well\">";
          	  $output .= "Successfully updated.You can login and proceed.Click here to <a href=\"?action=bio_login\">login</a>.You will be redirected in 3 seconds.";
          	  $output .= "</div>";
          	  $output .= "</div>";

          	  echo $output;

          	  header("refresh:5;url=index.html");
              exit();
              }
              else
                 $_SESSION['fmessage']='Error occured.Try again!';
             }
         else
             $_SESSION['fmessage']='Password should contain at least one lower case,upper case,number and special symbol';
         }
        else
           $_SESSION['fmessage']='Password should be of minimum 8 characters';
       }
       else
          $_SESSION['fmessage']='Passwords don\'t match';          
     }
  
  } 
  else{
     $_SESSION['fmessage'] = "Wrong link, please try again";
     header('location:index.html');
     exit();
  }

 ?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
 <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Page Title -->
  <title>Biosangam 2018 - Forgot Password</title>
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


<!-- Header
================================================== -->
<header id="header" class="">
  <div class="container">
    <!-- logo -->
    <div class="header-logo" id="header-logo">
      <img src="img/logo.png" alt=""/>
    </div>
    <!-- /logo -->
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
          <li>
        </li>
        </ul> 
      </nav>
  </div>  
  
</header>
<!-- /Header
================================================== -->


<div class="page-wrapper">
  
  <div id="body-content">

    <div class="section-team inverted-section2 section-padding" id="section-speakers">
      <div class="container">
        <!-- Section title -->
        <div class="section-title-wrapper">
          <h2 class="title-section">Forgot Password</h2>
          <!-- <h3>Expert speakers in the field of biotechnology will be confirmed soon for the conference.</h3> -->
        </div><br><br><center>
       <form method="post" action="reset.php?verifyuser=<?php echo $_GET['verifyuser']; ?>" autocomplete="off">
           <h4 align="center"><?php if(isset($_SESSION['fmessage'])) echo $_SESSION['fmessage']; 
             $_SESSION['fmessage']='';?></h4><br>
            
           <input type="password" name="pass" style="width:300px;" class="form-control" placeholder="Enter password" required/><br>
           <input type="password" name="cpass" style="width:300px;" class="form-control" placeholder="Re-Enter password" required/><br>
           
           <input type ="submit" value="Update" class="btn btn-primary">
           </form>
       </center>
       <br><br><br>
           </div>
           
 
      </div>
  </div>
</div>  
  

    
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
<!-- /Footer
================================================== -->

<!-- >> JS
============================================================================== -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="http://dotrex.co/theme-preview/eventer/vendor/bootstrap/js/bootstrap.min.js"></script>
<!-- Crossbrowser-->
<script src="http://dotrex.co/theme-preview/eventer/vendor/cross-browser.js"></script>
<!-- /Crossbrowser-->
<!-- CountDown -->
<script src="http://dotrex.co/theme-preview/eventer/vendor/jquery.countdown.min.js"></script>
<!-- /CountDown -->
<!-- Waypoints-->
<script src="http://dotrex.co/theme-preview/eventer/vendor/waypoints.min.js"></script>
<!-- /Waypoints-->
<!-- Validate -->
<script src="vendor/validate.js"></script>
<!-- / Validate -->
<!-- Fancybox -->
<script src="vendor/fancybox/jquery.fancybox.js"></script>
<!-- /fancybox -->
<!-- Owl Caroulsel -->
<script src="vendor/owl.carousel/owl-carousel/owl.carousel.js"></script>
<!-- /Owl Caroulsel -->


<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- >> /JS
============================================================================= -->
</body>
</html>




