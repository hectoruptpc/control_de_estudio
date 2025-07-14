<!DOCTYPE html>
<html>
<head>
	<title><?=$template['title']?></title>
	<meta charset="utf-8">
	<meta name="description" content="<?=DESCRIPTION?>">
	<meta name="keywords" content="<?=KEYWORDS?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	<link href="<?=BASE?>assets/plugins/jquery.ui/smoothness/jquery-ui-1.10.1.custom.css" rel="stylesheet" >
	<link href="<?=BASE?>assets/plugins/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="<?=BASE?>assets/plugins/daterangepicker/daterangepicker-bs3.css">
    <link rel="stylesheet" href="<?=BASE?>assets/plugins/simplepicker/jquery.simple-dtpicker.css">
    <link rel="stylesheet" href="<?=BASE?>assets/plugins/icheck/icheck.css">
    <link rel="stylesheet" href="<?=BASE?>assets/plugins/tags/css/jquery.tagit.css">
    <link rel="stylesheet" href="<?=BASE?>assets/plugins/tags/css/tagit.ui-zendesk.css">
	<link href="<?=BASE?>assets/plugins/elfinder/css/elfinder.min.css" rel="stylesheet" >
    <link href="<?=BASE?>assets/plugins/elfinder/css/theme.css" rel="stylesheet" >
    <link href="<?=BASE?>assets/plugins/elfinder/css/dialog.css" rel="stylesheet" >
	<link href="<?=BASE?>assets/css/fonts.css" rel="stylesheet" type="text/css">
	<link href="<?=BASE?>assets/css/style.css" rel="stylesheet" type="text/css">
	<link href="<?=BASE?>assets/plugins/emojionearea/emojionearea.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="https://maps.googleapis.com/maps/api/js?libraries=places&amp;key=<?=GOOGLE_API_KEY?>"></script>
	<script src="<?=BASE?>assets/plugins/jquery/jquery.min.js"></script>
	<script src="<?=BASE?>assets/plugins/geocomplete/jquery.geocomplete.js"></script>
	<script type="text/javascript">
        var PATH       = '<?=PATH?>';
        var BASE       = '<?=BASE?>';
        var list_chart = [];
        var token      = '<?=$this->security->get_csrf_hash();?>';
        var module     = '<?=ucfirst($this->router->fetch_class());?>';
        var system_processing = '<?=l('system-processing')?>';
        var not_activated = '<?=l('your-account-is-not-activated')?>';
        var follow     = '<?=l('Follow')?>';
        var unfollow   = '<?=l('Unfollow')?>';

    </script>
</head>
<body>
	<?=modules::run("Home/header")?>
	<?=$template['body']?>
	<?=modules::run("Home/footer")?>

	<!--Modal Login-->
	<div id="myModal" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	        <div class="modal-content">
	        	<form class="formUpdateAccount">
		            <div class="modal-header bg-owner">
		                <button type="button" class="close" data-dismiss="modal">&times;</button>
		                <h4 class="modal-title"><?=l('add-new-account')?></h4>
		            </div>
		            <div class="modal-body">
		                <div class="box-body text-center">
		                	<?php if(COUNT_ACCOUNT >= MAXIMUM_ACCOUNT){?>
		                		<ul class="account-errors has-message error text-left" style="display: block;">
		                			<li><i class="fa fa-exclamation-circle"></i> <?=l('desc-account-limit')?></li>
		                		</ul>
			                <?php }?>
		                	<div class="form-horizontal" style="max-width: 400px; margin: auto;">
		                        <div class="form-group">
		                            <label class="col-sm-3 control-label mt0"><?=l('username')?></label>
		                            <div class="col-sm-9">
		                                <input type="text" name="username" class="form-control">
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="col-sm-3 control-label mt0"><?=l('password')?></label>
		                            <div class="col-sm-9">
		                                <input type="password" name="password" class="form-control">
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="col-sm-3 control-label"></label>
		                            <div class="col-sm-9">
		                                <div class="msg-add-new-account"></div>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="form-group text-red"><?=l('you-can-be-assured')?></div>
		                </div>
		            </div>
		            <div class="modal-footer">
		                <button type="submit" class="btn btn-success"><?=l('submit')?></button>
		            </div>
	            </form>
	        </div>
	    </div>
	</div>

	<!--javascript-->
	<script src="<?=BASE?>assets/plugins/bootstrap/bootstrap.min.js"></script>
	<script src="<?=BASE?>assets/plugins/highcharts/highcharts.js"></script>
	<script src="<?=BASE?>assets/plugins/jquery.ui/jquery.ui.min.js"></script>
	<script src="<?=BASE?>assets/plugins/icheck/icheck.min.js"></script>
	<script src="<?=BASE?>assets/plugins/tags/js/tag-it.min.js"></script>
	<script src="<?=BASE?>assets/plugins/elfinder/js/elfinder.full.js"></script>
    <script src="<?=BASE?>assets/plugins/elfinder/js/jquery.dialogelfinder.js"></script>
    <script src="<?=BASE?>assets/plugins/daterangepicker/moment.min.js"></script>
    <script src="<?=BASE?>assets/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="<?=BASE?>assets/plugins/simplepicker/jquery.simple-dtpicker.js"></script>
    <script type="text/javascript" src="<?=BASE?>assets/plugins/emojionearea/emojionearea.js"></script>
	<script src="<?=BASE?>assets/js/instagram.js"></script>
	<script src="<?=BASE?>assets/js/main.js"></script>
</body>
</html>