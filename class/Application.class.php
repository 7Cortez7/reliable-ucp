<?php

/*
    @author Frex
*/

include_once 'assets/dbconfig.php';

class Application 
{
	private $id;
    private $name;
	private $account_id;
	private $account_name;
	private $telephone;
	private $drive_license;
	private $background;
	private $skin;
	private $date;
	private $status;
	private $reason;
	private $sex;
	private $character_id;
	private $handler_id;
	private $handled_at;
	private $ip;
	
	public static function getApplicationQuestions($id)
	{
		try 
		{
			$array = array();
            $db = getDB();
            $stmt = $db->prepare("SELECT question_id FROM character_application_answers WHERE application_id=:id");
            $stmt->bindParam("id", $id, PDO::PARAM_INT);
			$stmt->execute();
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) 
			{
                array_push($array, $data['question_id']);
            }
			
			return $array;
		}
		catch (PDOException $e) 
		{
            echo '{"errore":{"messaggio":' . $e->getMessage() . '}}';
        }
	}
	
	public static function getCount()
	{
        try 
        {
            $db = getDB();
            $stmt = $db->prepare("SELECT COUNT(id) AS app_count FROM character_applications WHERE is_accepted IS NULL");
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
			
			return $data['app_count'];
        } 
        catch (PDOException $e) 
        {
            echo '{"errore":{"messaggio":' . $e->getMessage() . '}}';
        }
    }
	
    function __construct($id) 
    {
        try
        {
			$db = getDB();
            $stmt = $db->prepare("SELECT * FROM character_applications WHERE id=:id");
            $stmt->bindParam("id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

			$this->id = $id;
			$this->ip = $data['ip_address'];
            $this->name = $data['char_name'];
			$this->skin = (int)$data['skin'];
			$this->telephone = (int)$data['wants_phone'];
			$this->drive_license = (int)$data['wants_license'];
			$this->account_id = (int)$data['account_id'];
			$this->background = $data['essay'];
			$this->date = $data['created_at'];
			$this->status = $data['is_accepted'];
			$this->reason = $data['reason'];
			$this->sex = (int)$data['sex'];
			$this->character_id = (int)$data['character_id'];
			$this->handler_id = $data['handler_id'];
			$this->handled_at = $data['handled_at'];
        } 
        catch (PDOException $e) 
        {
            echo '{"errore":{"messaggio":' . $e->getMessage() . '}}';
        }
    }
	
	function getIP() {
		return $this->ip;
	}
	
	function getAccountName() {
		
		$user = new User($this->account_id);

        return $user->getUsername();
    }
	
	function getID() {
		return $this->id;
	}
	
	function getName() {
		return $this->name;
	}
 	
	function getSkin() {
		return $this->skin;
	}
	
	function getTelephone() {
		return $this->telephone;
	}
	
	function getLicense() {
		return $this->drive_license;
	}
	
	function getAccountId() {
		return $this->account_id;
	}
	
	function getBackground() {
		return $this->background;
	}
	
	function getCharacterId() {
		return $this->character_id;
	}
	
	function getHandlerId() {
		return $this->handler_id;
	}
	
	function getHandledAt() {
		return $this->handled_at;
	}

	function getHandlerName() {
		
		$user = new User($this->handler_id);

        return $user->getUsername();
    }
	
	function getStatus() {
		return $this->status;
	}
	
	function getSex() {
		return $this->sex;
	}
	
	function getDate() {
		return $this->date;
	}
}

?>