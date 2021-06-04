<?php
class Post{
	//db things
	private $conn;
	private $table = 'posts';

	//post properties
	public $id;
	public $category_id;
	public $category_name;
	public $title;
	public $body;
	public $author;
	public $created_at;

	//constructor
	public function __construct($conn){
		$this->conn = $conn;
	}

	//getting posts from the database
	public function read(){
		$query = "SELECT c.name as category_name,
		p.id,
		p.category_id,
		p.title,
		p.body,
		p.author,
		p.created_at
		FROM 
		posts p 
		LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC";
		//prepare
		$stmt = $this->conn->prepare($query);
		//execute
		$stmt->execute();
		return $stmt;

	}
	public function read_single(){
		$query = "SELECT c.name as category_name,
		p.id,
		p.category_id,
		p.title,
		p.body,
		p.author,
		p.created_at
		FROM 
		posts p 
		LEFT JOIN categories c ON p.category_id = c.id WHERE p.id=:id";
		//prepare
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':id', $this->id);
		
		//execute
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->category_id = $row['category_id'];
		$this->title = $row['title'];
		$this->body = $row['body'];
		$this->author = $row['author'];
		$this->category_name = $row['category_name'];
		return $stmt;
	}

	//function to insert data
	public function create(){
		$query = "INSERT INTO posts (category_id, title, body, author) VALUES(:category_id, :title, :body, :author)";
		$stmt = $this->conn->prepare($query);
		//clean data
		$this->title = htmlspecialchars(strip_tags($this->title));
		$this->body = htmlspecialchars(strip_tags($this->body));
		$this->author = htmlspecialchars(strip_tags($this->author));
		$this->category_id = htmlspecialchars(strip_tags($this->category_id));
		$stmt->bindParam(':title', $this->title);
		$stmt->bindParam(':author', $this->author);
		$stmt->bindParam(':body', $this->body);
		$stmt->bindParam(':category_id', $this->category_id);

		//execute the query
		if ($stmt->execute()) {
			return true;
		}else{
			printf("Error %s. \n", $stmt->error);
			return false;
		}
		
	}

	//update function 
	function update(){
		$query = "UPDATE posts SET category_id=:category_id, title=:title, body=:body, author=:author WHERE id=:id";
		$stmt = $this->conn->prepare($query);
		$this->category_id = htmlspecialchars(strip_tags($this->category_id));
		$this->title = htmlspecialchars(strip_tags($this->title));
		$this->body = htmlspecialchars(strip_tags($this->body));
		$this->author = htmlspecialchars(strip_tags($this->author));
		$this->id = htmlspecialchars(strip_tags($this->id));

		$stmt->bindParam(':category_id', $this->category_id);
		$stmt->bindParam(':title', $this->title);
		$stmt->bindParam(':author', $this->author);
		$stmt->bindParam(':body', $this->body);
		$stmt->bindParam(':id', $this->id);

		if ($stmt->execute()) {
			return true;
		}else{
			printf("Error %s. \n", $stmt->error);
			return false;
		}

	}

	//the delete function
	public function delete(){
		$query = "DELETE FROM ".$this->table." WHERE id=:id";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':id', $this->id);

		if ($stmt->execute()) {
			return true;
		}else{
			printf("Error %s .\n", $stmt->error);
			return false;
		}
	}

}

?>
