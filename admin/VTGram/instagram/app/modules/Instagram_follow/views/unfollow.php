<div class="wrap">
	<div class="section-schedule">
		<div class="tab-head">
			<span class="title"> <i class="fa fa-thumbs-down text-info" aria-hidden="true"></i> <?=l('Auto Unfollow')?></span>
			<div class="right">
				<button type="submit" class="btn btn-info btn-flat AddNewSchedule" data-toggle="modal" data-target="#modal-addSchedule"><i class="fa fa-plus" aria-hidden="true"></i> <?=l('Add new')?></button>
				<a href="<?=PATH."instagram/follow/log?type=unfollow"?>" class="btn btn-danger btn-flat"><i class="fa fa-history" aria-hidden="true"></i> <?=l('History')?></a>
				<div class="btn-group">
			        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?=l('action')?>
			            <span class="fa fa-caret-down"></span></button>
			        <ul class="dropdown-menu">
			            <li><a class="btnActionList" data-action="repost" data-type="unfollow" href="javascript:void(0);"><?=l('Start All')?></a></li>
			            <li><a class="btnActionList" data-action="cancel" data-type="unfollow" href="javascript:void(0);"><?=l('Stop All')?></a></li>
			            <li><a class="btnActionList" data-action="delete" data-type="unfollow" href="javascript:void(0);"><?=l('Delete All')?></a></li>
			        </ul>
			    </div>
			</div>
		</div>

		<form class="formList">
			<div class="schedule-list-posts">
				<div class="row">
					<?php if($countSchedule > 0){?>
					<div class="list_load" data-page="0" data-action="<?=PATH."Instagram_follow/ajax_unfollow_page"?>"></div>
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
<div class="modal fade" id="modal-addSchedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content section-schedule">
        	<form class="formSchedule">
	        	<ul class="schedule-errors"></ul>
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
		        </div>
				<div class="schedule-content">
					<div class="schedule-loading"></div>
					<div class="schedule-option">
						<div class="form-group col-md-12">
		                	<div class="row">
							    <div class="row">
								    <div class="form-group col-md-12 mb0">
								        <label class="head-title col-md-12 p0 fn"><i class="fa fa-bullseye"></i> <?=l('time-cronjob')?></label>
								        <select class="form-control" name="deplay">
		                                    <?php for ($i=1; $i <= 60; $i++) { 
		                                    ?>
		                                        <option value="<?=$i*60?>" ><?=$i?> <?=l('minutes')?></option>
		                                    <?php }?>
		                                </select>
								    </div>
								    <div class="form-group col-md-6 mb0 hide">
								        <label class="head-title col-md-12 p0 fn"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i> <?=l('maximum-unfollow')?></label>
								        <select class="form-control" name="maximum">
		                                    <?php for ($i=1; $i <= 15; $i++) { ?>
		                                        <option value="<?=$i?>" ><?=$i?></option>
		                                    <?php }?>
		                                </select>
								    </div>
							    </div>
		                	</div>
		                </div>
						<div class="btn-group right">
						    <button type="button" class="btn btn-success btn-flat btnSaveScheduleUnFollow"><i class="fa fa-clock-o" aria-hidden="true"></i> <?=l('schedule')?></button>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="clearfix"></div>
				</div>
			</form>
        </div>
    </div>
</div>