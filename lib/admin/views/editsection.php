<div class="wrap">
    <h2><?php echo quiz_get_admin_title(); ?>
    <?php if($section):
            $vlink="";
            $sequence=$quiz->getSequence();
            $seqLen=count($sequence);
            if($seqLen) {
                $pbo=0;
                foreach($sequence as $seq) {
                    $pbo++;
                    $pborder=$pbo;
                    if($pborder < 10)
                        $pborder="0" . $pborder;
                    if(get_class($seq) == "QuizSection" && $seq->getId() == $section->getId()) {
                        $vlink=get_permalink($quiz->getPageId()) . $pborder . '/section/';
                    }
                }
            }
            ?>
        <a class="button add-new-h2" href="<?php echo $vlink; ?>" target="_blank" >View</a>
        <?php endif; ?>
    </h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>
    <div class="stylizedform" id="poststuff">      
    <form id="quizform"  method="post" action="">
        <fieldset>
        <div class="dlabel">Page Title *<br />
        <span class="small">Available Code: <?php quiz_insert_tag_ht('title', 'quiz-title'); ?></span></div>
        <input class="text required" type="text" tabindex="1"  name="title" id="title" value="<?php if($section) echo db_to_textfield($section->getTitle()); ?>" />
        
         <div class="dlabel">Content</div>
         <?php 
         $desc="";
         if($section) $desc=$section->getDescription();
         quiz_render_main_editor($desc, "description", 2); ?>
         
         
         <br />
         <div class="dlabel">Display Page Title</div>
         <input type="checkbox"  tabindex="4"  value="y" name="display_title" id="display_title" <?php $ch=true; if($section && !$section->shouldDisplayTitle()) $ch=false; if($ch) echo 'checked="checked"'; ?> />

         <div class="dlabel">Show Title on Results Page</div>
         <input type="checkbox"  tabindex="3"  value="y" name="display_at_result" id="display_at_result" <?php $ch=true; if($section && !$section->shouldDisplayTitleAtResult()) $ch=false; if($ch) echo 'checked="checked"'; ?> />
         
         
         <p>
         <input class="button-primary" type="submit" value="Update section" />
         </p>
         <input type="hidden" name="ftask" value="editsectionsubmit" />
         <input type="hidden" name="quiz_id" value="<?php echo $quiz->getId(); ?>" />
         <input type="hidden" name="id" value="<?php echo $section->getId(); ?>" />
         
        </fieldset>
    </form>
    </div>
</div>
<?php quiz_pageadmin_footer(); ?> 