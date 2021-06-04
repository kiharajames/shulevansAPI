<?php

Class MessageSettings{
	private $conn;
	private $table = "message_settings";

	public $pick_up_message;
    public $arrival_school_message;
    public $departure_message;
    public $drop_off_message;

    public function __construct($conn){
    	$this->conn = $conn;
    }

    public function getMessage(){
        $id = 1;
    	$query = "SELECT * FROM message_settings WHERE id=:id";
    	$stmt = $this->conn->prepare($query);
    	$stmt->bindParam(':id', $id);
    	$stmt->execute();

    	return $stmt;
    }
}

?>