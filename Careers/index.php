<?php 
// Pear library includes
// You should have the pear lib installed
include_once('Mail.php');
include_once('Mail_Mime/mime.php');

//Settings 
$max_allowed_file_size = 1000; // size in KB 
$allowed_extensions = array("doc", "docx", "pdf", "txt");
$upload_folder = '../uploads/'; //<-- this folder must be writeable by the script
$your_email = 'info@twin-p.com';//<<--  update this to your email address

$errors ='';

if(isset($_POST['submit']))
{
	//Get the uploaded file information
	$name_of_uploaded_file =  basename($_FILES['uploaded_file']['name']);
	
	//get the file extension of the file
	$type_of_uploaded_file = substr($name_of_uploaded_file, 
							strrpos($name_of_uploaded_file, '.') + 1);
	
	$size_of_uploaded_file = $_FILES["uploaded_file"]["size"]/1024;
	
	///------------Do Validations-------------
	if(empty($_POST['name'])||empty($_POST['email']))
	{
		$errors .= "\n Name and Email are required fields. ";	
	}
	if(IsInjected($visitor_email))
	{
		$errors .= "\n Bad email value!";
	}
	
	if($size_of_uploaded_file > $max_allowed_file_size ) 
	{
		$errors .= "\n Size of file should be less than $max_allowed_file_size";
	}
	
	//------ Validate the file extension -----
	$allowed_ext = false;
	for($i=0; $i<sizeof($allowed_extensions); $i++) 
	{ 
		if(strcasecmp($allowed_extensions[$i],$type_of_uploaded_file) == 0)
		{
			$allowed_ext = true;		
		}
	}
	
	if(!$allowed_ext)
	{
		$errors .= "\n The uploaded file is not supported file type. ".
		" Only the following file types are supported: ".implode(',',$allowed_extensions);
	}
	
	//send the email 
	if(empty($errors))
	{
		//copy the temp. uploaded file to uploads folder
		$path_of_uploaded_file = $upload_folder . $name_of_uploaded_file;
		$tmp_path = $_FILES["uploaded_file"]["tmp_name"];
		
		if(is_uploaded_file($tmp_path))
		{
		    if(!copy($tmp_path,$path_of_uploaded_file))
		    {
		    	$errors .= '\n error while copying the uploaded file';
		    }
		}
		
		//send the email
		$name = $_POST['name'];
		$visitor_email = $_POST['email'];
		$user_message = $_POST['message'];
		$to = $your_email;
		$subject="New form submission";
		$from = $your_email;
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		$text = "A New employment Application received from  $name :\n $user_message";
		
		$message = new Mail_mime(); 
		$message->setTXTBody($text); 
		$message->addAttachment($path_of_uploaded_file);
		$body = $message->get();
		$extraheaders = array("From"=>$from, "Subject"=>$subject,"Reply-To"=>$visitor_email, "IP"=>$ip);
		$headers = $message->headers($extraheaders);
		$mail = Mail::factory("mail");
		$mail->send($to, $headers, $body);
		//redirect to home page
		$success .= "\n Application was successifully sent";
		
		header('Location: index.php');
	}
}
///////////////////////////Functions/////////////////
// Function to validate against any email injection attempts
function IsInjected($str)
{
  $injections = array('(\n+)',
              '(\r+)',
              '(\t+)',
              '(%0A+)',
              '(%0D+)',
              '(%08+)',
              '(%09+)'
              );
  $inject = join('|', $injections);
  $inject = "/$inject/i";
  if(preg_match($inject,$str))
    {
    return true;
  }
  else
    {
    return false;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
<title>TWIN P LIMITED-Careers</title>
<!-- Bootstrap core CSS -->
    <link href="../dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../images/T.png">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../assets/js/ie-emulation-modes-warning.js"></script>
    <script src="..dist/js/bootstrap.js"></script>
    <script language="javascript" src="../css & javascript/gen_validatorv4.js" type="text/javascript"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

 <!-- Custom styles for this template -->
    <link href="../css & javascript/carousel.css" rel="stylesheet">
    
  
</head>
<!-- NAVBAR
================================================== -->

<body>
  <nav class="navbar navbar-default navbar-fixed-top">
         
        <div class="navbar-header">
        <div id="logo">
                 
                    <img alt="Twin-P Limited" src="../images/logo180.jpg">
                  
                </div>
                </div>
            <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li><a href="../index.html">HOME</a></li>
                <li><a href="../About/index.html">ABOUT</a></li>
               
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">SERVICES <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="../Pest Control/index.html">Pest Control Services</a></li>
                    
                    <li><a href="../Domestic & Commecial Cleaning/index.html">Cleaning Services</a></li>
                    <li><a href="../Sanitation/index.html">Sanitation Services</a></li>
                    
                  </ul>
                </li>
                <li class="active"><a href="index.php">CAREERS</a></li>
                 <li><a  href="../Contacts/index.php">CONTACTS</a></li>
              </ul>
            </div>
           
        </nav>
      
        
</div>
  <?php
if(!empty($errors)){
echo "<p class='alert alert-danger' role='alert'>".nl2br($errors)."</p>";
}
else if(!empty($success)){
echo "<p class=class='alert alert-success' role='alert'>".nl2br($success)."</p>";
}

?>

<div class="contained">
	<div class="blog-post">
		<h2 class="blog-post-title">Vacancies Available</h2>
		<p>None at this time</p>
	</div>
<!--Email form bruh!-->
<div id="email-form">
    <form method="POST" name="email_form_with_php" 
action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data"> 
<p>
<label for='name'>Name: </label><br>
<input type="text" size="80px" name="name" >
</p>
<p>
<label for='email'>Email: </label><br>
<input type="text" size="80px" name="email" >
</p>
<p>
<label for='message'>Message:</label> <br>
<textarea name="message" rows=10 cols="50"></textarea>
</p>
<p>
<label for='uploaded_file'>Select C.V. To Upload:</label> <br>
<input type="file" name="uploaded_file">
</p>
<input class="btn" type="submit" value="Submit Application" name='submit'>
</form>
    <script language="JavaScript">

var frmvalidator  = new Validator("email_form_with_php");
frmvalidator.addValidation("name","req","Please provide your name"); 
frmvalidator.addValidation("email","req","Please provide your email"); 
frmvalidator.addValidation("email","email","Please enter a valid email address"); 
</script>
    
</div>








<!--FOOTER-->
    <div class="footer">
    <div class="container">
    <div class="row">
    <ul class="list-unstyled">
        <li class="col-md-4 col-xs-4 col-sm-4">
            <h4>Connect With Us on Social Media:</h4>
           <a href="https://www.twitter.com/TwinPLimited" onClick="window.open(this.href,'Twitter','left=20,top=20,width=500,height=500,toolbar=1,resizable=1, scrollbars=1'); return false;"><img src="../icons/twitter.ico"></a>
            &nbsp;
            <a href="https://www.facebook.com/twinpco1" onClick="window.open(this.href,'Facebook','left=20,top=20,width=500,height=500,toolbar=1,resizable=1, scrollbars=1'); return false;"><img src="../icons/facebook.ico"></a>
            
        </li>
        
        <li class="col-md-3 col-sm-3 col-xs-3">
            <h4>Explore Twin-P:</h4>
            <a href="../About/index.html">About Us</a>
            </br>
            </br>
            <a href="../Service Policy/index.html">Service Policy</a>
        </li>
       <li class="col-md-5 col-sm-5 col-xs-5">
        <h4>Contact Information:</h4>
        <p> 2<sup>nd</sup> floor, Super House, Off Outering Rd.</p>
        <p>P.O BOX 225 - 00300,</p>
        <p>Nairobi, Kenya.</p>
        <p>TEL: (+254) 020 2322630.</p>
       
        <p>Cell: 0711 672 505.</p>
        <p>Email: <a href="mailto:info@twin-p.com">info(@)twin-p.com</a></p>
        <p>&copy; Twin-P Limited 2015. All Rights reserved.</p>
        <li>
    
    </ul>
    </div>
    </div>
    </div><!--END FOOTER-->
</div><!--END CONTAINED-->


<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="../dist/js/bootstrap.min.js"></script>
<script src="../assets/js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
