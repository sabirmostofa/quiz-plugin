<?php
require_once dirname(__FILE__) . "/QuizFront.php";
function quiz_wp() {
  global $wp_query;
  if(is_page()) {
       $post=$wp_query->get_queried_object();
       if($post->post_type == 'page') {
            $postId=$post->ID;
            $quiz=new Quiz();
            try {
                $quiz->loadByPageId($postId);   
                QuizFront::setQuiz($quiz);
                QuizFront::handleRequest();
            }
            catch(QuizException $qe) {
                
            }
       }    
  }
}
add_action("wp", "quiz_wp");
function quiz_parse_request(){             
    global $wp, $wpdb;
    
    $req_uri=$_SERVER['REQUEST_URI'];
    if(!( (stripos($req_uri , '/' . QuizFront::REQUEST_QUESTION )!== FALSE) || (stripos($req_uri , '/' . QuizFront::REQUEST_SECTION )!== FALSE) || (stripos($req_uri , '/' . QuizFront::REQUEST_RESULT . '/')!== FALSE) || (stripos($req_uri , '/' . QuizFront::REQUEST_OPTIN )!== FALSE) || (stripos($req_uri , '/' . QuizFront::REQUEST_THANKYOU)!== FALSE) )) {
        return;   
    }
    
    $sql="SELECT p.ID, p.post_name FROM " . QUIZ_DB_TABLE_QUIZ . " AS q INNER JOIN " . $wpdb->prefix . "posts AS p ON q.page_id = p.ID WHERE q.status = 'p'";
    $qpdata=$wpdb->get_results($sql);
    if($qpdata) {
        foreach($qpdata as $qpd) {
            if(stripos($req_uri , '/' . $qpd->post_name . '/') !== FALSE) {
                $wp->set_query_var("pagename" , $qpd->post_name);
                $wp->set_query_var("name" , null);
            }
        }   
    }
    
}
add_action("parse_request" , "quiz_parse_request");