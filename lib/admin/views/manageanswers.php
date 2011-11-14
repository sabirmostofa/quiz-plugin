<div class="wrap">
    <h2>Manage answers</h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>            
    <table class="widefat">
        <thead>
            <tr>
                <th>ID</th>
                <th>Content</th>
                <th>Response</th>
                <th>Value</th>
                <th width="100px">Tasks</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $i=0;
        if(count($answers)):
        foreach($answers as $answer): ?>
        <tr <?php if($i % 2 == 0) echo ' class="alternate" '; ?>>
            <td valign="top"><?php echo $answer->getId(); ?></td>
            <td valign="top"><?php echo htmlspecialchars($answer->getContent()); ?>...</td>
            <td valign="top"><?php echo htmlspecialchars($answer->getResponse()); ?></td>
            <td valign="top"><?php echo $answer->getValue(); ?></td>
            <td valign="top"><a href="<?php echo quiz_get_admin_url(array("task" => "editanswer" , "quizid" => $quiz->getId(), "questionid" => $question->getId(), "answerid" => $answer->getId())); ?>">Edit</a> | <a href="<?php echo quiz_get_admin_url(array("deleteanswer" => $answer->getId() , "task" => "manageanswers" , "quizid" => $quiz->getId(), "questionid" => $question->getId())); ?>" onclick="return quiz_delete_confirm()">Delete</a>
        </tr>
        <?php
        $i++;
        endforeach;
        else: ?>
        <tr><td  colspan="5">No results found</td></tr>
        <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Content</th>
                <th>Response</th>
                <th>Value</th>
                <th>Tasks</th>
            </tr>
        </thead>
    </table>
</div>
<?php quiz_pageadmin_footer(); ?> 
<script type="text/javascript"> 
function quiz_delete_confirm() {
    return confirm('Are you sure you want to delete this item?');
}
</script>