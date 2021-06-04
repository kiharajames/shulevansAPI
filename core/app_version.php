<?php
	Class AppVersion{
		public $id;
		public $version;
		private $table = "app_version";
		private $conn;

		public function __construct($conn){
			$this->conn = $conn;
		}

		public function checkVersion(){
			$query = "SELECT * FROM ".$this->table." WHERE app=:app";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(':app', $this->app);
			$stmt->execute();

			return $stmt;
		}
	}

?>