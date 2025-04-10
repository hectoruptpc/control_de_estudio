<div class="wrap"> 
	<div class="section-schedule">
		<div class="tab-head">
			<span class="title"> <i class="fa fa-paper-plane text-primary" aria-hidden="true"></i> <?=l('Auto Post')?></span>
			<div class="right">
				<button type="submit" class="btn btn-info btn-flat AddNewSchedule" data-toggle="modal" data-target="#modal-addSchedule"><i class="fa fa-plus" aria-hidden="true"></i> <?=l('Add new')?></button>
				<div class="btn-group">
			        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?=l('action')?>
			            <span class="fa fa-caret-down"></span></button>
			        <ul class="dropdown-menu">
			            <li><a class="btnActionList" data-action="repost" data-type="post" href="javascript:void(0);"><?=l('Repost All')?></a></li>
			            <li><a class="btnActionList" data-action="cancel" data-type="post" href="javascript:void(0);"><?=l('Cancel All')?></a></li>
			            <li><a class="btnActionList" data-action="delete" data-type="post" href="javascript:void(0);"><?=l('Delete All')?></a></li>
			        </ul>
			    </div>
			</div>
		</div>

        <form class="formList">
			<div class="schedule-list-posts">
				<div class="row">
					<?php if($countSchedule > 0){?>
					<div class="list_load" data-page="0" data-action="<?=PATH."Schedule/ajax_page"?>"></div>
					<?php }else{?>
					<div class="col-md-12">
						<div class="list_empty">
					        <i class="fa fa-chain-broken" aria-hidden="true"></i>
					        <div class="text"><?=l("No recent actions")?></div>
					    </div>
					</div>
					<?php }?>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-save" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-owner">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?=l('title')?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control save_title"/>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-modal-save"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?=l('save')?></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-addSchedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content section-schedule">
            <form class="formSchedule">
	        	<ul class="schedule-errors"></ul>
	        	<?php if(!empty($savepost)){?>
	            <div class="form-group">
	                <select class="form-control getSavePost">
	                    <option value=""><?=l('post-list-saved')?></option>
	                    <?php foreach ($savepost as $row){?>
	                    <option value="<?=$row->id?>"><?=$row->name?></option>
	                    <?php }?>
	                </select>
	            </div>
	            <?php }?>

				<div class="schedule-content">
					<div class="schedule-loading"></div>
					<textarea placeholder="<?=l('write-something')?>" class="post-message" name="description"></textarea>
					<div class="schedule-option" style="margin-top: 15px;">
						<div class="form-group">
		                    <input type="radio" class="input-icheck" checked="true" name="type" value="photo"> <span style="margin-right: 20px;">&nbsp;&nbsp;<i class="fa fa-camera-retro" aria-hidden="true"></i> <?=l('photo')?></span>
		                    <input type="radio" class="input-icheck" checked="true" name="type" value="story"> <span style="margin-right: 20px;">&nbsp;&nbsp;<i class="fa fa-camera-retro" aria-hidden="true"></i> <?=l('story-photo')?></span>
							<?php if(check_FFMPEG()){ ?>
		                    <input type="radio" class="input-icheck" name="type" value="video"> &nbsp;&nbsp;<i class="fa fa-video-camera" aria-hidden="true"></i> <?=l('video')?>
		                	<?php }?>
		                </div>
						<div class="input-group form-group mb15">
		                	<input type="text" class="form-control" name="media" checked="" placeholder="<?=l('enter-url-or-upload-image')?>">
		                    <span class="input-group-btn">
		                      <button type="button" class="btn btn-block btn-default dialog-upload"><i class="fa fa-camera" aria-hidden="true"></i> <?=l('add-photo')?></button>
		                    </span>
		              	</div>
		              	<div class="progress progress-xs progress-striped active schedule-progress">
	                  		<div class="progress-bar progress-bar-success progress-post-now" style="width: 0%"></div>
	                    </div>
	                    <div class="btn-group left">
						    <button type="button" class="btn btn-default btn-flat btnSavePost"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?=l('save-post')?></button>
						</div>
						<div class="btn-group right">
						    <button type="submit" class="btn btn-success btn-flat btnPostNow"><i class="fa fa-paper-plane" aria-hidden="true"></i> <?=l('post-now')?></button>
						    <button type="button" class="btn btn-success btn-flat btnOpenSchedule"><i class="fa fa-clock-o" aria-hidden="true"></i> <?=l('schedule')?></button>
						</div>
						<div class="clearfix"></div>
						<div class="schedule-list-option">
		                    <div class="form-group col-md-12 mt15 bt-line">
		                    	<div class="row">
								    <div class="row">
								    	<div class="form-group col-md-6 mb0">
									        <label class="head-title col-md-12 p0 fn"><i class="fa fa-clock-o"></i> <?=l('time-post')?></label>
									        <input type="text" class="form-control date_range" name="time_post">
									    </div>

									    <div class="form-group col-md-6 mb0">
									        <label class="head-title col-md-12 p0 fn"><i class="fa fa-bullseye"></i> <?=l('deplay')?></label>
									        <select class="form-control" name="deplay">
	                                            <?php foreach (deplay_time() as $value) {?>
	                                                <?php if(MINIMUM_DEPLAY <= $value){?>
	                                                <option value="<?=$value?>" <?=(DEFAULT_DEPLAY == $value)?"selected":""?>><?=$value?> <?=l('seconds')?></option>
	                                                <?php }?>
	                                            <?php }?>
	                                        </select>
									    </div>
								    </div>

		                			<div class="col-md-6 col-sm-6">
		                    			<div class="row">
							                <div class="box-icheck">
							                  	<input type="checkbox" class="icheck" name="random_post" id="random-post-accounts">
							                  	<label class="label-icheck" for="random-post-accounts"> <?=l('random-post-accounts')?></label>
							                </div>
		                    			</div>
			                    	</div>
			                    	<div class="col-md-6 col-sm-6 prmobile0">
						                <div class="box-icheck">
						                  	<input type="checkbox" class="icheck" name="delete_complete" id="delete-after-finished">
						                  	<label class="label-icheck" for="delete-after-finished"> <?=l('delete-schedule-after-finished')?></label>
						                </div>
			                    	</div>
		                    	</div>
		                    </div>
		                    <div class="form-group col-md-12 bt-line">
		                    	<div class="row">
		                    		<div class="box-icheck">
					                  	<input type="checkbox" name="repeat_post" class="icheck" id="repeat-post">
					                  	<label class="label-icheck" for="repeat-post"> <?=l('repeat-post')?></label>
					                </div>
			                        <div class="row">
						                <div class="clearfix"></div>
			                            <div class="col-md-6">
			                                <label class="col-md-12 p0 fn"><?=l('repeat')?></label>
			                                <select class="form-control" name="repeat_time">
			                                    <?php for ($i = 1; $i <= 23; $i++) {
                                                ?>
                                                    <option value="<?=$i*60*60?>"><?=$i." ".l('hours')?></option>
                                                <?php } ?>
                                                <?php for ($i = 1; $i <= 365; $i++) {
                                                ?>
                                                    <option value="<?=$i*86400?>"><?=$i." ".l('days')?></option>
                                                <?php } ?>
			                                </select>
			                            </div>
			                            <div class="col-md-6">
			                                <label class="col-md-12 p0 fn"><?=l('end-day')?></label>
			                                <input type="text" class="form-control date_range_only_day" name="repeat_end">
			                            </div>
			                        </div>
		                    	</div>
		                    </div>
							<div class="btn-group right">
							    <button type="button" class="btn btn-primary btn-flat btnSaveSchedule"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?=l('save-schedule')?></button>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="schedule-list-accounts mt15">
		        	<div class="row">
		        		<div class="col-md-12">
		        			<?php if(!empty($accounts)){?>	
			        			<?php foreach ($accounts as $row) {?>
			        			<div class="item">
			        				<input type="checkbox" name="accounts[]" data-id="<?=$row->id?>" value="<?=$row->id."{-}".$row->username?>"/>
					                <i class="fa fa-user"></i>
					                <div class="text"><?=$row->username?></div>
					                <div class="check">
					                	<i class="fa fa-check"></i>
					                </div>
					        	</div>
					        	<?php }?>
					        	<div class="item white" data-toggle="modal" data-target="#myModal">
				        			<i class="fa fa-plus"></i>
				        			<div class="text"><?=l('add-new')?></div>
				        		</div>
			        		<?php }else{?>
			        			<div class="item-empty" data-toggle="modal" data-target="#myModal">
				        			<i class="fa fa-instagram"></i>
				        			<div class="text"><?=l('add-new-account')?></div>
				        		</div>
			        		<?php }?>
		        		</div>
		        	</div>
		        	<div class="clearfix"></div>
		        </div>
	        </form>
        </div>
    </div>
</div>