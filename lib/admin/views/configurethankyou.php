<div class="wrap">
    <h2><?php echo quiz_get_admin_title(); ?></h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>
    <div class="stylizedform" id="poststuff"> 
    <p>If user has attempted any quiz with same aweber list before and if user is trying to use same email id then, second Thank You Page will be used. <a href="<?php echo quiz_get_admin_url(array("quizid" => $quiz->getId(), "task" => "configurethankyou2")); ?>">Configure Second Thank You Page</a>     </p>
    <form id="quizform"  method="post" action="">
        <fieldset>
        <div class="dlabel">Page Title *<br />
        <span class="small">Available Codes: <?php quiz_insert_tag_ht('thankyou_title', 'quiz-title,first-name,last-name,email,result-link,custom-field'); ?></span></div>
        <input class="text required" type="text" tabindex="1"  name="thankyou_title" id="thankyou_title" value="<?php echo db_to_textfield($quiz->getThankyouTitle()); ?>" />
        
        <div class="dlabel">Display Page Title</div>
         <input type="checkbox"  tabindex="2"  value="y" name="thankyou_display_title" id="thankyou_display_title" <?php $ch=true; if(!$quiz->shouldDisplayThankyouTitle()) $ch=false; if($ch) echo 'checked="checked"'; ?> />
        
         <div class="dlabel">Content<br />
        <span class="small">Available Codes: <?php quiz_insert_tag_ht('thankyou', 'quiz-title,first-name,last-name,email,result-link,custom-field', true); ?></span></div>
         <?php 
         $desc=$quiz->getThankyou();
         quiz_render_main_editor($desc, "thankyou", 3); ?>
         <p>
         <input class="button-primary" type="submit" tabindex="4" value="Save Changes" />
         </p>
         <input type="hidden" name="ftask" value="configurethankyou" />
         <input type="hidden" name="id" value="<?php echo $quiz->getId(); ?>" />
         
        </fieldset>
    </form>
    </div>
</div>
<?php quiz_pageadmin_footer(); ?> 