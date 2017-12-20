<?php 
$app->get('/rule/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM ruletable
            WHERE RuleID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $rule = $sth->fetchAll(PDO::FETCH_OBJ);
		if($rule) { 
			$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $rule));
   
        } else {
			$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "No record found"));
        }

    } catch(PDOException $e) {
		$app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
 
});



$app->get('/rule/get/:shopID(/:pageno(/:pagelimit))', function ($shopID,$pageno=0,$pagelimit=20) {
 
    try 
    {
		$app = \Slim\Slim::getInstance();
		
		$Query="SELECT * from ruletable Where RuleShopID=".$shopID." order by RuleID ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		 }
		$db = getDB();
 
        $sth = $db->prepare($Query);
		$sth->execute();
        $rule = $sth->fetchAll(PDO::FETCH_OBJ);

        if($rule) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $rule));
        } else {
			$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 0,"message"=> "No record found"));
        }
    } catch(PDOException $e) {
		$app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/rule/add/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		
		//$RuleID= $allPostVars['RuleID'];
		$RuleName= $allPostVars['RuleName'];
		$RuleShopID= $allPostVars['RuleShopID'];
		$RuleCompanyID= $allPostVars['RuleCompanyID'];
		//$TriggerTypeID= $allPostVars['TriggerTypeID'];
		$RuleTillDateTime= $allPostVars['RuleTillDateTime'];
		$RuleTillDateTime= date("Y-m-d H:i:s", strtotime(str_replace('/','-', $RuleTillDateTime)));
		$RuleFromDateTime= $allPostVars['RuleFromDateTime'];
		$RuleFromDateTime= date("Y-m-d H:i:s", strtotime(str_replace('/','-', $RuleFromDateTime)));
		$IsSunday= $allPostVars['IsSunday'];
		$IsMonday= $allPostVars['IsMonday'];
		$IsTuesday= $allPostVars['IsTuesday'];
		$IsWednesday= $allPostVars['IsWednesday'];
		$IsThursday= $allPostVars['IsThursday'];
		$IsFriday= $allPostVars['IsFriday'];
		$IsSaturday= $allPostVars['IsSaturday'];
		$RuleIsActive= $allPostVars['RuleIsActive'];
		$RuleDescription= $allPostVars['RuleDescription'];
		
		$qry="SELECT * FROM ruletable WHERE RuleName='".$RuleName."' and RuleCompanyID='".$RuleCompanyID."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $trigger = $sth->fetchAll(PDO::FETCH_OBJ);

		if($trigger){
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"rule name already exits"));
		}else{
		$sth = $db->prepare("INSERT INTO ruletable(RuleCompanyID ,RuleShopID,RuleName ,RuleFromDateTime ,RuleTillDateTime ,IsSunday ,IsMonday ,IsTuesday ,IsWednesday ,IsThursday ,IsFriday ,IsSaturday ,RuleIsActive ,RuleDescription ) VALUES (:RuleCompanyID,:RuleShopID,:RuleName,:RuleFromDateTime,:RuleTillDateTime,:IsSunday,:IsMonday,:IsTuesday,:IsWednesday,:IsThursday,:IsFriday,:IsSaturday,:RuleIsActive,:RuleDescription)");
		$sth->bindParam(':RuleCompanyID',$RuleCompanyID);
		$sth->bindParam(':RuleShopID',$RuleShopID);
		$sth->bindParam(':RuleName',$RuleName);
		
		$sth->bindParam(':RuleFromDateTime',$RuleFromDateTime);
		$sth->bindParam(':RuleTillDateTime',$RuleTillDateTime);
		$sth->bindParam(':IsSunday',$IsSunday);
		$sth->bindParam(':IsMonday',$IsMonday);
		$sth->bindParam(':IsTuesday',$IsTuesday);
		$sth->bindParam(':IsWednesday',$IsWednesday);
		$sth->bindParam(':IsThursday',$IsThursday);
		$sth->bindParam(':IsFriday',$IsFriday);
		$sth->bindParam(':IsSaturday',$IsSaturday);
		$sth->bindParam(':RuleIsActive',$RuleIsActive);
		$sth->bindParam(':RuleDescription',$RuleDescription);

		$sth->execute();
		$lastInsertedID = $db->lastInsertID();
		
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","id"=> $lastInsertedID));
		}
    } catch(Exception $e) {
		$app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
 
});

