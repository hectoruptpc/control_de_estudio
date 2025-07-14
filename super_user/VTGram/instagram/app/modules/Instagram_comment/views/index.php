<div class="wrap">
	<div class="section-schedule">
		<div class="tab-head">
			<span class="title"> <i class="fa fa-comment text-info" aria-hidden="true"></i> <?=l('Comment')?></span>
			<div class="right">
				<button type="submit" class="btn btn-info btn-flat AddNewSchedule" data-toggle="modal" data-target="#modal-addSchedule"><i class="fa fa-plus" aria-hidden="true"></i> <?=l('Add new')?></button>
				<div class="btn-group">
			        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?=l('action')?>
			            <span class="fa fa-caret-down"></span></button>
			        <ul class="dropdown-menu">
			            <li><a class="btnActionList" data-action="delete" data-type="auto_comment" href="javascript:void(0);"><?=l('Delete All')?></a></li>
			        </ul>
			    </div>
			</div>
		</div>

		<form class="formList">
			<div class="schedule-list-posts">
				<div class="row">
					<?php if($countSchedule > 0){?>
					<div class="list_load" data-page="0" data-action="<?=PATH."Instagram_comment/ajax_page"?>"></div>
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
				<div class="schedule-content">
					<div class="schedule-loading"></div>
					<textarea placeholder="<?=l('write-something')?>" class="post-message" name="description"></textarea>
					<div class="schedule-option">
						<div class="form-group col-md-12">
		                	<div class="row">
							    <div class="row">
							    	<div class="form-group col-md-6 mb0">
								        <label class="head-title col-md-12 p0 fn"><i class="fa fa-clock-o"></i> <?=l('time-comment')?></label>
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
		                	</div>
		                </div>
						<div class="btn-group right">
						    <button type="button" class="btn btn-success btn-flat btnSaveScheduleComment1"><i class="fa fa-clock-o" aria-hidden="true"></i> <?=l('schedule')?></button>
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
			        				<input type="radio" name="account" data-id="<?=$row->id?>" value="<?=$row->id."{-}".$row->username?>"/>
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
		        <div class="col-md-12">
		        	<div class="form-inline" style="display:inline-block;">
				    	<button type="button" style="margin-bottom: 10px;" class="btn btn-default btnSearchScrap" data-type="timeline"><?=l('timeline-feed')?></button>
				    	<button type="button" style="margin-bottom: 10px;" class="btn btn-default btnSearchScrap" data-type="popular"><?=l('popular-feed')?></button>
				    	<button type="button" style="margin-bottom: 10px;" class="btn btn-default btnSearchScrap" data-type="self"><?=l('self-feed')?></button>
				    	<button type="button" style="margin-bottom: 10px;" class="btn btn-default btnSearchScrap" data-type="explore"><?=l('explore-tab')?></button>
				    	<div class="input-group" style="margin-bottom: 10px;">
							<input type="text" class="form-control" name="user_comment_search" placeholder="<?=l('enter-hashtag')?>" value="">
							<div class="input-group-btn">
								<button type="button" class="btn btn-default btnSearchScrap" data-type="user"><i class="fa fa-search" aria-hidden="true"></i></button>
							</div>
						</div>
					</div>
		        </div>		
				<div class="clearfix"></div>
				<div class="list-search-feed mt15">
					
				</div>
			</form>
        </div>
    </div>
</div>