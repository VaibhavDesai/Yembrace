<?php 
$app->get('/beacons_master/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM beaconmaster
            WHERE BeconPrimaryKeyID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $beacons_master = $sth->fetchAll(PDO::FETCH_OBJ);
		if($beacons_master) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $beacons_master));
   
        } else {
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "No record found"));
        }

    } catch(PDOException $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
 
});

$app->get('/beacons_master/get/', function () { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT * FROM beaconmaster";
		
        $sth = $db->prepare($Query);
		$sth->execute();
        $beacons_master = $sth->fetchAll(PDO::FETCH_OBJ);
        if($beacons_master) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $beacons_master));
        } else {
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "No record found"));
        }
    } catch(PDOException $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->get('/beacons_master/get/count/', function () { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT * FROM beaconmaster";

        $sth = $db->prepare($Query);
		$sth->execute();
		$beacons_master=$sth->rowCount();
       // $beacons_master = $sth->fetchAll(PDO::FETCH_OBJ);
        if($beacons_master) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $beacons_master));
        } else {
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "No record found"));
        }
    } catch(PDOException $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/beacons_master/add/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		
		$BeaconName = $allPostVars['BeaconName'];
		$BeaconMacID = $allPostVars['BeaconMacID'];
		$BeaconProximity = $allPostVars['BeaconProximity'];
		$BeaconDescription = $allPostVars['BeaconDescription'];
		$BeaconIsActive = $allPostVars['BeaconIsActive'];
			
		$qry="SELECT * FROM beaconmaster WHERE BeaconName='".$BeaconName."' AND BeaconMacID='".$BeaconMacID."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $beacons_master = $sth->fetchAll(PDO::FETCH_OBJ);
		
		if($beacons_master){
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"beacon name already exits"));
		}else{		
				$sth = $db->prepare("INSERT INTO beaconmaster (BeaconName,BeaconMacID,BeaconProximity,BeaconDescription,BeaconIsActive) VALUES (:BeaconName,:BeaconMacID,:BeaconProximity,:BeaconDescription,:BeaconIsActive)");
		
		$sth->bindParam(':BeaconName', $BeaconName);
		$sth->bindParam(':BeaconMacID', $BeaconMacID);
		$sth->bindParam(':BeaconProximity', $BeaconProximity);
		$sth->bindParam(':BeaconDescription', $BeaconDescription);
		$sth->bindParam(':BeaconIsActive', $BeaconIsActive);
		$sth->execute();
		
		$lastInsertedID = $db->lastInsertID();
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","ID"=> $lastInsertedID));
		}
    } catch(Exception $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
 
});

$app->post('/beacons_master/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$BeconPrimaryKeyID = $allPostVars['BeconPrimaryKeyID'];
		$BeaconName = $allPostVars['BeaconName'];
		$BeaconMacID = $allPostVars['BeaconMacID'];
		$BeaconProximity = $allPostVars['BeaconProximity'];
		$BeaconIsActive = $allPostVars['BeaconIsActive'];
		$BeaconDescription = $allPostVars['BeaconDescription'];
		
		$db = getDB();
		$sth = $db->prepare("UPDATE beaconmaster SET BeaconName=:BeaconName,BeaconMacID=:BeaconMacID,BeaconProximity=:BeaconProximity,BeaconIsActive=:BeaconIsActive,BeaconDescription=:BeaconDescription WHERE BeconPrimaryKeyID = :BeconPrimaryKeyID");

		$sth->bindParam(':BeconPrimaryKeyID', $BeconPrimaryKeyID);
		$sth->bindParam(':BeaconName', $BeaconName);
		$sth->bindParam(':BeaconMacID', $BeaconMacID);
		$sth->bindParam(':BeaconProximity', $BeaconProximity);
		$sth->bindParam(':BeaconDescription', $BeaconDescription);
		$sth->bindParam(':BeaconIsActive', $BeaconIsActive);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","BeconPrimaryKeyID"=> $BeconPrimaryKeyID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/beacons_master/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$BeconPrimaryKeyID=$allPostVars['BeconPrimaryKeyID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From beaconmaster 
            WHERE BeconPrimaryKeyID = :BeconPrimaryKeyID");
 
        $sth->bindParam(':BeconPrimaryKeyID', $BeconPrimaryKeyID);
        $sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully"));
		
    } catch(PDOException $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		$db = null;
	}
 
});

?>