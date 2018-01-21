<?php
/*
Comments
=========
Biosangam 2018, MNNIT ALLAHABAD
BACKEND CODE BY MANJIT RAJ
*/
if (session_status() == PHP_SESSION_NONE){
  session_start();
}


require_once 'dompdf/autoload.inc.php';
  use Dompdf\Dompdf;

  class Pdf extends Dompdf{
  public function __construct() {
        parent::__construct();
    }

}

include('database.php');
class mainController{

	public $myconx;
    
    public function __construct(){
    	$dbcon = new dbase();
    	$this->myconx = $dbcon->connect();
    }
    
	public function usercreate($data){ //print_r($data);
		//lets clean the user data
        $data = $this->sanitize_data($data);
        //fields which we want to validate
        $check2 = array('user_fname'=>'2','user_pass'=>'8','user_mobile'=>'10');
        $validation_errors = $this->validate_data($data,$check2);

        if(!empty($validation_errors) && count($validation_errors)!=0){
        	//if any error output the errors to the forms
        	//print_r($validation_errors);
           $_SESSION['errmsg']=$validation_errors;

        }

        else
           {
        	//check for email exists in the database,cant register with same email twice
            
            if($this->email_if_exist($data['user_email']) == 'exist'){
              $_SESSION['coremsg']='This email is already registered';
            }

            else{
	           //put data into the database and send the verification email
				$data['acc_temp_token'] = substr((sha1(md5(time())+md5($data['user_email']))),3,50);
				$data['user_acc_created_date'] = time();
				$data['user_pass'] = $this->hash_password($data['user_pass']);
				$data['user_acc_created_ip'] = $_SERVER['REMOTE_ADDR'];
			    $sql = "INSERT INTO user(user_name,user_email,user_pass,user_addr,user_mobile,user_acc_type,user_acc_status,user_acc_temp_token,user_acc_created_date,user_acc_created_ip,salutation) 
			                VALUES 
		           ('".$data['user_fname']."','".$data['user_email']."','".$data['user_pass']."','".$data['user_addr']."','".$data['user_mobile']."','".$data['acctype']."','10','".$data['acc_temp_token']."','".$data['user_acc_created_date']."','".$data['user_acc_created_ip']."','".$data['user_salute']."')";
		        //echo $sql; die();
		           
		        $query = mysqli_query($this->myconx,$sql); 
		        if($query){
		          //send registration email $data['acc_temp_token'],$data['user_email']
		          $this->php_mailer($data);
		          return true;
		        }else{
		          $_SESSION['coremsg']='Unknown error occurred, contact Administrator';
		        }

	      }

       }

	}

	public function userlogin($data){
	   //lets clean the user data
       $data = $this->sanitize_data($data);
       $_SESSION['log_msg'] = '';
       //hash the password
		$pass = $this->hash_password($data['user_pass']);

       $sql = "select * from user where user_email = '".$data['user_email']."' and user_pass = '".$pass."' ";
       $query = mysqli_query($this->myconx,$sql); 
       if($query->num_rows > 0){
       	 $row = mysqli_fetch_row($query);
       	//can only log in if user is active
       	if($row[7] == '10'){
       		//dummy data to row
       		$_SESSION['log_msg'] = 'Email Activation Pending!';
       		$row = array('hello');
       		return $row;
       	}else{
       		//user is activated and login the user
	       	//update last logged in ip and date
	       	$date = time();
	       	$curr_ip = $_SERVER['REMOTE_ADDR'];
	       	$sql2 = "update user set user_last_logged_in_date = '".$date."' and user_last_logged_in_ip = '".$curr_ip."' where user_id = '".$row['0']."' "; 
	       	  //echo $sql2; die();
	       	$query2 = mysqli_query($this->myconx,$sql2);

	       	return $row;
	       }
      }
       return;
	}

	public function sanitize_data($info){
		if(!$info)
			return;
        foreach($info as $key => $formData){
          //escaping special characters,symbol if any present and setting the 
          //respective variables for validation
          $info[$key] = htmlspecialchars(mysqli_real_escape_string($this->myconx,$formData));
        }
        return $info;
	}

