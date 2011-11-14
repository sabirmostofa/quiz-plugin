<div class="wrap">
    <h2>Options</h2>
    <?php quiz_render_messages(); ?>
    <div class="stylizedform" id="poststuff">
    <form id="quizform"  method="post" action="">
        <fieldset>
         <?php
           $deleteAtDeactivate=(bool)get_option('quiz_option_delete_at_deactivate');
           $k=' checked="checked" ';
         ?>
         <div class="dlabel">Delete all data at deactivate</div>
         <input  tabindex="1"  type="checkbox" name="quiz_option_delete_at_deactivate" <?php if($deleteAtDeactivate) echo $k; ?> />

         <p>
         <input class="button-primary"  tabindex="5" type="submit" value="Save Changes" />
         </p>
         <input type="hidden" name="ftask" value="option" />
        </fieldset>
    </form>

    </div>
</div>
<?php quiz_pageadmin_footer(); ?> 