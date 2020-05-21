<?php

/*
    @author Frex
*/

include_once 'assets/dbconfig.php';

class Alert 
{
    private $id;
    private $object;
    private $text;
    private $date;
	private $opened;
	
    function __construct($id) 
	{
        try
		{
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM ucp_alerts WHERE id = :id");
            $stmt->bindParam("id", $id, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->id = (int)$data['id'];
            $this->object = $data['object'];
            $this->text = $data['text'];
            $this->date = $data['created_at'];
			$this->opened = (int)$data['opened'];
			
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

    function getObject() {
        return $this->object;
    }

	function getDate() {
        return $this->date;
    }
	
    function getText() {
        return $this->text;
    }
	
	function getOpened() {
		return $this->opened;
	}
}

?>