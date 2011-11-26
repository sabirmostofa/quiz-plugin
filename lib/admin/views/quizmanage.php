<div class="wrap">
    <h2>Manage quiz - <?php echo textarea_db_to_html($quiz->getTitle()); ?></h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>     
    <p><strong>Quiz Details</strong></p>
    <table class="widefat">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Include Social Links</th>
                <th>Number of Questions</th>
                <th>Number of Sections</th>
                <th>Number of Badges</th>
                <th>Status</th>
                <th>Tasks</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td valign="top"><?php echo $quiz->getId(); ?></td>
            <td valign="top"><?php echo textarea_db_to_html($quiz->getTitle()); ?></td>
            <td valign="top"><?php echo $quiz->shouldIncludeSocialLinks()?"Yes":"No"; ?></td>
            <td valign="top"><?php echo $quiz->getNumQuestions(); ?></td>
            <td valign="top"><?php echo $quiz->getNumSections(); ?></td>
            <td valign="top"><?php echo $quiz->getNumBadges(); ?></td>
            <td valign="top"><?php echo $quiz->isPublished()?"Publish":"Draft"; ?></td>
            <td valign="top"><a href="<?php echo quiz_get_admin_url(array("task" => "editquiz" , "quizid" => $quiz->getId())); ?>">Edit</a> | <a target="_blank" href="<?php echo get_permalink($quiz->getPageId()); ?>">View</a> | <?php
             if($quiz->isPublished()):
             ?>
             <a href="<?php echo quiz_get_admin_url(array("unpublishquiz" => $quiz->getId()) , array("kpage", "task", "quizid")); ?>">Unpublish</a> | 
             <?php else: ?>
             <a href="<?php echo quiz_get_admin_url(array("publishquiz" => $quiz->getId()), array("kpage",  "task", "quizid")); ?>">Publish</a> | 
             <?php endif; ?><a href="<?php echo quiz_get_admin_url(array("deletequiz" => $quiz->getId()), array("kpage")); ?>" onclick="return quiz_delete_confirm()">Delete</a></td>
        </tr>
        </tbody>
    </table>

    <p><strong>Quiz Pages Details</strong></p>
    <?php include dirname(__FILE__) . '/quizmanage-include0.php'; ?>

    <p><strong>Questions, Section Pages, and Answers</strong><br /><div style="float: left"><span class="small">Click & Drag Question and Section to reorder</span></div><div style="float: right">
    <div id="qseq-loading"><img src="<?php echo QUIZ_URL; ?>/images/loading.gif" /> Updating Order </div>
    <div id="qseq-loading-msg"></div>
    <a href="<?php echo quiz_get_admin_url(array("task" => "addquestion", "quizid" => $_GET["quizid"])); ?>">Add Question</a> | <a href="<?php echo quiz_get_admin_url(array("task" => "addsection", "quizid" => $_GET["quizid"])); ?>">Add Section</a></div><div class="clear"></div></p>
    <?php include dirname(__FILE__)  . "/quizmanage-include1.php"; ?>
    
    <p><div style="float: left;"><strong>Badges</strong></div><div style="float: right">
    <a href="<?php echo quiz_get_admin_url(array("task" => "addbadge", "quizid" => $_GET["quizid"])); ?>">Add Badge</a></div><div class="clear"></div></p>
    <?php include dirname(__FILE__)  . "/quizmanage-include2.php"; ?> 
</div>
<?php quiz_pageadmin_footer(); ?> 
<script type="text/javascript"> 
function quiz_delete_confirm() {
    return confirm('Are you sure you want to delete this item?');
}
</script>