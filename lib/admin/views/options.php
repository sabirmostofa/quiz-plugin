<?php
global $wpdb;
if(isset($_POST['opt-tabs'])):
    $tabs = array("quiz_results", "quiz_results_data");
foreach($tabs as $tab):
   $wpdb->query("optimize table {$wpdb->prefix}$tab");
$opt_msg = 'Tables Optimized Successfully';
endforeach;
    
endif;

if(isset($_POST['del-in'])):
    
    $ids=$wpdb->get_col("SELECT id FROM `{$wpdb->prefix}quiz_results` WHERE status='i'");
    $num = count($ids);
    $limit=1000;
  $done = $num-1000;
if($num>1000)
     $wpdb->query("Delete from  `{$wpdb->prefix}quiz_results` where status='i' limit $done ");      

  
 $del_msg= ($num>1000)? "{$done} Incomplete Results Have Been Deleted Successfully":"No Data Was Deleted";
      
    
endif;
$del_msg = isset($del_msg)?$del_msg:'';
$opt_msg = isset($opt_msg)?$opt_msg:'';
?>

<div class="wrap">
    <h2>Viral Conversion Options</h2>
    <?php quiz_render_messages(); ?>
    <h2>Database Maintenance</h2>
        <p>NOTE - For high traffic sites, it is a good idea to periodically delete incomplete results and optimize your tables. This helps to ensure your database queries are executed as quickly and efficiently as possible.</p>
    <div id="vc-extra-func">
        <h3>Delete Incomplete Results</h3>
          <div class="updated"><?php echo $del_msg ?></div>
    <form method="post" action="">
      
        <input class="button-primary" type="submit" value="Delete Now" name="del-in"/>
        
    </form>
        <h3>Optimize Tables</h3>
      <div class="updated"><?php echo $opt_msg ?></div>
         <form method="post" action="">
  
        <input class="button-primary" type="submit" value="Optimize" name="opt-tabs"/>
        
    </form>
    <br/>    
    <hr/>
    <br/>    
    <h2>Deactivation Options</h2>
    <div class="stylizedform" id="poststuff">
    <form id="quizform"  method="post" action="">
        <fieldset>
         <?php
           $deleteAtDeactivate=(bool)get_option('quiz_option_delete_at_deactivate');
           $k=' checked="checked" ';
         ?>
         <div class="dlabel">Delete all data at deactivate (leave this unchecked, unless you want all of your quizzes deleted)</div>
         <input  tabindex="1"  type="checkbox" name="quiz_option_delete_at_deactivate" <?php if($deleteAtDeactivate) echo $k; ?> />

         <p>
         <input class="button-primary"  tabindex="5" type="submit" value="Save Changes" />
         </p>
         <input type="hidden" name="ftask" value="option" />
        </fieldset>
    </form>
        
        

    </div>
    </div>
</div>
<?php quiz_pageadmin_footer(); ?> 