<?php 
$your_email ='snsindya@gmail.com';// <<=== update to your email address

session_start();
$errors = '';
$success='';
$name = '';
$subject='';
$message='';
$visitor_email = '';

if(isset($_POST['submit']))
{

	
		$name=$_POST['name'];
		$visitor_email=$_POST['email'];
		$subject=$_POST['subject'];
		$message=$_POST['message'];

	///------------Do Validations-------------
	if(empty($name)||empty($visitor_email))
	{
		$errors .= "\n Name and Email are required fields. ";	
	}
	if(IsInjected($visitor_email))
	{
		$errors .= "\n Bad email value!";
	}
	if(empty($_SESSION['6_letters_code'] ) ||
	  strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0)
	{
	//Note: the captcha code is compared case insensitively.
	//if you want case sensitive match, update the check above to
	// strcmp()
		$errors .= "\n The captcha code does not match!";
	}
	
	if(empty($errors))
	{
		$success .= "\n Mail was successifully sent";
		//send the email		
		$to = $your_email;
		$subject="New Email Received";
		$from = $your_email;
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		
		$body = "You have Received a new mail from .\n".
		"Name: $name\n".
		"Email: $visitor_email \n".
		"Message: \n ".
		"$message\n".
		"IP: $ip\n";	
		
		$headers="Sent From: $from \r\n";
	    $headers .="Reply To: $visitor_email \r\n";
		
		mail($to, $subject, $body,$headers);		
		
		header('Location: index.php');
		
	}
}

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
<title>TWIN P LIMITED-Contacts</title>
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
    
    
    <!--google api bruh! -->
<script src="https://maps.googleapis.com/maps/api/js"></script>
    
    <!---javascript to load map bruh!--->
   <script>
var myCenter=new google.maps.LatLng(-1.294155,36.887932);

function initialize()
{
var mapProp = {
  center:myCenter,
  zoom:16,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("map-canvas"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,
  });

marker.setMap(map);
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
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
                    <li class="divider"></li>
                    <li class="dropdown-header">Cleaning Services</li>
                    <li><a href="../Domestic & Commecial Cleaning/index.html">Domestic &amp; Commercial Cleaning</a></li>
                    <li><a href="../Sanitation/index.html">Sanitation Services</a></li>
                    <li><a href="../Upholstery & Carpet Cleaning/index.html">Upholstery &amp; Carpet Cleaning</a></li>
                  </ul>
                </li>
                 <li class="active"><a href="index.php">CONTACTS</a></li>
              </ul>
            </div>
           
        </nav>
      
        
        <!--googlemap bruh! -->
<div id="map-canvas">
</div>
  <?php
if(!empty($errors)){
echo "<p class='alert alert-danger' role='alert'>".nl2br($errors)."</p>";
}
else if(!empty($success)){
echo "<p class=class='alert alert-success' role='alert'>".nl2br($success)."</p>";
}

?>


<!--Email form bruh!-->
<div id="email-form">
    <form method="post" name="myemailform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
     
    <p>
    <label for="name">Enter your Name*</label><br>
    <input type="text" id="name" name="name" size="80px" value="<?php htmlentities($name)?>">
    </p>
    <p>
    <label for="email">Email*</label><br>
    <input type="text" id="email" name="email" size="80px" value="<?php htmlentities($visitor_email) ?>">
    </p>
    <p>
    <label for="subject">Subject</label><br>
    <input type="text" id="subject" name="subject" size="80px" value="<?php htmlentities($subject)?>">
    </p>
    <p>
    <label for="message">Message</label><br>
    <textarea name="message" id="message" rows="10" cols="50"><?php htmlentities($message)?></textarea>
    </p>
    <p>
    <img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' ><br>
    <label for='message'>Enter the code above here :</label><br>
    <input id="6_letters_code" name="6_letters_code" type="text"><br>
    <small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
	</p>
    <p>
    <input type="submit" name="submit" class="btn" value="Send">
    </p>
    </form>
    
    <!--client side validation bruh!-->
    <script language="javascript">
	
	var frmValidator= new Validator("myemailform");
	//frmValidator.EnableOnPageErrorDisplaySingleBox();
    //frmValidator.EnableMsgsTogether();
	
	frmValidator.addValidation("name","req","Please Enter your Name");
	frmValidator.addValidation("name","maxlen=40","The name is too long(40 charcters max)");
	
	frmValidator.addValidation("email","req","Please Enter your Email address");
	frmValidator.addValidation("email","email","Enter a valid email format");
	frmValidator.addValidation("email","maxlen=50","Email exceeds Limit(max 50 characters)");
	
	frmValidator.addValidation("subject","req","Subject cannot be empty");
	frmValidator.addValidation("subject","maxlen=50","Subject too long(max 50 characters");
	
	frmValidator.addValidation("message","req","Message cannot be empty");
	frmValidator.addValidation("message","maxlen=2048","Message too long(over 2kb)");
	</script>
    
		<script language='JavaScript' type='text/javascript'>
    function refreshCaptcha()
    {
        var img = document.images['captchaimg'];
        img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
    }
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
            &nbsp;
            <a href="https://www.plus.google.com/xxxx" onClick="window.open(this.href,'GooglePlus','left=20,top=20,width=500,height=500,toolbar=1,resizable=1, scrollbars=1'); return false;"><img src="../icons/google_plus.ico"></a>
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