	public function sendemail($code,$email){
		$to = $email;
		$subject = "Please Click on the link to complete the registration";

		$htmlContent = '
		    <html>
		    <head>
		        <title>BIOSANGAM 2018,MNNIT ALLAHABAD</title>
		    </head>
		    <body>
		        <h1Biosangam.in Registration</h1><h3> Information that was received.</h3>
		        <table cellspacing="0" style="border: 2px solid blue; width: 700px; height: 500px; cellpadding="2px">
		            <tr>
		                <th>Name:</th><td>from php</td>
		            </tr>
		            <tr style="background-color: #e0e0e0;">
		                <th>Email:</th><td>from php</td>
		            </tr>
		            <tr>
		                <th>Mobile Number:</th><td>+91-from php</td>
		            </tr>
		        </table>
		        <h2>
		         <a href="http://localhost/mnnit_biosangam/profile.php/?code=$code&un=$email">Complete Registration</a>
		        </h2>
		    </body>
		    </html>';

		// Set content-type header for sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// Additional headers
		$headers .= 'From: Biosanagam 2018,MNNIT Allahabad<noreply@biosangam.com>' . "\r\n";

		// Send email
		if(mail($to,$subject,$htmlContent,$headers)){
		   return true;
		}
		return;
	}


   public function validate_data($data,$check2){
    //check for presence. we will check for all the fields, except for checkbox field. Here we are getting 
    //all the keys using array function but separately we can make it as array
    $check = array_keys($data);
    $errors = '';
    foreach($check as $value){
       if(!$this->has_presence($data[$value])){
         $errors[$value] = $value." can't be blank";
       }
    }

    foreach($check2 as $key => $val){
      if(!empty($data[$key]) && $data[$key] != ''){
        if(!$this->has_min_length($data[$key],$val)){
          if($key=='user_fname')
          $errors[$key] ='Name should be of minimum '.$val.' characters';
          else if($key=='user_pass')
          $errors[$key] ='Password should be of minimum '.$val.' characters';
          else
          $errors[$key] ='Mobile number should be of minimum '.$val.' digits';
        }
      }
    }

    //for password
	if(!empty($data['user_pass']) && empty($errors['user_pass'])){
	   if($data['user_pass'] !== $data['user_cpass']){
	  	 $errors['password'] = "Password do not match";
	   }elseif($this->check_pass($data['user_pass']) == false){
	   	$errors['user_pass'] = "Password should contain at least one lower case,upper case,number and special symbol";
	   }
	 }

	 //matching email pattern
	 if(empty($errors['user_email']) && isset($data['user_email'])){
	 	if(filter_var($data['user_email'],FILTER_VALIDATE_EMAIL) === false){
	 		$errors['user_email'] = 'Email is not valid';
	 	}
	 }

	 //matching mobile number
	 if(empty($errors['user_mobile']) && isset($data['user_mobile'])){
	 	if($this->validate_mobile($data['user_mobile']) == false){
	 		$errors['user_mobile'] = 'Invalid mobile number';
	 	}
	 }

    //ans assign an extra key for error array for any error
    //print_r($errors);die();  
    return $errors; 

   }

   public function has_presence($val){
      if(!empty($val) && $val!=''){
        return true;
      }
      return false;
   }

   public function has_min_length($val,$length){
      if(strlen($val) >= $length){
        return true;
      }
      return false;
   }

   public function validate_mobile($val){
   	//for all preg_match('/^[0-9]{10}+$/',$val) for all 10 digit numbers
      return true;
   }

   public function validate_email($val){
 	  return preg_match('/^[A-z0-9_.\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{2,4}$/', $val);
   }

   public function check_pass($val){
      return preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{6,40}$/',$val);
   }

   public function email_if_exist($email){
   	if(!$email)
   		return;
   	$sql = "select user_email from user where user_email = '".$email."' ";
   	$query = mysqli_query($this->myconx,$sql);
   	if($query->num_rows > 0){
   		return 'exist';
   	}
   	return;
   }

   public function hash_password($pass){
   	$salt = "sd35#dfgAS{2aQOFf4]!#36kjdh+-khl*&".$pass."9&FHhf@#hk^FM^%$897fhjj}";
    $pass = hash('sha512',$salt);
    return $pass;
   }

   public function email_to_user(){

   }

