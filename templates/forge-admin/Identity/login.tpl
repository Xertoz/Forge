{include file='header.tpl'}
<div class="login-box">
    <div class="login-logo">
        <a href="../../index2.html"><b>Forge</b> {FORGE_VERSION|truncate:1:''}</a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">{'Sign in to start your session'|l}</p>
        <form action="{$smarty.server.REQUEST_URI}" method="post">
            {input type='hidden' name='forge[controller]' value='Accounts\\Login'}
            <div class="form-group has-feedback">
                {input type='text' name='account' class='form-control' placeholder='E-mail'|l}
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                {input type='password' name='password' auto=false class='form-control' placeholder='Password'|l}
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label class="">
                            <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div> {'Remember me'|l}
                        </label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{'Sign In'|l}</button>
                </div>
            </div>
        </form>
        <div class="social-auth-links text-center">
            <p>- {'Or'|l|upper} -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> {'Sign in using Facebook'|l}</a>
            <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> {'Sign in using Google+'|l}</a>
        </div>
        <a href="/user/lost-password">{'I forgot my password'|l}</a><br>
        <a href="/user/register" class="text-center">{'Register a new membership'|l}</a>
    </div>
</div>
{include file='footer.tpl'}