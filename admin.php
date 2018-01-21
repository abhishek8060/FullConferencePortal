<?php

include('core.functions.php');
$file = new mainController();

if(!isset($_COOKIE["login"]))
  header("location: adminlogin.php");

/*
require_once 'dompdf/autoload.inc.php';
  use Dompdf\Dompdf;

  class Pdf extends Dompdf{
  public function __construct() {
        parent::__construct();
    }

}
*/
if(isset($_GET["id"]))
 {
  
  

  $pdfile = new mainController();
  
  $id=$_GET["id"];

  $output = '';

    $sql = "select * from abstract_info where user_id=$id";
   	$query = mysqli_query($pdfile->myconx,$sql);
   	if($query->num_rows > 0){
   		$row = mysqli_fetch_row($query);
//       print_r($row);
        }

    if($row[10]=='20'){    
     
    $sql = "select * from user where user_id=$id";
    $query = mysqli_query($pdfile->myconx,$sql);
    if($query->num_rows > 0){
      $data = mysqli_fetch_row($query);
//       print_r($row);
        } 

$dompdf = new Pdf();

	
  
   $pageid='BS'.$row[11];

   if(strcmp($row[2],'Biotechnology: Innovations, Translation and IPR')==0)
      $pageid.='A';
   else if(strcmp($row[2],'Nano Biotechnology: A revolutionary Approach')==0)
      $pageid.='B';
   else if(strcmp($row[2],'Food and Agriculture Biotechnology')==0)
      $pageid.='C';
   else if(strcmp($row[2],'Medical Biotechnology: A Translational Perspective')==0)
      $pageid.='D';
   else if(strcmp($row[2],'Environment Biotechnology')==0)
      $pageid.='E';
   else if(strcmp($row[2],'Industrial Biotechnology')==0)
      $pageid.='F';
   else if(strcmp($row[2],'Bioinformatics and Computational Biology')==0)
      $pageid.='G';
   else if(strcmp($row[2],'For Prevention of disease and Promotion of Health')==0)
      $pageid.='H'; 
    
    
       $authors=explode('+', $row[3]);
       $author_af=explode('+', $row[5]);
       $author_names='';
        $r=1;
       foreach ($authors as $key) {
         $key=trim($key);
         $author_names.=$key.'<font size="3"><sup>'.$r.'</sup></font>';
         $r++;
         $author_names.=' ';
       }

       $author_aff='';
        $r=1;
       
       foreach ($author_af as  &$value) {
         $value=trim($value);
       }

       $author_a=array_unique($author_af);
       $author_a=array_values($author_a);

      for($i=0;$i<count($author_a);$i++) {
         
         $ind='';
         for($j=1;$j<=count($author_af);$j++) {
           
          if(strcmp($author_a[$i],$author_af[$j-1])==0){
             
             $ind.=$j.',';

         }
         
         
        }
        $ind=trim($ind,",");
        $author_aff.='<font size="3"><sup>'.$ind.'</sup></font>'.$author_a[$i].' ';
      }


       $pageid.=$row[0];

   $output='<center><h3 align="right">'.$pageid.'</h3>
                    <font size="6">'.$row[1].'</font><br>
                    <font size="4"><b>'.$author_names.'</b></font><br>
                    <font size="4"><i>'.$author_aff.'</i></font><br>
                    <i><font size="3"><sup>1</sup></font>'.$data[2].'</i>
                     <br>
                     </center>
                    
                     <hr>
                     <p>'.$row[7].'</p>
                    <font size="4">Keywords:<i>'.$row[8].'</i></font>
                    
                        ';


	
  $file_name = 'Abstract-'.$pageid.'.pdf';
  $dompdf->loadHtml($output);
	$dompdf->setPaper('A4', 'portrait');
  $dompdf->render();
  $dompdf->stream($file_name,array("Attachment"=>false));

}
else
{
  echo ''; 
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
	<title>Biosangam 2018 - ADMIN</title>
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
	        <li><a href="adminlogin.php?action=logout">logout</a></li>
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
					<h2 class="title-section">Welcome Admin</h2>
					<!-- <h3>Expert speakers in the field of biotechnology will be confirmed soon for the conference.</h3> -->
				</div>
			         <u><h2 align="center">Submitted Abstracts</h2></u>
				   </div>
				   
 
			</div>
	</div>
</div>	
		<div style="margin-left:50px; margin-right:50px;">
      <div class="abstract_reg brdr">
      <table class="table" style="font-size:16px;">
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
     
    <?php 
   
     $sql = "select * from abstract_info order by abstract_created_date desc";
   	$query = mysqli_query($file->myconx,$sql);
   	if($query->num_rows > 0){
   		$i=1;
   		while($row = mysqli_fetch_row($query)){
       if($row[10]=='20'){
      
        ?>
        <tr>
      		<td><?php echo $i; ?></td>
      		<td><?php echo $row[2]; ?></td>
      		<td><?php echo $row[1]; ?></td>
      		<td><?php echo $row[3]; ?></td>
      		<td><?php echo $row[5] ?></td>
      		<td><?php echo $row[8] ?></td>
      		<td><?php echo date('m/d/Y',$row[9]); ?></td>
      		<td>
	 		  <p><a href="admin.php?id=<?php echo $row[11];?>" class="btn btn-primary btn preview"/>Preview Abstract</a>
	 		</td>
      	</tr>
<?php
   		$i++;}}
       }
    
     ?>
 </table>
</div>
</div>

    <br><br><br>
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

