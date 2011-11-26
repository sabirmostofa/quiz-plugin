<?php

if (!function_exists("textarea_to_db")):

    function textarea_to_db($text) {
        $text = esc_html($text);
        return $text;
    }

    function textarea_db_to_html($text) {
        $text = html_entity_decode(stripslashes_deep($text), ENT_QUOTES);
        return $text;
    }

    function wysiwyg_to_db($text) {
        $text = wpautop($text);
        //$text=esc_html($text);
        return $text;
    }

    function wysiwyg_db_to_html($text) {
        $text = html_entity_decode(stripslashes_deep($text), ENT_QUOTES);
        return $text;
    }

    function textarea_db_to_textarea($text) {
        return stripslashes($text);
    }

    function wysiwyg_db_to_textarea($text) {
        return stripslashes($text);
    }

    function wysiwyg_db_to_html_source($text) {
        return htmlentities(stripslashes($text));
    }

    function textarea_db_to_html_source($text) {
        return htmlentities(stripslashes($text));
    }

    function db_to_textfield($text) {
        return stripslashes(esc_attr($text));
    }

endif;

function quiz_check_if_field_exists($table, $field) {
    $fields = mysql_list_fields(DB_NAME, $table);

    $columns = mysql_num_fields($fields);

    for ($i = 0; $i < $columns; $i++) {
        $field_array[] = mysql_field_name($fields, $i);
    }
    return in_array($field, $field_array);
}

function quiz_check_if_key_exists($table, $column) {
global $wpdb;
return $wpdb ->get_var("select index_name from information_schema.statistics where table_schema ='".DB_NAME."' and table_name='$table' and column_name='$column' ");
}

function quiz_self_url() {
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $protocol = quiz_strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/") . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function quiz_strleft($s1, $s2) {
    return substr($s1, 0, strpos($s1, $s2));
}