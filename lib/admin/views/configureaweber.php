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
        
        <div class="dlabel">Hide Aweber Form <a href="#" id="hide" style="display:none;">Hide</a><a href="#" id="show">?</a></div>
         <input type="checkbox"  tabindex="2"  value="y" name="skip_optin" id="skip_optin" <?php $ch=false; if($aweber && $aweber->shouldSkipOptin()) $ch=true; if($ch) echo 'checked="checked"'; ?> />
         
		 <div class="target" style="display:none;border:1px solid #aaa;padding:10px;margin-top:5px;">
			<p style="padding:0;margin:0;">You can use pretty much any Email Service Provider by simply copying and pasting your HTML optin form code into the HTML tab below. Just be sure to include a hidden field with a value of {result-link}. This will ensure that each user's custom result link is submitted to your autoresponder service. You will most likely need to create a custom variable from within your Email Service Provider account to accept this custom variable. If you are using AWeber, you will need to create a custom variable in AWeber called result-link. That will ensure that you can email the custom result link to your subscribers in their first autoresponder email.
			<br/>
			<br/>Here is an example hidden input field:
			<br/>
			<br/>&lt;input type="hidden" id="result-link" value="{result-link}"/&gt;
			<br/>
			<br/>You can also direct link to your results page using the result link variable. Here is an example:
			<br/>
			<br/>&lt;a href="{result-link}"&gt;Click here to view your results&lt;/a&gt;
			</p>
		 </div>
         <div class="dlabel">Display Page Title</div>
         <input type="checkbox"  tabindex="2"  value="y" name="display_title" id="display_title" <?php $ch=true; if($aweber && !$aweber->shouldDisplayTitle()) $ch=false; if($ch) echo 'checked="checked"'; ?> />
         
         

         <div class="dlabel">Content<br />
        <span class="small">Available Code: <?php quiz_insert_tag_ht('content', 'result-link', true); ?></span></div>
         <?php 
         $desc="";
         if($aweber) $desc=$aweber->getContent();
         quiz_render_main_editor($desc, "content", 3); ?>
        
         <a href="http://robjones.aweber.com/" target="_blank" style="color: #00F; font-size: 105%; font-weight: bold;">Sign Up For an AWeber Account</a>
              <div class="dlabel">Confirmation Page*:<br />
         <span class="small">Url of your confirmation page</span></div>
         <input type="text required" name="confirmation_page" tabindex="4"  id="confirm_page" value="<?php if($quiz) echo $quiz->getThankyou(); ?>" />
         
           <div class="dlabel">Already on list page*:<br />
         <span class="small">Url of the page if the email is already on the list</span></div>
         <input type="text required" name="alreadyin_page" tabindex="4"  id="confirm_page" value="<?php if($quiz) echo $quiz->getThankyou2(); ?>" />
         
         <div class="dlabel">AWeber List Name *<br />
         <span class="small">Listname from your AWeber account</span></div>
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
    jQuery(document).ready(function() {
		jQuery(".target").hide();
		jQuery("#hide").hide();
		
		jQuery("#hide").click(function(){
         jQuery(".target").hide();
		 jQuery("#hide").hide();
		 jQuery("#show").show();
      });

      jQuery("#show").click(function(){
         jQuery(".target").show();
		 jQuery("#show").hide();
		 jQuery("#hide").show();
      });
    });
</script>
<?php quiz_pageadmin_footer(); ?> 