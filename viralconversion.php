<?php
/*
Plugin Name: Viral Conversion
Plugin URI: http://www.viralconversion.com
Description: 
Version: 2.1.0
Author: Rob Jones
Author URI: http://rob-jones.com
*/

// using session for session messages
session_start();


define("QUIZ_SHORTCODE", "viralconversion");
define("QUIZ_PLUGIN_FOLDER" , "viralconversion");
define("QUIZ_PLUGIN_NAME", "Viral Conversion");


// base paths and urls
define("QUIZ_PATH", dirname(__FILE__));
define("QUIZ_URL", WP_PLUGIN_URL . "/" . QUIZ_PLUGIN_FOLDER);

//tables
global $wpdb;
define('QUIZ_DB_TABLE_QUIZ', $wpdb->prefix . 'quiz');
define('QUIZ_DB_TABLE_QUESTIONS' , $wpdb->prefix . 'quiz_questions');
define('QUIZ_DB_TABLE_SECTIONS', $wpdb->prefix . 'quiz_sections');
define('QUIZ_DB_TABLE_BADGES' , $wpdb->prefix . 'quiz_badges');
define('QUIZ_DB_TABLE_LAYOUTS', $wpdb->prefix . 'quiz_layouts');
define('QUIZ_DB_TABLE_AWEBER' , $wpdb->prefix . 'quiz_aweber');
define('QUIZ_DB_TABLE_ANSWERS', $wpdb->prefix . 'quiz_answers');
define('QUIZ_DB_TABLE_RESULTS', $wpdb->prefix . "quiz_results");
define('QUIZ_DB_TABLE_RESULT_DATA', $wpdb->prefix . "quiz_results_data");

// include utils
require_once QUIZ_PATH . "/lib/utils.php";
require_once QUIZ_PATH . "/lib/logic.php";

// include classes
require_once QUIZ_PATH . "/lib/classes/Messages.php";
require_once QUIZ_PATH . "/lib/classes/QuizException.php";
require_once QUIZ_PATH . "/lib/classes/Answer.php";
require_once QUIZ_PATH . "/lib/classes/Question.php";
require_once QUIZ_PATH . "/lib/classes/Section.php";
require_once QUIZ_PATH . "/lib/classes/Badge.php";
require_once QUIZ_PATH . "/lib/classes/Layout.php";
require_once QUIZ_PATH . "/lib/classes/Aweber.php";
require_once QUIZ_PATH . "/lib/classes/Quiz.php";

if(is_admin()) {
    // load admin 
    require_once QUIZ_PATH . "/lib/admin/admin.php";   
}
else {
    require_once QUIZ_PATH . "/front/front.php";   
}


function quiz_activate() {
    global $wpdb;
    return;
    //require_once QUIZ_PATH . "/install.php";   
}
register_activation_hook(__FILE__ , "quiz_activate");

function quiz_deactivate() {
    global $wpdb;
    if(get_option("quiz_option_delete_at_deactivate"))
        require_once QUIZ_PATH . "/uninstall.php";
}
register_deactivation_hook(__FILE__ , "quiz_deactivate");

function quiz_sequence_handle() {      
   $quiz_id=intval($_POST["quiz_id"]);
   try {
       $quiz=new Quiz($quiz_id);                           
   }
   catch(QuizException $qe) {
       
       echo "Cannot update the order";
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
   echo "The order updated successfully";
   die();
}
add_action("wp_ajax_quiz_sequence_handle" , "quiz_sequence_handle"); 


// add menus
function quiz_admin_menu() {

    if(get_option('viral_conversion_verified')):
        add_menu_page(QUIZ_PLUGIN_NAME, QUIZ_PLUGIN_NAME, 'administrator', QUIZ_SHORTCODE, 'quiz_admin_page_manage_quizes');
    add_submenu_page(QUIZ_SHORTCODE, 'Manage Quizzes', 'Manage Quizzes', 'administrator', QUIZ_SHORTCODE , 'quiz_admin_page_manage_quizes');
    add_submenu_page(QUIZ_SHORTCODE, 'Add Quiz', 'Add Quiz', 'administrator', QUIZ_SHORTCODE . '-addquiz', 'quiz_admin_page_add_quiz');
    add_submenu_page(QUIZ_SHORTCODE, 'Options', 'Options', 'administrator', QUIZ_SHORTCODE . '-options', 'quiz_admin_page_options');
   
    else:
         add_menu_page(QUIZ_PLUGIN_NAME, QUIZ_PLUGIN_NAME, 'administrator', QUIZ_SHORTCODE, 'quiz_admin_page_activate');

    endif;
    quiz_handle_forms(); 
    add_action("admin_footer" , "quiz_admin_footer");
}
add_action('admin_menu', 'quiz_admin_menu');

