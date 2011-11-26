<div class="wrap">
    <h2><?php echo quiz_get_admin_title(); ?></h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>
    <div class="stylizedform" id="poststuff"> 
    <form id="quizform"  method="post" action="">      
    <fieldset>        
         <div class="dlabel"><?php echo ucfirst($layoutType); ?> Space</div>
         <?php 
         $desc="";
         if($layout) {
             $fn="get" . ucfirst($layoutType) . "Content";
             $desc=$layout->$fn();
         }
         quiz_render_main_editor($desc, $layoutType . "_content" , 1); ?>
         
         <p>
         <input class="button-primary"  tabindex="2"  type="submit" value="Save Changes" />
         </p>
         <input type="hidden" name="ftask" value="configurelayoutcc" />
         <input type="hidden" name="quiz_id" value="<?php echo $quiz->getId(); ?>" />
         <?php if($layout && $layout->getId()): ?>
         <input type="hidden" name="id" value="<?php echo $layout->getId(); ?>" />
         <?php endif;?>
        </fieldset>
    </form>
    
    </div>
</div>
<?php quiz_pageadmin_footer(); ?> 