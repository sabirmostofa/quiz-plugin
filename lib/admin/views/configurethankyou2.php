<div class="wrap">
    <h2><?php echo quiz_get_admin_title(); ?></h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>
    <div class="stylizedform" id="poststuff">      
    <form id="quizform"  method="post" action="">
        <fieldset>
        <div class="dlabel">Page Title *<br />
        <span class="small">Available Codes: <?php quiz_insert_tag_ht('thankyou2_title', 'quiz-title,first-name,last-name,email,result-link,custom-field'); ?></span></div>
        <input class="text required" type="text" tabindex="1"  name="thankyou2_title" id="thankyou2_title" value="<?php echo db_to_textfield($quiz->getThankyou2Title()); ?>" />
        
        <div class="dlabel">Display Page Title</div>
         <input type="checkbox"  tabindex="2"  value="y" name="thankyou2_display_title" id="thankyou2_display_title" <?php $ch=true; if(!$quiz->shouldDisplayThankyou2Title()) $ch=false; if($ch) echo 'checked="checked"'; ?> />
        
         <div class="dlabel">Content<br />
        <span class="small">Available Codes: <?php quiz_insert_tag_ht('thankyou2', 'quiz-title,first-name,last-name,email,result-link,custom-field', true); ?></span></div>
         <?php 
         $desc=$quiz->getThankyou2();
         quiz_render_main_editor($desc, "thankyou2", 3); ?>
         <p>
         <input class="button-primary" type="submit" tabindex="4" value="Save Changes" />
         </p>
         <input type="hidden" name="ftask" value="configurethankyou2" />
         <input type="hidden" name="id" value="<?php echo $quiz->getId(); ?>" />
         
        </fieldset>
    </form>
    </div>
</div>
<?php quiz_pageadmin_footer(); ?> 