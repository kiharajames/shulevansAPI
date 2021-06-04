<?php
	Class Messages(){
		public $recipient;
		public $from = "SHULEVANS";
		public $message;

		public function __construct($recipient,$message){
			$this->recipient = $recipient;
			$this->message = $message;
		}

		public function sendMessage(){
			
		}
	}

?>