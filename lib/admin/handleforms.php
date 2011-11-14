<?php
function quiz_handle_forms() {
    quiz_handle_deletes();
    quiz_handle_smalltasks();
    $ftask=false;
    if(isset($_POST["ftask"]))
        $ftask=$_POST["ftask"];
    if(!$ftask)
        return;
    
    $fn="quiz_handle_form_" . $ftask;
    $fn();   
}
function quiz_handle_smalltasks() {
    //publishquiz   
    if(isset($_GET["publishquiz"])) {
        $quizId=intval($_GET["publishquiz"]);
        try {
            $quiz=new Quiz($quizId);
            if($quiz->isDraft())
            {
                $quiz->setData("status" , Quiz::PUBLISH);
                $quiz->save();
            }
            QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_QUIZ_PUBLISHED , "Quiz published successfully" , false, QuizMessages::SUCCESS , false));
        }
        catch(QuizException $qe) {
            
        }   
    }
    //unpublishquiz
    if(isset($_GET["unpublishquiz"])) {
        $quizId=intval($_GET["unpublishquiz"]);
        try {
            $quiz=new Quiz($quizId);
            if($quiz->isPublished())
            {
                $quiz->setData("status" , Quiz::DRAFT);
                $quiz->save();
            }
            QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_QUIZ_UNPUBLISHED , "Quiz unpublished successfully" , false, QuizMessages::SUCCESS , false));
        }
        catch(QuizException $qe) {
            
        }   
    }
    if(isset($_GET["duplicatequiz"])) {
        $quizId=intval($_GET["duplicatequiz"]);
        try {
            $quiz=new Quiz($quizId);

            $q=$quiz;
            $s=$q->getSequence();
            $b=$q->getBadges();
            $a=$q->getAweber();
            $l=$q->getLayout();
            
            // duplicate this quiz
            $q->setData('id' , 0);
            $q->save();
            // create new quiz and related quiz page
            $status="draft";
            if($q->isPublished())
                $status="publish";
            $postId=quiz_add_page($q->getTitle() , "Page for quiz [" . $q->getId() . "]" , $status);
            if($postId) {
                // added a page, update quiz
                $q->setData("page_id" , $postId);
                $q->save();
                
            }
            // duplicate all sections and questions
            
            if(is_array($s) && count($s)) {
                foreach($s as $seq) {
                    if(get_class($seq) == "QuizQuestion") {
                        $answers=$seq->getAnswers();
                    }

                    $seq->setData('id', 0);
                    $seq->setData('quiz_id', $q->getId());
                    $seq->save();

                    // if obj is a question duplicate all answers
                    if(get_class($seq) == "QuizQuestion") {
                        if(is_array($answers) && count($answers)) {
                            foreach($answers as $ans) {
                                $ans->setData('id', 0);
                                $ans->setData('question_id' , $seq->getId());
                                $ans->save();
                            }
                        }
                    }
                    
                }
            }
            
            // duplicate all badges          
            if(is_array($b) && count($b)) {
                foreach($b as $bv) {
                    $bv->setData('id' , 0);
                    $bv->setData('quiz_id', $q->getId());
                    $bv->save();
                }
            }

            // duplicate aweber
            if($a) {
                $a->setData('id', 0);
                $a->setData('quiz_id', $q->getId());
                $a->save();
            }
            // duplicate layout
            if($l) {
                $l->setData('id', 0);
                $l->setData('quiz_id', $q->getId());
                $l->save();
            }

            

            QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_QUIZ_DUPLICATED , "Quiz duplicated successfully" , false, QuizMessages::SUCCESS , false));
        }
        catch(QuizException $qe) {

        }
    }
    
}
function quiz_handle_deletes() {
    //deleteanswer
    if(isset($_GET["deleteanswer"])) {
        
        $answerId=intval($_GET["deleteanswer"]);
        try {
            $answer=new QuizAnswer($answerId);
            $answer->delete();
            QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_DELETE_ANSWER , "Answer deleted successfully" , false, QuizMessages::SUCCESS , false));
        }
        catch(QuizException $qe) {
            
        }
    }
    //deletequestion
    if(isset($_GET["deletequestion"])) {
        
        $questionId=intval($_GET["deletequestion"]);
        try {
            $question=new QuizQuestion($questionId);
            $question->delete();
            QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_DELETE_QUESTION , "Question deleted successfully" , false, QuizMessages::SUCCESS , false));
        }
        catch(QuizException $qe) {
            
        }
    }
    //deletesection
    if(isset($_GET["deletesection"])) {
        
        $sectionId=intval($_GET["deletesection"]);
        try {
            $section=new QuizSection($sectionId);
            $section->delete();
            QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_DELETE_SECTION , "Section deleted successfully" , false, QuizMessages::SUCCESS , false));
        }
        catch(QuizException $qe) {
            
        }
    }
    //deletebadge
    if(isset($_GET["deletebadge"])) {
        
        $badgeId=intval($_GET["deletebadge"]);
        try {
            $badge=new QuizBadge($badgeId);
            $badge->delete();
            QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_DELETE_BADGE , "Badge deleted successfully" , false, QuizMessages::SUCCESS , false));
        }
        catch(QuizException $qe) {
            
        }
    }
    //deletequiz
    if(isset($_GET["deletequiz"])) {
        
        $quizId=intval($_GET["deletequiz"]);
        try {
            $quiz=new Quiz($quizId);
            $quiz->delete();
            QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_DELETE_QUIZ , "Quiz deleted successfully" , false, QuizMessages::SUCCESS , false));
        }
        catch(QuizException $qe) {
            
        }
    }
    
}
function quiz_handle_form_addquizsubmit() {
    $_POST["description"]=wysiwyg_to_db($_POST["description"]);
    
    
    $q=new Quiz($_POST);
    if($q->save()) {
        // add a page 
        $status="draft";
        if($q->isPublished())
            $status="publish";
        $postId=quiz_add_page($q->getTitle() , "Page for quiz [" . $q->getId() . "]" , $status);   
        if($postId) {
            // added a page, update quiz
            $q->setData("page_id" , $postId);
            $q->save();
            
        }
        
        // saved the quiz, carry on with editing this quiz
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_ADD_QUIZ_SUCCESS , "Quiz added successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        wp_redirect(quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $q->getId())));
        die(); 
    }
    else {
        // couldnt save sent data load add quiz page
        $qe=new QuizMessage(QuizMessages::TYPE_ADD_QUIZ_FAIL , "Couldn't add quiz. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }
       
}
function quiz_handle_form_editquizsubmit() {
    $_POST["description"]=wysiwyg_to_db($_POST["description"]);
    
    
    $q=new Quiz($_POST["id"]);
    $q->setData($_POST);
    
    if($q->save()) {
        // saved the quiz, carry on with editing this quiz
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_UPDATE_QUIZ_SUCCESS , "Quiz updated successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        wp_redirect(quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $q->getId())));
        die(); 
    }
    else {
        // couldnt save sent data load add quiz page
        $qe=new QuizMessage(QuizMessages::TYPE_UPDATE_QUIZ_FAIL , "Couldn't update quiz. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }
    
}
function quiz_handle_form_option() {
    $_POST["quiz_option_delete_at_deactivate"]=isset($_POST["quiz_option_delete_at_deactivate"])?1:0;
    foreach($_POST as $pi => $pv) {
        if(strpos($pi, "quiz_option_") !== FALSE) {
            update_option($pi, $pv);
        }
    }
    QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_QUIZ_OPTION_SAVED , "Options saved successfully" , null, QuizMessages::SUCCESS , true));
    wp_redirect(quiz_get_admin_url(array("page" => QUIZ_SHORTCODE . '-options')));
    die();
}
function quiz_handle_form_configurethankyou() {
    $_POST["thankyou"]=wysiwyg_to_db($_POST["thankyou"]);
    $_POST["thankyou_display_title"]=isset($_POST["thankyou_display_title"])?"y":"n";
    $q=new Quiz($_POST["id"]);
    $q->setData($_POST);
    
    if($q->save()) {
        // saved the quiz, carry on with editing this quiz
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_THANKYOU_UPDATE_SUCCESS , "Thank You Page configured successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        wp_redirect(quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $q->getId())));
        die(); 
    }
    else {
        // couldnt save sent data load add quiz page
        $qe=new QuizMessage(QuizMessages::TYPE_THANKYOU_UPDATE_FAIL , "Couldn't configure Thank You Page. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }
}
function quiz_handle_form_configurethankyou2() {
    $_POST["thankyou2"]=wysiwyg_to_db($_POST["thankyou2"]);
    $_POST["thankyou2_display_title"]=isset($_POST["thankyou2_display_title"])?"y":"n";
    $q=new Quiz($_POST["id"]);
    $q->setData($_POST);
    
    if($q->save()) {
        // saved the quiz, carry on with editing this quiz
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_THANKYOU_UPDATE_SUCCESS , "Thank You Page configured successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        wp_redirect(quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $q->getId())));
        die(); 
    }
    else {
        // couldnt save sent data load add quiz page
        $qe=new QuizMessage(QuizMessages::TYPE_THANKYOU_UPDATE_FAIL , "Couldn't configure Thank You Page. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }
}
function quiz_handle_form_configureresult() {
    $_POST["result"]=wysiwyg_to_db($_POST["result"]);
    $_POST["result_display_title"]=isset($_POST["result_display_title"])?"y":"n";
    $_POST["include_social_links"]=isset($_POST["include_social_links"])?"y":"n";
    $q=new Quiz($_POST["id"]);
    $q->setData($_POST);
    
    if($q->save()) {
        // saved the quiz, carry on with editing this quiz
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_RESULT_UPDATE_SUCCESS , "Result Page configured successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        wp_redirect(quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $q->getId())));
        die(); 
    }
    else {
        // couldnt save sent data load add quiz page
        $qe=new QuizMessage(QuizMessages::TYPE_RESULT_UPDATE_FAIL , "Couldn't configure Result Page. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }
}
function quiz_handle_form_addquestionsubmit() {
    $quiz_id=intval($_GET["quizid"]);
   
   try {
       $quiz=new Quiz($quiz_id);                           
   }
   catch(QuizException $qe) {
       
       $qe=new QuizMessage(QuizMessages::TYPE_QUIZ_NOT_EXISTS , "Quiz with the provided id [$quiz_id] doesn't exist " , false , QuizMessages::ERROR, true);
       QuizMessages::addMessage($qe); 
       wp_redirect(quiz_get_admin_url());
       die();
   }
    $_POST["description"]=wysiwyg_to_db($_POST["description"]);
    $_POST["display_order"]=$quiz->getCurrentDisplayOrder();
    $_POST["display_title"]=isset($_POST["display_title"])?"y":"n";
    $q=new QuizQuestion($_POST);
    if($q->save()) {
        // saved the question, carry on with editing this question
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_ADD_QUESTION_SUCCESS , "Question added successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        wp_redirect(quiz_get_admin_url(array("task" => "editquestion",  "quizid" => $_GET["quizid"], "questionid" => $q->getId())));
        die(); 
    }
    else {
        // couldnt save sent data load add quiz page
        $qe=new QuizMessage(QuizMessages::TYPE_ADD_QUESTION_FAIL , "Couldn't add question. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }   
}
function quiz_handle_form_editquestionsubmit() {
    $_POST["description"]=wysiwyg_to_db($_POST["description"]);
    $_POST["display_title"]=isset($_POST["display_title"])?"y":"n";
    $q=new QuizQuestion($_POST["id"]);
    $q->setData($_POST);
    if($q->save()) {
        // saved the question, carry on with editing this question
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_QUESTION_UPDATE_SUCCESS , "Question updated successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        wp_redirect(quiz_get_admin_url(array("task" => "editquestion",  "quizid" => $_GET["quizid"], "questionid" => $q->getId())));
        die(); 
    }
    else {
        // couldnt save
        $qe=new QuizMessage(QuizMessages::TYPE_QUESTION_UPDATE_FAIL , "Couldn't update question. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }   
}
function quiz_handle_form_addanswersubmit() {
    $_POST["response"]=wysiwyg_to_db($_POST["response"]);
    $_POST["content"]=textarea_to_db($_POST["content"]);
    $q=new QuizAnswer($_POST);
    if($q->save()) {
        // saved the answer, carry on with editing this question
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_ADD_ANSWER_SUCCESS , "Answer added successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        if(isset($_GET["ret"]) && $_GET["ret"] == "question")
            wp_redirect(quiz_get_admin_url(array("task" => "editquestion",  "quizid" => $_GET["quizid"], "questionid" => $_GET["questionid"])));
        else
            wp_redirect(quiz_get_admin_url(array("task" => "quizmanage",  "quizid" => $_GET["quizid"])));
        die(); 
    }
    else {
        // couldnt save
        $qe=new QuizMessage(QuizMessages::TYPE_ADD_ANSWER_FAIL , "Couldn't add answer. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }   
}
function quiz_handle_form_editanswersubmit() {
    $_POST["response"]=wysiwyg_to_db($_POST["response"]);
    $_POST["content"]=textarea_to_db($_POST["content"]);
    $q=new QuizAnswer($_POST["id"]);
    $q->setData($_POST);
    if($q->save()) {
        // saved the answer, carry on with editing this question
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_ANSWER_UPDATE_SUCCESS , "Answer updated successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        if(isset($_GET["ret"]) && $_GET["ret"] == "question")
            wp_redirect(quiz_get_admin_url(array("task" => "editquestion",  "quizid" => $_GET["quizid"], "questionid" => $_GET["questionid"])));
        else
            wp_redirect(quiz_get_admin_url(array("task" => "quizmanage",  "quizid" => $_GET["quizid"])));
        die(); 
    }
    else {
        // couldnt save
        $qe=new QuizMessage(QuizMessages::TYPE_ANSWER_UPDATE_FAIL , "Couldn't update answer. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }
}
function quiz_handle_form_addsectionsubmit() {
    $quiz_id=intval($_GET["quizid"]);
   
   try {
       $quiz=new Quiz($quiz_id);                           
   }
   catch(QuizException $qe) {
       
       $qe=new QuizMessage(QuizMessages::TYPE_QUIZ_NOT_EXISTS , "Quiz with the provided id [$quiz_id] doesn't exist " , false , QuizMessages::ERROR, true);
       QuizMessages::addMessage($qe); 
       wp_redirect(quiz_get_admin_url());
       die();
   }
    $_POST["description"]=wysiwyg_to_db($_POST["description"]);
    $_POST["display_order"]=$quiz->getCurrentDisplayOrder(); 
    $_POST["display_title"]=isset($_POST["display_title"])?"y":"n";
    $_POST["display_at_result"]=isset($_POST["display_at_result"])?"y":"n";
    $q=new QuizSection($_POST);
    if($q->save()) {
        // saved the section, carry on with editing the quiz
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_SECTION_ADD_SUCCESS , "Section added successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        wp_redirect(quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $_GET["quizid"])));
        die(); 
    }
    else {
        // couldnt save 
        $qe=new QuizMessage(QuizMessages::TYPE_SECTION_ADD_FAIL , "Couldn't add section. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }
}
function quiz_handle_form_editsectionsubmit() {
    $_POST["description"]=wysiwyg_to_db($_POST["description"]);
    $_POST["display_title"]=isset($_POST["display_title"])?"y":"n";
    $_POST["display_at_result"]=isset($_POST["display_at_result"])?"y":"n";
    
    $q=new QuizSection($_POST["id"]);
    $q->setData($_POST);
    if($q->save()) {
        // saved the section, carry on with editing the quiz
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_SECTION_UPDATE_SUCCESS , "Section updated successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        wp_redirect(quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $_GET["quizid"])));
        die(); 
    }
    else {
        // couldnt save 
        $qe=new QuizMessage(QuizMessages::TYPE_SECTION_UPDATE_FAIL , "Couldn't update section. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }
}
function quiz_handle_form_addbadgesubmit() {
    $_POST["content"]=wysiwyg_to_db($_POST["content"]);
    $_POST["meta_description"]=textarea_to_db($_POST["meta_description"]);
    $_POST["is_random"]=(isset($_POST["is_random"]) && $_POST["is_random"] == "y")?"y":"n";
    $q=new QuizBadge($_POST);
    if($q->save()) {
        // saved the badge, carry on with editing the quiz
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_BADGE_ADD_SUCCESS , "Badge added successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        wp_redirect(quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $_GET["quizid"])));
        die(); 
    }
    else {
        // couldnt save 
        $qe=new QuizMessage(QuizMessages::TYPE_BADGE_ADD_FAIL , "Couldn't add badge. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }
}
function quiz_handle_form_editbadgesubmit() {
    $_POST["content"]=wysiwyg_to_db($_POST["content"]);
    $_POST["meta_description"]=textarea_to_db($_POST["meta_description"]);
    $_POST["is_random"]=(isset($_POST["is_random"]) && $_POST["is_random"] == "y")?"y":"n";
    $q=new QuizBadge($_POST["id"]);
    $q->setData($_POST);
    if($q->save()) {
        // saved the badge, carry on with editing the quiz
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_BADGE_UPDATE_SUCCESS , "Badge updated successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        wp_redirect(quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $_GET["quizid"])));
        die(); 
    }
    else {
        // couldnt save 
        $qe=new QuizMessage(QuizMessages::TYPE_BADGE_UPDATE_FAIL , "Couldn't update badge. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }
}
function quiz_handle_form_configurelayout() {
    
    $_POST["use_at_question"]=isset($_POST["use_at_question"])?"y":"n";
    $_POST["use_at_section"]=isset($_POST["use_at_section"])?"y":"n";
    $_POST["use_at_optin"]=isset($_POST["use_at_optin"])?"y":"n";
    $_POST["use_at_result"]=isset($_POST["use_at_result"])?"y":"n";
    
    if(!isset($_POST["id"]))
        $q=new QuizLayout($_POST);
    else {
        $q=new QuizLayout($_POST["id"]);
        $q->setData($_POST);   
    }
    if($q->save()) {
        //saved layout, edit quiz
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_LAYOUT_UPDATE_SUCCESS , "Layout configured successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        wp_redirect(quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $_GET["quizid"])));
        die(); 
    }
    else {
        // couldnt save 
        $qe=new QuizMessage(QuizMessages::TYPE_LAYOUT_UPDATE_FAIL , "Couldn't configure layout. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }                                  
}
function quiz_handle_form_configurelayoutcc() {
    if(isset($_POST["header_content"]))
        $_POST["header_content"]=wysiwyg_to_db($_POST["header_content"]);
    if(isset($_POST["left_content"]))
        $_POST["left_content"]=wysiwyg_to_db($_POST["left_content"]);
    if(isset($_POST["right_content"]))
        $_POST["right_content"]=wysiwyg_to_db($_POST["right_content"]);
    if(isset($_POST["bottom_content"]))
        $_POST["bottom_content"]=wysiwyg_to_db($_POST["bottom_content"]);
    
    $layoutId=false;
    if(isset($_POST["id"]))
        $layoutId=$_POST["id"];
    
    if($layoutId) {
        // layout exist, just load and set and save
        $q=new QuizLayout($layoutId);
        $q->setData($_POST);
           
    }
    else {
        $data=array(
              'quiz_id' => $_POST["quiz_id"],
              'header_content' => "",
              'left_content' => "",
              'right_content' => "",
              'bottom_content' => "",
              'use_at_question' => "y",
              'use_at_section' => "y",
              'use_at_optin' => "y",
              'use_at_result' => "y"
            );
       $data=array_merge($data, $_POST);
       $q=new QuizLayout($data);
       
    }
    if($q->save()) {
        //saved layout, edit quiz
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_LAYOUT_UPDATE_SUCCESS , "Layout configured successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        wp_redirect(quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $_GET["quizid"])));
        die(); 
    }
    else {
        // couldnt save 
        $qe=new QuizMessage(QuizMessages::TYPE_LAYOUT_UPDATE_FAIL , "Couldn't configure layout. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }   
}
function quiz_handle_form_configureaweber() {
    $_POST["content"]=wysiwyg_to_db($_POST["content"]);
    $_POST["display_title"]=isset($_POST["display_title"])?"y":"n";
    $_POST["skip_optin"]=isset($_POST["skip_optin"])?"y":"n";

    $use_custom_field=isset($_POST["use_custom_field"]);
    $custom_field_hidden=isset($_POST["custom_field_hidden"]);
    
    $cft="n";
    if($use_custom_field)
        $cft="v";
    if($use_custom_field && $custom_field_hidden)
        $cft="h";

    $_POST["custom_field_type"]=$cft;

    $q=new QuizAweber($_POST);
    if($q->save()) {
        //saved aweber, edit quiz
        QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_AWEBER_UPDATE_SUCCESS , "Aweber configured successfully" , $q->getId(), QuizMessages::SUCCESS , true));
        wp_redirect(quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $_GET["quizid"])));
        die(); 
    }
    else {
        // couldnt save 
        $qe=new QuizMessage(QuizMessages::TYPE_AWEBER_UPDATE_FAIL , "Couldn't configure aweber. Please try again" , $q , QuizMessages::ERROR);
        QuizMessages::addMessage($qe);
    }   
}
function quiz_handle_form_managequestionsectionsubmit() {
   $quiz_id=intval($_GET["quizid"]);
   try {
       $quiz=new Quiz($quiz_id);                           
   }
   catch(QuizException $qe) {
       
       $qe=new QuizMessage(QuizMessages::TYPE_QUIZ_NOT_EXISTS , "Quiz with the provided id [$quiz_id] doesn't exist " , false , QuizMessages::ERROR, true);
       QuizMessages::addMessage($qe); 
       wp_redirect(quiz_get_admin_url());
       die();
   }
   
   $sequence=$quiz->getSequence();
   foreach($sequence as $seq) {
        
        if(get_class($seq) == "QuizQuestion") {
           $pid="quizsequenceq" . $seq->getId(); 
        }
        else {
            $pid="quizsequences" . $seq->getId();
        }   
        if(isset($_POST[$pid])) {
            $seq->setData("display_order" , intval($_POST[$pid]));
            $seq->save();   
        }
   }
   QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_SEQUENCE_UPDATE_SUCCESS , "Quiz sequence updated successfully" , false, QuizMessages::SUCCESS , true));
   wp_redirect(quiz_get_admin_url(array("task" => "managequestionsection", "quizid" => $quiz->getId())));
   die(); 
}
