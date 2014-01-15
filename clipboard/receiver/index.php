<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body>
<?php

mysql_connect('localhost', 'boris', '5X2LLePkCVPbHIv');
mysql_selectdb('boris');
mysql_query('SET NAMES UTF8');

if ($_POST['data'])
{
//    $data = mb_convert_encoding($_POST['data'], 'UTF-8');
    $data = mysql_real_escape_string($_POST['data']);

    $sql = 'INSERT INTO clipboard SET ts='.time().', txt="'.$data.'"';
	mysql_query($sql);

//    file_put_contents('./test.data', $_POST['data']);
//    echo json_encode(array('ok' => 1));
}
else
{
	if ($_GET['id'])
	{
		$sql = 'SELECT txt FROM clipboard WHERE id='.intval($_GET['id']);
		$res = mysql_query($sql);
		$row = mysql_fetch_assoc($res);
		echo $row['txt'];
	}
	else
	{
		$sql = 'SELECT id,ts,txt FROM clipboard ORDER BY id DESC LIMIT 0,50';
		$res = mysql_query($sql);
		while ($row = mysql_fetch_assoc($res))
		{
			echo '<div style="margin-bottom: 10px; border-bottom: 5px solid black;">'.$row['txt'].'</div>';
		}
	}

}
//    echo file_get_contents('./test.data');

?>
</body>
</html>