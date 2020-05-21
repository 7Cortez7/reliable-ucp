<?php

/*
    @author Frex
*/

include_once 'assets/dbconfig.php';

class Question 
{
	public static function getQuestionsList() 
	{
		try 
		{
			$array = array();
            $db = getDB();
            $stmt = $db->prepare("SELECT id FROM questions WHERE type = 'textarea'");
            $stmt->execute();
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) 
			{
                array_push($array, $data['id']);
            }
			return $array;
		}
		catch (PDOException $e) 
		{
            echo '{"errore":{"messaggio":' . $e->getMessage() . '}}';
        }
	}

    public static function getQuestion($question_id) 
	{
        try 
		{
            $db = getDB();
            $stmt = $db->prepare("SELECT question FROM questions WHERE id=:question_id");
            $stmt->bindParam("question_id", $question_id, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
			return ($count)  ? $data['question'] : "error";
        } 
		catch (PDOException $e) 
		{
            echo '{"errore":{"messaggio":' . $e->getMessage() . '}}';
        }
    }
	
	public static function getQuestionAnswer($application_id, $question_id)
	{
        try 
		{
            $db = getDB();
            $stmt = $db->prepare("SELECT answer FROM character_application_answers WHERE question_id=:question_id AND application_id=:application_id");
            $stmt->bindParam("question_id", $question_id, PDO::PARAM_STR);
            $stmt->bindParam("application_id", $application_id, PDO::PARAM_STR);
			$stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
			return $data['answer'];
        } 
		catch (PDOException $e) 
		{
            echo '{"errore":{"messaggio":' . $e->getMessage() . '}}';
        }		
	}
}

?>