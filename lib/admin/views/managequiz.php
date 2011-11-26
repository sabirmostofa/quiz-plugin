<div class="wrap">
    <h2>Manage quizzes <a class="button" href="<?php echo quiz_get_admin_url(array("page" => QUIZ_SHORTCODE . "-addquiz")); ?>">Add Quiz</a></h2>
    <?php quiz_render_messages(); ?>
    <?php quiz_render_tasks(); ?>            
    <table class="widefat">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Include Social Links</th>
                <th>Number of Questions</th>
                <th>Number of Sections</th>
                <th>Number of Badges</th>
                <th>Status</th>
                <th>Tasks</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $i=0;
        if(count($quizzes)):
        foreach($quizzes as $quiz): ?>
        <tr <?php if($i % 2 == 0) echo ' class="alternate" '; ?>>
            <td valign="top"><?php echo $quiz->getId(); ?></td>
            <td valign="top"><?php echo textarea_db_to_html($quiz->getTitle()); ?></td>
            <td valign="top"><?php echo $quiz->shouldIncludeSocialLinks()?"Yes":"No"; ?></td>
            <td valign="top"><?php echo $quiz->getNumQuestions(); ?></td>
            <td valign="top"><?php echo $quiz->getNumSections(); ?></td>
            <td valign="top"><?php echo $quiz->getNumBadges(); ?></td>
            <td valign="top"><?php echo $quiz->isPublished()?"Publish":"Draft"; ?></td>
            <td valign="top" align="right" width="260px"><!--<?php
             if($quiz->isPublished()):
             ?>
             <a href="<?php echo quiz_get_admin_url(array("unpublishquiz" => $quiz->getId()) , array("kpage")); ?>">Unpublish</a> | 
             <?php else: ?>
             <a href="<?php echo quiz_get_admin_url(array("publishquiz" => $quiz->getId()), array("kpage")); ?>">Publish</a> | 
             <?php endif; ?>-->
             <a href="<?php echo quiz_get_admin_url(array("task" => "quizmanage" , "quizid" => $quiz->getId())); ?>">Manage</a> |
             <a href="<?php echo quiz_get_admin_url(array("duplicatequiz" => $quiz->getId()) , array("kpage")); ?>">Duplicate</a> | <a href="<?php echo quiz_get_admin_url(array("task" => "editquiz" , "quizid" => $quiz->getId())); ?>">Edit</a> | <a href="<?php echo quiz_get_admin_url(array("deletequiz" => $quiz->getId()), array("kpage")); ?>" onclick="return quiz_delete_confirm()">Delete</a> | <a  target="_blank"  href="<?php echo get_permalink($quiz->getPageId()); ?>">View</a></td>
        </tr>
        <?php
        $i++;
        endforeach;
        else: ?>
        <tr><td  colspan="8">No results found</td></tr>
        <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Include Social Links</th>
                <th>Number of Questions</th>
                <th>Number of Sections</th>
                <th>Number of Badges</th>
                <th>Status</th>  
                <th>Tasks</th>
            </tr>
        </tfoot>
    </table>
    <div class="tablenav">
        <div class="tablenav-pages">
        <span class="displaying-num">Displaying <?php echo $minLimit + 1; ?> - <?php echo $minLimit + count($quizzes); ?> of <?php echo $totalQuizzes; ?></span>
        <?php if($totalPages > 1): ?>
            <?php 
                  $status="all";
                  if(isset($_GET["status"]))
                    $status=$_GET["status"];
            
            for($i=1; $i<=$totalPages; $i++) {
                if($currentPage == $i) {
                   ?>
                   <span class="page-numbers current"><?php echo $i; ?></span>
                   <?php
                }    
                else {
                  ?>
                    <a class="page-numbers" href="<?php echo quiz_get_admin_url(array("kpage" => $i, "status" => $status)); ?>"><?php echo $i; ?></a>
                  <?php     
                }                                                                                          
            }                                                                                                                   
            if($currentPage < $totalPages ) {
                ?>
                <a class="next page-numbers" href="<?php echo quiz_get_admin_url(array("kpage" => $currentPage + 1 , "status" => $status)); ?>" >&raquo;</a>
                <?php   
            }
            ?>
        <?php endif; ?>
        </div>
    </div>
	<h2>Getting Started Guide - <span style="font-size:18px;font-weight:bold;"><a href="#" id="hide1" style="display:none;">click to hide</a><a href="#" id="show1">click to show</a></span></h2>
	<div id="gettingstarted" style="display:none;">
		<h3>Step 1 - Configure your Wordpress Permalinks</h3>
		<p>IMPORTANT - You must correctly configure your permalink settings, or the Viral Conversion plugin will not function correctly. To do this, expand the Settings menu in the left navigation bar. Click on Permalinks. Select "Day and name" or "Month and name." Click the save changes button.</p>
		<p>NOTE - If your website uses another permalink setting, you can always install a second installation of Wordpress in a subfolder. For example, http://yourwebsite.com/quiz/. Although, if you choose this route, keep in mind that you cannot set a quiz as the static homepage on your Wordpress installation, or it will not function correctly.</p>
		<h3>Step 2 - Add a Quiz</h3>
		<p>To add a quiz, either click the Add Quiz button at the top of this page or the Add Quiz link in the Viral Conversion left navigation menu.</p>
		<p>You will be taken to a page where you can set up your quiz intro page and title.</p>
		<p>When you add a new quiz, it will create a new page in Wordpress. If you would like to edit the page template for that quiz, you can do so by accessing that page directly from the pages menu. However, each page's content will be replaced by the quiz content that you configure within the Viral Conversion plugin.</p>
		<p>The edit link will allow you to edit the quiz intro page. From that page, you can select the "skip intro" option if you like so that your quiz users will not see the intro page and go straight to the questions and section pages.</p>
		<p>Once you have created a quiz, you will be taken to the manage quiz page for that quiz. You can also access each quiz's Manage Quiz Page by clicking on the manage link next to the corresponding quiz in the list of quizzes above.</p>
		<p>You can add as many quizzes as you like. You can also duplicate, view or delete each quiz by clicking the links above.</p>
		<h3>Step 3 - Add your Questions & Section Pages</h3>
		<p>You can add as many questions and section pages as you like by clicking the Add Question and Add Section links on the Manage Quiz page.</p>
		<p>Questions & Section pages can include pretty much anything, including text, images, videos, html, javascript, Wordpress shortcodes, etc...anything you can think of.</p>
		<p>Generally the more effort you put into the design of your question and section pages, the better success your quiz will have. Design is a trust factor, and your visitors will judge you based on the design of your site, as well as the content. Adding images and video will help to keep your quizzes interesting and build trust.</p>
		<p>The questions you choose will have a big effect on the success of your Optin Funnel. They can be used to gather information about your visitor and to build their anticipation.</p>
		<p>Think of them as micro-commitments. With each question they answer, your visitor will become more committed and more likely to opt-in to your list. However, if your quiz is too long, your visitor may lose interest. If your quiz is too short, you won't build up enough of a commitment, and your opt-in rates will drop off. The optimal length can vary greatly in different niches and applications.</p>
		<p>For best results, you should experiment with various quiz lengths and content. In some applications, 3-5 questions might be plenty. In other applications, it may be more appropriate to have more than 30. The optimal length will depend on the specific needs of your audience and the function of your application.</p>
		<p>Applications can include self assessments, comparison tools, automated segmenting, personality tests, product comparisons, dynamic sales pages, games, entertainment, viral quizzes and much more. The possibilities really are endless.</p>
		<h3>Step 4 - Add your Answers & Responses</h3>
		<p>Responses are optional. You can configure responses, badges or both.</p>
		<p>For each question, you will need to add answers. Your answers will show up on your question pages. If you create a response for an answer, that response will show up on the results page if someone chooses that answer.</p>
		<p>Responses can be anything, including text, images, videos, html, Javascript, Wordpress shortcodes, etc.</p>
		<p>Remember, design is a trust factor. Your results page is your first chance to build trust & rapport. You have essentially made a promise when you enticed your visitor to take your quiz. The better you deliver on that promise, the more reciprocity you will build. This will greatly improve the effectiveness of your email marketing efforts.</p>
		<p>You must assign a number value to each answer, although you are allowed to enter zero as a value. When someone completes a quiz, they will be assigned a total score based the answers they chose. Their score will determine which badge will show up on their results page, if you have configured badges.</p>
		<h3>Step 5 - Create your Badges</h3>
		<p>Badges are optional. You may create as many as you like. Your results page will display whichever badge corresponds to your total score.</p>
		<p>To add a badge, simply click one of the Add Badge links on the Manage Quiz page.</p>
		<p>Badges can be anything, including text, images, videos, html, Javascript, Wordpress shortcodes, etc. Once again, remember that design is a trust factor, and the quality of your content and design will help to build trust and rapport, which will improve the success of your email marketing efforts.</p>
		<p>You can either assign a range of values to each badge, or you can set it to random.</p>
		<p>You can use any combination of random badges and badges with specified ranges. Your results page will display a badge with a specified range, before it will display a random badge. If your total score does not fall within the specified range of any badge, and no badges are set to random, no badge will be displayed on your results page.</p>
		<h3>Step 6 - Configure your list settings in your Email Service Provider</h3>
		<p>If you are using the built-in Aweber form, you will need to set up a custom variable in your list called "resultlink." If you are using a custom optin form, you will still need to configure a custom variable in your email service provider account to store your result link variable along with your subscriber's name and email address. You will generally send this link to your new subscriber in their first autoresponder email.</p>
		<p>You will also need to create a confirmation page. This page will let your visitor know what to expect next. You can inform them to check their email for their quiz results link. You can present an offer. You can give them additional content to continue to build trust and rapport. You will have their greatest attention on this page, so be sure to take advantage of it.</p>
		<h3>Step 7 - Configure your Optin Page</h3>
		<p>To configure your Optin Page, click on the corresponding Configure link in the Manage Quiz page for your quiz.</p>
		<p>You can use the built-in form for AWeber, or you can hide the AWeber form and insert your own Opt-in form for any email service provider. In this case you will want to submit your custom link variable with your form. To do this, create a hidden input field within your form, and set the value to the {result-link} variable. Here is an example:</p>
		<code>&lt;input type="hidden" id="customvariable" value="{result-link}" /&gt;</code>
		<p>As long as you've set up a custom variable in your email service provider account to store your result link value, you should be fine.</p>
		<p>You can also link directly to your results page by creating a link and setting the destination URL to {result-link}.</p>
		<h3>Step 8 - Configure your Results Page</h3>
		<p>To configure your Results Page, click on the corresponding Configure link from your Manage Quiz page.</p>
		<p>From your Configure Results Page, you can turn your Twitter and Facebook share buttons on and off. You can set a title. You can add content that will show up above the dynamic results.</p>
		<p>There are several dynamic variables that you can use in your page content. NOTE - The first name, last name, and email variables will only work if you use the built-in AWeber form. These can be a little finicky. They don't always work with every browser, so be sure to test your results page.</p>
		<h3>Step 9 - Configure your Ad Space</h3>
		<p>To configure your Ad Space, click on the corresponding Configure link from your Manage Quiz page.</p>
		<p>You can set up ads to show in four areas surronding your quiz content. You can choose to display them on your Question, Section, Optin, or Results page.</p>
		<h3>Step 10 - Link to your Quiz</h3>
		<p>You're ready to go! Simply link to your quiz from anywhere.</p>
		<p>Your success will typically depend on how engaging and relevant your content is and how appealing your hook is. Take advantage of micro-commitments to slowly hook your visitors and build the anticipation. The greater the anticipation, the stronger the desire will be to opt-in. The cliffhanger/tease is a very powerful psychological influencer.</p>
		<p>I suggest you test out several hooks and several different variations of your quizzes. The one click duplicate capability makes it dead simple to create and edit quiz variations, so be sure to take advantage of it!</p>
		<p>For much more advanced implementations, be sure to sign up for the Viral Conversion Ninja Training in the members area!</p>
	</div>
	<h2>Troubleshooting Checklist - <span style="font-size:18px;font-weight:bold;"><a href="#" id="hide2" style="display:none;">click to hide</a><a href="#" id="show2">click to show</a></span></h2>
	<div id="troubleshooting" style="display:none;">
		<h3>The Causes of the Most Common Problems</h3>
		<ol>
			<li><b>Incorrect Permalink Settings</b> - See Above</li>
			<li><b>SEO Plugins that affect Permalink Settings</b> - Some SEO plugins will remove URL parameters, which will break the Viral Conversion plugin functionality.</li>
			<li><b>Setting a quiz as your static Home Page</b> - You cannot set a quiz page as your Wordpress static homepage, or it will not function correctly. You will need to link to your quiz instead.</li>
		</ol>
	</div>
</div>
<?php quiz_pageadmin_footer(); ?> 
<script type="text/javascript"> 
function quiz_delete_confirm() {
    return confirm('Are you sure you want to delete this item?');
}
    jQuery(document).ready(function() {
		jQuery("#troubleshooting").hide();
		jQuery("#gettingstarted").hide();
		jQuery("#hide1").hide();
		jQuery("#hide2").hide();
		
	jQuery("#hide1").click(function(){
		jQuery("#gettingstarted").hide();
		jQuery("#hide1").hide();
		jQuery("#show1").show();
	});

	jQuery("#hide2").click(function(){
		jQuery("#troubleshooting").hide();
		jQuery("#hide2").hide();
		jQuery("#show2").show();
	});

	jQuery("#show1").click(function(){
		jQuery("#gettingstarted").show();
		jQuery("#show1").hide();
		jQuery("#hide1").show();
	});

	jQuery("#show2").click(function(){
		jQuery("#troubleshooting").show();
		jQuery("#show2").hide();
		jQuery("#hide2").show();
	});
});

</script>