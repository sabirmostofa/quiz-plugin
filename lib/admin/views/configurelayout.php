<div class="wrap">
    <h2><?php echo quiz_get_admin_title(); ?></h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>
    <div class="stylizedform" id="poststuff"> 
    <fieldset>        
    <p>
        You can provide content for the header, left, right, or bottom space of the quiz. The content will be used in all the pages you choose below.
    </p>
    <p><strong>Header Space</strong><br />
    <?php if($layout) echo substr(wysiwyg_db_to_html_source($layout->getHeaderContent()) , 0 , 200); ?>...
    <br /><a href="<?php echo quiz_get_admin_url(array("task" => "configurelayoutheader") , array("quizid")); ?>">Update Header Space</a>
    </p>
    <p><strong>Left Space</strong><br />
    <?php if($layout) echo substr(wysiwyg_db_to_html_source($layout->getLeftContent()) , 0 , 200); ?>...
    <br /><a href="<?php echo quiz_get_admin_url(array("task" => "configurelayoutleft") , array("quizid")); ?>">Update Left Space</a>
    </p>
    <p><strong>Right Space</strong><br />
    <?php if($layout) echo substr(wysiwyg_db_to_html_source($layout->getRightContent()) , 0 , 200); ?>...
    <br /><a href="<?php echo quiz_get_admin_url(array("task" => "configurelayoutright") , array("quizid")); ?>">Update Right Space</a>
    </p>
    <p><strong>Bottom Space</strong><br />
    <?php if($layout) echo substr(wysiwyg_db_to_html_source($layout->getBottomContent()) , 0 , 200); ?>...
    <br /><a href="<?php echo quiz_get_admin_url(array("task" => "configurelayoutbottom") , array("quizid")); ?>">Update Bottom Space</a>
    </p>
    </fieldset>
    <form id="quizform"  method="post" action="">
        <fieldset>
         <?php
           $useAtQuestion=true;
           $useAtSection=true;
           $useAtOptin=true;
           $useAtResult=true;
           
           if($layout) {
                $useAtQuestion=$layout->shouldUseAtQuestion();   
                $useAtSection=$layout->shouldUseAtSection();   
                $useAtOptin=$layout->shouldUseAtOptin();   
                $useAtResult=$layout->shouldUseAtResult();   
           }
           
           
           $k=' checked="checked" ';
         ?>
         <p><strong>Display Ad Space on the following pages</strong></p>
         <div class="dlabel">Display on question page</div>
         <input  tabindex="1"  type="checkbox" name="use_at_question" id="use_at_question" <?php if($useAtQuestion) echo $k; ?> />
         
         <div class="dlabel">Display on section page</div>
         <input  tabindex="2" type="checkbox" name="use_at_section" id="use_at_section" <?php if($useAtSection) echo $k; ?> />
         
         <div class="dlabel">Display on optin page</div>
         <input  tabindex="3" type="checkbox" name="use_at_optin" id="use_at_optin" <?php if($useAtOptin) echo $k; ?> />
         
         <div class="dlabel">Display on result page</div>
         <input  tabindex="4" type="checkbox" name="use_at_result" id="use_at_result" <?php if($useAtResult) echo $k; ?> />
         
         
         <p>
         <input class="button-primary"  tabindex="5" type="submit" value="Save Changes" />
         </p>
         <input type="hidden" name="ftask" value="configurelayout" />
         <input type="hidden" name="quiz_id" value="<?php echo $quiz->getId(); ?>" />
         <?php if($layout && $layout->getId()): ?>
         <input type="hidden" name="id" value="<?php echo $layout->getId(); ?>" />
         <?php endif;?>
         
        </fieldset>
    </form>
    
    </div>
</div>
<?php quiz_pageadmin_footer(); ?> 