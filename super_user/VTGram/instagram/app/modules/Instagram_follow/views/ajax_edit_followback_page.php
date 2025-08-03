<!-- Modal -->
<div class="modal fade" id="modal-edit-page" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content section-schedule">
        	<form class="formSchedule">
        		<input type="hidden" name="accounts[]" data-id="<?=$item->account?>" value="<?=$item->account."{-}".$item->name?>"/>
	        	<ul class="schedule-errors"></ul>
				<div class="schedule-content">
					<div class="schedule-loading"></div>
					<textarea placeholder="<?=l('send-direct-message')?>" class="post-message" name="description" ><?=$item->description?></textarea>
					<div class="schedule-option">
						<div class="form-group col-md-12">
		                	<div class="row">
							    <div class="row">
								    <div class="form-group col-md-12 mb0">
								        <label class="head-title col-md-12 p0 fn"><i class="fa fa-bullseye"></i> <?=l('time-cronjob')?></label>
								        <select class="form-control" name="deplay">
		                                    <?php for ($i=1; $i <= 1440; $i++) { 
		                                    ?>
		                                        <option value="<?=$i*60?>" <?=($item->deplay == $i*60)?"selected":""?>><?=$i?> <?=l('minutes')?></option>
		                                    <?php }?>
		                                </select>
								    </div>
								    <div class="form-group col-md-6 mb0 hide">
								        <label class="head-title col-md-12 p0 fn"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i> <?=l('maximum-follow-back')?></label>
								        <select class="form-control" name="maximum">
		                                    <?php for ($i=1; $i <= 20; $i++) { ?>
		                                        <option value="<?=$i?>" ><?=$i?></option>
		                                    <?php }?>
		                                </select>
								    </div>
							    </div>
		                	</div>
		                </div>
						<div class="btn-group right">
						    <button type="button" class="btn btn-success btn-flat btnSaveScheduleFollowBack"><i class="fa fa-clock-o" aria-hidden="true"></i> <?=l('schedule')?></button>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="clearfix"></div>
				</div>
			</form>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(function(){
		if($('.post-message').length > 0){
            el = $(".post-message").emojioneArea({
                hideSource: true,
                useSprite: false,
                pickerPosition    : "bottom",
                filtersPosition   : "bottom",
            });

            el[0].emojioneArea.on("keyup", function(editor) {
                _data = editor.html();
                _type = $('.post_type .active').data("type");
                if($(".data-message").length > 0){
                    if(_data != ""){
                        $(".data-message").html("<b>"+Lang["Anonymous"]+"</b> " + _data);
                    }else{
                        $(".data-message").html('<div class="line-no-text"></div><div class="line-no-text"></div><div class="line-no-text w50"></div>');
                    }
                }else{
                    _el = $(".data-message-content");
                    if(_data != ""){
                        _el.show()
                    }else{
                        _el.hide();
                    }
                    _el.html(_data);
                }
            });

            el[0].emojioneArea.on("change", function(editor) {
                _data = editor.html();
                _type = $('.post_type .active').data("type");
                if($(".data-message").length > 0){
                    if(_data != ""){
                        $(".data-message").html("<b>Anonymous</b> " + _data);
                    }else{
                        $(".data-message").html('<div class="line-no-text"></div><div class="line-no-text"></div><div class="line-no-text w50"></div>');
                    }
                }else{
                    _el = $(".data-message-content");
                    if(_data != ""){
                        _el.show()
                    }else{
                        _el.hide();
                    }
                    _el.html(_data);
                }
            });

            el[0].emojioneArea.on("emojibtn.click", function(editor) {
                _data = $(".emojionearea-editor").html();
                _type = $('.post_type .active').data("type");
                if($(".data-message").length > 0){
                    if(_data != ""){
                        $(".data-message").html(_data);
                    }else{
                        $(".data-message").html('<div class="line-no-text"></div><div class="line-no-text"></div><div class="line-no-text w50"></div>');
                    }
                }else{
                    _el = $(".data-message-content");
                    if(_data != ""){
                        _el.show()
                    }else{
                        _el.hide();
                    }
                    _el.html(_data);
                }
            });
        }
	});	
</script>