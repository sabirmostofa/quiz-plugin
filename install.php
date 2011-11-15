<?php
//If accessing directly
if(!isset($_REQUEST['key']) && !isset($_REQUEST['email'])):
    return;
endif;
if(!get_option('viral_conversion_verified'))return;


  $sql_quiz_table="CREATE TABLE `" . $wpdb->prefix . "quiz` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` TEXT NOT NULL ,
`description` TEXT NOT NULL ,
`status` CHAR( 1 ) NOT NULL ,
`page_id` INT( 11 ) NOT NULL ,
`include_social_links` CHAR( 1 ) NOT NULL ,
`thankyou` TEXT NOT NULL ,
`thankyou_title` VARCHAR( 255 ) NOT NULL ,
`thankyou2` TEXT NOT NULL ,
`thankyou2_title` VARCHAR( 255 ) NOT NULL ,
`result` TEXT NOT NULL ,
`result_title` VARCHAR( 255 ) NOT NULL ,
`thankyou_display_title` CHAR( 1 ) NOT NULL DEFAULT 'y',
`thankyou2_display_title` CHAR( 1 ) NOT NULL DEFAULT 'y',
`result_display_title` CHAR( 1 ) NOT NULL DEFAULT 'y',
`skip_intro` CHAR( 1 ) NOT NULL DEFAULT 'n',
 key `status`(`status`),
 key `page_id`(`page_id`)
) ENGINE = innodb;";
  $sql_question_table="CREATE TABLE `" . $wpdb->prefix . "quiz_questions` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` TEXT NOT NULL ,
`description` TEXT NOT NULL ,
`type` CHAR( 1 ) NOT NULL ,
`quiz_id` INT( 11 ) NOT NULL ,
`display_order` INT( 11 ) NOT NULL ,
`display_title` CHAR( 1 ) NOT NULL DEFAULT 'y',
  key `quiz_id`(`quiz_id`)
) ENGINE = innodb;";
  $sql_section_table="CREATE TABLE `" . $wpdb->prefix . "quiz_sections` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` TEXT NOT NULL ,
`description` TEXT NOT NULL ,
`display_at_result` CHAR( 1 ) NOT NULL ,
`quiz_id` INT( 11 ) NOT NULL ,
`display_order` INT( 11 ) NOT NULL ,
`display_title` CHAR( 1 ) NOT NULL DEFAULT 'y',
 key `quiz_id`(`quiz_id`)
) ENGINE = innodb;";
  $sql_badges_table="CREATE TABLE `" . $wpdb->prefix . "quiz_badges` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`content` TEXT NOT NULL ,
`range_min` INT( 11 ) NOT NULL ,
`range_max` INT( 11 ) NOT NULL ,
`quiz_id` INT( 11 ) NOT NULL ,
`meta_description` TEXT NOT NULL,
 key `quiz_id`(`quiz_id`)
) ENGINE = innodb;";
 $sql_layouts_table="CREATE TABLE `" . $wpdb->prefix . "quiz_layouts` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`quiz_id` INT( 11 ) NOT NULL ,
`header_content` TEXT NOT NULL ,
`left_content` TEXT NOT NULL ,
`right_content` TEXT NOT NULL ,
`bottom_content` TEXT NOT NULL ,
`use_at_question` CHAR( 1 ) NOT NULL ,
`use_at_section` CHAR( 1 ) NOT NULL ,
`use_at_optin` CHAR( 1 ) NOT NULL ,
`use_at_result` CHAR( 1 ) NOT NULL,
 key `quiz_id`(`quiz_id`)
) ENGINE = innodb;";
$sql_aweber_table="CREATE TABLE `" . $wpdb->prefix . "quiz_aweber` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`quiz_id` INT( 11 ) NOT NULL ,
`listname` VARCHAR( 255 ) NOT NULL ,
`firstname_label` VARCHAR( 255 ) NOT NULL ,
`lastname_label` VARCHAR( 255 ) NOT NULL ,
`email_label` VARCHAR( 255 ) NOT NULL ,
`submit_label` VARCHAR( 255 ) NOT NULL ,
`content` TEXT NOT NULL ,
`title` VARCHAR( 255 ) NOT NULL ,
`display_title` CHAR( 1 ) NOT NULL DEFAULT 'y',
 key `quiz_id`(`quiz_id`)
) ENGINE = innodb;";

$sql_answers_table="CREATE TABLE `" . $wpdb->prefix . "quiz_answers` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`question_id` INT( 11 ) NOT NULL ,
`content` TEXT NOT NULL ,
`response` TEXT NOT NULL ,
`value` INT( 11 ) NOT NULL,
 key `question_id`(`question_id`)
) ENGINE = innodb;";

$sql_results_table="CREATE TABLE `" . $wpdb->prefix . "quiz_results` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`quiz_id` INT( 11 ) NOT NULL ,
`status` CHAR( 1 ) NOT NULL DEFAULT 'i',
`firstname` VARCHAR( 100 ) NOT NULL ,
`lastname` VARCHAR( 100 ) NOT NULL ,
`email` VARCHAR( 255 ) NOT NULL ,
`resultkey` VARCHAR( 100 ) NOT NULL ,
`numviews` INT( 11 ) NOT NULL DEFAULT '0',
key `quiz_id`(`quiz_id`),
key `email`(`email`),
key `resultkey`(`resultkey`)
) ENGINE = innodb;
";
$sql_results_data_table="CREATE TABLE `" . $wpdb->prefix . "quiz_results_data` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`result_id` INT( 11 ) NOT NULL ,
`question_id` INT( 11 ) NOT NULL ,
`answer_id` INT( 11 ) NOT NULL,
key `result_id`(`result_id`)

) ENGINE = innodb;";

