<div class="wrap">
    <h2><?php echo quiz_get_admin_title(); ?></h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>
    <div class="stylizedform" id="poststuff">      
    <form id="quizform"  method="post" action="">
        <fieldset>

            <div class="dlabel">Title<br />
            <span class="small">Badge title will be available in Result Page Title field as a code</span></div>
            <input class="text" type="text" tabindex="1"  name="meta_title" id="meta_title" value="<?php if($badge) echo db_to_textfield($badge->getMetaTitle()); ?>" />

        <div class="dlabel">Content<br />
         <span class="small">Available Codes: <?php quiz_insert_tag_ht('content', 'quiz-title,first-name,last-name,email,result-link,numviews,totalscore,custom-field', true); ?></span></div>
        <?php 
         $desc="";
         if($badge) $desc=$badge->getContent(); 
         quiz_render_main_editor($desc, "content", 1); ?>
         
         <?php
  $range=$quiz->getValidBadgeRange();
?>
        <div class="dlabel">Is Random</div>
        <input type="checkbox" onchange="isRandomCb();" tabindex="2" value="y" name="is_random" id="is_random" <?php if($badge && $badge->isRandom()): ?>checked="checked"<?php endif; ?> />

        <div id="rangec">
         <div class="dlabel">Range Min (<?php echo $range["min"]; ?>-<?php echo $range["max"]; ?>)<br />
         <span class="small">Minimum total value. Must be a positive number</span></div>
         <input type="text"  tabindex="3"  class="" name="range_min" id="range_min" value="<?php  if($badge) echo $badge->getRangeMin(); else echo "0"; ?>" />
         
         <div class="dlabel">Range Max (<?php echo $range["min"]; ?>-<?php echo $range["max"]; ?>)<br />
         <span class="small">Maximum total value. Must be a positive number. 0 means unlimited</span></div>
         <input type="text"  tabindex="4" name="range_max" id="range_max" class="" value="<?php  if($badge) echo $badge->getRangeMax(); else echo "0"; ?>" />
        </div>
        
         <div class="dlabel">Meta Description<br />
         <span class="small">This description will be used on the result page (in case user of that result page won this badge) and consecutively it will be used in Facebook Share description. Available Codes: <?php quiz_insert_tag_ht('meta_description', 'quiz-title,first-name,last-name,email,result-link,numviews,totalscore,custom-field'); ?></span></div>
         <textarea name="meta_description" id="meta_description" tabindex="5" ><?php  if($badge) echo textarea_db_to_textarea($badge->getMetaDescription()); ?></textarea>
         
         <p>
         <input class="button-primary"  tabindex="6"  type="submit" value="Add badge" />
         </p>
         <input type="hidden" name="ftask" value="addbadgesubmit" />
         <input type="hidden" name="quiz_id" value="<?php echo $quiz->getId(); ?>" />
         
        </fieldset>
    </form>
    </div>
</div>                            
<?php quiz_pageadmin_footer(); ?> 
<script type="text/javascript">
function isRandomCb() {
    if(jQuery('#is_random').is(":checked")) {
        jQuery("#rangec").hide();
    }
    else {
        jQuery("#rangec").show();
    }
}
function isRangeProper(value, element) {
    element=jQuery(element);
    
    var rangeMinE=jQuery("#range_min");
    var rangeMaxE=jQuery("#range_max");
    var rangeMinV=parseInt(rangeMinE.val());
    var rangeMaxV=parseInt(rangeMaxE.val());
    
    
    var currentRangeMin=false;
    if(element.attr("id") == "range_min")
        currentRangeMin=true;
    
    // if we are checking range min
    if(currentRangeMin) {
        //if max value is non zero then it must be greater than min value
        if(rangeMaxV > 0 ) {
            if(rangeMinV >= rangeMaxV) {
                return false;   
            }
        }   
    }
    else {
        // if we are checking range max
        //if max value is non zero then it must be greater than min value
        if(rangeMaxV > 0 ) {
            if(rangeMinV >= rangeMaxV) {
                return false;   
            }
        }   
        
    }
        
    return true;   
}

jQuery(document).ready(function(){

jQuery.validator.addMethod("isRangeProper", isRangeProper, 'Please provide number in proper range.');
isRandomCb();

});
</script>