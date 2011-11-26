<div class="quiz-container quiz-container-section">
    <div class="section-content">
        <?php echo wysiwyg_db_to_html(self::$section->getDescription()); ?>
    </div>
    <div class="question-form-container">
        <?php
          $nextAction=self::getNextAction();
        ?>
        <div class="formsubmit">
        <center><input type="button" onclick="location.href='<?php echo $nextAction["url"]; ?>'" value="Continue" /></center>
        </div>
    </div>
</div>