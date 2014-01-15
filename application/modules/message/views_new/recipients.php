<? foreach($recipients as $i=>$user) { if ($user->id == $this->user->id) continue; ?>
	<a href="/collections/<?=$user->uri_name?>"><?=$user->full_name?></a>
	<?=$i<count($recipients)-1 ? ',' : ''?>
<? } ?> 