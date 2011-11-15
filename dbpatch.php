<?php

// Add Key to the required Tables
require_once '../../../wp-load.php';
global $wpdb;
//quiz table
$wpdb->query("create index status on {$wpdb->prefix}quiz(status)");
$wpdb->query("create index page_id on {$wpdb->prefix}quiz(page_id)");
//quiz questions
$wpdb->query("create index quiz_id on {$wpdb->prefix}quiz_questions(quiz_id)");
//quiz answers
$wpdb->query("create index question_id on {$wpdb->prefix}quiz_answers(question_id)");
//quiz badges
$wpdb->query("create index quiz_id on {$wpdb->prefix}quiz_badges(quiz_id)");
//quiz layouts
$wpdb->query("create index quiz_id on {$wpdb->prefix}quiz_layouts(quiz_id)");
//quiz sections
$wpdb->query("create index quiz_id on {$wpdb->prefix}quiz_sections(quiz_id)");
//quiz results
$wpdb->query("create index quiz_id on {$wpdb->prefix}quiz_results(quiz_id)");
$wpdb->query("create index email on {$wpdb->prefix}quiz_results(email)");
$wpdb->query("create index resultkey on {$wpdb->prefix}quiz_results(resultkey)");
//quiz results data
$wpdb->query("create index result_id on {$wpdb->prefix}quiz_results_data(result_id)");
?>
