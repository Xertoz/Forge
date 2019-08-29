<div class="login-box">
	<div class="login-logo">
		<a href="../../index2.html"><b>Forge</b> <?=substr(FORGE_VERSION, 0, 1)?></a>
	</div>
	<?php echo self::response('Accounts\Login'); ?>
	<div class="login-box-body">
		<p class="login-box-msg"><?=self::l('Sign in to start your session')?></p>
		<form action="<?=self::html($_SERVER['REQUEST_URI'])?>" method="post">
			<?=self::input('hidden', 'forge[controller]', 'Accounts\\Login')?>
			<div class="form-group has-feedback">
				<?=self::input('text', 'account', null, true, ['class'=>'form-control', 'placeholder' => self::l('E-mail')])?>
				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<?php echo self::input('password', 'password', null, false, ['class'=>'form-control', 'placeholder'=>self::l('Password')]); ?>
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="row">
				<div class="col-xs-8">
					<div class="checkbox icheck">
						<label class="">
							<div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div> <?=self::l('Remember Me')?>
						</label>
					</div>
				</div>
				<div class="col-xs-4">
					<button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
				</div>
			</div>
		</form>
		<div class="social-auth-links text-center">
			<p>- OR -</p>
			<a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
				Facebook</a>
			<a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
				Google+</a>
		</div>
		<a href="/user/lost-password"><?=self::l('I forgot my password')?></a><br>
		<a href="/user/register" class="text-center"><?=self::l('Register a new membership')?></a>
	</div>
</div>