   public function save_abstract($data){ //print_r($data); die();
   	if(!$data)
   		return;
   	//sanitize the user data
    $data = $this->sanitize_data($data);
    //fields which we want to validate
    $check2 = array('abstract_title'=>'2','abstract_main_author_affil'=>'2');
    //validating only required fields for length and presence
    $compulsory_data = array('interest_areas' => $data['interest_areas'],'abstract_title' => $data['abstract_title'],'abstract_main_author' => $data['abstract_main_author'],'abstract_main_author_affil' => $data['abstract_main_author_affil'],'abstract_content' => $data['abstract_content']);
    $validation_errors = $this->validate_data($compulsory_data,$check2);

    //check for max words in abstract content
    $check_con = array('abstract_content'=> 250);
    //if keywords not empty then check for total no of words
    if($data['abstract_keywords'] != '' || $data['abstract_keywords'] != null){
    	$check_con = array('abstract_content'=> 250, 'abstract_keywords' => 4);
    }

    foreach($check_con as $content => $allow_len){
    	  if($this->validate_words($data[$content],$allow_len) != 'pass'){
    	  	$validation_errors[$content] = 'Max allowed word for '.$content.' is only '.$allow_len; 
    	  }
    }

    if(!empty($validation_errors) && count($validation_errors)!=0){
       	//if any error output the errors to the forms
       //	print_r($validation_errors);
       $_SESSION['errors']=array();
        $_SESSION['errors']=$validation_errors;
     }else{
	   	$abs_created_date = time();
	    $sql = "INSERT INTO abstract_info(abstract_title,abstract_section,abstract_main_author,abstract_main_author_affil,abstract_ext_author,abstract_ext_author_affil,abstract_content,abstract_keywords,abstract_created_date,abstract_lock_status,user_id) 
				                VALUES 
			      ('".$data['abstract_title']."','".$data['interest_areas']."','".$data['abstract_main_author']."','".$data['abstract_main_author_affil']."','','','".$data['abstract_content']."','".$data['abstract_keywords']."','".$abs_created_date."','".$data['lock_status']."','".$_SESSION['uid']."')";
	    //echo $sql; die();
			           
		$query = mysqli_query($this->myconx,$sql);
		if($query){
	      return true;
		}
	 	return;
     }
   }

   public function update_abstract($data,$uid){ //print_r($data); die();
    if(!$data)
      return;
    //sanitize the user data
    $data = $this->sanitize_data($data);
    //fields which we want to validate
    $check2 = array('abstract_title'=>'1','abstract_main_author_affil'=>'1');
    //validating only required fields for length and presence
    $compulsory_data = array('interest_areas' => $data['interest_areas'],'abstract_title' => $data['abstract_title'],'abstract_main_author' => $data['abstract_main_author'],'abstract_main_author_affil' => $data['abstract_main_author_affil'],'abstract_content' => $data['abstract_content']);
    $validation_errors = $this->validate_data($compulsory_data,$check2);

    //check for max words in abstract content
    $check_con = array('abstract_content'=> 250);
    //if keywords not empty then check for total no of words
    if($data['abstract_keywords'] != '' || $data['abstract_keywords'] != null){
      $check_con = array('abstract_content'=> 250, 'abstract_keywords' => 4);
    }

    foreach($check_con as $content => $allow_len){
        if($this->validate_words($data[$content],$allow_len) != 'pass'){
          $validation_errors[$content] = 'Max allowed word for '.$content.' is only '.$allow_len; 
        }
    }

    if(!empty($validation_errors) && count($validation_errors)!=0){
        //if any error output the errors to the forms
        $_SESSION['errors']=array();
        $_SESSION['errors']=$validation_errors;
     }else{
      $abs_created_date = time();
 
      $sql='UPDATE abstract_info SET abstract_title="'.$data['abstract_title'].'",abstract_section="'.$data['interest_areas'].'",
      abstract_main_author="'.$data['abstract_main_author'].'",abstract_main_author_affil="'.$data['abstract_main_author_affil'].'",
      abstract_content="'.$data['abstract_content'].'",abstract_keywords="'.$data['abstract_keywords'].'",abstract_created_date="'.$abs_created_date.'",
      abstract_lock_status="'.$data['lock_status'].'" WHERE user_id='.$uid.'';
       
   
   

   //echo $sql; die();
                 
    $query = mysqli_query($this->myconx,$sql);
    if($query){
        return true;
    }
    return;
     }
   }


