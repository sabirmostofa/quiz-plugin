<?php
global $wpdb;
$quizTables=array("quiz", "quiz_questions", "quiz_sections", "quiz_badges", "quiz_layouts", "quiz_aweber", "quiz_answers", "quiz_results", "quiz_results_data");

// drop all tables
foreach($quizTables as $quizTable) {
    $sql="TRUNCATE TABLE `" . $wpdb->prefix . $quizTable .  "`";
    $wpdb->query($sql);
    
    $sql="DROP TABLE `" . $wpdb->prefix . $quizTable .  "`";
    $wpdb->query($sql);
}

// remove all options
$quizOptions=array('quiz_option_delete_at_deactivate','viral_conversion_verified');
foreach($quizOptions as $quizOption) {
    delete_option($quizOption);
}