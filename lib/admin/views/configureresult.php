<div class="wrap">
    <h2><?php echo quiz_get_admin_title(); ?></h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>
    <div class="stylizedform" id="poststuff">      
    <form id="quizform"  method="post" action="">
        <fieldset>
        <div class="dlabel">Page Title *<br />
        <span class="small">Available Codes: <?php quiz_insert_tag_ht('result_title', 'quiz-title,badge-title,first-name,last-name,email,result-link,numviews,totalscore,custom-field'); ?></span></div>
        <input class="text required" type="text" tabindex="1"  name="result_title" id="result_title" value="<?php echo db_to_textfield($quiz->getResultTitle()); ?>" />
        
        <div class="dlabel">Display Page Title</div>
         <input type="checkbox"  tabindex="2"  value="y" name="result_display_title" id="result_display_title" <?php $ch=true; if(!$quiz->shouldDisplayResultTitle()) $ch=false; if($ch) echo 'checked="checked"'; ?> />
        
        <div class="dlabel">Include Social Links</div>
         <input type="checkbox" name="include_social_links" id="include_social_links" <?php 
            
            $include_social_links=true;
            if($quiz)
                $include_social_links=$quiz->shouldIncludeSocialLinks();
            if($include_social_links)
                echo " checked='checked' ";
         ?> />
        
         <div class="dlabel">Content<br />
        <span class="small">Available Codes: <?php quiz_insert_tag_ht('result', 'quiz-title,badge-title,first-name,last-name,email,result-link,numviews,totalscore,custom-field', true); ?></span></div>
         <?php 
         $desc=$quiz->getResult();
         quiz_render_main_editor($desc, "result", 3); ?>
         <p>
         <input class="button-primary" type="submit" tabindex="4" value="Save Changes" />
         </p>
         <input type="hidden" name="ftask" value="configureresult" />
         <input type="hidden" name="id" value="<?php echo $quiz->getId(); ?>" />
         
        </fieldset>
    </form>
    </div>
</div>
<?php quiz_pageadmin_footer(); ?> 