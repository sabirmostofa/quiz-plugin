<div class="wrap">
    <h2><?php echo quiz_get_admin_title(); ?> 
        <?php if($question):
            $vlink="";
            $sequence=$quiz->getSequence();
            $seqLen=count($sequence);
            if($seqLen) {
                $pbo=0;
                foreach($sequence as $seq) {
                    $pbo++;
                    $pborder=$pbo;
                    if($pborder < 10)
                        $pborder="0" . $pborder;
                    if(get_class($seq) == "QuizQuestion" && $seq->getId() == $question->getId()) {
                        $vlink=get_permalink($quiz->getPageId()) . $pborder . '/question/';
                    }
                }
            }
            ?>
        <a class="button add-new-h2" href="<?php echo $vlink; ?>" target="_blank" >View</a>
        <?php endif; ?>
    </h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>
    <div class="stylizedform" id="poststuff">      
    <form id="quizform"  method="post" action="">
        <fieldset>
         <div class="dlabel">Page Title *<br />
        <span class="small">Available Code: <?php quiz_insert_tag_ht('title', 'quiz-title'); ?></span></div>
        <input  class="text required" type="text" tabindex="1" name="title" id="title" value="<?php if($question) echo db_to_textfield($question->getTitle()); ?>" />
        
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
         <input type="radio" name="type" tabindex="3"  id="types" <?php echo $types; ?> value="<?php echo QuizQuestion::TYPE_SINGLE_ANSWER; ?>" /> No(Radio Buttons) <input id="typem" <?php echo $typem; ?> type="radio" tabindex="4"  name="type" value="<?php echo QuizQuestion::TYPE_MULTIPLE_ANSWER; ?>" /> Yes(Checkboxes)
         
         <div class="dlabel">Display Page Title</div>
         <input type="checkbox"  tabindex="5"  value="y" name="display_title" id="display_title" <?php $ch=true; if($question && !$question->shouldDisplayTitle()) $ch=false; if($ch) echo 'checked="checked"'; ?> />
         
         <p>
         <input class="button-primary" tabindex="6"  type="submit" value="Update question" />
         </p>
         <input type="hidden" name="ftask" value="editquestionsubmit" />
         <input type="hidden" name="quiz_id" value="<?php echo $quiz->getId(); ?>" /> 
         <input type="hidden" name="id" value="<?php echo $question->getId(); ?>" />
        </fieldset>
    </form>

        <?php if($question): ?>
        <fieldset>
        <legend>Answers
        <?php 
        $vlink=quiz_get_admin_url(array("task" => "addanswer", "quizid" => $_GET["quizid"], "questionid" => $question->getId(), "ret" => "question"));
        ?>
        <a class="button add-new-h2" href="<?php echo $vlink; ?>">Add Answer</a>
        </legend>
        <table class="widefat">
        <thead>
            <tr>
                <th>ID</th>
                <th>Content</th>
                <th>Response</th>
                <th>Value</th>
                <th>Tasks</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $answers=$question->getAnswers();
        $i=0;
        if($answers && count($answers)):
        foreach($answers as $answer): ?>
        <tr <?php if($i % 2 == 0) echo ' class="alternate" '; ?>>
            <td valign="top"><?php echo $answer->getId(); ?></td>
            <td valign="top"><?php $c=$answer->getContent(); echo substr(textarea_db_to_html_source($c) , 0 , 30); ?>...</td>
            <td valign="top"><?php $c=$answer->getResponse(); echo substr(textarea_db_to_html_source($c) , 0 , 40); ?>...</td>
            <td valign="top"><?php echo $answer->getValue(); ?></td>
            <td valign="top"><a href="<?php echo quiz_get_admin_url(array("task" => "editanswer" , "answerid" => $answer->getId(), "questionid" => $question->getId() , "quizid" => $quiz->getId(), "ret" => "question")); ?>" >Edit</a> | <a href="<?php echo quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $quiz->getId(), "deleteanswer" => $answer->getId(), "ret" => "question")); ?>" onclick="return quiz_delete_confirm();">Delete</a></td>
        </tr>
        <?php
        $i++;
        endforeach;
        else: ?>
        <tr><td  colspan="5">No results found</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
        </fieldset>
        <?php endif; ?>
    </div>
</div>
<?php quiz_pageadmin_footer(); ?>
<script type="text/javascript">
function quiz_delete_confirm() {
    return confirm('Are you sure you want to delete this item?');
}
</script>