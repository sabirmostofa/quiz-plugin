<?php
function quiz_add_page($title, $content, $status) {
    $d=array(
       'post_title' => $title,
       'post_content' => $content,
       'post_type' => 'page',
       'post_status' => $status
    );
    $id=wp_insert_post($d);   
    if(is_numeric($id) && $id > 0 )
        return $id;
    return false;
}
function quiz_render_messages($echo=true) {
    $messages=QuizMessages::getAllMessages();
    if($messages):
    if(!$echo)
        ob_start();
    ?>
    <div class="quiz-messages">
    <?php foreach($messages as $msg): ?>
        <div class="message-<?php echo $msg->getType(); ?>">
            <?php echo $msg->getMessage(); ?>
        </div>
    <?php endforeach; ?>
    </div>
    <?php
    if(!$echo)
        return ob_get_clean();
    endif;
}
function quiz_sequence_sort($v1, $v2) {
    $do1=$v1->getDisplayOrder();
    $do2=$v2->getDisplayOrder();
    return intval($do1)-intval($do2);
}
function quiz_process_string($string, $replacements) {
    return str_replace(array_keys($replacements) , array_values($replacements) , $string);
}
function quiz_insert_tag_ht($target, $tag, $isTinyMce=false) {
    if(strpos($tag, ",") !== FALSE) {
        $tags=explode("," , $tag);
        $first=true;
        foreach($tags as $tag):
        $tag=trim($tag);
        if($tag) {
            if(!$first)
                echo ', ';
            quiz_insert_tag_ht($target, $tag, $isTinyMce);
            $first=false;
        }
        endforeach;
        return;
    }
    ?>
    <a href="#" onclick="return quiz_insert_tag('#<?php echo $target; ?>', '{<?php echo $tag; ?>}', <?php if($isTinyMce) echo 1; else echo 0; ?>)">{<?php echo $tag; ?>}</a>
    <?php
}