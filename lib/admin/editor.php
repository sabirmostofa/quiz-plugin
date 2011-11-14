<?php
function the_keditor($content, $id = 'content', $prev_id = 'title', $media_buttons = true, $tab_index = 2) {
    $rows = get_option('default_post_edit_rows');
    if (($rows < 3) || ($rows > 100))
        $rows = 12;

    if ( !current_user_can( 'upload_files' ) )
        $media_buttons = false;

    $richedit =  user_can_richedit();
    $class = '';

    if ( $richedit || $media_buttons ) { ?>
    <div id="editor-toolbar-<?php echo $id; ?>">
<?php
    if ( $richedit ) {
        $wp_default_editor = wp_default_editor(); ?>
        <div class="zerosize"><input accesskey="e" type="button" onclick="switchEditors.go('<?php echo $id; ?>')" /></div>
<?php    if ( 'html' == $wp_default_editor ) {
            add_filter('the_editor_content', 'wp_htmledit_pre'); ?>
            <a id="edButtonHTML-<?php echo $id; ?>" class="active hide-if-no-js" onclick="switchEditors.go('<?php echo $id; ?>', 'html');"><?php _e('HTML'); ?></a>
            <a id="edButtonPreview-<?php echo $id; ?>" class="hide-if-no-js" onclick="switchEditors.go('<?php echo $id; ?>', 'tinymce');"><?php _e('Visual'); ?></a>
<?php    } else {
            $class = " class='theEditor'";
            add_filter('the_editor_content', 'wp_richedit_pre'); ?>
            <a id="edButtonHTML-<?php echo $id; ?>" class="hide-if-no-js" onclick="switchEditors.go('<?php echo $id; ?>', 'html');"><?php _e('HTML'); ?></a>
            <a id="edButtonPreview-<?php echo $id; ?>" class="active hide-if-no-js" onclick="switchEditors.go('<?php echo $id; ?>', 'tinymce');"><?php _e('Visual'); ?></a>
<?php    }
    }

    if ( $media_buttons ) { ?>
        <div id="media-buttons-<?php echo $id; ?>" class="hide-if-no-js">
<?php    do_action( 'media_buttons' ); ?>
        </div>
<?php
    } ?>
    </div>
<?php
    }
?>
    <div id="quicktags-<?php echo $id; ?>"><?php
    wp_print_scripts( 'quicktags' ); ?>
    <script type="text/javascript">edToolbar()</script>
    </div>

<?php
    $the_editor = apply_filters('the_editor', "<div id='editorcontainer-$id'><textarea rows='$rows'$class cols='40' name='$id' tabindex='$tab_index' id='$id'>%s</textarea></div>\n");
    $the_editor_content = apply_filters('the_editor_content', $content);

    printf($the_editor, $the_editor_content);

?>
    <script type="text/javascript">
    edCanvas-<?php echo $id; ?> = document.getElementById('<?php echo $id; ?>');
    </script>
<?php
}