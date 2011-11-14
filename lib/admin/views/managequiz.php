<div class="wrap">
    <h2>Manage quizzes <a class="button" href="<?php echo quiz_get_admin_url(array("page" => QUIZ_SHORTCODE . "-addquiz")); ?>">Add Quiz</a></h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>            
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
        <?php 
        $i=0;
        if(count($quizzes)):
        foreach($quizzes as $quiz): ?>
        <tr <?php if($i % 2 == 0) echo ' class="alternate" '; ?>>
            <td valign="top"><?php echo $quiz->getId(); ?></td>
            <td valign="top"><?php echo textarea_db_to_html($quiz->getTitle()); ?></td>
            <td valign="top"><?php echo $quiz->shouldIncludeSocialLinks()?"Yes":"No"; ?></td>
            <td valign="top"><?php echo $quiz->getNumQuestions(); ?></td>
            <td valign="top"><?php echo $quiz->getNumSections(); ?></td>
            <td valign="top"><?php echo $quiz->getNumBadges(); ?></td>
            <td valign="top"><?php echo $quiz->isPublished()?"Publish":"Draft"; ?></td>
            <td valign="top" align="right" width="260px"><?php
             if($quiz->isPublished()):
             ?>
             <a href="<?php echo quiz_get_admin_url(array("unpublishquiz" => $quiz->getId()) , array("kpage")); ?>">Unpublish</a> | 
             <?php else: ?>
             <a href="<?php echo quiz_get_admin_url(array("publishquiz" => $quiz->getId()), array("kpage")); ?>">Publish</a> | 
             <?php endif; ?>
             <a href="<?php echo quiz_get_admin_url(array("duplicatequiz" => $quiz->getId()) , array("kpage")); ?>">Duplicate</a> |
             <a href="<?php echo quiz_get_admin_url(array("task" => "quizmanage" , "quizid" => $quiz->getId())); ?>">Manage</a> | <a href="<?php echo quiz_get_admin_url(array("task" => "editquiz" , "quizid" => $quiz->getId())); ?>">Edit</a> | <a href="<?php echo quiz_get_admin_url(array("deletequiz" => $quiz->getId()), array("kpage")); ?>" onclick="return quiz_delete_confirm()">Delete</a> | <a  target="_blank"  href="<?php echo get_permalink($quiz->getPageId()); ?>">View</a></td>
        </tr>
        <?php
        $i++;
        endforeach;
        else: ?>
        <tr><td  colspan="8">No results found</td></tr>
        <?php endif; ?>
        </tbody>
        <tfoot>
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
        </tfoot>
    </table>
    <div class="tablenav">
        <div class="tablenav-pages">
        <span class="displaying-num">Displaying <?php echo $minLimit + 1; ?> - <?php echo $minLimit + count($quizzes); ?> of <?php echo $totalQuizzes; ?></span>
        <?php if($totalPages > 1): ?>
            <?php 
                  $status="all";
                  if(isset($_GET["status"]))
                    $status=$_GET["status"];
            
            for($i=1; $i<=$totalPages; $i++) {
                if($currentPage == $i) {
                   ?>
                   <span class="page-numbers current"><?php echo $i; ?></span>
                   <?php
                }    
                else {
                  ?>
                    <a class="page-numbers" href="<?php echo quiz_get_admin_url(array("kpage" => $i, "status" => $status)); ?>"><?php echo $i; ?></a>
                  <?php     
                }                                                                                          
            }                                                                                                                   
            if($currentPage < $totalPages ) {
                ?>
                <a class="next page-numbers" href="<?php echo quiz_get_admin_url(array("kpage" => $currentPage + 1 , "status" => $status)); ?>" >&raquo;</a>
                <?php   
            }
            ?>
        <?php endif; ?>
        </div>
    </div>
</div>
<?php quiz_pageadmin_footer(); ?> 
<script type="text/javascript"> 
function quiz_delete_confirm() {
    return confirm('Are you sure you want to delete this item?');
}
</script>