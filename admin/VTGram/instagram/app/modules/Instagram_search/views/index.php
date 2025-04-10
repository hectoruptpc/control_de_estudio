<div class="wrap">
	<div class="section-dashboard" style="min-height: 300px;">
        <div class="tab-head">
			<span class="title"> <i class="fa fa-search text-info" aria-hidden="true"></i> <?=l('Instagram Search')?></span>
		</div>
        <div class="section-list-dashboard">
		    <div class="row">
		    	<div class="col-md-12">
		    		<?php if(COUNT_ACCOUNT){?>


		    		<form class="formSearch">
					    <div class="box box-widget">
					        <div class="box-body">
					        	<div class="form-inline right" style="display:inline-block;">
								    <div class="form-group">
								    	<input type="text" style="min-width: 300px;" class="form-control" name="keyword" value="<?=get("keyword")?>" placeholder="<?=l('enter-keyword')?>">
								    </div>
								    <div class="form-group">
									    <select class="form-control" name="type">
											<option value="hashtag"><?=l('hashtag')?></option>								    		
											<option value="user" <?=(get("type") == "user")?"selected":""?> ><?=l('user')?></option>	
								    	</select>
								    </div>
								    <div class="form-group">
									    <select class="form-control" name="username">
								    		<?php if(!empty($accounts)){
								    		foreach ($accounts as $key => $row) {
								    		?>
											<option value="<?=$row->username?>" <?=(get("username") == $row->username)?"selected":""?>><?=$row->username?></option>
											<?php }}?>							    		
								    	</select>
								    </div>
				    			    <button type="submit" class="btn btn-danger"><?=l('search')?> <i class="fa fa-search"></i></button>
								</div>
					        </div>
					        <div class="clearfix"></div>
					    </div>
						<?php if(!empty($result)){?>    
						<div class="box box-widget">
						    <div class="box-body no-padding mt15">
						        <div class="table-responsive">
						        	<?php if(get("type") == "hashtag") {?>
						        		<table class="table table-striped table-bordered">
								            <tbody>
									            <tr>
									                <th><?=l('no.')?></th>
									                <th><?=l('hashtag')?></th>
									                <th><?=l('media-count')?></th>
									            </tr>
									            <?php 
									            foreach ($result as $key => $row) {?>
									            <tr>
									                <td><?=$key+1?></td>
									                <td><?=$row->name?></td>
									                <td><?=number_format($row->media_count)?></td>
									            </tr>
								            <?php }?>
								        	</tbody>
								        </table>
						        	<?php }else if(get("type") == "user"){?>
										<table class="table table-striped table-bordered">
								            <tbody>
									            <tr>
									                <th><?=l('no.')?></th>
									                <th><?=l('avatar')?></th>
									                <th><?=l('username')?></th>
									                <th><?=l('fullname')?></th>
									                <th><?=l('follower-count')?></th>
									                <th><?=l('mutual-followers-count')?></th>
									                <th>Option</th>
									            </tr>
									            <?php 
									            foreach ($result->users as $key => $row) {?>
									            <tr>
									                <td><?=$key+1?></td>
									                <td><img src="<?=$row->profile_pic_url?>" /></td>
									                <td><?=$row->username?></td>
									                <td><?=$row->full_name?></td>
									                <td><?=number_format($row->follower_count)?></td>
									                <td><?=number_format($row->mutual_followers_count)?></td>
									                <td>
									                	<?php if($row->friendship_status->following == 1 || $row->friendship_status->outgoing_request){?>
									                		<button type="button" class="btn btn-sm btn-danger btnFollowItem" data-username="<?=get("username")?>" data-action="unfollow" data-id="<?=$row->pk?>"><?=l('unfollow')?></button>
									                	<?php }else{?>
									                		<button type="button" class="btn btn-sm btn-success btnFollowItem" data-username="<?=get("username")?>" data-username="" data-action="follow" data-id="<?=$row->pk?>"><?=l('follow')?></button>
									                	<?php }?>
									                </td>
									            </tr>
								            <?php }?>
								        	</tbody>
								        </table>
						        	<?php }else{?>

						        	<?php }?>
						        </div>
						    </div>
						</div>
						<?php }else{?>
							<div class="box box-solid">
							    <div class="box-body">
							        <div class="schedule-empty mt15"><?=l('empty')?></div>
							    </div>
							</div>
						<?php }?>
					</form>
					<?php }else{?>
						<div class="schedule-list-accounts mt15">
				        	<div class="row">
				        		<div class="item-empty" data-toggle="modal" data-target="#myModal">
				        			<i class="fa fa-instagram"></i>
				        			<div class="text"><?=l('add-new-account')?></div>
				        		</div>
				        	</div>
				        </div>
					<?php }?>
				</div>
		    </div>
		</div>
	</div>
</div>
