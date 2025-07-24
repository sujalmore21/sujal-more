<div class="eP emptySpace90 emptySpace-xs30"></div>
<?php 
// Google reCAPTCHA API key configuration 
$siteKey     = '6LcqKVorAAAAALekGojw9zcEJ8uDoTk26rfvNe25'; 
$secretKey   = '6LcqKVorAAAAAMvvBIUEvLtLoWOUQWE_O_8Ujzqu'; 

// Email configuration 
$toEmail     = 'web.relligio@gmail.com'; 
$fromName    = 'SME Capital'; 
$formEmail   = 'social.relligio@gmail.com'; 

$postData = $statusMsg = $valErr = ''; 
$status = 'error'; 

// If the form is submitted 
if(isset($_POST['submit'])){
    // Get the submitted form data 
    $postData = $_POST; 
    $name = trim($_POST['name']); 
    $email = trim($_POST['email']); 
    $msg_subject = trim($_POST['msg_subject']); 
    $message = trim($_POST['message']); 

    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){ 
        // Verify the reCAPTCHA response 
        $verifyResponse = file_get_contents(
            'https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']
        ); 
             
        // Decode json data 
        $responseData = json_decode($verifyResponse); 

        // If reCAPTCHA response is valid 
        if($responseData->success){ 
            $subject = 'New contact request submitted'; 
            $htmlContent = " 
                <h2>Contact Request Details</h2> 
                <p><b>Name: </b>".$name."</p> 
                <p><b>Email: </b>".$email."</p> 
                <p><b>Mobile Number: </b>".$msg_subject."</p> 
                <p><b>Message: </b>".$message."</p> 
            ";

            $headers = "MIME-Version: 1.0\r\n"; 
            $headers .= "Content-type:text/html;charset=UTF-8\r\n"; 
            $headers .= 'From: '.$name.' <'.$email.'>' . "\r\n"; 

            // Send email 
            @mail($toEmail, $subject, $htmlContent, $headers); 

            $status = 'success'; 
            $statusMsg = 'Thank you! Your contact request has submitted successfully, we will get back to you soon.'; 
            $postData = '';

            // ✅ Redirect to EzBuss payment page (after successful CAPTCHA + mail)
            echo "<script>window.location.href='https://ezbuss.in/pay/YOUR-PAYMENT-LINK?amount=99&redirect_url=https://yourdomain.com/thank-you';</script>";
            exit;
        } else { 
            $statusMsg = 'Robot verification failed, please try again.'; 
        } 
    } else { 
        $statusMsg = 'Please check on the reCAPTCHA box.'; 
    }
}
?>

<div class="col-xs-12 col-sm-6">
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="contactForm form row">
        <div class="col col-xs-12">
            <input class="simple-input form-control" name="name" value="<?php echo !empty($postData['name'])?$postData['name']:''; ?>" type="text" required placeholder="Name">
        </div>
        <div class="col col-xs-12">
            <input class="simple-input form-control" name="email" value="<?php echo !empty($postData['email'])?$postData['email']:''; ?>" type="email" required placeholder="Email">
        </div>
        <div class="col col-xs-12">
            <input class="simple-input form-control" name="msg_subject" value="<?php echo !empty($postData['msg_subject'])?$postData['msg_subject']:''; ?>" type="text" required placeholder="Subject">
        </div>
        <div class="col col-xs-12">
            <textarea class="simple-input form-control" name="message" required placeholder="Message"><?php echo !empty($postData['message'])?$postData['message']:''; ?></textarea>
        </div>
        <div class="col col-xs-12">
            <div class="form-input">
                <!-- Google reCAPTCHA box -->
                <div class="g-recaptcha" data-sitekey="6LddZSopAAAAAKDj5qzpKFDrUtjRfyRps8l4pTuq"></div>
            </div>
        </div>
        <div class="col col-xs-12">
            <button type="submit" class="button btnStyle5 btnSize4" name="submit">Submit now</button>
        </div>
    </form>
</div>

<div class="emptySpace20"></div>

<?php if(!empty($statusMsg)){ ?>
    <div class="status-msg <?php echo $status; ?>"><?php echo $statusMsg; ?></div>
<?php } ?>

<!-- Add this before </body> -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
