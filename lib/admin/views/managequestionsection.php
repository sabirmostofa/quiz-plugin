<div class="wrap">
    <h2>Manage Questions/Sections</h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?> 
    <span class="small">Drag and adjust the sequence as per your need</span><br />
    <div id="qseq-container">
        <div class="qseq-header">
            <div class="qseq-header-unit qseq-header-unit-id">ID</div>
            <div class="qseq-header-unit qseq-header-unit-type">Type</div>
            <div class="qseq-header-unit qseq-header-unit-title">Title</div>
            <div class="qseq-header-unit qseq-header-unit-tasks">Tasks</div>
            <div class="clear"></div>
        </div>
        <div id="qseq-drag-container">
        <?php if($sequence): ?>
            <?php 
            $k=0;
            foreach($sequence as $seq): 
            $isQuestion=get_class($seq) == "QuizQuestion";
            ?>
            <div id="qseq-drag-unit-<?php echo $isQuestion?"q":"s"; echo $seq->getId(); ?>" class="qseq-drag-unit<?php if($k%2 == 0) echo " qseq-drag-unit-alternate"; ?>">
                <div class="qseq-header-unit qseq-header-unit-id">
                    <?php echo $seq->getId(); ?>
                </div>
                <div class="qseq-header-unit qseq-header-unit-type"><?php if($isQuestion) echo "Question"; else echo "Section"; ?></div>
                <div class="qseq-header-unit qseq-header-unit-title">
                    <?php echo $seq->getTitle(); ?>
                </div>
                <div class="qseq-header-unit qseq-header-unit-tasks">
                    <?php if($isQuestion): ?>
                        <a href="<?php echo quiz_get_admin_url(array("task" => "editquestion" , "questionid" => $seq->getId() , "quizid" => $quiz->getId())); ?>" onclick="return quiz_moving_away();">Edit</a> | <a href="<?php echo quiz_get_admin_url(array("task" => "managequestionsection", "quizid" => $quiz->getId(), "deletequestion" => $seq->getId())); ?>" onclick="return quiz_delete_confirm();">Delete</a>
                    <?php else: ?>
                        <a href="<?php echo quiz_get_admin_url(array("task" => "editsection" , "sectionid" => $seq->getId() , "quizid" => $quiz->getId())); ?>" onclick="return quiz_moving_away();">Edit</a> | <a href="<?php echo quiz_get_admin_url(array("task" => "managequestionsection", "quizid" => $quiz->getId(), "deletesection" => $seq->getId())); ?>" onclick="return quiz_delete_confirm();">Delete</a>   
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>    
            <?php 
            $k++;
            endforeach; ?>
        <?php else: ?>
            No results found
        <?php endif; ?>
        </div>
        <div class="qseq-header">
            <div class="qseq-header-unit qseq-header-unit-id">ID</div>
            <div class="qseq-header-unit qseq-header-unit-type">Type</div>
            <div class="qseq-header-unit qseq-header-unit-title">Title</div>
            <div class="qseq-header-unit qseq-header-unit-tasks">Tasks</div>
            <div class="clear"></div>
        </div>
    </div>
    <?php if($sequence): ?>
    <form method="post" action="">
        <?php foreach($sequence as $seq): ?>
            <input type="hidden" name="quizsequence<?php if(get_class($seq) == "QuizQuestion") echo "q"; else echo "s";  echo $seq->getId(); ?>" id="quizsequence<?php if(get_class($seq) == "QuizQuestion") echo "q"; else echo "s";  echo $seq->getId(); ?>" value="<?php echo $seq->getDisplayOrder(); ?>" />
        <?php endforeach; ?>
        <p>
         <input class="button-primary" type="submit" value="Update sequence" />
         </p>
         
         <input type="hidden" name="ftask" value="managequestionsectionsubmit" />
         <input type="hidden" name="quiz_id" value="<?php echo $quiz->getId(); ?>" /> 
         
    </form>
    <?php endif; ?>
</div>
<?php quiz_pageadmin_footer(); ?> 
<script type="text/javascript">
var quiz_current_sort_element=false;
(function($) {
   $(document).ready(function() {
       $("#qseq-drag-container").sortable({ 
                containment: jQuery("#qseq-drag-container"), 
                revert : 100,
                start: function (event, ui) {
                    quiz_current_sort_element=ui.item;
                    
                    // change style for drag
                    quiz_current_sort_element.addClass("qseq-drag-unit-dragging");
                },
                stop: function (event, ui) {
                    // revert back the style
                    quiz_current_sort_element.removeClass("qseq-drag-unit-dragging");
                    
                    // adjust all alternate style, plus adjust all display order
                    quiz_update_sequence(); 
                },
                change: function (event, ui) {
                    // adjust all alternate style, plus adjust all display order
                    quiz_update_sequence(); 
                } 
       });
       
  }); // dready
})(jQuery);
var quiz_update_seq_kpos=0;
var quiz_update_seq_changed=false;
function quiz_update_sequence() {
   quiz_update_seq_kpos=0;
   jQuery(".qseq-drag-unit").each(function(){
       var unit=jQuery(this);
       if(quiz_update_seq_kpos%2 == 0) {
           unit.addClass("qseq-drag-unit-alternate"); 
       } 
       else {
            unit.removeClass("qseq-drag-unit-alternate");   
       }
       quiz_update_seq_kpos++;
       var unitId=unit.attr("id");
       var kunitId=unitId.replace("qseq-drag-unit-" , "");
       var hunitId="quizsequence" + kunitId;
       if(jQuery("#" + hunitId).val() != quiz_update_seq_kpos) {
           quiz_update_seq_changed=true;    
       }
       jQuery("#" + hunitId).val(quiz_update_seq_kpos);
       
   }); 
}
function quiz_moving_away() {
    if(quiz_update_seq_changed) {
        return confirm('You have changed the sequence, if you will move away without saving then all changes will be lost. Are you sure you want to proceed?');   
    }
    return true;
}
function quiz_delete_confirm() {
    return confirm('Are you sure you want to delete this item?');
}
</script>