<?php
//Activate the plugin.
if(isset($_REQUEST['submit'])):
 if(!wp_verify_nonce($_POST['activate_nonce'],'quiz_action_activate_nonce')) die("Nonce verification failed");
ob_start();  
 include QUIZ_PATH.'/install.php';
 $content=ob_get_contents();
 ob_end_clean();
if(strlen($content) >5)$message=$content;
else
    $message ='Activation error. Please check your email and key again';
endif;

$message = isset($message)? $message:'';
?>
<div class="wrap">
    <div id="activate-quiz" style="width:400px;margin:50px auto;text-align: center">
        <h2>Activate viralconversion</h2>
    <form action="" method="post">
        <div class="updated"><?php echo $message ?></div>
         Insert Your Email address 
         <input style="width:70%" type="text" name="email"  style="width:200px"/>
        <br/>
       
        Insert Your plugin key 
        <br/>
   
    <input type="text" style="width:70%" name="key"  style="width:200px"/>

    <br/>
    <input class="button-primary" type="submit" name="submit" value="Activate Now"/>
    <?php wp_nonce_field('quiz_action_activate_nonce','activate_nonce'); ?>
    </form>
    </div>
</div>