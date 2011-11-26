<div class="quiz-container quiz-container-result">
    <div class="result-content">
        <?php echo wysiwyg_db_to_html($resultContent); ?>  
    </div>
        
        <?php if($showShareLinks): ?>
        <div class="quiz-share-links">
            <div class="quiz-share-link-twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php echo get_permalink(self::$post->ID); ?>" data-text="<?php echo self::$title . ' - ' . $resultLink; ?>" data-count="horizontal">Tweet</a></div><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
            
            <div class="quiz-share-link-share" ><a name="fb_share" type="button_count" share_url="<?php echo $resultLink; ?>"></a></div> 
            
            <div class="quiz-share-link-like"><iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink(self::$quiz->getPageId())); ?>&amp;layout=button_count&amp;show_faces=false&amp;width=150&amp;action=like&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:150px; height:21px;" allowTransparency="true"></iframe></div>
            
            <div class="clear"></div>           
        </div>
        <?php endif; ?>
        <?php if($resultBadge): ?>
        <div class="quiz-badge">
            <?php echo wysiwyg_db_to_html(self::processString($resultBadge->getContent() , $resultReplace)); ?>
            <div class="clear"></div>
        </div>
        <?php endif; ?>
        <?php foreach($resultArray as $res): ?>
        <?php if($res["type"] == "section"): ?>
        <div class="quiz-result-unit quiz-result-unit-section">
            <?php echo textarea_db_to_html($res["title"]); ?>
        </div>    
        <?php else: ?>
        <div class="quiz-result-unit quiz-result-unit-response">
            <?php echo wysiwyg_db_to_html($res["response"]); ?>
        </div>    
        <?php endif; ?>
        <?php endforeach; ?>
    
</div>