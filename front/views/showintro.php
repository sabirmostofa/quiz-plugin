<div class="quiz-container quiz-container-intro">
    <div class="intro-content">
        <?php echo wysiwyg_db_to_html(self::$quiz->getDescription()); ?>
    </div>
    <div class="question-form-container">
    <?php
          $nextAction=self::getNextAction();
        ?>
        <div class="formsubmit">
        <input type="button" onclick="location.href='<?php echo $nextAction["url"]; ?>'" value="Continue" />
        </div>
    </div>
</div>