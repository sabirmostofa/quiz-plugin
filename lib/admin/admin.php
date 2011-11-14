<?php

require_once "editor.php";
require_once "handleforms.php";

// if plugins page then add use buffer
$page="";
if(isset($_GET["page"]))
    $page=$_GET["page"];
if(!in_array($page, array(QUIZ_SHORTCODE , QUIZ_SHORTCODE . "-addquiz", QUIZ_SHORTCODE . "-options"))) {
    return;
}
ob_start();
function quiz_get_admin_title() {
    global $title;
    $page="";
    if(isset($_GET["page"]))
        $page=$_GET["page"];
    if($page != "viralconversion")
        return;
    $task="manage";
    if(isset($_GET["task"]))
        $task=$_GET["task"];
    if($task == "manage")
       return; 
    
    if(isset($_GET["quizid"])) {
        $quiz=new Quiz($_GET["quizid"]);
               
    }
    if(isset($_GET["questionid"])) {
        $question=new QuizQuestion($_GET["questionid"]);
        $dorder=$question->getDisplayOrder();
        $porder=$dorder < 10 ? ("0" . $dorder) : $dorder;       
    }
    if(isset($_GET["sectionid"])) {
        $section=new QuizSection($_GET["sectionid"]);
        $dorder=$section->getDisplayOrder();
        $porder=$dorder < 10 ? ("0" . $dorder) : $dorder;              
    }
    switch($task) {
        case "editquiz":
            $title="Edit Quiz";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());   
        break;
        case "addquestion":
            $title="Question Details";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
        break;
        case "quizmanage":
            $title="Manage Quiz";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
        break;
        case "editquestion":
            $title="Question Details";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
            $title .= " - Order " . $porder;
            
        break;
        case "addsection":
            $title="Section Details";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
        break;
        case "editsection":
            $title="Section Details";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
            $title .= " - Order " . $porder;   
        break;
        case "addanswer":
            $title="Answer Details";
            
        break;
        case "editanswer":
            $title="Answer Details";
            
        break;
        case "addbadge":
            $title="Badge Details";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
        break;
        case "editbadge":
            $title="Badge Details";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
        break;
        case "configurelayout":
            $title="Configure Ad Space";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
        break;
        case "configurelayoutheader":
            $title="Configure Ad Space - Header";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
        break;
        case "configurelayoutleft":
            $title="Configure Ad Space - Left";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
        break;
        case "configurelayoutright":
            $title="Configure Ad Space - Right";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
        break;
        case "configurelayoutbottom":
            $title="Configure Ad Space - Bottom";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
        break;
        case "configureaweber":
            $title="Configure Optin Page";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
        break;             
        case "configurethankyou":
            $title="Configure Thank You Page";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
        break;
        case "configurethankyou2":
            $title="Configure Second Thank You Page";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
        break;
        case "configureresult":
            $title="Configure Result Page";
            $title .= " - " . textarea_db_to_html($quiz->getTitle());
        break;
        default:
            $title=$task;
        break;   
    }
    return $title;
}
quiz_get_admin_title();

