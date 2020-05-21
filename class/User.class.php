<?php

/*
    @author Frex
*/

include_once 'assets/dbconfig.php';

class User
{
    private $arrayStaffLevels = array("Oyuncu", "Helper", "Admin", "Developer", "Lead Developer", "Lead Admin", "Management");
    private $id;
    private $username;
    private $password;
    private $staffLevel;
    private $role;
    private $banned;
    private $ban_time;
    private $ip;
    private $email;
    private $premium;
    private $premiumExpires;
    private $coynPoints;
    private $charactersSlot;
    private $lastLogin;
    private $maxSlots;
    private $unreadAlerts = 0;
	private $charactersCount = 0;
	private $deletedCount = 0;
	private $charactersList = array();
	private $alertsList = array();
	private $deletedList = array();
	private $applicationsCount = 0;
	private $applicationsWaiting = 0;
	private $applicationsList = array();
	private $freeSlots;
	private $isOfficer = false;

    public static function playerLogin($username, $password, $ip)
    {
        try
        {
            $db = getDB();
            $hashPassword = hash('whirlpool', $password);
            $stmt = $db->prepare("SELECT id, banned, confirmed FROM accounts WHERE username=:username AND password=:hashPassword");
            $stmt->bindParam("username", $username, PDO::PARAM_STR);
            $stmt->bindParam("hashPassword", $hashPassword, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

			$banned = $data['banned'];
			$confirmed = $data['confirmed'];

            if($banned < 1 && $count && $confirmed)
            {
				$tmp_token = random_bytes(16);
				$token = bin2hex($tmp_token);

                setcookie("account", $data['id'], time() + (60 * 60 * 24 * 365), "/");
                setcookie("code", $token, time() + (60 * 60 * 24 * 365), "/");

				$stmt = $db->prepare("UPDATE accounts SET ip=:ip, remember_token=:token WHERE username=:username");
                $stmt->bindParam("ip", $ip, PDO::PARAM_STR);
				$stmt->bindParam("token", $token, PDO::PARAM_STR);
                $stmt->bindParam("username", $username, PDO::PARAM_STR);
                $stmt->execute();

				$user = new User($data['id']);
				$user->playerLog("login", $data['id'], -1);

				return "success";
            }

            if($banned && $count) return "banned";
			if($confirmed < 1 && $count) return "unconfirmed";
			if($count < 1) return "fail";
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
            $stmt = $db->prepare("SELECT * FROM accounts WHERE id=:id");
            $stmt->bindParam("id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->accountId = $data['id'];
            $this->username = $data['username'];
            $this->password = $data['password'];
            $this->staffLevel = (int) $data['admin_level'];
            $this->role = $this->arrayStaffLevels[$this->staffLevel];
            $this->banned = $data['banned'];
            $this->banTime = $data['ban_time'];
            $this->ip = $data['last_ip'];
            $this->email = $data['email'];
            $this->premium = $data['premium'];
            $this->premiumExpires = $data['premium_expires'];
            $this->coynPoints = $data['coyn_points'];
            $this->charactersSlot = (int)$data['max_slot'];
            $this->lastLogin = $data['last_login'];

            $stmt = $db->prepare("SELECT id FROM characters WHERE account_id=:id AND deleted = 1 AND deleted_at IS NOT NULL");
			$stmt->bindParam("id", $id, PDO::PARAM_INT);
            $stmt->execute();
			while($data = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->deletedCount++;
				array_push($this->deletedList, $data['id']);
			}

            $stmt = $db->prepare("SELECT id, faction_id FROM characters WHERE account_id=:id AND deleted = 0 AND deleted_at IS NULL");
			$stmt->bindParam("id", $id, PDO::PARAM_INT);
            $stmt->execute();
			while($data = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->charactersCount++;
				if(!$data['faction_id'] || $data['faction_id'] == 8)$this->isOfficer = true;
				array_push($this->charactersList, $data['id']);
			}

            $stmt = $db->prepare("SELECT id, opened FROM ucp_alerts WHERE account=:id AND archived = 0 ORDER BY created_at ASC LIMIT 5;");
			$stmt->bindParam("id", $id, PDO::PARAM_INT);
			$stmt->execute();
			while($data = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				if($data['opened'] != 1)$this->unreadAlerts++;
				array_push($this->alertsList, $data['id']);
			}

			$stmt = $db->prepare("SELECT is_accepted, id FROM character_applications WHERE account_id=:id");
			$stmt->bindParam("id", $id, PDO::PARAM_INT);
			$stmt->execute();
			while($data = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				if(is_numeric($data['is_accepted']) == false)
				{
					$this->applicationsWaiting++;
					array_push($this->applicationsList, $data['id']);
				}

				$this->applicationsCount++;
			}
        }
        catch (PDOException $e)
        {
            echo '{"errore":{"messaggio":' . $e->getMessage() . '}}';
        }
    }

	function getOfficer() {
		return $this->isOfficer;
	}

	function getApplicationsList() {
        return $this->applicationsList;
    }

	function getApplicationsCount() {
        return $this->applicationsCount;
    }

	function getApplicationsWaiting() {
        return $this->applicationsWaiting;
    }

	function getCharactersList() {
        return $this->charactersList;
    }

	function getDeletedList() {
        return $this->deletedList;
    }

	function getAlertsList() {
        return $this->alertsList;
    }

    function getID() {
        return $this->accountId;
    }

    function getUsername() {
        return $this->username;
    }

    function getPassword() {
        return $this->password;
    }

    function getAdmin() {
        return $this->staffLevel;
    }

    function getBanned() {
        return $this->banned;
    }

    function getBanTime() {
        return $this->banTime;
    }

	function getDeletedCount() {
        return $this->deletedCount;
    }

	function getCharactersCount() {
        return $this->charactersCount;
    }

    function getIp() {
        return ($this->ip == -1) ? "Yok" : $this->ip;
    }

    function getEmail() {
        return $this->email;
    }

    function getPremium() {
        return $this->premium;
    }

    function getPremiumExpires() {
        return $this->premiumExpires;
    }

    function getCoynPoints() {
        return $this->coynPoints;
    }

    function getCharactersSlot() {
        return $this->charactersSlot;
    }

    function getUnreadAlerts() {
        return $this->unreadAlerts;
    }

    function getLastLogin() {
        return ($this->lastLogin < 1) ? "Mai" : $this->lastLogin;
    }

    function getRole() {
        return $this->role;
    }

    function getMaxSlots() {
        return $this->maxSlots;
    }

    function getStaffLevel() {
        return $this->staffLevel;
    }

	function playerAlert($object, $text)
	{
        try
		{
            $db = getDB();
			$stmt = $db->prepare("INSERT INTO ucp_alerts (created_at, account, object, text) VALUES (NOW(), :id, :object, :text)");
			$stmt->bindParam("id", $this->accountId, PDO::PARAM_INT);
			$stmt->bindParam("object", $object, PDO::PARAM_STR);
			$stmt->bindParam("text", $text, PDO::PARAM_STR);
			$stmt->execute();
            $db = null;
            return true;
        }
		catch (PDOException $e)
		{
            echo '{"errore":{"messaggio":' . $e->getMessage() . '}}';
        }
    }

	function playerLog($action, $receiver, $parameter)
	{
        try
		{
            $db = getDB();
            $stmt = $db->prepare("INSERT INTO ucp_logs (account_id, action, receiver_id, parameter, created_at, ip) VALUES (:id, :action, :receiver, :parameter, NOW(), :ip)");
            $stmt->bindParam("id", $this->accountId, PDO::PARAM_INT);
            $stmt->bindParam("action", $action, PDO::PARAM_STR);
            $stmt->bindParam("receiver", $receiver, PDO::PARAM_INT);
            $stmt->bindParam("parameter", $parameter, PDO::PARAM_STR);
            $stmt->bindParam("ip", $this->ip, PDO::PARAM_STR);
            $stmt->execute();
            $db = null;
            return true;
        }
		catch (PDOException $e)
		{
            echo '{"errore":{"messaggio":' . $e->getMessage() . '}}';
        }
    }
}

?>
