<?php
	
	/* =======================================================================
	Class - Mail
	========================================================================== */
	class Mail {
		
		private $name;
		private $from;
		private $to;
		private $subject;
		private $body;
		private $cc;
		private $bcc;
		private $header;
		private $param;
		private $files;
		private $boundary;
		
		const ENCODING = 'UTF-8';
		
		/* =======================================================================
		Constructor
		========================================================================== */
		public function __construct($subject = '',$body = '') {
			
			$this->name     = '';
			$this->from     = '';
			$this->to       = $to;
			$this->subject  = $subject;
			$this->body     = $body;
			$this->cc       = array();
			$this->bcc      = array();
			$this->header   = '';
			$this->param    = '';
			$this->files    = array();
			$this->boundary = md5(uniqid(rand(),true));
			
		}
		
			/* =======================================================================
			Public - Init
			========================================================================== */
			public static function init($subject = '',$body = '') {

				return new self($to,$subject,$body);

			}
		
			/* =======================================================================
			Public - Set Name
			========================================================================== */
			public function setName($value) {

				$this->name = $value;
				return $this;

			}
			
			/* =======================================================================
			Public - Set From
			========================================================================== */
			public function setFrom($value) {

				if (!self::getIsMailaddress($value)) return false;
				$this->from = $value;

				return $this;

			}
			
			/* =======================================================================
			Public - Set To
			========================================================================== */
			public function setTo($value) {

				if (!self::getIsMailaddress($value)) return false;
				$this->to = $value;

				return $this;

			}
			
			/* =======================================================================
			Public - Set Subject
			========================================================================== */
			public function setSubject($value) {

				$this->subject = $value;
				return $this;

			}
			
			/* =======================================================================
			Public - Set Body
			========================================================================== */
			public function setBody($value) {

				$this->body = $value;
				return $this;

			}
			
			/* =======================================================================
			Public - Set Header
			========================================================================== */
			public function setHeader($value) {

				$this->header = $value;
				return $this;

			}
			
			/* =======================================================================
			Public - Set Param
			========================================================================== */
			public function setParam($value) {

				$this->param = $value;
				return $this;

			}
			
			/* =======================================================================
			Public - Set Files
			========================================================================== */
			public function setFiles($array) {
				
				foreach ($array as $key=>$path) {
					if (!file_exists($path)) return false;
				}
				
				$this->files = $array;

				return $this;

			}
			
			/* =======================================================================
			Public - Set Cc
			========================================================================== */
			public function setCc($array) {
				
				foreach ($array as $index=>$mailaddress) {
					if (!self::getIsMailaddress($mailaddress)) return false;
				}
				
				$this->cc = $array;

				return $this;

			}
			
			/* =======================================================================
			Public - Set Bcc
			========================================================================== */
			public function setBcc($array) {
				
				foreach ($array as $index=>$mailaddress) {
					if (!self::getIsMailaddress($mailaddress)) return false;
				}
				
				$this->bcc = $array;

				return $this;

			}
			
			/* =======================================================================
			Public - Set Boundary
			========================================================================== */
			public function setBoundary($value) {

				$this->boundary = $value;
				return $this;

			}
			
			/* =======================================================================
			Public - Send
			========================================================================== */
			public function send() {

				mb_language('ja');
				mb_internal_encoding(self::ENCODING);

				$to      = $this->to;
				$subject = mb_encode_mimeheader($this->subject,'ISO-2022-JP','B');
				$body    = $this->getBody();
				$header  = $this->getHeader();
				$param   = $this->getParam();

				return mail($to,$subject,$body,$header,$param);

			}
			
		/* =======================================================================
		Get Body
		========================================================================== */
		private function getBody() {
			
			$result = mb_convert_encoding($this->body,'JIS',self::ENCODING);
			
			if (count($this->files) < 1) return $result;
			else return $this->getBodyWithFiles($result);
			
		}
		
		/* =======================================================================
		Get Body With Files
		========================================================================== */
		private function getBodyWithFiles($body) {
			
			$boundary = $this->boundary;
			
			$result  = '';
			$result .= '--'.$boundary."\n";
			$result .= 'Content-Type: text/plain; charset="iso-2022-jp"'."\n";
			$result .= 'Content-Transfer-Encoding: 7bit'."\n\n";
			$result .= $body."\n";
			
			foreach ($this->files as $filename=>$path) {
				
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
		
		/* =======================================================================
		Get From
		========================================================================== */
		private function getFrom() {
			
			$result = '';
			
			if (strlen($this->name) < 1) $result .= $this->from;
			else $result .= mb_encode_mimeheader($this->name,'ISO-2022-JP','B').' <'.$this->from.'>';
			
			return $result;
			
		}
		
		/* =======================================================================
		Get Cc
		========================================================================== */
		private function getCc() {
			
			$result = '';
			
			if (count($this->cc) > 0) {
				$result .= 'Cc: '.implode(',',$this->cc)."\r\n";
			}
			
			return $result;
			
		}
		
		/* =======================================================================
		Get Bcc
		========================================================================== */
		private function getBcc() {
			
			$result = '';
			
			if (count($this->bcc) > 0) {
				$result .= 'Bcc: '.implode(',',$this->bcc)."\r\n";
			}
			
			return $result;
			
		}
		
		/* =======================================================================
		Get Header
		========================================================================== */
		private function getHeader() {
			
			$result = '';
			
			$result .= 'X-Mailer: PHP5'."\r\n";
			$result .= 'From: '.$this->getFrom()."\r\n";
			$result .= 'Return-Path: '.$this->getFrom()."\r\n";
			$result .= $this->getCc().$this->getBcc();
			$result .= 'MIME-Version: 1.0'."\r\n";
			$result .= 'Content-Transfer-Encoding: 7bit'."\r\n";
			
			if (count($this->files) < 1) {
				$result .= 'Content-Type: text/plain; charset="iso-2022-jp"'."\n";
			} else {
				$result .= 'Content-Type: multipart/mixed; boundary="'.$this->boundary.'"'."\n";
			}
			
			$result .= $this->header;
			
			return $result;
			
		}
		
		/* =======================================================================
		Get Param
		========================================================================== */
		private function getParam() {
			
			$result = '';
			
			$result .= '-f '.$this->from;
			$result .= $this->param;
			
			return $result;
			
		}
		
		/* =======================================================================
		Get Is Mailaddress
		========================================================================== */
		private function getIsMailaddress($value) {
			
			if (strlen($value) < 1) return false;
			
			$string = '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/';
			$result = preg_match($string,$value);
			
			if ($result === false || $result === 0) {
				
				echo('Error');
				return false;
				
			}
			
			return true;
			
		}
		
	}

?>