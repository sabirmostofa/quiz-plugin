<div class="wrap">
    <h2><?php echo quiz_get_admin_title(); ?></h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>
    <div class="stylizedform" id="poststuff">      
    <form id="quizform"  method="post" action="">
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
         <input type="radio" name="status" id="statusp"  tabindex="3"  <?php echo $statusp; ?> value="<?php echo Quiz::PUBLISH; ?>" /> Publish <input id="statusd" <?php echo $statusd; ?> type="radio" name="status"  tabindex="4"  value="<?php echo Quiz::DRAFT; ?>" /> Draft
         
         
         <?php
           $status="n";
           if($quiz)
               $status=$quiz->shouldSkipIntro();
           $status=$status?"y":"n";
           $statusy="";
           $statusn="";
           $k="status" . $status;
           $$k='checked="checked"';
         ?>
         <br />
         <div class="dlabel">Skip Intro</div>
         <input type="radio" name="skip_intro" id="skipintroy"  tabindex="3"  <?php echo $statusy; ?> value="y" /> Yes <input id="skip_intron" <?php echo $statusn; ?> type="radio" name="skip_intro"  tabindex="4"  value="n" /> No

         
         
         <p>
         <input  tabindex="5"  class="button-primary" type="submit" value="Update quiz" />
         </p>
         <input type="hidden" name="ftask" value="editquizsubmit" />
         <input type="hidden" name="id" value="<?php echo $quiz->getId(); ?>" />
        </fieldset>
    </form>
    </div>
</div>
<?php quiz_pageadmin_footer(); ?> 