<?php
	
	if (!Mail::create()->name('Test')->from('sakata@graphic.co.jp')->to($_POST['to'])->title('Test')->body($_POST['body'])->files(array('企画概要.pdf'=>'../attachment/outline.pdf'))->send()) {
		
		echo('Error');
		
	}
	
	class Mail {
		
		private $name     = '';
		private $from     = '';
		private $to       = '';
		private $title    = '';
		private $body     = '';
		private $cc       = array();
		private $bcc      = array();
		private $header   = '';
		private $param    = '';
		private $files    = array();
		private $boundary = '';
		
		const ENCODING = 'UTF-8';
		
		public function construct($to = '',$subject = '',$message = '',$additional_headers = '',$additional_parameters = '') {
			
			$this->to     = $to;
			$this->title  = $subject;
			$this->body   = $message;
			$this->header = $additional_headers;
			$this->param  = $additional_parameters;
			
		}
		
		public static function create($to = '',$subject = '',$message = '',$additional_headers = '',$additional_parameters = '') {
			
			return new self($to,$subject,$message,$additional_headers,$additional_parameters);
			
		}
		
		public static function checkMailaddress($mailaddress) {
			
			if (strlen($mailaddress) < 1) return false;
			
			$string = '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/';
			$result = preg_match($string,$mailaddress);
			
			if ($result === false || $result === 0) {
				
				echo('Error');
				return false;
				
			}
			
			return true;
			
		}
		
		public function name($name = null) {
			
			if (is_null($name)) return $this->name;
			else $this->name = $name;
			
			return $this;
			
		}
		
		public function from($mailaddress = null) {
			
			if (is_null($mailaddress)) {
				
				return $this->from;
				
			} else {
				
				if (!self::checkMailaddress($mailaddress)) return false;
				$this->from = $mailaddress;
				
			}
			
			return $this;
			
		}
		
		public function to($mailaddress = null) {
			
			if (is_null($mailaddress)) {
				
				return $this->from;
				
			} else {
				
				if (!self::checkMailaddress($mailaddress)) return false;
				$this->to = $mailaddress;
				
			}
			
			return $this;
			
		}
		
		public function title($string = null) {
			
			if (is_null($string)) return $this->title;
			else $this->title = $string;
			
			return $this;
			
		}
		
		public function body($string = null) {
			
			if (is_null($string)) return $this->body;
			else $this->body = $string;
			
			return $this;
			
		}
		
		public function header($string = null) {
			
			if (is_null($string)) return $this->header;
			else $this->header = $string;
			
			return $this;
			
		}
		
		public function param($string = null) {
			
			if (is_null($string)) return $this->param;
			else $this->param = $string;
			
			return $this;
			
		}
		
		public function files($array = null) {
			
			if (is_null($array)) {
				
				return $this->files;
				
			} else {
				
				foreach ($array as $key=>$path) {
					if (!file_exists($path)) return false;
				}
				
				$this->files = $array;
				
			}
			
			return $this;
			
		}
		
		public function cc($array = null) {
			
			if (is_null($array)) {
				
				return $this->cc;
				
			} else {
				
				foreach ($array as $index=>$mailaddress) {
					if (!self::checkMailaddress($mailaddress)) return false;
				}
				
				$this->cc = $array;
				
			}
			
			return $this;
			
		}
		
		public function bcc($array = null) {
			
			if (is_null($array)) {
				
				return $this->bcc;
				
			} else {
				
				foreach ($array as $index=>$mailaddress) {
					if (!self::checkMailaddress($mailaddress)) return false;
				}
				
				$this->bcc = $array;
				
			}
			
			return $this;
			
		}
		
		public function boundary($string) {
			
			if (is_null($string)) {
				
				if (strlen($this->boundary) < 1) {
					$this->boundary = md5(uniqid(rand(),true));
				}
				
				return $this->boundary;
				
			} else {
				
				$this->boundary = $string;
				
			}
			
			return $this;
			
		}
		
		public function send() {
			
			mb_language('ja');
			mb_internal_encoding(self::ENCODING);
			
			$to      = $this->to();
			$subject = mb_encode_mimeheader($this->title(),'ISO-2022-JP','B');
			$body    = $this->buildBody();
			$header  = $this->buildHeader();
			$param   = $this->buildParam();
			
			return mail($to,$subject,$body,$header,$param);
			
		}
		
		private function buildBody() {
			
			$result = mb_convert_encoding($this->body(),'JIS',self::ENCODING);
			
			if (count($this->files()) < 1) return $result;
			else return $this->appendFiles($result);
			
		}
		
		private function appendFiles($body) {
			
			$boundary = $this->boundary();
			
			$result  = '';
			$result .= '--'.$boundary."\n";
			$result .= 'Content-Type: text/plain; charset="iso-2022-jp"'."\n";
			$result .= 'Content-Transfer-Encoding: 7bit'."\n\n";
			$result .= $body."\n";
			
			foreach ($this->files() as $filename=>$path) {
				
				if (!file_exists($path)) continue;
				
				$filename = mb_encode_mimeheader($filename,'ISO-2022-JP','B');
				
				$result .= "\n";
				$result .= '--'.$boundary."\n";
				$result .= 'Content-Type: application/octet-stream; charset="iso-2022-jp" name="'.$filename.'"'."\n";
				$result .= 'Content-Transfer-Encoding: base64'."\n";
				$result .= 'Content-Disposition: attachment; filename="'.$filename .'"'."\n\n";
				$result .= chunk_split(base64_encode(file_get_contents($path)))."\n";
				
			}
			
			$result .= '--'.$boundary.'--';
			return $result;
			
		}

	 /* Fromの構築
	 --------------------------------------------------------------------------*/
	 private function buildFrom()
	 {
	  $from = '';
	  if( strlen( $this->name() ) <= 0 ){
	   $from .= $this->from();
	  }
	  else{
	   $from .= mb_encode_mimeheader( $this->name(), 'ISO-2022-JP', 'B' ) . ' <' . $this->from() . '>';
	  }
	  return $from;
	 }

	 /* Ccの構築
	 --------------------------------------------------------------------------*/
	 private function buildCc()
	 {
	  $cc = '';
	  if( count( $this->cc() ) > 0 ){
	   $cc .= 'Cc: ' . implode( ',', $this->cc() ) . "\r\n";
	  }
	  return $cc;
	 }

	 /* Bccの構築
	 --------------------------------------------------------------------------*/
	 private function buildBcc()
	 {
	  $bcc = '';
	  if( count( $this->bcc() ) > 0 ){
	   $bcc .= 'Bcc: ' . implode( ',', $this->bcc() ) . "\r\n";
	  }
	  return $bcc;
	 }

	 private function buildHeader() {
		
	  $header = '';

	  $header .= 'X-Mailer: PHP5'."\r\n";
	  $header .= 'From: ' . $this->buildFrom() . "\r\n";
	  $header .= 'Return-Path: ' . $this->buildFrom()."\r\n";
	  $header .= $this->buildCc();
	  $header .= $this->buildBcc();
	  $header .= 'MIME-Version: 1.0'."\r\n";
	  $header .= 'Content-Transfer-Encoding: 7bit'."\r\n";
	  if( count( $this->files() ) <= 0 ){
	   $header .= 'Content-Type: text/plain; charset="iso-2022-jp"'."\n";
	  }
	  else {
	   $header .= 'Content-Type: multipart/mixed; boundary="' . $this->boundary() . '"'."\n";
	  }

	  // ユーザ定義
	  $header .= $this->header();

	  return $header;
	 }

	 /* パラメータ構築
	 --------------------------------------------------------------------------*/
	 private function buildParam()
	 {
	  $param = '';

	  // デフォルト
	  $param .= '-f ' . $this->from();

	  // ユーザ定義
	  $param .= $this->param();

	  return $param;
	 }
	}

?>