<div class="wrap">
    <h2><?php echo quiz_get_admin_title(); ?></h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>
    <div class="stylizedform" id="poststuff">      
    <form id="quizform" method="post" action="">
        <fieldset>
        <div class="dlabel">Answer *</div>
        <textarea class="required" tabindex="1"  name="content" id="content"><?php if($answer) echo textarea_db_to_textarea($answer->getContent()); ?></textarea>
        
         <div class="dlabel">Response</div>
         <?php 
         $desc="";
         if($answer) $desc=$answer->getResponse();
         quiz_render_main_editor($desc, "response", 2); ?>
         
         <div class="dlabel">Value *</div>
         <input type="text" class="required digits" tabindex="3"  name="value" id="value" value="<?php if($answer) echo $answer->getValue(); ?>" />
         
         <p>
         <input class="button-primary" type="submit" tabindex="4"  value="Update answer" />
         </p>
         <input type="hidden" name="ftask" value="editanswersubmit" />
         <input type="hidden" name="question_id" value="<?php echo $question->getId(); ?>" />
         <input type="hidden" name="id" value="<?php echo $answer->getId(); ?>" />
        </fieldset>
    </form>
    </div>
</div>
<?php quiz_pageadmin_footer(); ?>
