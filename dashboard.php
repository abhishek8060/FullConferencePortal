<?php

if (session_status() == PHP_SESSION_NONE){
  session_start();
}
include('core.functions.php');
$file = new mainController();
//print_r($_SESSION['user_info']);

//check if user logged or not
if(!isset($_SESSION['uid']) && !isset($_SESSION['uname']) && !isset($_SESSION['uemail'])){
   $_SESSION['lgn_msg'] = 'Please login to continue';
   header('location:registration.php?action=bio_login');
   exit();
}else{
	//save abstract
	if(isset($_POST['preview_abstract'])){
		//check if user logged or not
		if(!isset($_SESSION['uid']) && !isset($_SESSION['uname']) && !isset($_SESSION['uemail'])){
		   $_SESSION['lgn_msg'] = 'Please login to continue';
		   header('location:registration.php?action=bio_login');
		   exit();
		}
	    //print_r($_POST);
	    $_POST['lock_status'] = '10';
		$save_data = $file->save_abstract($_POST);
		if($save_data){
			header('location:dashboard.php?action=view_abstract');
			exit();
		}
	}
    
    //update abstract
	if(isset($_POST['edit_abstract'])){
		//check if user logged or not
		if(!isset($_SESSION['uid']) && !isset($_SESSION['uname']) && !isset($_SESSION['uemail'])){
		   $_SESSION['lgn_msg'] = 'Please login to continue';
		   header('location:registration.php?action=bio_login');
		   exit();
		}
	    //print_r($_POST);
	    $_POST['lock_status'] = '10';
		$save_data = $file->update_abstract($_POST,$_SESSION['uid']);
		if($save_data){
			header('location:dashboard.php?action=view_abstract');
			exit();
		}
	}


	//submit abstract
	if(isset($_POST['abstract_submit'])){
		//check if user logged or not
		if(!isset($_SESSION['uid']) && !isset($_SESSION['uname']) && !isset($_SESSION['uemail'])){
		   $_SESSION['lgn_msg'] = 'Please login to continue';
		   header('location:registration.php?action=bio_login');
		   exit();
		}
	    //print_r($_POST);
	    echo "<script>alert('Abstract will be submitted and can't be modified, but will still be available for view')</script>";
	    $_POST['lock_status'] = '20';
		$save_data = $file->save_abstract($_POST);
		if($save_data){
            
            if($file->sendthanks($_SESSION['uemail'],$_SESSION['uname'])){
			header('location:dashboard.php?action=view_abstract');
			exit();
		   }
		   else
		   {
		   	echo 'Mail sending error';
		   	exit();
		   }

		}
	}

	//submit abstract when clicked on submit in view page
	if(isset($_GET['action']) && $_GET['action'] == 'view_abstract_submit'){
		//check if user logged or not
		if(!isset($_SESSION['uid']) && !isset($_SESSION['uname']) && !isset($_SESSION['uemail'])){
		   $_SESSION['lgn_msg'] = 'Please login to continue';
		   header('location:registration.php?action=bio_login');
		   exit();
		}
		$save_data = $file->save_abstract_perman();
		if($save_data){  

           if($file->sendthanks($_SESSION['uemail'],$_SESSION['uname'])){

		   echo "<script>alert('Abstract submitted')</script>";
		   echo "<script>location.href='dashboard.php?action=view_abstract';</script>";
		  }
		  else
		   {
		   	echo 'Mail sending error';
		   	exit();
		   }

		}
	}

    
    //generate pdf
    if(isset($_GET['action']) && $_GET['action'] == 'view_abstract_pdf'){
		//check if user logged or not
		if(!isset($_SESSION['uid']) && !isset($_SESSION['uname']) && !isset($_SESSION['uemail'])){
		   $_SESSION['lgn_msg'] = 'Please login to continue';
		   header('location:registration.php?action=bio_login');
		   exit();
		}
		$gen_pdf = $file->create_pdf();
    }


	//view submitted abstract
	if(isset($_GET['action']) && $_GET['action'] == 'view_abstract'){
		//check if user logged or not
		if(!isset($_SESSION['uid']) && !isset($_SESSION['uname']) && !isset($_SESSION['uemail'])){
		   $_SESSION['lgn_msg'] = 'Please login to continue';
		   header('location:registration.php?action=bio_login');
		   exit();
		}
	  $abs_data = $file->fetch_abstract($_SESSION['uid']);
	  $_SESSION['abs_id'] = $abs_data[0];
	}
   //edit abstract
	if(isset($_GET['action']) && $_GET['action'] == 'edit_abstract'){
		//check if user logged or not
		if(!isset($_SESSION['uid']) && !isset($_SESSION['uname']) && !isset($_SESSION['uemail'])){
		   $_SESSION['lgn_msg'] = 'Please login to continue';
		   header('location:registration.php?action=bio_login');
		   exit();
		}
	  $abs_data = $file->fetch_abstract($_SESSION['uid']);
	  $_SESSION['abs_id'] = $abs_data[0];
	}

	//for logout
	if(isset($_GET['action']) && $_GET['action'] == 'logout'){
		session_destroy();
		header('location:index.html');
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



		<!-- SECTION: Team
		================================================== -->
		<div class="section-team inverted-section2 section-padding" id="section-speakers">
			<div class="container">
				<!-- Section title -->
				<div class="section-title-wrapper">
					<h2 class="title-section">Dashboard</h2>
					<!-- <h3>Expert speakers in the field of biotechnology will be confirmed soon for the conference.</h3> -->
				</div>
			
				   </div>
				   <br><br><br>
 <div class="row">
 	<div class="col-md-2"></div>
 	<div class="col-md-6">
 		<h3>Welcome  <span><?php echo $_SESSION['user_info'][13].' '.$_SESSION['user_info'][1]; ?></span></h3>
        <h4>Your Id: <span><?php echo 'BS'.$_SESSION['user_info'][0]; ?></span></h4>
        <h4>Email  : <span><?php echo $_SESSION['user_info'][2]; ?></span></h4>
 		<h4>Address: <span><?php echo $_SESSION['user_info'][4]; ?></span></h4>
 		<?php 
 		  $abbs=0;
          if($_SESSION['user_info'][5] == 'AT'){
          echo '<h3>You have been registered as <u>Author</u>.</h3>';  
 		 ?>
      </div>
      <div class="col-md-4" style="float: left;">
      	<a href="?action=logout" class="btn btn-primary btn" id="submit-sbmt_abstract" style="width:180px; margin-bottom:13px;">Logout</a>
 		 <?php 
          //abstract can be submitted only once
 		  if(!$file->if_any_abstract()){
 		  ?>

 		  
  		     <p><a href="?action=submit_abstract" style="width:180px;" class="btn btn-primary btn" id="submit-sbmt_abstract"/>Submit Abstract</a></p>
  		  <?php 
            }else{
            	$abbs=1;
  		   ?>
             
          <?php 
            }
           ?>
      
 		  <p><a href="?action=view_abstract" style="width:180px;" class="btn btn-primary btn" id="submit-view_abstract"/>View Abstract</a></p>
 		  <p><a href="?action=payment" style="width:180px;" class="btn btn-primary btn" id="submit-view_abstract"/>Payment</a></p>
          </div>
 		<?php 
         }else{ 
         	echo '<h3>You have been registered as <u>Non-Author</u>.</h3>';
        ?>
          </div>
          <div class="col-md-4" style="float: left;">
          <a href="?action=logout" class="btn btn-primary btn" id="submit-sbmt_abstract" style="width:180px; margin-bottom:13px;">Logout</a>	
 		  <p><a href="?action=payment" style="width:180px;" class="btn btn-primary btn" id="submit-view_abstract"/>Payment</a></p>
          </div>
          
        <?php
         }
         
 		 ?>

 	  
 	  
 </div>
 </div>
</div>
<div class="section-team section-padding" id="abspart">

 <?php 
   $stat = $file->check_abs_status(); 

  if(!isset($_GET['action']) && $_SESSION['user_info'][5] == 'AT' && $stat[10]=='20')
         echo '<h3 align="center">You have submitted an abstract. Click on View Abstract.</h3>';

 if(isset($_GET['action']) && $_GET['action'] == 'submit_abstract'){
  
    
  ?>
   
     


    <div class="container" id="register-form">
     <div class="form-title">
        <h3 class="brdr">Submit Abstract</h3>
     </div>
     <div class="form-reg brdr" style="width: 85%;">
      <form class="form-horizontal" name="Form" method="post" action="#" id="reg-form" onsubmit="return validateform()">
           <div class="form-group">
             <label class="control-label col-sm-4" for="fname">*Interest Areas:</label>
             <div class="col-sm-7"> 
               <select class="form-control" name="interest_areas" id="interest_areas">
                 <option value="sel_areas">Select Thrust Areas Topic</option>
               	 <option value="Biotechnology: Innovations, Translation and IPR">Biotechnology: Innovations, Translation and IPR</option>
               	 <option value="Nano Biotechnology: A revolutionary Approach">Nano Biotechnology: A revolutionary Approach</option>
               	 <option value="Food and Agriculture Biotechnology">Food and Agriculture Biotechnology</option>
               	 <option value="Medical Biotechnology: A Translational Perspective">Medical Biotechnology: A Translational Perspective</option>
               	 <option value="Environment Biotechnology">Environment Biotechnology</option>
               	 <option value="Industrial Biotechnology">Industrial Biotechnology</option>
               	 <option value="Bioinformatics and Computational Biology">Bioinformatics and Computational Biology</option>
                 <option value="For Prevention of disease and Promotion of Health">For Prevention of disease and Promotion of Health</option>
               </select>
             </div>
           </div>
           <div class="form-group">
             <label class="control-label col-sm-4" for="title">*Title:</label>
             <div class="col-sm-7"> 
               <input type="text" name="abstract_title" class="form-control textInput" id="title" placeholder="Enter title" required/>
             </div>
           </div>
           <div class="form-group">
             <label class="control-label col-sm-4" for="pass">*Author Names:<br><span style="font-size: 12px; color:red;">(separate by plus (+) )</span></label>
             <div class="col-sm-7"> 
               <input type="text" name="abstract_main_author" class="form-control textInput" id="author" placeholder="Author 1 + Author 2... " required/>
             </div>
           </div>
           <div class="form-group">
             <label class="control-label col-sm-4" for="abstract_main_author_affil">*Authors affiliation:<br><span style="font-size: 12px; color:red;">(separate by plus (+) )</span></label>
             <div class="col-sm-7"> 
               <input type="text" name="abstract_main_author_affil" class="form-control textInput" id="abstract_main_author_affil" placeholder="Author1 affiliation + Author2 affiliation..." required/>
             </div>
           </div>
          <!--  <div class="form-group">
             <label class="control-label col-sm-4" for="abstract_ext_aut">Authors if more:<br><span style="font-size: 12px;">(separate by commas)</span></label>
             <div class="col-sm-7"> 
               <textarea name="abstract_ext_aut" class="form-control" id="abstract_ext_aut"></textarea>
             </div>
           </div>
           <div class="form-group">
             <label class="control-label col-sm-4" for="abstract_ext_aut">Additional Authors affiliation:<br><span style="font-size: 12px;">(separate by commas)</span></label>
             <div class="col-sm-7"> 
               <textarea name="abstract_ext_aut_affil" class="form-control" id="abstract_ext_aut"></textarea>
             </div>
           </div> -->
            <?php if(isset($_SESSION['errors']['abstract_content']) && $_SESSION['errors']['abstract_content']!='') 
                  echo '<h5 align="center"><font color="#ff1a0a">*Maximum allowed word for abstract content is 250.</font></h5>'; 
                  $_SESSION['errors']['abstract_content']='';
                  ?>
           <div class="form-group">
             <label class="control-label col-sm-4" for="abstract_content">*Main Abstract:<br><span style="font-size: 12px; color:red;">(Only 250 words)</span></label>
             <div class="col-sm-7"> 
               <textarea name="abstract_content" class="form-control" id="abstract_content" rows="10"></textarea>
             </div>
           </div>
            <?php if(isset($_SESSION['errors']['abstract_keywords']) && $_SESSION['errors']['abstract_keywords']!='') 
                  echo '<h5 align="center"><font color="#ff1a0a">*Maximum allowed keywords is 4.</font></h5>'; 
                  $_SESSION['errors']['abstract_keywords']='';
                  ?>
            <div class="form-group">
             <label class="control-label col-sm-4" for="abstract_keywords">Keywords:<br><span style="font-size: 12px; color:red;">(Only 4, separate by commas)</span></label>
             <div class="col-sm-7"> 
               <input type="text" name="abstract_keywords" class="form-control" id="abstract_keywords" required/>
             </div>
            </div>
           <div class="form-group"> 
              <div class="aab controls col-md-4"></div>
                 <div class="controls col-md-8 ">
                      <input type="submit"class="btn btn-primary " id="preview-abstract" value="Save" name="preview_abstract" onsubmit="" />
                      <input type="submit" name="abstract_submit" value="Submit" class="btn btn-success" id="submit-abstract"/>
               </div>
         </div> 
          <p style="text-align: center;">Fields with Asterisk(*) are required fields</p>
      </form>
    </div>

    </div><!-- /.container -->

<?php 
 }
  else if(isset($_GET['action']) && $_GET['action'] == 'edit_abstract'){
  	$abbs=0;
  ?>
   <?php 
    if($abs_data != '' && !empty($abs_data)){

    

   // print_r($abs_data);
    ?>
    <div class="container" id="register-form">
     <div class="form-title">
        <h3 class="brdr">Edit Abstract</h3>
     </div>
     <div class="form-reg brdr" style="width: 85%;">
      <form class="form-horizontal" name="Form" method="post" action="#" id="reg-form" onsubmit="return validateform()">
           <div class="form-group">
             <label class="control-label col-sm-4" for="fname">*Interest Areas:</label>
             <div class="col-sm-7"> 
               <select class="form-control" name="interest_areas" id="interest_areas">
                 <option value="sel_areas">Select Thrust Areas Topic</option>
               	 <option value="Biotechnology: Innovations, Translation and IPR">Biotechnology: Innovations, Translation and IPR</option>
               	 <option value="Nano Biotechnology: A revolutionary Approach">Nano Biotechnology: A revolutionary Approach</option>
               	 <option value="Food and Agriculture Biotechnology">Food and Agriculture Biotechnology</option>
               	 <option value="Medical Biotechnology: A Translational Perspective">Medical Biotechnology: A Translational Perspective</option>
               	 <option value="Environment Biotechnology">Environment Biotechnology</option>
               	 <option value="Industrial Biotechnology">Industrial Biotechnology</option>
               	 <option value="Bioinformatics and Computational Biology">Bioinformatics and Computational Biology</option>
                 <option value="For Prevention of disease and Promotion of Health">For Prevention of disease and Promotion of Health</option>
               </select>
             </div>
           </div>
           <div class="form-group">
             <label class="control-label col-sm-4" for="title">*Title:</label>
             <div class="col-sm-7"> 
               <input type="text" name="abstract_title" class="form-control textInput" id="title" value="<?php echo $abs_data[1];?>" required/>
             </div>
           </div>
           <div class="form-group">
             <label class="control-label col-sm-4" for="pass">*Author Names:<br><span style="font-size: 12px;color:red;">(separate by plus (+) )</span></label>
             <div class="col-sm-7"> 
               <input type="text" name="abstract_main_author" class="form-control textInput" id="author" value="<?php echo $abs_data[3];?>" required/>
             </div>
           </div>
           <div class="form-group">
             <label class="control-label col-sm-4" for="abstract_main_author_affil">*Authors affiliation:<br><span style="font-size: 12px;color:red;">(separate by plus (+))</span></label>
             <div class="col-sm-7"> 
               <input type="text" name="abstract_main_author_affil" class="form-control textInput" id="abstract_main_author_affil" value="<?php echo $abs_data[5];?>" required/>
             </div>
           </div>
         
           <?php if(isset($_SESSION['errors']['abstract_content']) && $_SESSION['errors']['abstract_content']!='') 
                  echo '<h5 align="center"><font color="#ff1a0a">*Maximum allowed word for abstract content is 250.</font></h5>'; 
                  $_SESSION['errors']['abstract_content']='';
                  ?>
           <div class="form-group">
             <label class="control-label col-sm-4" for="abstract_content">*Main Abstract:<br><span style="font-size: 12px; color:red;">(Only 250 words)</span></label>
             <div class="col-sm-7"> 
               <textarea name="abstract_content" class="form-control" id="abstract_content" rows="10" ><?php echo $abs_data[7];?></textarea>
             </div>
           </div>
           <?php if(isset($_SESSION['errors']['abstract_keywords']) && $_SESSION['errors']['abstract_keywords']!='') 
                  echo '<h5 align="center"><font color="#ff1a0a">*Maximum allowed keywords is 4.</font></h5>'; 
                  $_SESSION['errors']['abstract_keywords']='';
                  ?>
            <div class="form-group">
             <label class="control-label col-sm-4" for="abstract_keywords">Keywords:<br><span style="font-size: 12px; color:red;">(Only 4, separate by commas)</span></label>
             <div class="col-sm-7"> 
               <input type="text" name="abstract_keywords" class="form-control" id="abstract_keywords" value="<?php echo $abs_data[8];?>" required/>
             </div>
            </div>
           <div class="form-group"> 
              <div class="aab controls col-md-4"></div>
                 <div class="controls col-md-8 ">
                 <input type="submit"class="btn btn-primary " id="edit-abstract" value="Update" name="edit_abstract" onsubmit="" />
                      
               </div>
         </div> 
          <p style="text-align: center;">Fields with Asterisk(*) are required fields</p>
      </form>
    </div>

    </div><!-- /.container -->


    <?php 
     }else{
     	echo '<h3 align="center">You don\'t have any abstract added.</h3>';

     }

     ?>
<?php 
 }
 elseif(isset($_GET['action']) && $_GET['action'] == 'view_abstract'){
    
    
 	if($abbs==1 && $stat[10]=='10')
         echo '<h3 align="center">You have submitted an abstract. You can edit,preview or submit it.</h3>';
    else if($abbs==1 && $stat[10]!='10')
    	echo '<h3 align="center">You have submitted an abstract. You can preview it.</h3>';
 ?>

    <?php 
    if($abs_data != '' && !empty($abs_data)){
    //print_r($abs_data);
    ?>

    <div class="container" id="register-form">
     <div class="form-title">
        <h3 class="brdr">View Abstract</h3>
     </div>
     <div class="abstract_reg brdr">
      <table class="table">
      	<tr>
      		<th>S.No</th>
      		<th>Section</th>
      		<th>Title</th>
      		<th>Authors</th>
      		<th>Authors Affiliation</th>
      		<th>Keywords</th>
      		<th>Submission date</th>
      		<th>Operations</th>
      	</tr>
      	<tr>
      		<td>1</td>
      		<td><?php echo $abs_data[2]; ?></td>
      		<td><?php echo $abs_data[1]; ?></td>
      		<td><?php echo $abs_data[3]; ?></td>
      		<td><?php echo $abs_data[5] ?></td>
      		<td><?php echo $abs_data[8] ?></td>
      		<td><?php echo date('d/m/Y',$abs_data[9]); ?></td>
      		<td>
      		 <span>
	 		 	
	 		  <a href="?action=view_abstract_pdf" class="btn btn-primary btn" id="submit-view_abstract" style="width:110px; margin-bottom:13px;"/>Preview</a>
	 		 
	 		 <?php
	 		 //show submit button only if abstrct is not submitted
	 		  $stat = $file->check_abs_status(); 
               if($stat[10] == '10'){
	 		  ?>
	 		    <a href="?action=edit_abstract" class="btn btn-primary btn" id="submit-view_abstract" style="width:95px; margin-bottom:13px;"/>Edit</a></span>
	 		    <a href="dashboard.php?action=view_abstract_submit" class="btn btn-primary btn" id="submit-view_abstract" style="width:210px; margin-bottom:13px;" />Submit Abstract</a></p>
	 		  <?php 
                }
	 		   ?>
      		</td>
      	</tr>
      </table>
    </div>

    </div><!-- /.container -->

    <?php 
     }else{
     	echo '<h3 align="center">You don\'t have any abstract added.</h3>';

     }

     ?>
</div>
<?php 
}else{
   // header('location:dashboard.php');
   // exit();
  }
 ?>



<?php } ?>


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
					<a href="https://www.facebook.com/Biosangam/">fb</a>
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
<script type="text/javascript">

$(document).ready(function () {
    
   // $('#abspart').scrollTop($('#abspart')[0].scrollHeight);
    
    $(window).load(function() {
  $("html, body").animate({ scrollTop: $(document).height() }, 1000);
});

});



//check for empty fields in the login form
function validateform(){
    var a=document.forms["Form"]["abstract_title"].value;
    var b=document.forms["Form"]["abstract_main_author"].value;
    var c=document.forms["Form"]["abstract_main_author_affil"].value;
    var d=document.forms["Form"]["abstract_content"].value;
    if (a==null || a=="",b==null || b=="",c==null || c=="",d==null || d==""){
      alert("Please Fill Required Fields, fields with Asterisk(*) are required ones");
      return false;
    }
    
    var ddl = document.getElementById("interest_areas");
	var intareas = ddl.options[ddl.selectedIndex].value;
	if(intareas  == "sel_areas") {
	    alert("Please select thrust areas topic");
	    return false;
	}
}




</script>
