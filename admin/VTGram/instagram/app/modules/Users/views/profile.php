<div class="wrap">
	<div class="section-dashboard">
        <div class="tab-head">
            <span class="title"> <i class="fa fa-user text-info" aria-hidden="true"></i> <?=l('profile')?></span>
        </div>
		<form class="formProfile" role="form">
		    <div class="clearfix"></div>
            <div class="col-md-offset-3 col-md-6">
                <div class="form-group">
                    <label><?=l('maximum-account-instagram')?></label>
                    <input type="text" class="form-control" readonly="true" value="<?=!empty($result)?$result->maximum_account:""?>">
                </div>
                <div class="form-group">
                    <label><?=l('fullname')?></label>
                    <input type="text" class="form-control" name="fullname" value="<?=!empty($result)?$result->fullname:""?>">
                </div>
                <div class="form-group">
                    <label><?=l('email')?></label>
                    <input type="text" class="form-control" name="email" value="<?=!empty($result)?$result->email:""?>">
                </div>
                <div class="form-group">
                    <label><?=l('password')?></label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="form-group">
                    <label><?=l('re-password')?></label>
                    <input type="password" class="form-control" name="re-password">
                </div>
                <div class="clearfix"></div>
                <div class="form-group mt15">
                    <div class="message"></div>
                </div>
                <div class="form-group">
                	<button type="submit" class="btn btn-primary right"><?=l('submit')?></button>
                </div>
            </div>
            <div class="clearfix"></div>
		</form>
	</div>
</div>