    <div id="qseq-container">
        <div class="qseq-header">
            <div class="qseq-header-unit qseq-header-unit-icon"><div class="qseq-header-icon qseq-header-icon-plus" id="qseq-header-icon-all"  onclick="return quiz_toggle_answers_icon_all(this);"></div></div>
            <div class="qseq-header-unit qseq-header-unit-id">ID</div>
            <div class="qseq-header-unit qseq-header-unit-order">Order</div>
            <div class="qseq-header-unit qseq-header-unit-type">Type</div>
            <div class="qseq-header-unit qseq-header-unit-title">Title/Answer</div>
            <div class="qseq-header-unit qseq-header-unit-content">Content/Response</div>
            <div class="qseq-header-unit qseq-header-unit-value">Value</div>
            <div class="qseq-header-unit qseq-header-unit-answers">Answers</div>
            <div class="qseq-header-unit qseq-header-unit-tasks" align="center">Tasks</div>
            <div class="clear"></div>
        </div>
        <div id="qseq-drag-container">
        <?php
        $sequence=$quiz->getSequence();
         if($sequence): ?>
            <?php 
            $k=0;
            $dorder=1;
            foreach($sequence as $seq): 
            $isQuestion=get_class($seq) == "QuizQuestion";
            $answers=false;
            if($isQuestion)
                $answers=$seq->getAnswers();
            
            $pdorder=$dorder;
            if($dorder<10)
                $pdorder="0" . $dorder;
            ?>            
            <div id="qseq-drag-unit-<?php echo $isQuestion?"q":"s"; echo $seq->getId(); ?>" class="qseq-drag-unit<?php if($k%2 == 0) echo " qseq-drag-unit-alternate"; ?>">
                <div class="qseq-header-unit qseq-header-unit-icon">
                <?php if($answers): ?><div class="qseq-header-icon qseq-header-icon-plus" id="qseq-header-icon-<?php echo $isQuestion?"q":"s"; echo $seq->getId(); ?>"  onclick="return quiz_toggle_answers_icon(this);"></div><?php endif; ?>
                </div>
                <div class="qseq-header-unit qseq-header-unit-id">
                    <?php echo $seq->getId(); ?>
                </div>
                <div class="qseq-header-unit qseq-header-unit-order" id="qseq-header-unit-order-<?php echo $isQuestion?"q":"s"; echo $seq->getId(); ?>"><?php echo $pdorder; ?></div>
                <div class="qseq-header-unit qseq-header-unit-type"><?php if($isQuestion) echo "Question"; else echo "Section"; ?></div>
                <div class="qseq-header-unit qseq-header-unit-title">
                    <?php $title=$seq->getTitle(); echo substr(stripslashes($title) , 0 , 30); ?>...
                </div>
                <div class="qseq-header-unit qseq-header-unit-content">
                    <?php $desc=$seq->getDescription(); echo substr(wysiwyg_db_to_html_source($desc) , 0 , 40); ?>...
                </div>
                <div class="qseq-header-unit qseq-header-unit-value"></div>
                <div class="qseq-header-unit qseq-header-unit-answers"><?php if($isQuestion) {
                    if($answers) echo count($answers); else echo "0";
                } ?></div>
                <div class="qseq-header-unit qseq-header-unit-tasks" align="right">
                    <?php if($isQuestion): ?>
                        <a href="<?php echo quiz_get_admin_url(array("task" => "addanswer", "quizid" => $_GET["quizid"], "questionid" => $seq->getId())); ?>">Add Answer</a>  | <a id="quiz-view-link-q<?php echo $seq->getId(); ?>" href="<?php echo get_permalink($quiz->getPageId()); echo $pdorder; ?>/question"  target="_blank" >View</a> | <a href="<?php echo quiz_get_admin_url(array("task" => "editquestion" , "questionid" => $seq->getId() , "quizid" => $quiz->getId())); ?>" onclick="return quiz_moving_away();">Edit</a> | <a href="<?php echo quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $quiz->getId(), "deletequestion" => $seq->getId())); ?>" onclick="return quiz_delete_confirm();">Delete</a>
                    <?php else: ?>
                          <a  id="quiz-view-link-s<?php echo $seq->getId(); ?>"  href="<?php echo get_permalink($quiz->getPageId()); echo $pdorder; ?>/section"  target="_blank" >View</a> | <a href="<?php echo quiz_get_admin_url(array("task" => "editsection" , "sectionid" => $seq->getId() , "quizid" => $quiz->getId())); ?>" onclick="return quiz_moving_away();">Edit</a> | <a href="<?php echo quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $quiz->getId(), "deletesection" => $seq->getId())); ?>" onclick="return quiz_delete_confirm();">Delete</a>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>                    
                <?php if($answers): ?>
                    <div class="qseq-answers-container" id="qseq-answers-container-q<?php echo $seq->getId(); ?>" style="display: none">
                    <?php foreach($answers as $answer): ?>
                            <div style="border-top:dashed 1px #ccc; height: 1px"></div>
                            <div class="qseq-header-unit qseq-header-unit-icon"></div>
                            <div class="qseq-header-unit qseq-header-unit-id"><?php echo $answer->getId(); ?></div>
                            <div class="qseq-header-unit qseq-header-unit-order"></div>  
                            <div class="qseq-header-unit qseq-header-unit-type">Answer</div>
                            <div class="qseq-header-unit qseq-header-unit-title"><?php $c=$answer->getContent(); echo substr(textarea_db_to_html_source($c) , 0 , 30); ?>...</div>
                            <div class="qseq-header-unit qseq-header-unit-content"><?php $c=$answer->getResponse(); echo substr(textarea_db_to_html_source($c) , 0 , 40); ?>...</div>
                            <div class="qseq-header-unit qseq-header-unit-value"><?php echo $answer->getValue(); ?></div>
                            <div class="qseq-header-unit qseq-header-unit-answers"></div>
                            <div class="qseq-header-unit qseq-header-unit-tasks" align="right">
                            <a href="<?php echo quiz_get_admin_url(array("task" => "editanswer" , "answerid" => $answer->getId(), "questionid" => $seq->getId() , "quizid" => $quiz->getId())); ?>" onclick="return quiz_moving_away();">Edit</a> | <a href="<?php echo quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $quiz->getId(), "deleteanswer" => $answer->getId())); ?>" onclick="return quiz_delete_confirm();">Delete</a>
                            </div>
                            <div class="clear"></div>
                    <?php 
                    
                    endforeach; ?>
                    </div>
                <?php endif; ?>                                                                                  
            </div>    
            <?php 
            $k++;
            $dorder++;
            endforeach; ?>
        <?php else: ?>
            No results found
        <?php endif; ?>
        </div>
    </div>
    <?php if($sequence): ?>
    <form method="post" action="">
        <?php foreach($sequence as $seq): ?>
            <input type="hidden" name="quizsequence<?php if(get_class($seq) == "QuizQuestion") echo "q"; else echo "s";  echo $seq->getId(); ?>" id="quizsequence<?php if(get_class($seq) == "QuizQuestion") echo "q"; else echo "s";  echo $seq->getId(); ?>" value="<?php echo $seq->getDisplayOrder(); ?>" />
        <?php endforeach; ?>
         <input type="hidden" name="quiz_id" value="<?php echo $quiz->getId(); ?>" />  
    </form>
    <?php endif; ?>
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
                    quiz_update_seq_cc=false;
                },
                stop: function (event, ui) {
                    // revert back the style
                    quiz_current_sort_element.removeClass("qseq-drag-unit-dragging");
                    
                    // adjust all alternate style, plus adjust all display order
                    quiz_update_sequence(); 
                    quiz_submit_seq_form(); 
                },
                change: function (event, ui) {
                    // adjust all alternate style, plus adjust all display order
                    quiz_update_sequence(); 
                    quiz_update_seq_cc=true;
                } 
       });
       
  }); // dready
})(jQuery);
var quiz_update_seq_kpos=0;
var quiz_update_seq_changed=false;
var quiz_update_seq_cc=false;
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
       var pdorder=0+quiz_update_seq_kpos;
       if(pdorder<10)
          pdorder="0"+pdorder;
       var unitId=unit.attr("id");
       var kunitId=unitId.replace("qseq-drag-unit-" , "");
       var hunitId="quizsequence" + kunitId;
       if(jQuery("#" + hunitId).val() != quiz_update_seq_kpos) {
           quiz_update_seq_changed=true;    
       }
       jQuery("#" + hunitId).val(quiz_update_seq_kpos);
       
       // update order html
       jQuery("#qseq-header-unit-order-"+kunitId).html(pdorder);
       
       // update view link
       var atag=jQuery("#quiz-view-link-"+kunitId);
       if(kunitId.charAt(0) == "q") {
            // question  
            atag.attr("href" , '<?php echo get_permalink($quiz->getPageId()); ?>' +pdorder + '/question'); 
       }
       else {
            // section   
            atag.attr("href" , '<?php echo get_permalink($quiz->getPageId()); ?>' +pdorder + '/section');
       }
       
   }); 
}
function quiz_moving_away() {
    return true;
    if(quiz_update_seq_changed) {
        return confirm('You have changed the sequence, if you will move away without saving then all changes will be lost. Are you sure you want to proceed?');   
    }
    return true;
}
function quiz_toggle_answers_icon(div) {
    div=jQuery(div);
    var kid=div.attr("id");
    kid=kid.replace("qseq-header-icon-" , "");
    var ac=jQuery("#qseq-answers-container-" +kid);
    if(div.hasClass("qseq-header-icon-minus")) {
        // hide
        ac.hide();
        div.removeClass("qseq-header-icon-minus");
        div.addClass("qseq-header-icon-plus");
    }
    else {
        // show
        ac.show();
        div.removeClass("qseq-header-icon-plus");
        div.addClass("qseq-header-icon-minus");
    }
}
function quiz_toggle_answers_icon_all(div) {
    div=jQuery(div);
    if(div.hasClass("qseq-header-icon-minus")) {
        // hide all
        jQuery(".qseq-header-icon").each(function () {
           jQuery(this).removeClass("qseq-header-icon-minus");
           jQuery(this).addClass("qseq-header-icon-plus");
        
           var id=jQuery(this).attr("id");
           id=id.replace("qseq-header-icon-" , "");
           if(id != "all") {
              jQuery("#qseq-answers-container-" +id).hide();  
           } 
        });
        
        
    }
    else {
        // show all
        jQuery(".qseq-header-icon").each(function () {
            jQuery(this).removeClass("qseq-header-icon-plus");
            jQuery(this).addClass("qseq-header-icon-minus");
            
            var id=jQuery(this).attr("id");
           id=id.replace("qseq-header-icon-" , "");
           if(id != "all") {
              jQuery("#qseq-answers-container-" +id).show();  
           } 
        });
        
        
        
    }   
}
function quiz_submit_seq_form() {
    if(!quiz_update_seq_cc)
        return;
    var fdata={};
     
    quiz_update_seq_kpos=0;       
    jQuery(".qseq-drag-unit").each(function(){
       var unit=jQuery(this);
       quiz_update_seq_kpos++;
       var unitId=unit.attr("id");
       var kunitId=unitId.replace("qseq-drag-unit-" , "");
       var hunitId="quizsequence" + kunitId;
       fdata[hunitId]=jQuery("#" + hunitId).val();
       
   });
    
    fdata.action="quiz_sequence_handle";
    fdata.quiz_id=<?php echo $quiz->getId(); ?>;
    jQuery.ajax({
       type: "POST",
       url: "<?php bloginfo( 'url' ); ?>/wp-admin/admin-ajax.php",
       data: fdata,
       success: function(d){
           // d = 0 => fail, d = 1 => success
           jQuery("#qseq-loading").hide();
           jQuery("#qseq-loading-msg").html(d);
           jQuery("#qseq-loading-msg").show();
           jQuery("#qseq-loading-msg").fadeOut(5000);
       }

    });  
    
    jQuery("#qseq-loading").show();
}
</script>