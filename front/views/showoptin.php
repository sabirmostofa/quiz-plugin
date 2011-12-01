<div class="quiz-container quiz-container-optin">
    <div class="optin-content">
        <?php
        echo wysiwyg_db_to_html(self::processString(self::$quiz->getAweber()->getContent() , array("{result-link}" => $resultLink)));
        
        ?>
    </div>
    <?php
    if($skipOptin) {

        echo '</div>';
        return;
    }
    ?>
    <?php
          $nextAction=self::getNextAction();
          $aweber=self::$quiz->getAweber();
          /*
            "name (awf_first)"
            
          */

          $turl=get_permalink(self::$quiz->getPageId()) . self::REQUEST_THANKYOU .'/';
          $thankyouUrl=$turl;
          if(strpos($turl, "?") === FALSE) {
              $turl .= "?onlist=1";
          }
          else {
              $turl .= "&onlist=1";
          }
          $thankyouUrl2=$turl;
        ?>
    <div class="question-form-container stylizedform">
        <form method="post" action="http://www.aweber.com/scripts/addlead.pl" onsubmit="return aweberformcheck();">

            <input type="hidden" name="listname" value="<?php echo $aweber->getListname(); ?>" />
           <!--
            <input type="hidden" name="redirect" value="<?php echo $thankyouUrl; ?>" />
            -->
            <input type="hidden" name="meta_redirect_onlist" value="<?php echo $thankyouUrl2; ?>" />
            <input type="hidden" name="meta_required" value="email" />
            <input type="hidden" name="meta_message" value="1" />
            <input type="hidden" name="meta_forward_vars" value="1" />
            <input type="hidden" name="custom resultlink" value="<?php echo $resultLink; ?>" />


             <label><?php echo $aweber->getFirstnameLabel(); ?></label>
             <input type="text" name="name (awf_first)" id="firstname" value="" />
             
             <label><?php echo $aweber->getLastnameLabel(); ?></label>
             <input type="text" name="name (awf_last)" id="lastname" value="" />
             
             <label><?php echo $aweber->getEmailLabel(); ?></label>
             <input type="text" name="email" value="" id="email" />

             <?php if($aweber->getCustomFieldType()!="n"): ?>
                 <?php if($aweber->getCustomFieldType()=="h"): ?>
                    <input type="hidden" name="custom cfield" value="<?php echo $aweber->getCustomFieldValue(); ?>" />
                 <?php else: ?>
                    <label><?php echo $aweber->getCustomFieldLabel(); ?></label>
                    <input type="text" name="custom cfield" value="" />
                 <?php endif; ?>
             <?php endif; ?>

             <p>
                <input type="submit" name="submit" value="<?php echo $aweber->getSubmitLabel(); ?>" />
             </p>
        </form>
    </div>
</div>
<script type="text/javascript">
function aweberformcheck() {
    if(jQuery.trim(jQuery("#firstname").val()) == "" ) {
        alert("Please enter First Name");
        return false;
    }
    if(jQuery.trim(jQuery("#email").val()) == "" ) {
        alert("Please enter Email");
        return false;
    }
    var email=jQuery.trim(jQuery("#email").val());
    var emailValid=/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(email);
    if(!emailValid) {
        alert("Please enter valid Email");
        return false;
    }
    return true;
}
</script>
