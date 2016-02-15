<?php

	require('core/Mail.php');
	
	$attachmentFolder = '../attachment/';
	
	$files = array(
		
		'企画概要.pdf'    => $attachmentFolder.'outline.pdf',
		'アンケート用紙.doc' => $attachmentFolder.'questionnaire.doc'
		
	);
	
	$mail = new Mail('【キッズ／無料掲載企画】GWオープンキャンパス情報アンケートご回答のお願い（3/7〆）',$_POST['body']);
	$to   = $_POST['to'];
	
	$mail->setName('キッズ媒体編集部')->setFrom('edit@kidscorp.jp')->setFiles($files);
	
	if ($mail->setTo($to)->send()) echo($to.' : 送信成功！');
	else echo($to.' : 送信失敗！');

?>