<?php
//Activate the plugin.
if(isset($_REQUEST['submit'])):
 if(!wp_verify_nonce($_POST['activate_nonce'],'quiz_action_activate_nonce')) die("Nonce verification failed");

 include QUIZ_PATH.'/install.php';

if($response instanceof  WP_Error || $response == null)
    $message='Plugin activatoin server not available now. Please try again later';
else
switch($response['body']):
    case 'proceed':
        $message = 'Plugin has been activated! Please refresh the page to access the plugin' ;
break;
    case 'mail_invalid':
        $message = 'Invalid mail. Your mail address is not registered' ;
break;
    case 'key_invalid':
        $message = 'Invalid activation key. Please check your activation key again' ;
break;
    case 'count_exceeds':
        $message = 'plugin activation limit has been exceeded' ;
break;
endswitch;
 
endif;

$message = isset($message)? $message:'';
?>
<div class="wrap">
    <div id="activate-quiz" style="width:400px;margin:50px auto;text-align: center">
        <h2>Activate Viral Conversion</h2>
    <form action="" method="post">
        <div class="updated"><?php echo $message ?></div>
         Insert Your Email Address 
         <input style="width:70%" type="text" name="email"  style="width:200px"/>
        <br/>
       
        Insert Your Plugin Key 
        <br/>
   
    <input type="text" style="width:70%" name="key"  style="width:200px"/>

    <br/>
    <input class="button-primary" type="submit" name="submit" value="Activate Now"/>
    <?php wp_nonce_field('quiz_action_activate_nonce','activate_nonce'); ?>
    </form>
    </div>
</div>
