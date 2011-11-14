<div class="wrap">
    <h2><?php echo quiz_get_admin_title(); ?></h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>
    <div class="stylizedform" id="poststuff">      
    <form id="quizform"  method="post" action="">
        <fieldset>
        
        <div class="dlabel">Page Title *<br />
        <span class="small">Available Code: <?php quiz_insert_tag_ht('title', 'quiz-title'); ?></span></div>
        <input class="text required" type="text" tabindex="1" name="title" id="title" value="<?php if($question) echo db_to_textfield($question->getTitle()); ?>" />
        
         <div class="dlabel">Question</div>
         <?php 
         $desc="";
         if($question) $desc =$question->getDescription();
         quiz_render_main_editor($desc, "description", 2); ?>
         
         
         <?php
           $type=QuizQuestion::TYPE_SINGLE_ANSWER;
           if($question)
               $type=$question->getType();
           $types="";
           $typem="";
           $k="type" . $type;
           $$k='checked="checked"';
         ?>
         <br />
         <div class="dlabel">Allow Multiple Selections</div>
         <input  tabindex="3"  type="radio" name="type" id="types" <?php echo $types; ?> value="<?php echo QuizQuestion::TYPE_SINGLE_ANSWER; ?>" /> No(Radio Buttons) <input id="typem"  tabindex="4"  <?php echo $typem; ?> type="radio" name="type" value="<?php echo QuizQuestion::TYPE_MULTIPLE_ANSWER; ?>" /> Yes(Checkboxes)
         
         <div class="dlabel">Display Page Title</div>
         <input type="checkbox"  tabindex="5"  value="y" name="display_title" id="display_title" <?php $ch=true; if($question && !$question->shouldDisplayTitle()) $ch=false; if($ch) echo 'checked="checked"'; ?> />
         
         <p>
         <input class="button-primary"  tabindex="6"  type="submit" value="Add question" />
         </p>
         <input type="hidden" name="ftask" value="addquestionsubmit" />
         <input type="hidden" name="quiz_id" value="<?php echo $quiz->getId(); ?>" />
         
        </fieldset>
    </form>
    </div>
</div>
<?php quiz_pageadmin_footer(); ?> 