// check if post table has required field
$table_name=$wpdb->prefix . "quiz";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
    $wpdb->query($sql_quiz_table);
} 
$table_name=$wpdb->prefix . "quiz_questions";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
    $wpdb->query($sql_question_table);
} 
$table_name=$wpdb->prefix . "quiz_sections";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
    $wpdb->query($sql_section_table);
} 
$table_name=$wpdb->prefix . "quiz_badges";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
    $wpdb->query($sql_badges_table);
} 
$table_name=$wpdb->prefix . "quiz_layouts";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
    $wpdb->query($sql_layouts_table);
} 
$table_name=$wpdb->prefix . "quiz_aweber";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
    $wpdb->query($sql_aweber_table);
} 
$table_name=$wpdb->prefix . "quiz_answers";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
    $wpdb->query($sql_answers_table);
} 
 
$table_name=$wpdb->prefix . "quiz_results";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
    $wpdb->query($sql_results_table);
} 
$table_name=$wpdb->prefix . "quiz_results_data";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
    $wpdb->query($sql_results_data_table);
}


// 2nd version update
if(!quiz_check_if_field_exists($wpdb->prefix . "quiz", "skip_intro")) {
    $sql="ALTER TABLE `" . $wpdb->prefix . "quiz` ADD `skip_intro` CHAR( 1 ) NOT NULL DEFAULT 'n';";
    $wpdb->query($sql);
}
if(!quiz_check_if_field_exists($wpdb->prefix . "quiz_badges", "is_random")) {
    $sql="ALTER TABLE `" . $wpdb->prefix . "quiz_badges` ADD `is_random` CHAR( 1 ) NOT NULL DEFAULT 'n';";
    $wpdb->query($sql);
}
if(!quiz_check_if_field_exists($wpdb->prefix . "quiz_results", "badge_id")) {
    $sql="ALTER TABLE `" . $wpdb->prefix . "quiz_results` ADD `badge_id` INT( 11 ) NOT NULL DEFAULT '0';";
    $wpdb->query($sql);
}
if(!quiz_check_if_field_exists($wpdb->prefix . "quiz_aweber", "skip_optin")) {
    $sql="ALTER TABLE `" . $wpdb->prefix . "quiz_aweber` ADD `skip_optin` CHAR( 1 ) NOT NULL DEFAULT 'n';";
    $wpdb->query($sql);
}
if(!quiz_check_if_field_exists($wpdb->prefix . "quiz_badges", "meta_title")) {
    $sql="ALTER TABLE `" . $wpdb->prefix . "quiz_badges` ADD `meta_title` VARCHAR( 255 ) NOT NULL;";
    $wpdb->query($sql);
}
if(!quiz_check_if_field_exists($wpdb->prefix . "quiz_aweber", "custom_field_type")) {
    $sql="ALTER TABLE `" . $wpdb->prefix . "quiz_aweber` ADD `custom_field_type` CHAR( 1 ) NOT NULL DEFAULT 'n', ADD `custom_field_label` VARCHAR( 255 ) NOT NULL , ADD `custom_field_value` VARCHAR( 255 ) NOT NULL;";
    $wpdb->query($sql);
}
if(!quiz_check_if_field_exists($wpdb->prefix . "quiz_results", "cfield")) {
    $sql="ALTER TABLE `" . $wpdb->prefix . "quiz_results` ADD `cfield` VARCHAR( 255 ) NOT NULL ;";
    $wpdb->query($sql);
}

//2.1.0 updates


    //quiz table
if(!quiz_check_if_key_exists("{$wpdb->prefix}quiz",'status'))
$wpdb->query("create index status on {$wpdb->prefix}quiz(status)");
if(!quiz_check_if_key_exists("{$wpdb->prefix}quiz",'page_id'))
$wpdb->query("create index page_id on {$wpdb->prefix}quiz(page_id)");

//quiz questions
if(!quiz_check_if_key_exists("{$wpdb->prefix}quiz_questions",'quiz_id'))
$wpdb->query("create index quiz_id on {$wpdb->prefix}quiz_questions(quiz_id)");

//quiz answers
if(!quiz_check_if_key_exists("{$wpdb->prefix}quiz_answers",'question_id'))
$wpdb->query("create index question_id on {$wpdb->prefix}quiz_answers(question_id)");


//quiz badges
if(!quiz_check_if_key_exists("{$wpdb->prefix}quiz_badges",'quiz_id'))
$wpdb->query("create index quiz_id on {$wpdb->prefix}quiz_badges(quiz_id)");


//quiz layouts
if(!quiz_check_if_key_exists("{$wpdb->prefix}quiz_layouts",'quiz_id'))
$wpdb->query("create index quiz_id on {$wpdb->prefix}quiz_layouts(quiz_id)");

//quiz sections
if(!quiz_check_if_key_exists("{$wpdb->prefix}quiz_sections",'quiz_id'))
$wpdb->query("create index quiz_id on {$wpdb->prefix}quiz_sections(quiz_id)");

//quiz results
if(!quiz_check_if_key_exists("{$wpdb->prefix}quiz_results",'quiz_id'))
$wpdb->query("create index quiz_id on {$wpdb->prefix}quiz_results(quiz_id)");

if(!quiz_check_if_key_exists("{$wpdb->prefix}quiz_results",'email'))
$wpdb->query("create index email on {$wpdb->prefix}quiz_results(email)");

if(!quiz_check_if_key_exists("{$wpdb->prefix}quiz_results",'resultkey'))
$wpdb->query("create index resultkey on {$wpdb->prefix}quiz_results(resultkey)");


//quiz results data
if(!quiz_check_if_key_exists("{$wpdb->prefix}quiz_results",'result_id'))
$wpdb->query("create index result_id on {$wpdb->prefix}quiz_results_data(result_id)");