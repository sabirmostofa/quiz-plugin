<?php
  $range=$quiz->getValidBadgeRange();
?>
<table class="widefat">
    <thead>
        <tr>
            <th>ID</th>
            <th>Content</th>
            <th>Range Minimum(<?php echo $range["min"]; ?>)</th>
            <th>Range Maximum(<?php echo $range["max"]; ?>)</th>
            <th>Is Random</th>
            <th>Tasks</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $badges=$quiz->getBadges();
    $i=0;
    if($badges && count($badges)):
    foreach($badges as $badge): ?>
    <tr <?php if($i % 2 == 0) echo ' class="alternate" '; ?>>
        <td valign="top"><?php echo $badge->getId(); ?></td>
        <td valign="top"><?php echo $badge->getContentPortion(100); ?>...</td>
        <td valign="top"><?php echo $badge->isRandom()?"--":$badge->getRangeMin(); ?></td>
        <td valign="top"><?php echo $badge->isRandom()?"--":$badge->getRangeMax(); ?></td>
        <td valign="top"><?php echo $badge->isRandom()?"Yes":"No"; ?></td>
        <td valign="top"><a href="<?php echo quiz_get_admin_url(array("task" => "editbadge" , "quizid" => $quiz->getId(), "badgeid" => $badge->getId())); ?>">Edit</a> | <a href="<?php echo quiz_get_admin_url(array("deletebadge" => $badge->getId() , "task" => "quizmanage" , "quizid" => $quiz->getId())); ?>" onclick="return quiz_delete_confirm()">Delete</a>
    </tr>
    <?php
    $i++;
    endforeach;
    else: ?>
    <tr><td  colspan="5">No results found</td></tr>
    <?php endif; ?>
    </tbody>
</table>