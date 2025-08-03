<!-- Modal -->
<div class="modal fade" id="modal-edit-page" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content section-schedule">
        	<form class="formSchedule">
	        	<ul class="schedule-errors"></ul>
				<div class="schedule-content">
					<div class="schedule-loading"></div>
					<div class="schedule-option">
						<div class="form-group col-md-12">
		                	<div class="row">
							    <div class="row">
							    	<div class="form-group col-md-12 mb0">
							    		<input type="hidden" name="accounts[]" data-id="<?=$item->account?>" value="<?=$item->account."{-}".$item->name?>"/>
					                    <span style="display: inline-block; margin-bottom: 5px;">
					                    	<input type="radio" class="input-icheck check-type-follow" checked="true" name="type" value="1" <?=($item->title == 1)?"checked":""?>> <span style="margin-right: 20px;">&nbsp;&nbsp;<i class="fa fa-hashtag" aria-hidden="true"></i> <?=l('Folow by hashtags')?></span>
					                    </span>
					                    <span style="display: inline-block; margin-bottom: 5px;">
					                    	<input type="radio" class="input-icheck check-type-follow" name="type" value="2" <?=($item->title == 2)?"checked":""?>> <span style="margin-right: 20px;">&nbsp;&nbsp;<i class="fa fa-map-marker" aria-hidden="true"></i> <?=l('Follow by location')?></span>
					                    </span>
					                    <span style="display: inline-block; margin-bottom: 5px;">
					                    	<input type="radio" class="input-icheck check-type-follow" name="type" value="3" <?=($item->title == 3)?"checked":""?>> &nbsp;&nbsp;<i class="fa fa-user" aria-hidden="true"></i> <?=l('Follow by username')?>
					                    </span>
					                </div>
								    <div class="form-group col-md-12">
								    	<div class="tab-hashtag" <?=($item->title == 1)?"":'style="display:none"'?>>
							            	<label class="head-title col-md-12 p0 fn m0"><i class="fa fa-hashtag"></i> <?=l('Hashtags')?></label>
							           		<input type="hidden" name="tags" id="mySingleEditField" value="<?=($item->title == 1)?$item->description:""?>" >
							            	<ul id="singleEditFieldTags"></ul>
							            </div>
										<div class="tab-location" <?=($item->title == 2)?"":'style="display:none"'?>>
											<label class="head-title col-md-12 p0 fn m0"><i class="fa fa-map-marker"></i> <?=l('Location')?></label>
											<input id="geocomplete_edit" class="form-control" type="text" placeholder="<?=l('Enter your location name')?>" />
											<input type="hidden" name="location" class="form-control" type="text" value="">
											<input type="hidden" name="formatted_address" type="text" value="">
										</div>
										<div class="tab-username" <?=($item->title == 3)?"":'style="display:none"'?>>
											<label class="head-title col-md-12 p0 fn m0"><i class="fa fa-user"></i> <?=l('Username')?></label>
							           		<input type="hidden" name="usernames" id="mySingleEditFieldUsername" value="<?=($item->title == 3)?$item->description:""?>" >
							           		<ul id="singleEditFieldUsername"></ul>
										</div>
								        <label class="head-title col-md-12 p0 fn"><i class="fa fa-bullseye"></i> <?=l('time-cronjob')?></label>
								        <select class="form-control" name="deplay">
		                                    <?php for ($i=1; $i <= 1440; $i++) { 
		                                    ?>
		                                        <option value="<?=$i*60?>" <?=($item->deplay == $i*60)?"selected":""?>><?=$i?> <?=l('minutes')?></option>
		                                    <?php }?>
		                                </select>
								    </div>
								    <div class="form-group col-md-6 mb0 hide">
								        <label class="head-title col-md-12 p0 fn"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i> <?=l('maximum-follow')?></label>
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
						    <button type="button" class="btn btn-success btn-flat btnSaveScheduleFollow"><i class="fa fa-clock-o" aria-hidden="true"></i> <?=l('schedule')?></button>
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
		$('.input-icheck').iCheck({
		    radioClass: 'iradio_square-orange'
	  	});

	  	$a = $("#geocomplete_edit").geocomplete({
            "details": "form"
        })
          .bind("geocode:result", function(event, result){
            console.log(result);
          });
        
        $("#find").click(function(){
          $("#geocomplete_edit").trigger("geocode");
        });

        var lat_and_long = "<?=($item->title == 2)?$item->description:""?>";
		$("#geocomplete_edit").geocomplete("find", lat_and_long);
        
        console.log($a);
        
        $("#examples a").click(function(){
          $("#geocomplete_edit").val($(this).text()).trigger("geocode");
          return false;
        });

        _tagEdit1 = $('#singleEditFieldTags');
		_tagEdit1.tagit({
            singleField: true,
            singleFieldNode: $('#mySingleEditField'),
            beforeTagAdded: function(event, ui) {
		        if (!ui.duringInitialization) {
		            var tagArray = ui.tagLabel.split(/[\s,]+/);
		            if (tagArray.length>1) {
		                for (var i=0,max=tagArray.length;i<max;i++) {
		                    _tagEdit1.tagit("createTag", tagArray[i]);
		                }
		                return false;
		            }       
		        }
		    }
        });

		_tagEdit2 = $('#singleEditFieldUsername');
        _tagEdit2.tagit({
            singleField: true,
            singleFieldNode: $('#mySingleEditFieldUsername'),
            beforeTagAdded: function(event, ui) {
		        if (!ui.duringInitialization) {
		            var tagArray = ui.tagLabel.split(/[\s,]+/);
		            if (tagArray.length>1) {
		                for (var i=0,max=tagArray.length;i<max;i++) {
		                    _tag2.tagit("createTag", tagArray[i]);
		                }
		                return false;
		            }       
		        }
		    }
        });

        $('input.check-type-follow').on('ifChecked', function(event){
            _value = $(this).val();
            console.log(_value);
            if(_value == 1){
                $(".tab-hashtag").show();
                $(".tab-location").hide();
                $(".tab-username").hide();
            }else if(_value == 2){
                $(".tab-location").show();
                $(".tab-hashtag").hide();
                $(".tab-username").hide();
            }else if(_value == 3){
                $(".tab-username").show();
                $(".tab-hashtag").hide();
                $(".tab-location").hide();
            }
        });
	});
</script>