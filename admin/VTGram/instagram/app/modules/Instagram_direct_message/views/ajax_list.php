<?php if(!empty($schedule)){
$date = "";
foreach ($schedule as $row) {
	$spintax   = new Spintax();
	$time_post = date("Y-m-d", strtotime($row->time_post));
	$status    = INSTAGRAM_STATUS($row->status);
?>
<div class="col-md-4">
	<div class="item" data-id="<?=$row->id?>">
		<div class="title">
			<span class="username"><?=$row->name?></span>
			<span class="time"><i class="fa fa-clock-o" aria-hidden="true"></i> <?=date("M d, Y h:i a", strtotime($row->time_post))?></span>
		</div>

		<div class="image" style="height: 60px;">
			<div class="status label-<?=$status->label?>" ><?=$status->text?> 
			<?php if($row->message_error != ""){?>
			<i class="fa fa-question-circle" aria-hidden="true" data-toggle="popover" title="<?=$status->text?>" data-trigger="hover" data-content="<?=$row->message_error?>" data-placement="bottom"></i>
			<?php }?>
			</div>
		</div>

		<div class="info">
			<div class="type">
				<span class="icon">
					<i class="fa fa-commenting" aria-hidden="true"></i>
				</span>
				<?=$row->title?>
			</div>
			<div class="action right">
				<a href="https://www.instagram.com/<?=$row->title?>" target="_blank" class="icon">
					<i class="fa fa-external-link" aria-hidden="true"></i>
				</a>
				<?php if($row->status != 1 && $row->status != 4){?>
				<span class="icon btnActionItem" data-action="repost">
					<i class="fa fa-undo" aria-hidden="true"></i>
				</span>
				<?php }?>
				<?php if($row->status == 1 || $row->status == 4){?>
				<span class="icon btnActionItem" data-action="cancel">
					<i class="fa fa-stop" aria-hidden="true"></i>
				</span>
				<?php }?>
				<span class="icon btnActionItem" data-action="delete">
					<i class="fa fa-trash-o" aria-hidden="true"></i>
				</span>
			</div>
		</div>
	</div>
</div>
<?php }}?>