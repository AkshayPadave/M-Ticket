<?php
include 'connect.php' ;


session_start();
if ($_SESSION['log'] == '')
{
    header("location:index.php");
}
include 'header.php';
?>
<style>
    #font{
      font-family: Montserrat, sans-serif;
     font-size: 18px !important;

}</style>

 <link rel='stylesheet' href='index.css'>


           
           
           
           <script type="text/javascript">
        
           </script>
          

</head>
 <?php 
   $result = mysqli_query($connect , "SELECT * FROM `transactions` WHERE `email`='".$_SESSION['email']."' ORDER BY `T_No.` DESC LIMIT 1");
  while ($row = mysqli_fetch_assoc($result)): 
    $tno = $row["T_No."];
    $_SESSION['tno']= $tno ;
  ?>




 <?php endwhile; ?>

<?php
include 'connect.php';
session_start();
if ($_SESSION['log'] == '')
{
    header("location:sindex.php");
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/autoload.php';






//Create a new PHPMailer instance
$mail = new PHPMailer();

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging

$mail->SMTPDebug = 2;

//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';

//Set the hostname of the mail server
$mail->Host = 'email-smtp.us-east-1.amazonaws.com';

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
// I tried PORT 25, 465 too
$mail->Port = 587;

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "ABCD";

//Password to use for SMTP authentication
$mail->Password = "ABCD";

//Set who the message is to be sent from
$mail->setFrom('prajwalsutar0@gmail.com', 'M-Ticket');

//Set who the message is to be sent to
$mail->addAddress($_SESSION[email], 'receiver');

//Set the subject line
$mail->Subject = 'Ticket Booking Details';


$mail->Body = "You  Have Sucessfully Booked Ticket For $_SESSION[source] to $_SESSION[dest] , Thank You for using M-Ticket Booking Portal";
//Replace the plain text body with one created manually
$mail->AltBody = "You  Have Sucessfully Booked Ticket For $_SESSION[source] to $_SESSION[source]";

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}




require 'vendor/autoload.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);

$params = array(
    'credentials' => array(
        'key' => 'ABCD',
        'secret' => 'ABCD',
    ),
  'region'  => 'eu-west-1',
    'version' => 'latest',
);
$sns = new \Aws\Sns\SnsClient($params);

$args = array(
    "MessageAttributes" => [
                'AWS.SNS.SMS.SenderID' => [
                    'DataType' => 'String',
                    'StringValue' => 'sms'
                ],
                'AWS.SNS.SMS.SMSType' => [
                    'DataType' => 'String',
                    'StringValue' => 'Transactional'
                ]
            ],
    "Message" => "You  Have Sucessfully Booked Ticket For $_SESSION[source] to $_SESSION[dest] and booking id is $_SESSION[tno], Thank You for using M-Ticket Booking Portal",
    "PhoneNumber" => "+91$_SESSION[phone]"
);


$result = $sns->publish($args);
echo "<pre>";
var_dump($result);
echo "</pre>";
exit;
header("location: bookdone.php");



?>
<?php include 'footer.php';
?> 

 </html>

