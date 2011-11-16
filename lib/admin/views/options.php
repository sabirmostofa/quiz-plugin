<?php
global $wpdb;
if(isset($_POST['opt-tabs'])):
    $tabs = array("quiz_results", "quiz_results_data");
foreach($tabs as $tab):
   $wpdb->query("optimize table {$wpdb->prefix}$tab");
$opt_msg = 'Tables optmized successfully';
endforeach;
    
endif;

if(isset($_POST['del-in'])):
    
    $ids=$wpdb->get_col("SELECT id FROM `{$wpdb->prefix}quiz_results` WHERE status='i'");
    $num = count($ids);
    $limit=1000;
  $done = $num-1000;
if($num>1000)
     $wpdb->query("Delete from  `{$wpdb->prefix}quiz_results` where status='i' limit $done ");      

  
 $del_msg= ($num>1000)? "{$done}Records has been deleted successfully":"No Data was deleted";
      
    
endif;
$del_msg = isset($del_msg)?$del_msg:'';
$opt_msg = isset($opt_msg)?$opt_msg:'';
?>

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
    <h2>Database Maintainance</h2>
    <div id="vc-extra-func">
        <h3>Delete Incomplete Results</h3>
          <div class="updated"><?php echo $del_msg ?></div>
    <form method="post" action="">
      
        <input class="button-primary" type="submit" value="Delete now" name="del-in"/>
        
    </form>
        <h3>Optimize Tables</h3>
      <div class="updated"><?php echo $opt_msg ?></div>
         <form method="post" action="">
  
        <input class="button-primary" type="submit" value="Optmize" name="opt-tabs"/>
        
    </form>
        
        
    </div>
</div>
<?php quiz_pageadmin_footer(); ?> 