<div class="quiz-container quiz-container-question">
    <div class="question-content">
        <?php echo wysiwyg_db_to_html(self::$question->getDescription()); ?>
        <div class="clear"></div>
    </div>
    <div class="question-form-container">
        <?php
          $singleAnswer=self::$question->isSingleAnswerType();
          $nextAction=self::getNextAction();
          $answers=self::$question->getAnswers();
          
          //skipping optin

        ?>
        <?php if($answers): ?>
        <form method="post" action="<?php if($nextAction) echo $nextAction["url"]; ?>">
            <table class="quiz-layout-table">
            <?php foreach($answers as $answer): ?>
            <tr valign="top" align="left">
                <td valign="top" align="left" width="20">
                    <input type="<?php if($singleAnswer) echo "radio"; else echo "checkbox"; ?>" name="answer<?php if(!$singleAnswer) echo $answer->getId(); ?>" id="answer<?php echo $answer->getId(); ?>" value="y<?php echo $answer->getId(); ?>"  />
                </td>
                <td valign="top" align="left"><div class="fieldlabel"><?php echo textarea_db_to_html($answer->getContent()); ?></div></td>
            </tr>
            <?php endforeach; ?>
            <tr valign="top" align="left" >
                <td valign="top" align="left"  width="20" > </td>
                <td valign="top" align="left" ><div class="formsubmit">
                <input id="submitbutton" type="submit" value="Continue" />
            </div></td>
            </tr>
            </table>
            <input type="hidden" name="formtask" value="<?php echo self::FORMTASK_HANDLEQUESTION; ?>" />
            <input type="hidden" name="oldtask" value="<?php echo self::TASK_SHOWQUESTION; ?>" />
            <input type="hidden" name="oldquestionid" value="<?php echo self::$question->getId(); ?>" />
            
        </form>
        <?php else: ?>
        <?php endif; ?>
    </div>
</div>
<?php if($singleAnswer): ?>
<script type="text/javascript">
    jQuery(document).ready(function(){
      jQuery('#submitbutton').click(function() {
        if (!jQuery("input[@name='answer']:checked").val()) {
            alert("Please provide the answer");
            return false;
        }
        else {
          return true;
        }
      });
    });
</script>
<?php endif; ?>