<?php if(!empty($result)){?>
<table class="table table-striped">
    <tbody>
	    <tr>
	    	<th>
	    		<div class="box-icheck">
                  	<input type="checkbox" class="icheck checkAllFeed" id="checkAllFeed">
                  	<label class="label-icheck m0" for="checkAllFeed">&nbsp;</label>
                </div>
	    	</th>
	        <th><?=l('photo')?></th>
	        <th><?=l('username')?></th>
	        <th><?=l('fullname')?></th>
	        <th><?=l('option')?></th>
	    </tr>
	    <?php 
	    foreach ($result as $row) {?>
	    <tr>
	    	<td>
	    		<div class="box-icheck">
                  	<input type="checkbox" class="icheck checkItemFeed" name="media_id[]" id="feed-<?=$row->pk?>" value="<?=$row->pk."{-}".$row->username?>">
                  	<label class="label-icheck m0" for="feed-<?=$row->pk?>">&nbsp;</label>
                </div>
	    	</td>
	        <td>
	        	<div class="photo" style="background-image: url('<?=$row->profile_pic_url?>')"></div>
	        </td>
	        <td class="caption"><?=$row->username?></td>
	        <td class="caption"><?=$row->full_name?></td>
	        <td>
	        	<div class="btn-group">
             	 	<a href="https://www.instagram.com/<?=$row->username?>" class="btn btn-default" target="_blank"><i class="fa fa-eye"></i></a>
                </div>
	        </td>
	    </tr>
	    <?php }?>
	</tbody>
</table>
<?php }else{?>
	<div style="margin-bottom: 20px;" class="schedule-empty zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz"><?=l('empty')?></div>
<?php }?>