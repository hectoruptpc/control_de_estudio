<div class="wrap">
	<div class="section-dashboard">
        <div class="tab-head">
            <span class="title"> <i class="fa fa-instagram text-primary" aria-hidden="true"></i> <?=l('list-instagram-tools')?></span>
        </div>
        <div class="section-list-dashboard">
    		<div class="item">
    			<a href="<?=PATH?>schedule" title="">
	    			<img src="<?=BASE?>assets/img/icon-instagram-schedule.png" title="" alt="">
	    			<div class="text"><?=l('auto-posts')?></div>
    			</a>
    		</div>
            <div class="item">
                <a href="<?=PATH?>instagram/direct-message" title="">
                    <img src="<?=BASE?>assets/img/icon-instagram-auto-direct-message.png" title="" alt="">
                    <div class="text"><?=l('auto-direct-message')?></div>
                </a>
            </div> 
        	<div class="item">
        		<a href="<?=PATH?>instagram/auto_comment" title="">
	    			<img src="<?=BASE?>assets/img/icon-instagram-comment.png" title="" alt="">
	    			<div class="text"><?=l('auto-comments')?></div>
	    		</a>
    		</div>
    		<div class="item">
    			<a href="<?=PATH?>instagram/auto_like" title="">
	    			<img src="<?=BASE?>assets/img/icon-instagram-like.png" title="" alt="">
	    			<div class="text"><?=l('auto-likes')?></div>
	    		</a>
    		</div>
    		<div class="item">
    			<a href="<?=PATH?>instagram/follow" title="">
	    			<img src="<?=BASE?>assets/img/icon-instagram-follow.png" title="" alt="">
	    			<div class="text"><?=l('auto-follow')?></div>
	    		</a>
    		</div>
    		<div class="item">
    			<a href="<?=PATH?>instagram/followback" title="">
	    			<img src="<?=BASE?>assets/img/icon-instagram-followback.png" title="" alt="">
	    			<div class="text"><?=l('auto-follow-back')?></div>
	    		</a>
    		</div>
    		<div class="item">
    			<a href="<?=PATH?>instagram/unfollow" title="">
	    			<img src="<?=BASE?>assets/img/icon-instagram-unfollow.png" title="" alt="">
	    			<div class="text"><?=l('auto-unfollow')?></div>
	    		</a>
    		</div>
    		<div class="item">
    			<a href="<?=PATH?>instagram/search" title="">
	    			<img src="<?=BASE?>assets/img/icon-instagram-search.png" title="" alt="">
	    			<div class="text"><?=l('search')?></div>
	    		</a>
    		</div>
            <div class="item">
                <a href="<?=PATH?>instagram/comment" title="">
                    <img src="<?=BASE?>assets/img/comment1.png" title="" alt="">
                    <div class="text"><?=l('comments')?></div>
                </a>
            </div>
            <div class="item">
                <a href="<?=PATH?>instagram/like" title="">
                    <img src="<?=BASE?>assets/img/like.png" title="" alt="">
                    <div class="text"><?=l('likes')?></div>
                </a>
            </div>
        	<div class="clearfix"></div>
        </div>
	</div>
    <div class="section-dashboard" style="margin-top: 30px;">
        <div class="row">
            <div class="tab-head">
                <i class="fa fa-bar-chart text-info" aria-hidden="true"></i> <?=l('analytics')?>
            </div>
        </div>
        <div class="schedule-analytics row">
            <div class="schedule-loading"></div>
            <div class="report_posts"></div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function(){
        Instagram.chart();
    });
</script> 