function quiz_get_admin_url($params=array(), $mergeOld=false) {
    if(!isset($params["page"]))
        $params["page"]=QUIZ_SHORTCODE;
    $base=get_bloginfo("url") . '/wp-admin/admin.php?';
    if($mergeOld) {
        foreach($mergeOld as $gi) {
            if(!isset($params[$gi]) && isset($_GET[$gi]))
                $params[$gi]=$_GET[$gi];
        }   
    }
    $i=0;
    foreach($params as $k => $v) {
        if($i>0)
            $base .= '&';
        $base .= $k . "=" . urlencode($v);   
        $i++;
    }
    return $base;
}
function quiz_need_editor() {
    return true;   
}
function quiz_enable_editor() {
    if(quiz_need_editor()) {
       wp_tiny_mce( false, array("editor_selector" => "theEditor"));
    }   
}
// add admin stylesheet
function quiz_admin_stylesheet()
{
     wp_register_style('quizadmincss' , QUIZ_URL . '/lib/admin/css/admin.css');
     wp_enqueue_style('quizadmincss');   
}
add_action('admin_print_styles', 'quiz_admin_stylesheet');   
// add admin javascript
function quiz_admin_script()
{
    
    wp_register_script('quizadminjs' , QUIZ_URL . '/lib/admin/js/admin.js' , array('jquery'));
    wp_enqueue_script('quizadminjs');
    
    wp_register_script('jqueryvalidate' , 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.8/jquery.validate.min.js' , array('jquery'));
    wp_enqueue_script('jqueryvalidate');
    
    if(isset($_GET["task"]) && $_GET["task"] == "managequestionsection") {
        wp_enqueue_script("jquery-ui-sortable");   
    }
    
    $enableMainEditor=true;
    
    if($enableMainEditor) {
        wp_enqueue_script('word-count');
        wp_enqueue_script('post');
        wp_enqueue_script('editor');
        wp_enqueue_script('media-upload');

        wp_enqueue_script( 'common' );
        wp_enqueue_script( 'jquery-color' );
        if (function_exists('add_thickbox')) add_thickbox();
        wp_enqueue_script('utils');
        do_action("admin_print_styles-post-php");
        do_action('admin_print_styles');
        remove_all_filters('mce_external_plugins');

    }
    
}
add_action('admin_print_scripts', 'quiz_admin_script');  
function quiz_admin_head() {
   wp_tiny_mce();
}
add_action("admin_head", "quiz_admin_head");


/******* PAGES ***********/
function quiz_admin_footer(){
  $page=isset($_GET["page"])?$_GET["page"]:"";
  if(!in_array($page , array(QUIZ_SHORTCODE, QUIZ_SHORTCODE . "-addquiz")))
    return;
  ?>
  
  <?php   
}
function quiz_pageadmin_footer() {
   ?>
   
   <?php 
}
function quiz_render_main_editor($content, $contentId, $tabindex=2) {
    ?>
    <div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
    <?php the_editor(stripslashes($content), $contentId,$contentId . "aa", true , $tabindex); ?>
    </div>   
    <?php
}
function quiz_admin_page_manage_quizes() {
    global $wpdb;
    
    $task="manage";
    if(isset($_GET["task"]))
        $task=$_GET["task"];
    if($task != "manage") {
        
       $fn="quiz_admin_page_" . $task;
       $fn();
       echo ob_get_clean();
       return; 
    }
    
    $itemPerPage=10;
    $currentPage=1;
    if(isset($_GET["kpage"]))
        $currentPage=intval($_GET["kpage"]);
    
    $status="all";
    if(isset($_GET["status"])) 
        $status=$_GET["status"];
    if($status == "publish") 
        $status="p";
    if($status == "draft")
        $status="d";
    $whereSql="";
    if($status == "p" || $status == "d") {
        $whereSql=" WHERE status = '$status' ";   
    }
        
    // get total quizzes which are published
    $totalQuizzes=$wpdb->get_var("SELECT COUNT(id) as c FROM " . QUIZ_DB_TABLE_QUIZ . "  $whereSql ;");
    
    // get quizzes
    $minLimit=($currentPage-1)*$itemPerPage;
    $quizzesD=$wpdb->get_results("SELECT * FROM " . QUIZ_DB_TABLE_QUIZ . " $whereSql ORDER BY id DESC LIMIT $minLimit, $itemPerPage; ");
    $quizzes=array();
    foreach($quizzesD as $d) {
        $quizzes[]=new Quiz($d);   
    }
    
    $totalPages=ceil($totalQuizzes / $itemPerPage);
    
    require QUIZ_PATH . "/lib/admin/views/managequiz.php";  
    echo ob_get_clean();
}
function quiz_admin_page_options() {
   require QUIZ_PATH . "/lib/admin/views/options.php";
   echo ob_get_clean();
}

function quiz_admin_page_add_quiz() {
   $quiz=false;
   $qmsg=QuizMessages::getMessage(QuizMessages::TYPE_ADD_QUIZ_FAIL);
   if($qmsg)
      $quiz=$qmsg->getData();
   require QUIZ_PATH . "/lib/admin/views/addquiz.php";
   echo ob_get_clean();
}           
function quiz_admin_page_editquiz() {
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
   $qmsg=QuizMessages::getMessage(QuizMessages::TYPE_UPDATE_QUIZ_FAIL);
   if($qmsg)
      $quiz=$qmsg->getData();
   
   require QUIZ_PATH . "/lib/admin/views/editquiz.php";
   quiz_enable_editor();
       
}
function quiz_admin_page_addquestion() {
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
   $question=false;
   $qmsg=QuizMessages::getMessage(QuizMessages::TYPE_ADD_QUESTION_FAIL);
   if($qmsg)
      $question=$qmsg->getData();
   require QUIZ_PATH . "/lib/admin/views/addquestion.php";
   quiz_enable_editor();
       
}
function quiz_admin_page_editquestion() {
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
   $question_id=intval($_GET["questionid"]);
   try {
       $question=new QuizQuestion($question_id);                           
   }
   catch(QuizException $qe) {
       
       $qe=new QuizMessage(QuizMessages::TYPE_QUESTION_NOT_EXISTS , "Question with the provided id [$question_id] doesn't exist " , false , QuizMessages::ERROR, true);
       QuizMessages::addMessage($qe); 
       wp_redirect(quiz_get_admin_url());
       die();
   }
   $qmsg=QuizMessages::getMessage(QuizMessages::TYPE_QUESTION_UPDATE_FAIL);
   if($qmsg)
      $question=$qmsg->getData();
   
   require QUIZ_PATH . "/lib/admin/views/editquestion.php";
   quiz_enable_editor();
       
}
function quiz_admin_page_addanswer() {
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
   $question_id=intval($_GET["questionid"]);
   try {
       $question=new QuizQuestion($question_id);                           
   }
   catch(QuizException $qe) {
       
       $qe=new QuizMessage(QuizMessages::TYPE_QUESTION_NOT_EXISTS , "Question with the provided id [$question_id] doesn't exist " , false , QuizMessages::ERROR, true);
       QuizMessages::addMessage($qe); 
       wp_redirect(quiz_get_admin_url());
       die();
   }
   $answer=false;
   $qmsg=QuizMessages::getMessage(QuizMessages::TYPE_ADD_ANSWER_FAIL);
   if($qmsg)
      $answer=$qmsg->getData();
   
   require QUIZ_PATH . "/lib/admin/views/addanswer.php";
   quiz_enable_editor();
       
}
function quiz_admin_page_editanswer() {
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
   $question_id=intval($_GET["questionid"]);
   try {
       $question=new QuizQuestion($question_id);                           
   }
   catch(QuizException $qe) {
       
       $qe=new QuizMessage(QuizMessages::TYPE_QUESTION_NOT_EXISTS , "Question with the provided id [$question_id] doesn't exist " , false , QuizMessages::ERROR, true);
       QuizMessages::addMessage($qe); 
       wp_redirect(quiz_get_admin_url());
       die();
   }
   $answer_id=intval($_GET["answerid"]);
   try {
        $answer=new QuizAnswer($answer_id);   
   }
   catch(QuizException $qe) {
      $qe=new QuizMessage(QuizMessages::TYPE_ANSWER_NOT_EXISTS , "Answer with the provided id [$answer_id] doesn't exist " , false , QuizMessages::ERROR, true);
       QuizMessages::addMessage($qe); 
       wp_redirect(quiz_get_admin_url());
       die(); 
   }
   $qmsg=QuizMessages::getMessage(QuizMessages::TYPE_ANSWER_UPDATE_FAIL);
   if($qmsg)
      $answer=$qmsg->getData();
   require QUIZ_PATH . "/lib/admin/views/editanswer.php";
   quiz_enable_editor();
    
}
function quiz_admin_page_addsection() {
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
   $section=false;
   $qmsg=QuizMessages::getMessage(QuizMessages::TYPE_SECTION_ADD_FAIL);
   if($qmsg)
      $section=$qmsg->getData();
   require QUIZ_PATH . "/lib/admin/views/addsection.php";
   quiz_enable_editor();
    
       
}
function quiz_admin_page_editsection() {
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
   $section_id=intval($_GET["sectionid"]);
   try {
       $section=new QuizSection($section_id);                           
   }
   catch(QuizException $qe) {
       
       $qe=new QuizMessage(QuizMessages::TYPE_SECTION_NOT_EXISTS , "Section with the provided id [$section_id] doesn't exist " , false , QuizMessages::ERROR, true);
       QuizMessages::addMessage($qe); 
       wp_redirect(quiz_get_admin_url());
       die();
   }
   $qmsg=QuizMessages::getMessage(QuizMessages::TYPE_SECTION_UPDATE_FAIL);
   if($qmsg)
      $section=$qmsg->getData();
   require QUIZ_PATH . "/lib/admin/views/editsection.php";
   quiz_enable_editor();
    
       
}
function quiz_admin_page_addbadge() {
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
   $badge=false;
   $qmsg=QuizMessages::getMessage(QuizMessages::TYPE_BADGE_ADD_FAIL);
   if($qmsg)
      $badge=$qmsg->getData();
   require QUIZ_PATH . "/lib/admin/views/addbadge.php";
   quiz_enable_editor();
    
       
}
function quiz_admin_page_editbadge() {
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
   $badge_id=intval($_GET["badgeid"]);
   $badge=false;
   try {
       $badge=new QuizBadge($badge_id);                           
   }
   catch(QuizException $qe) {
       
       $qe=new QuizMessage(QuizMessages::TYPE_BADGE_NOT_EXISTS , "Badge with the provided id [$badge_id] doesn't exist " , false , QuizMessages::ERROR, true);
       QuizMessages::addMessage($qe); 
       wp_redirect(quiz_get_admin_url());
       die();
   }
   
   $qmsg=QuizMessages::getMessage(QuizMessages::TYPE_BADGE_UPDATE_FAIL);
   if($qmsg)
      $badge=$qmsg->getData();
   require QUIZ_PATH . "/lib/admin/views/editbadge.php";
   quiz_enable_editor();
    
       
}
function quiz_admin_page_configurelayout() {
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
   $layout=$quiz->getLayout(); 
   $qmsg=QuizMessages::getMessage(QuizMessages::TYPE_LAYOUT_UPDATE_FAIL);
   if($qmsg)
      $layout=$qmsg->getData();
   require QUIZ_PATH . "/lib/admin/views/configurelayout.php";
   quiz_enable_editor();
       
}
function quiz_admin_page_configureaweber() {
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
   $aweber=$quiz->getAweber(); 
   $qmsg=QuizMessages::getMessage(QuizMessages::TYPE_AWEBER_UPDATE_FAIL);
   if($qmsg)
      $aweber=$qmsg->getData();
   require QUIZ_PATH . "/lib/admin/views/configureaweber.php";
       
}
function quiz_admin_page_managebadges() {
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
   $badges=$quiz->getBadges();
   require QUIZ_PATH . "/lib/admin/views/managebadges.php";    
}                 
function quiz_admin_page_manageanswers() {
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
   $question_id=intval($_GET["questionid"]);
   try {
       $question=new QuizQuestion($question_id);                           
   }
   catch(QuizException $qe) {
       
       $qe=new QuizMessage(QuizMessages::TYPE_QUESTION_NOT_EXISTS , "Question with the provided id [$question_id] doesn't exist " , false , QuizMessages::ERROR, true);
       QuizMessages::addMessage($qe); 
       wp_redirect(quiz_get_admin_url());
       die();
   }
   $answers=$question->getAnswers();
   require QUIZ_PATH . "/lib/admin/views/manageanswers.php";    
}
function quiz_admin_page_managequestionsection() {
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
   require QUIZ_PATH . "/lib/admin/views/managequestionsection.php";
} 
function quiz_admin_page_quizmanage() {
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
   require QUIZ_PATH . "/lib/admin/views/quizmanage.php";      
}
function quiz_admin_page_configurelayoutcc() {
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
   $layout=$quiz->getLayout();
   $layoutType=str_replace("configurelayout" , "" , $_GET["task"]);
   require QUIZ_PATH . "/lib/admin/views/configurelayoutc.php";   
} 
function quiz_admin_page_configurethankyou() {
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
   require QUIZ_PATH . "/lib/admin/views/configurethankyou.php";    
}
function quiz_admin_page_configurethankyou2() {
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
   require QUIZ_PATH . "/lib/admin/views/configurethankyou2.php";    
}
function quiz_admin_page_configureresult() {
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
   require QUIZ_PATH . "/lib/admin/views/configureresult.php";    
}
function quiz_admin_page_configurelayoutheader() {
    quiz_admin_page_configurelayoutcc();   
}
function quiz_admin_page_configurelayoutleft() {
    quiz_admin_page_configurelayoutcc();   
}
function quiz_admin_page_configurelayoutright() {
    quiz_admin_page_configurelayoutcc();   
}
function quiz_admin_page_configurelayoutbottom() {
    quiz_admin_page_configurelayoutcc();   
}
/****** UTILS **************/

function quiz_render_tasks() {
    $page=isset($_GET["page"])?$_GET["page"]:"";   
    $task="manage";
    if($page == QUIZ_SHORTCODE . "-addquiz")
        $task="addquiz";
    if(isset($_GET["task"]))
        $task=$_GET["task"];
    
    if(isset($_GET["quizid"]))
        $quiz=new Quiz($_GET["quizid"]);
    
    if(isset($_GET["questionid"]))
        $question=new QuizQuestion($_GET["questionid"]);
    

    switch($task) {
        case "quizmanage":
            ?>
              <div class="quiz-breadcrumb">
                <ul>
                    <li class="last"><a href="<?php echo quiz_get_admin_url(); ?>">&laquo; Back to Manage Quizzes</a></li>                 
                </ul> 
                <div class="clear"></div>   
              </div>  
              <div class="quiz-tasks">
                <ul>
                    <li><a href="<?php echo quiz_get_admin_url(array("task" => "addquestion", "quizid" => $_GET["quizid"])); ?>">Add Question</a></li>
                    <li><a href="<?php echo quiz_get_admin_url(array("task" => "addsection", "quizid" => $_GET["quizid"])); ?>">Add Section</a></li>
                    <li class="last"><a href="<?php echo quiz_get_admin_url(array("task" => "addbadge", "quizid" => $_GET["quizid"])); ?>">Add Badge</a></li>
                </ul>
                <div class="clear"></div>   
              </div>
              <div class="clear" style="height: 4px"></div>   
              <?php if(false): ?>
              <div class="quiz-tasks">
                <ul>    
                    <li><a href="<?php echo quiz_get_admin_url(array("task" => "configurelayout", "quizid" => $_GET["quizid"])); ?>">Configure Ad Space</a></li>
                    <li><a href="<?php echo quiz_get_admin_url(array("task" => "configureaweber", "quizid" => $_GET["quizid"])); ?>">Configure Optin Page</a></li>                 
                    <li><a href="<?php echo quiz_get_admin_url(array("task" => "configurethankyou", "quizid" => $_GET["quizid"])); ?>">Configure Thank You Page</a></li>          
                    <li><a href="<?php echo quiz_get_admin_url(array("quizid" => $quiz->getId(), "task" => "configurethankyou2")); ?>">Configure Second Thank You Page</a></li>   
                    <li class="last"><a href="<?php echo quiz_get_admin_url(array("task" => "configureresult", "quizid" => $_GET["quizid"])); ?>">Configure Result Page</a></li>             
                </ul> 
                <div class="clear"></div>   
             </div>
              <?php endif; ?>
           <?php
        break;
        
        case "addquiz":
        
        break;
        
        case "manage":
            ?>
              <div class="quiz-tasks">
                <div class="ul" style="font-weight: bold">Filter results:</div>
                <?php 
                  $status="all";
                  if(isset($_GET["status"]))
                    $status=$_GET["status"];
                ?>
                <ul>
                    <li><?php if($status != "all"): ?><a href="<?php echo quiz_get_admin_url(); ?>">All</a><?php else: ?><span>All</span><?php endif; ?></li>
                    <li><?php if($status != "publish"): ?><a href="<?php echo quiz_get_admin_url(array("status" => "publish")); ?>">Published</a><?php else: ?><span>Published</span><?php endif; ?></li>
                    <li class="last"><?php if($status != "draft"): ?><a href="<?php echo quiz_get_admin_url(array("status" => "draft")); ?>">Draft</a><?php else: ?><span>Draft</span><?php endif; ?></li>
                </ul> 
                <div class="clear"></div>   
             </div>
            
            <?php
        break;
        
        case "configurelayoutheader":
        case "configurelayoutleft":
        case "configurelayoutright":
        case "configurelayoutbottom":
              ?>
               <div class="quiz-breadcrumb">
                <a href="<?php echo quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $_GET["quizid"])); ?>">&laquo; Back to Manage Quiz - <?php echo textarea_db_to_html($quiz->getTitle()); ?></a></li>  &laquo; <a href="<?php echo quiz_get_admin_url(array("task" => "configurelayout", "quizid" => $_GET["quizid"])); ?>">&laquo; Back to Configure Ad Space - <?php echo textarea_db_to_html($quiz->getTitle()); ?></a>
                </ul> 
                <div class="clear"></div>   
              </div>
              <?php
        break;
        
        case "addquestion":
        case "addsection":
        case "addbadge":
        case "configurelayout":
        case "configureaweber":
        case "managequestionsection":
        case "managebadges":
        case "editquiz":
        case "editquestion":
        default:
            ?>
               <div class="quiz-breadcrumb">
                <ul>
                    <?php
                    if(isset($_GET["ret"]) && $_GET["ret"] == "question"):
                    ?>
                    <li><a href="<?php echo quiz_get_admin_url(array("task" => "editquestion", "quizid" => $_GET["quizid"] , "questionid" => $question->getId())); ?>">&laquo; Back to Manage Question - <?php echo textarea_db_to_html(quiz_process_string($question->getTitle() , array("{quiz-title}" => $quiz->getTitle()))); ?></a></li>
                    <?php endif; ?>
                    <li class="last"><a href="<?php echo quiz_get_admin_url(array("task" => "quizmanage", "quizid" => $_GET["quizid"])); ?>">&laquo; Back to Manage Quiz - <?php echo textarea_db_to_html($quiz->getTitle()); ?></a></li>                 
                </ul> 
                <div class="clear"></div>   
              </div>
            <?php
        break;
        
        
    }   
}