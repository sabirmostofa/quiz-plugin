<div class="wrap">
    <h2><?php echo quiz_get_admin_title(); ?></h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>
    <div class="stylizedform" id="poststuff">      
    <form id="quizform"  method="post" action="">
        <fieldset>
         <div class="dlabel">Title *<br />
        <span class="small">Available Code: <?php quiz_insert_tag_ht('title', 'quiz-title'); ?></span></div>
        <input class="text required" type="text" tabindex="1"  name="title" id="title" value="<?php if($aweber) echo db_to_textfield($aweber->getTitle()); ?>" />
        
        <div class="dlabel">Skip Optin Page</div>
         <input type="checkbox"  tabindex="2"  value="y" name="skip_optin" id="skip_optin" <?php $ch=false; if($aweber && $aweber->shouldSkipOptin()) $ch=true; if($ch) echo 'checked="checked"'; ?> />
         

         <div class="dlabel">Display Page Title</div>
         <input type="checkbox"  tabindex="2"  value="y" name="display_title" id="display_title" <?php $ch=true; if($aweber && !$aweber->shouldDisplayTitle()) $ch=false; if($ch) echo 'checked="checked"'; ?> />
         
         

         <div class="dlabel">Content<br />
        <span class="small">Available Code: <?php quiz_insert_tag_ht('content', 'result-link', true); ?></span></div>
         <?php 
         $desc="";
         if($aweber) $desc=$aweber->getContent();
         quiz_render_main_editor($desc, "content", 3); ?>
        
         <a href="http://aweber.com/?366408" target="_blank" style="color: #00F; font-size: 105%; font-weight: bold;">Sign Up For an aWeber Account</a>
         <div class="dlabel">aWeber List Name *<br />
         <span class="small">Listname of the list of your aweber account where all the optin data for this quiz must be submitted</span></div>
         <input type="text required" name="listname" tabindex="4"  id="listname" value="<?php if($aweber) echo $aweber->getListname(); ?>" />
         
         <div class="dlabel">First Name Label *<br />
         <span class="small">Label for first name field</span></div>
         <input type="text required" name="firstname_label" tabindex="5" id="firstname_label" value="<?php if($aweber) echo $aweber->getFirstnameLabel(); else echo "First Name"; ?>" />
         
         <div class="dlabel">Last Name Label *<br />
         <span class="small">Label for last name field</span></div>
         <input type="text required" name="lastname_label" tabindex="6"  id="lastname_label" value="<?php if($aweber) echo $aweber->getLastnameLabel(); else echo "Last Name"; ?>" />
         
         <div class="dlabel">Email Label *<br />
         <span class="small">Label for email field</span></div>
         <input type="text required" name="email_label" tabindex="7"  id="email_label" value="<?php if($aweber) echo $aweber->getEmailLabel(); else echo "Email"; ?>" />
         
         <div class="dlabel">Submit Label *<br />
         <span class="small">Label for submit button</span></div>
         <input type="text required" name="submit_label" tabindex="8"  id="submit_label" value="<?php if($aweber) echo $aweber->getSubmitLabel(); else echo "Submit"; ?>" />
         
         
         <div class="dlabel">Use Custom Field</div>
         <input type="checkbox"  tabindex="9" onchange="updateCustomFieldPanel();" value="y" name="use_custom_field" id="use_custom_field" <?php $ch=false; if($aweber && $aweber->getCustomFieldType()!="n") $ch=true; if($ch) echo 'checked="checked"'; ?> />
         
         <div id="qcfp">

            <div class="dlabel">Is Hidden</div>
            <input type="checkbox"  onchange="updateCustomFieldPanel();" tabindex="10"  value="y" name="custom_field_hidden" id="custom_field_hidden" <?php $ch=false; if($aweber && $aweber->getCustomFieldType()=="h") $ch=true; if($ch) echo 'checked="checked"'; ?> />

            <div id="qcfp2">
            <div class="dlabel">Custom Field Label</div>
            <input type="text" name="custom_field_label" tabindex="11"  id="custom_field_label" value="<?php if($aweber) echo $aweber->getCustomFieldLabel(); ?>" />
            </div>
            <div id="qcfp3">
               <div class="dlabel">Custom Field Value</div>
                <input type="text" name="custom_field_value" tabindex="12"  id="custom_field_value" value="<?php if($aweber) echo $aweber->getCustomFieldValue(); ?>" />
            </div>

         </div>



         <p>
         <input class="button-primary" type="submit" tabindex="9"  value="Save Changes" />
         </p>
         <input type="hidden" name="ftask" value="configureaweber" />
         <input type="hidden" name="quiz_id" value="<?php echo $quiz->getId(); ?>" />
         <?php if($aweber && $aweber->getId()): ?>
         <input type="hidden" name="id" value="<?php echo $aweber->getId(); ?>" />
         <?php endif;?>




        </fieldset>
    </form>
    </div>
</div>
<script type="text/javascript">
    function updateCustomFieldPanel() {
        var $=jQuery;
        if($("#use_custom_field").is(":checked")) {
            $("#qcfp").show();
        }
        else {
            $("#qcfp").hide();
        }

        if($("#custom_field_hidden").is(":checked")) {
            $("#qcfp2").hide();
            $("#qcfp3").show();
        }
        else {
            $("#qcfp2").show();
            $("#qcfp3").hide();
        }
    }
    jQuery(document).ready(function() {
        updateCustomFieldPanel();
    });
</script>
<?php quiz_pageadmin_footer(); ?> 