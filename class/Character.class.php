<?php

/*
    @author Frex
*/

include_once 'assets/dbconfig.php';

class Character
{
    private $name;
	private $skin;
	private $cash;
	private $cash_bank;
	private $faction;
	private $faction_rank;
	private $level;
	private $jail;
	private $banned;
	private $last_login;
	private $account_id;
	private $id;
	private $background;
	private $spawn_point;
	private $sex;
	private $job;
	private $job_pay;
	private $exp;
	private $hours;
	private $upgrades;
	private $drive_license;
	private $deleted;
	private $inventory;
	private $weapons;
	private $fly_license;
	private $boat_license;
	private $house;
	private $drive_warns;
	private $savings;
	private $deleted_at;

    function __construct($id)
    {
        try
        {
			$db = getDB();
            $stmt = $db->prepare("SELECT * FROM characters WHERE id=:id");
            $stmt->bindParam("id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

			$this->id = $id;
            $this->name = $data['char_name'];
			$this->skin = (int)$data['skin'];
			$this->cash = (int)$data['cash'];
			$this->account_id = (int)$data['account_id'];
			$this->cash_bank = (int)$data['cash_bank'];
			$this->faction = (int)$data['faction_id'];
			$this->faction_rank = (int)$data['faction_rank_id'];
			$this->level = (int)$data['level'];
			$this->jail = (int)$data['jail_time'];
			$this->banned = (int)$data['banned'];
			$this->last_login = $data['last_login'];
			$this->background = $data['description'];
			$this->spawn_point = $data['spawn_point'];
			$this->sex = $data['sex'];
			$this->job = $data['job'];
			$this->job_pay = $data['job_pay'];
			$this->exp = $data['experience'];
			$this->hours = $data['playing_hours'];
			$this->upgrades = $data['upgrade_points'];
			$this->drive_license = $data['drive_license'];
			$this->inventory = $data['string_inventory'];
			$this->weapons = $data['string_weapons'];
			$this->fly_license = $data['fly_license'];
			$this->boat_license = $data['boat_license'];
			$this->house = $data['house'];
			$this->drive_warns = $data['drive_license_warn'];
			$this->savings = $data['savings'];
			$this->deleted_at = $data['deleted_at'];
			$this->deleted = $data['deleted'];
        }
        catch (PDOException $e)
        {
            echo '{"errore":{"messaggio":' . $e->getMessage() . '}}';
        }
    }

	function getDeleted() {
		return $this->deleted;
	}

	function getSavings() {
		return $this->savings;
	}

	function getFlyLicense() {
		return $this->fly_license;
	}

	function getDeleteDate() {
		return $this->deleted_at;
	}

	function getBoatLicense() {
		return $this->boat_license;
	}

	function getDriveWarns() {
		return $this->drive_warns;
	}

	function getHouse() {
		return $this->house;
	}

	function getWeapons() {
		return $this->weapons;
	}

	function getInventory() {
		return $this->inventory;
	}

	function getDriveLicense() {
		return $this->drive_license;
	}

	function getUpgrades() {
		return $this->upgrades;
	}

	function getHours() {
		return $this->hours;
	}

	function getExp() {
		return $this->exp;
	}

	function getJobPay() {
		return $this->job_pay;
	}

	function getJob() {
		return $this->job;
	}

	function getSex() {
		return $this->sex;
	}

	function getSpawnPoint() {
		return $this->spawn_point;
	}

	function getBackground() {
		return $this->background;
	}

	function getID() {
		return $this->id;
	}

	function getAccountID() {
		return $this->account_id;
	}

	function getAccountName() {

		$user = new User($this->account_id);

        return $user->getUsername();
    }

	function getCharacterName() {
        return $this->name;
    }

	function getLastLogin()
	{
		$str = $this->last_login;
        return (!$str) ? "Yok" : $str;
    }

	function getSkin() {
        return $this->skin;
    }

	function getCash() {
        return $this->cash;
    }

	function getCashBank() {
        return $this->cash_bank;
    }

	function getFaction() {
        return $this->faction;
    }

	function getFactionRank() {
        return $this->faction_rank;
    }

	function getLevel() {
        return $this->level;
    }

	function getJail() {
        return $this->jail;
    }

	function getBanned() {
        return $this->banned;
    }
}

?>