   public function fetch_abstract($uid){ 
   	if(!$uid)
   		return;
   	$sql = "select * from abstract_info where user_id = '".$uid."' ";
   	$query = mysqli_query($this->myconx,$sql);
   	if($query->num_rows > 0){
   		$row = mysqli_fetch_row($query);
        return $row;
   	}
   	return;
   }

   public function validate_words($word,$allowed_len){ 
     $tot_word = str_word_count($word,1); //print_r($tot_word);//echo count($tot_word);
     $find_word = $tot_word;
     foreach($find_word as $key => $val){
     	if($val == 'r'){
     		unset($tot_word[$key]);
     	}
     	if($val == ','){
     		unset($tot_word[$key]);
     	}
     	if($val == '\''){
     		unset($tot_word[$key]);
     	}
     }
       //echo '<br>';
       //print_r($tot_word);//echo count($tot_word);
     $tot_word_count = count($tot_word);
     if($tot_word_count <= $allowed_len){
     	return 'pass';
     }
     return 'exceeded';
   }

   public function php_mailer($data){
        require 'PHPMailer/PHPMailerAutoload.php';

        $mail = new PHPMailer;
        $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
          )
        );
        $mail->isSMTP();                            // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                     // Enable SMTP authentication
        $mail->Username = '';          // SMTP username
        $mail->Password = ''; // SMTP password
        $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                          // TCP port to connect to

        $mail->setFrom('info@biosangam.in', 'Biosangam');
        $mail->addReplyTo('info@biosangam.in', 'Biosangam');
        $mail->addAddress($data['user_email']);   // Add a recipient
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        $mail->isHTML(true);  // Set email format to HTML

        $bodyContent = '
            <html>
            <head>
                <title>BIOSANGAM 2018,MNNIT ALLAHABAD</title>
            </head>
            <body>
                <img src="img\intro-logo4-01.png">
                <p>

                  Dear '.$data['user_salute'].' '.$data['user_fname'].'<br><br>

                  Thank you for creating an account on the website.
                  <br>Please click on the link below to confirm your registration.
                  <br><br>
                  
                    <h3>
                    <a href="http://www.biosangam.in/verifyuser.php?verifyuser='.$data['acc_temp_token'].'">Complete Registration</a>
                    </h3>
                    <br><br>
                    Kind Regards,<br>
                    Biosangam 2018 Secretariat


                  </p>
              

            </body>
            </html>
        ';

        $mail->Subject = 'Verification Email. Biosangam 2018';
        $mail->Body    = $bodyContent;

        if(!$mail->send()) {
           $_SESSION['mailmsg']='N';
            
        } else {
            $_SESSION['mailmsg']='Y';
        }
	
  }


  public function fphp_mailer($data){
        require 'PHPMailer/PHPMailerAutoload.php';

        $mail = new PHPMailer;
        $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
          )
        );
        $mail->isSMTP();                            // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                     // Enable SMTP authentication
        $mail->Username = '';          // SMTP username
        $mail->Password = ''; // SMTP password
        $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                          // TCP port to connect to

        $mail->setFrom('info@biosangam.in', 'Biosangam');
        $mail->addReplyTo('info@biosangam.in', 'Biosangam');
        $mail->addAddress($data[2]);   // Add a recipient
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        $mail->isHTML(true);  // Set email format to HTML

        $bodyContent = '
            <html>
            <head>
                <title>BIOSANGAM 2018,MNNIT ALLAHABAD</title>
            </head>
            <body>
                
                <p> Dear '.$data[13].' '.$data[1].' </p>

                  <p>
                  You have requested a password reset, please follow the link below to reset your password. Please ignore this email if you did not request a password change.

                  </p>
                  <p>
                  
                <h3>

                 <a href="http://www.biosangam.in/reset.php?verifyuser='.$data[8].'">Follow this link to reset your password.</a>
                </h3>

                  </p>
                  Kind Regards <br>
                  Biosangam 2018 Secretariat.

            </body>
            </html>
        ';

        $mail->Subject = 'Forgot password Email. Biosangam 2018';
        $mail->Body    = $bodyContent;

        if(!$mail->send()) {
           return false; 
        } else {
            return true;
        }
  
  }
 
  public function if_any_abstract(){
  	$sql = "select * from abstract_info where user_id = '".$_SESSION['uid']."'";
  	$query = mysqli_query($this->myconx, $sql);
  	if($query->num_rows > 0){
  		return true;
  	}
  	return false;
  }

  public function check_abs_status(){
  	$sql = "select * from abstract_info where user_id = '".$_SESSION['uid']."'";
  	$query = mysqli_query($this->myconx, $sql);
  	if($query->num_rows > 0){
  		$row = mysqli_fetch_row($query);
  		return $row;
  	}
  	return false;
  }

  public function save_abstract_perman(){
  	$sql = "update abstract_info set abstract_lock_status  = '20' where user_id = '".$_SESSION['uid']."' and abstract_id = '".$_SESSION['abs_id']."' "; //echo $sql ;
  	$query = mysqli_query($this->myconx, $sql);
  	if($query){
  		return true;
  	}
  	return false;
  }

  public function verifyuser($code){
    $sql = "select * from user where user_acc_temp_token = '".$code."' ";
    $query = mysqli_query($this->myconx,$sql);
  	if($query->num_rows > 0){
  		$row = mysqli_fetch_row($query);
  		return $row;
  	}
  	return false;
  }

  public function update_user_status($uid){
    $acc_token = md5('verified_user');
  	$sql = "update user set user_acc_status  = '20',user_acc_temp_token = '".$acc_token."' where user_id = '".$uid."' "; //echo $sql ;
  	$query = mysqli_query($this->myconx, $sql);
  	if($query){
  		return true;
  	}
  	return false;
  }

  public function create_pdf(){
  	//fetch datails of abstract
  	$row = $this->check_abs_status();

	// instantiate and use the dompdf class
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
       foreach ($authors as &$value) {
         $value=trim($value);
         $author_names.=$value.'<font size="3"><sup>'.$r.'</sup></font>';
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
                    <i><font size="3"><sup>1</sup></font>'.$_SESSION['user_info'][2].'</i>
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


   public function sendthanks($email,$name){


    $row = $this->check_abs_status();

  
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
    
    
      

       $pageid.=$row[0];




       $authors=explode('+', $row[3]);
       $author_af=explode('+', $row[5]);
       $author_names='';
        $r=1;
       foreach ($authors as &$value) {
         $value=trim($value);
         $author_names.=$value.'<font size="3"><sup>'.$r.'</sup></font>';
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




   $output='
<p><img src="/img/intro-logo4-01.png"></p>

   <p>

​Dear Sir/Madam,
<br><br>
Thank you very much for using the Biosangam 2018 Abstract Submission System.
<br>
Your Abstract No- '.$pageid.' has been successfully submitted.
<br><br><br>
<div style="border:1px solid; margin-left:100px;margin-right:100px;padding:20px;">
<center><h3 align="right">'.$pageid.'</h3>
                    <font size="5">'.$row[1].'</font><br>
                    <font size="4"><b>'.$author_names.'</b></font><br>
                    <font size="4"><i>'.$author_aff.'</i></font><br>
                    <i><font size="3"><sup>1</sup></font>'.$email.'</i>
                     <br>
                     </center>
                    
                     <hr>
                     <p>'.$row[7].'</p>
                    <font size="3">Keywords:<i>'.$row[8].'</i></font>
</div>
<br><br><br>
</p>


<p>
Kind Regards,<br>
Biosangam 2018<br>
Secretariat<br><br><br>

This email is automatically generated on behalf of  Biosanam,2018 please do not reply to this email. For further <br>information regarding the content of this email please contact info@biosangam.in

</p>';


  require 'PHPMailer/PHPMailerAutoload.php';

        $mail = new PHPMailer;
        $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
          )
        );
        $mail->isSMTP();                            // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                     // Enable SMTP authentication
        $mail->Username = '';          // SMTP username
        $mail->Password = ''; // SMTP password
        $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                          // TCP port to connect to

        $mail->setFrom('info@biosangam.in', 'Biosangam');
        $mail->addReplyTo('info@biosangam.in', 'Biosangam');
        $mail->addAddress($email);   // Add a recipient
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        $mail->isHTML(true);  // Set email format to HTML

        

        $mail->Subject = 'Biosangam 2018 - Abstract Submission '.$pageid;
        $mail->Body    = $output;

        if(!$mail->send()) {
           return false; 
        } else {
            return true;
        }


   }


}


 ?>