$app->post('/rule/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$RuleShopID= $allPostVars['RuleShopID'];
		$RuleID= $allPostVars['RuleID'];
		$RuleCompanyID= $allPostVars['RuleCompanyID'];
		$RuleName= $allPostVars['RuleName'];
		
		$RuleFromDateTime= $allPostVars['RuleFromDateTime'];
		$RuleFromDateTime= date("Y-m-d H:i:s", strtotime(str_replace('/','-', $RuleFromDateTime)));
		$RuleTillDateTime= $allPostVars['RuleTillDateTime'];
		$RuleTillDateTime= date("Y-m-d H:i:s", strtotime(str_replace('/','-', $RuleTillDateTime)));
		$IsSunday= $allPostVars['IsSunday'];
		$IsMonday= $allPostVars['IsMonday'];
		$IsTuesday= $allPostVars['IsTuesday'];
		$IsWednesday= $allPostVars['IsWednesday'];
		$IsThursday= $allPostVars['IsThursday'];
		$IsFriday= $allPostVars['IsFriday'];
		$IsSaturday= $allPostVars['IsSaturday'];
		$RuleIsActive= $allPostVars['RuleIsActive'];
		
		$RuleDescription= $allPostVars['RuleDescription'];
			
        $db = getDB();
        $sth = $db->prepare("UPDATE ruletable SET RuleCompanyID=:RuleCompanyID ,RuleShopID=:RuleShopID,RuleName=:RuleName ,RuleFromDateTime=:RuleFromDateTime ,RuleTillDateTime=:RuleTillDateTime ,IsSunday=:IsSunday ,IsMonday=:IsMonday ,IsTuesday=:IsTuesday ,IsWednesday=:IsWednesday ,IsThursday=:IsThursday ,IsFriday=:IsFriday,IsSaturday=:IsSaturday ,RuleIsActive=:RuleIsActive ,RuleDescription=:RuleDescription Where RuleID=:RuleID");
		
		$sth->bindParam(':RuleID',$RuleID);
		$sth->bindParam(':RuleCompanyID',$RuleCompanyID);
		$sth->bindParam(':RuleShopID',$RuleShopID);
		$sth->bindParam(':RuleName',$RuleName);
		$sth->bindParam(':RuleFromDateTime',$RuleFromDateTime);
		$sth->bindParam(':RuleTillDateTime',$RuleTillDateTime);
		$sth->bindParam(':IsSunday',$IsSunday);
		$sth->bindParam(':IsMonday',$IsMonday);
		$sth->bindParam(':IsTuesday',$IsTuesday);
		$sth->bindParam(':IsWednesday',$IsWednesday);
		$sth->bindParam(':IsThursday',$IsThursday);
		$sth->bindParam(':IsFriday',$IsFriday);
		$sth->bindParam(':IsSaturday',$IsSaturday);
		$sth->bindParam(':RuleIsActive',$RuleIsActive);
		$sth->bindParam(':RuleDescription',$RuleDescription);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","RuleID"=> $RuleID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/rule/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$RuleID=$allPostVars['RuleID'];
		$RuleIsActive=$allPostVars['RuleIsActive'];
		$db = getDB();
        $sth = $db->prepare("Update ruletable SET RuleIsActive=:RuleIsActive Where RuleID=:RuleID");
 
        $sth->bindParam(':RuleIsActive', $RuleIsActive);
		$sth->bindParam(':RuleID', $RuleID);
        $sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Action successfully"));
		
    } catch(PDOException $e) {
		$app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		$db = null;
	}
 
});

?>