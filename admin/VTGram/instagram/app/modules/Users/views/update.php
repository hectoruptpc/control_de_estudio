<div class="wrap">
	<div class="section-dashboard">
		<div class="box box-solid section-dashborad-head">
            <div class="box-header with-border section-head">
                <i class="fa fa-user" aria-hidden="true"></i> <?=l('user-manage')?>
            </div>
        </div>
        <div class="tab-head">
            <span class="title"> <i class="fa fa-user text-info" aria-hidden="true"></i> <?=l('user-manage')?></span>
        </div>
		<form class="formUpdate" role="form" data-redirect="users">
		    <div class="clearfix"></div>
            <div class="col-md-offset-3 col-md-6">
                <div class="form-group">
                    <label><?=l('role')?></label>
                    <select class="form-control" name="admin" >
                        <option value="0" <?=(!empty($result) && $result->admin == 0)?"selected":""?>><?=l('user')?></option>
                        <option value="1" <?=(!empty($result) && $result->admin == 1)?"selected":""?>><?=l('admin')?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label><?=l('maximum-account-instagram')?></label>
                    <input type="text" class="form-control" name="maximum_account" value="<?=!empty($result)?$result->maximum_account:""?>">
                </div>
                <div class="form-group">
                    <label><?=l('fullname')?></label>
                    <input type="hidden" class="form-control" name="id" value="<?=!empty($result)?$result->id:""?>">
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
                <div class="checkbox">
                    <label>
                    	<div class="box-icheck">
                        	<input type="checkbox" class="icheck checkItem" id="checkItem" name="status" value="1" <?php if(empty($result) || (!empty($result) && $result->status == 1)){ echo "checked"; }?> > <?=l('active-disable')?>
		                  	<label class="label-icheck m0" for="checkItem">&nbsp;</label>
		                </div>
                    </label>
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