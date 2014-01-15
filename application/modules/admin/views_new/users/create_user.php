<!-- Wrapper -->
<div class="wrapper">
	<!-- Left column/section -->
	<section class="column width6 first">	
		<? $result = $this->session->flashdata('result');
		if(isset($result['error']) && $result['error']):?>		
			<div class="box box-error"><?=$result['msg']?></div>
		<? elseif($result):?>
			<div class="box box-info">User created sucessfully</div>
		<?endif?>

		<?= Form_Helper::open('dummy_account/create_simple_accounts')?>
			<p>
				<label class="required" for="name">Name:</label><br>
				<input type="text" id="name" class="half" autocomplete="off" value="" name="name">
			</p>
			<p>
				<label class="required" for="password">Password:</label><br>
				<input type="password" id="password" class="half" autocomplete="off" value="" name="password">
			</p>
			<p>
				<label class="required" for="email">Email:</label><br>
				<input type="text" id="email" class="half" value="" name="email">
			</p>
			<p>		
				<label class="required" for="username">Username:</label><br>
				<input type="text" id="username" class="half" value="" name="username">
			</p>
			<p class="box">
				<input type="submit" class="btn btn-blue" value="Create user" />
			</p>
		<?=Form_Helper::close()?>
	</section
</div> 