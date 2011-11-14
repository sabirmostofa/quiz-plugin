<div class="wrap">
    <h2>Manage badges</h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>            
    <table class="widefat">
        <thead>
            <tr>
                <th>ID</th>
                <th>Content</th>
                <th>Range Minimum</th>
                <th>Range Maximum</th>
                <th>Tasks</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $i=0;
        if(count($badges)):
        foreach($badges as $badge): ?>
        <tr <?php if($i % 2 == 0) echo ' class="alternate" '; ?>>
            <td valign="top"><?php echo $badge->getId(); ?></td>
            <td valign="top"><?php echo $badge->getContentPortion(50); ?>...</td>
            <td valign="top"><?php echo $badge->getRangeMin(); ?></td>
            <td valign="top"><?php echo $badge->getRangeMax(); ?></td>
            <td valign="top"><a href="<?php echo quiz_get_admin_url(array("task" => "editbadge" , "quizid" => $quiz->getId(), "badgeid" => $badge->getId())); ?>">Edit</a> | <a href="<?php echo quiz_get_admin_url(array("deletebadge" => $badge->getId() , "task" => "managebadges" , "quizid" => $quiz->getId())); ?>" onclick="return quiz_delete_confirm()">Delete</a>
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
                <th>Range Minimum</th>
                <th>Range Maximum</th>
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