<?php

// check if page is configured
// 1) optin
$aweber=$quiz->getAweber();
$aweberConfigured=$aweber && ($aweber->getContent() || $aweber->getTitle());
$aweberRequired=!($aweber && $aweber->shouldSkipOptin());

$thankyouConfigured=(bool)$quiz->getThankyou() || (bool)$quiz->getThankyouTitle();
$thankyou2Configured=(bool)$quiz->getThankyou2() || (bool)$quiz->getThankyou2Title();

$resultConfigured=(bool)$quiz->getResult() || (bool)$quiz->getResultTitle();
$layoutConfigured=false;
$layout=$quiz->getLayout();
if($layout) {
    $layoutConfigured=$layout->getHeaderContent() || $layout->getLeftContent() || $layout->getRightContent() || $layout->getBottomContent();
}


// configure link
$aweberConfigureLink=quiz_get_admin_url(array("task" => "configureaweber", "quizid" => $_GET["quizid"]));
$thankyouConfigureLink=quiz_get_admin_url(array("task" => "configurethankyou", "quizid" => $_GET["quizid"]));
$thankyou2ConfigureLink=quiz_get_admin_url(array("task" => "configurethankyou2", "quizid" => $_GET["quizid"]));

$resultConfigureLink=quiz_get_admin_url(array("task" => "configureresult", "quizid" => $_GET["quizid"]));
$layoutConfigureLink=quiz_get_admin_url(array("task" => "configurelayout", "quizid" => $_GET["quizid"]));

// view link
$aweberViewLink="";
if($quiz->getPageId())
    $aweberViewLink=get_permalink($quiz->getPageId()) . 'optin/';
$thankyouViewLink="";
if($quiz->getPageId())
    $thankyouViewLink=get_permalink($quiz->getPageId()) . 'thankyou/';
$thankyou2ViewLink="";
if($quiz->getPageId())
    $thankyou2ViewLink=get_permalink($quiz->getPageId()) . 'thankyou/?onlist=1';

$resultViewLink="";
if($quiz->getPageId())
    $resultViewLink=get_permalink($quiz->getPageId()) . 'result/';

?>
<table class="widefat">
    <thead>
        <tr>
            <th>Page</th>
            <th>Configured</th>
            <th>Required</th>
            <th>Tasks</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td valign="top">Optin</td>
            <td valign="top"><?php if($aweberConfigured) echo 'Yes'; else echo 'No'; ?></td>
            <td valign="top"><?php if($aweberRequired) echo 'Yes'; else echo 'No'; ?></td>
            <td valign="top">
                <a href="<?php echo $aweberConfigureLink; ?>">Configure</a>
            </td>
        </tr>
        <tr class="alternate">
            <td valign="top">Thank You</td>
            <td valign="top"><?php if($thankyouConfigured) echo 'Yes'; else echo 'No'; ?></td>
            <td valign="top">--</td>
            <td valign="top">
                <a href="<?php echo $thankyouConfigureLink; ?>">Configure</a> | <a target="_blank"  href="<?php echo $thankyouViewLink; ?>">View</a>
            </td>
        </tr>
        <tr >
            <td valign="top">Thank You 2</td>
            <td valign="top"><?php if($thankyou2Configured) echo 'Yes'; else echo 'No'; ?></td>
            <td valign="top">--</td>
            <td valign="top">
                <a href="<?php echo $thankyou2ConfigureLink; ?>">Configure</a> | <a target="_blank" href="<?php echo $thankyou2ViewLink; ?>">View</a>
            </td>
        </tr>
        <tr class="alternate">
            <td valign="top">Result</td>
            <td valign="top"><?php if($resultConfigured) echo 'Yes'; else echo 'No'; ?></td>
            <td valign="top">--</td>
            <td valign="top">
                <a href="<?php echo $resultConfigureLink; ?>">Configure</a>
            </td>
        </tr>
        <tr >
            <td valign="top">Ad Space</td>
            <td valign="top"><?php if($layoutConfigured) echo 'Yes'; else echo 'No'; ?></td>
            <td valign="top">--</td>
            <td valign="top">
                <a href="<?php echo $layoutConfigureLink; ?>">Configure</a>
            </td>
        </tr>
    <?php
    if(false):
    $badges=$quiz->getBadges();
    $i=0;
    if($badges && count($badges)):
    foreach($badges as $badge): ?>
    <tr <?php if($i % 2 == 0) echo ' class="alternate" '; ?>>
        <td valign="top"><?php echo $badge->getId(); ?></td>
        <td valign="top"><?php echo $badge->getContentPortion(100); ?>...</td>
        <td valign="top"><?php echo $badge->isRandom()?"--":$badge->getRangeMin(); ?></td>
        <td valign="top"><?php echo $badge->isRandom()?"--":$badge->getRangeMax(); ?></td>
        <td valign="top"><?php echo $badge->isRandom()?"Yes":"No"; ?></td>
        <td valign="top"><a href="<?php echo quiz_get_admin_url(array("task" => "editbadge" , "quizid" => $quiz->getId(), "badgeid" => $badge->getId())); ?>">Edit</a> | <a href="<?php echo quiz_get_admin_url(array("deletebadge" => $badge->getId() , "task" => "quizmanage" , "quizid" => $quiz->getId())); ?>" onclick="return quiz_delete_confirm()">Delete</a>
    </tr>
    <?php
    $i++;
    endforeach;
    else: ?>
    <tr><td  colspan="5">No results found</td></tr>
    <?php endif;
    endif;?>
    </tbody>
</table>