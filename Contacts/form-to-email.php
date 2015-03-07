<?php

$name=$_POST['Name'];
$visitor_email=$_POST['Email'];
$subject=$_POST['Subject'];
$message=$_POST['Message'];

//validate Bruh! :-(
if(empty($name)||empty($visitor_email)||empty($subject)||empty($message)){
echo"error";
header('Location: index.html');
exit;
}

$email_from='info@twin-p.com'; //my email here Bruh! :-)
$email_subject="New Email Received";
$email_message="You have Received a new mail from $name .\n".
                "Email Address: $visitor_email . \n".
				"Subject: $subject \n".
				"Message: \n $message";

$to="info@twin-p.com"; //my email too bruh :-)
$headers="Sent From: $email_from  \r\n";
$headers .="Reply To: $visitor_email \r\n";

if(IsInjected($visitor_email))
{
echo"Bad Visitor Email Value";
exit;
}
elseif(IsInjected($email_from))
{
echo"Bad Host Email Value";
exit;
}

else
{
mail($to,$email_subject,$email_message,$headers);

header('Location: index.html');

}

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