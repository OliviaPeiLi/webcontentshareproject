<!-- Wrapper -->
<div class="wrapper-login">
	<!-- Login form -->
	<section class="full">
		<h3>Login</h3>
		<div class="box box-info">Type anything to log in</div>
		<?=Form_Helper::open('', array('id'=>"loginform"))?>
			<p>
				<label class="required" for="email">Username:</label><br/>
				<input type="text" id="email" class="full" value="" name="email"/>
			</p>
			<p>
				<label class="required" for="password">Password:</label><br/>
				<input type="password" id="password" class="full" value="" name="password"/>
			</p>
			<p>
				<input type="checkbox" id="remember" class="" value="1" name="remember"/>
				<label class="choice" for="remember">Keep me logged in?</label>
			</p>
			<p>
				<input type="submit" class="btn btn-green big" value="Login"/> &nbsp; <a href="javascript: //;" onclick="$('#emailform').slideDown(); return false;">Forgot password?</a> or <a href="#">Need help?</a>
			</p>
			<div class="clear">&nbsp;</div>
		</form>
	</section>
	<!-- End of login form -->
</div>
<!-- End of Wrapper --> 