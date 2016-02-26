<?php

	require('core/Mail.php');
	
	$to   = $_POST['to'];
	$body = $_POST['body'];
	
	$attachmentFolder = '../attachment/';
	
	$files = array(
		
		'gwop_kikaku.pdf' => $attachmentFolder.'gwop_kikaku.pdf',
		'gwop_anke.pdf'   => $attachmentFolder.'gwop_anke.pdf'
		
	);
	
	$mail = new Mail('【キッズ／無料掲載企画】GWオープンキャンパス情報アンケートご回答のお願い（3/7〆）',$_POST['body']);
	
	$mail->setName('キッズ媒体編集部')->setFrom('edit@kidscorp.jp')->setFiles($files);
	
	if ($mail->setTo($to)->send()) echo('1');
	else echo('0');

?>