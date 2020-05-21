<?php

/*
    @author Frex
*/

include_once 'assets/dbconfig.php';

class News 
{
    private $id;
    private $title;
    private $text;
    private $date;
	private $admin;
	
    function __construct() 
	{
        try
		{
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM news");
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->id = (int)$data['id'];
            $this->title = $data['title'];
            $this->text = $data['text'];
            $this->date = $data['created_at'];
			$this->admin = (int)$data['admin'];
			
            $db = null;
            return true;
        } 
		catch (PDOException $e) 
		{
            echo '{"errore":{"messaggio":' . $e->getMessage() . '}}';
        }
    }
    
    function getId() {
        return $this->id;
    }

    function getTitle() {
        return $this->title;
    }

	function getDate() {
        return $this->date;
    }
	
    function getText() {
        return $this->text;
    }
	
	function getAdmin() {
		return $this->admin;
	}
}

?>