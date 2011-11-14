<div class="wrap">
    <h2>Add Quiz</h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>
    <div class="stylizedform" id="poststuff">      
    <form  id="quizform" method="post" action="">
        <fieldset>
        <legend>Quiz Details</legend>
        
        <div class="dlabel">Title *</div>
        <input class="text required" type="text" tabindex="1"  name="title" id="title" value="<?php if($quiz) echo db_to_textfield($quiz->getTitle()); ?>" />
        
         <div class="dlabel">Description</div>
         <?php 
         $desc="";
         if($quiz) $desc = $quiz->getDescription();
         quiz_render_main_editor($desc, "description", 2); ?>
         
         <?php
           $status="p";
           if($quiz)
               $status=$quiz->getStatus();
           $statusp="";
           $statusd="";
           $k="status" . $status;
           $$k='checked="checked"';
         ?>
         <br />
         <div class="dlabel">Status</div>
         <input type="radio" name="status"  tabindex="3"  id="statusp" <?php echo $statusp; ?> value="<?php echo Quiz::PUBLISH; ?>" /> Publish <input id="statusd" <?php echo $statusd; ?> type="radio" name="status" value="<?php echo Quiz::DRAFT; ?>"  tabindex="4"  /> Draft
         
         
         
         <p>
         <input class="button-primary" type="submit"  tabindex="5"  value="Add quiz" />
         </p>
         <input type="hidden" name="ftask" value="addquizsubmit" />
        </fieldset>
    </form>
    </div>
</div>
<?php quiz_pageadmin_footer(); ?> 