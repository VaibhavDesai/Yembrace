<?php 
$app->get('/company/beacons/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
         $sth = $db->prepare("SELECT b.*,bm.BeaconName,c.CompanyName,cs.ShopName FROM beaconscompanymap b, beaconmaster bm, company c, company_shop cs WHERE bm.BeaconPrimaryKeyID=b.BeaconPrimaryKeyID AND c.CompanyID=b.CompanyID AND cs.ShopID=b.ShopID and b.CompanyID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $beacons_company = $sth->fetchAll(PDO::FETCH_OBJ);
 
		if($beacons_company) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $beacons_company));
   
        } else {
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 0,"message"=> "No record found."));
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
$app->get('/company/beacons/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM beaconscompanymap
            WHERE BeaconCompanyMapID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $beacons_company = $sth->fetchAll(PDO::FETCH_OBJ);
 
		if($beacons_company) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $beacons_company));
   
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

$app->get('/company/beacons/get(/)', function () { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT b.*,bm.BeaconName,c.CompanyName,cs.ShopName FROM beaconscompanymap b, beaconmaster bm, company c, company_shop cs WHERE bm.BeaconPrimaryKeyID=b.BeaconPrimaryKeyID AND c.CompanyID=b.CompanyID AND cs.ShopID=b.ShopID";
  
        $sth = $db->prepare($Query);
		$sth->execute();
        $beacons_company = $sth->fetchAll(PDO::FETCH_OBJ);
  
        if($beacons_company) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $beacons_company));
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

$app->post('/company/beacons/add(/)', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		
		$BeaconPrimaryKeyID = $allPostVars['BeaconPrimaryKeyID'];
		$CompanyID = $allPostVars['CompanyID'];
		$ShopID = $allPostVars['ShopID'];
		$BeaconIsActive = $allPostVars['BeaconIsActive'];
		$BeaconTitle = $allPostVars['BeaconTitle'];
		$Description = $allPostVars['Description'];
		
		$qry="SELECT * FROM beaconscompanymap WHERE BeaconPrimaryKeyID='".$BeaconPrimaryKeyID."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $beacons_company = $sth->fetchAll(PDO::FETCH_OBJ);
		
		if($beacons_company){
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"beacons_id already exits"));
		}else{		
		$sth = $db->prepare("INSERT INTO beaconscompanymap (BeaconPrimaryKeyID,CompanyID,ShopID,BeaconIsActive,BeaconTitle,Description) VALUES (:BeaconPrimaryKeyID,:CompanyID,:ShopID,:BeaconIsActive,:BeaconTitle,:Description)");
		
		$sth->bindParam(':BeaconPrimaryKeyID', $BeaconPrimaryKeyID);
		$sth->bindParam(':CompanyID', $CompanyID);
		$sth->bindParam(':ShopID', $ShopID);
		$sth->bindParam(':BeaconIsActive', $BeaconIsActive);
		$sth->bindParam(':BeaconTitle', $BeaconTitle);
		$sth->bindParam(':Description', $Description);
		$sth->execute();
		
		$lastInsertedID = $db->lastInsertID();
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","BeaconCompanyMapID"=> $lastInsertedID));
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

$app->post('/company/beacons/update(/)', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$BeaconCompanyMapID = $allPostVars['BeaconCompanyMapID'];
		$BeaconPrimaryKeyID = $allPostVars['BeaconPrimaryKeyID'];
		$CompanyID = $allPostVars['CompanyID'];
		$ShopID = $allPostVars['ShopID'];
		$BeaconIsActive = $allPostVars['BeaconIsActive'];
		$BeaconTitle = $allPostVars['BeaconTitle'];
		$Description = $allPostVars['Description'];
		
		$db = getDB();
		$sth = $db->prepare("UPDATE beaconscompanymap SET BeaconPrimaryKeyID=:BeaconPrimaryKeyID,CompanyID=:CompanyID,ShopID=:ShopID,BeaconIsActive=:BeaconIsActive,BeaconTitle=:BeaconTitle,Description=:Description WHERE BeaconCompanyMapID = :BeaconCompanyMapID");

		$sth->bindParam(':BeaconPrimaryKeyID', $BeaconPrimaryKeyID);
		$sth->bindParam(':CompanyID', $CompanyID);
		$sth->bindParam(':ShopID', $ShopID);
		$sth->bindParam(':BeaconIsActive', $BeaconIsActive);
		$sth->bindParam(':BeaconTitle', $BeaconTitle);
		$sth->bindParam(':Description', $Description);
		$sth->bindParam(':BeaconCompanyMapID', $BeaconCompanyMapID);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","BeaconCompanyMapID"=> $BeaconCompanyMapID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/company/beacons/delete(/)', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$BeaconCompanyMapID=$allPostVars['BeaconCompanyMapID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From beaconscompanymap 
            WHERE BeaconCompanyMapID = :BeaconCompanyMapID");
 
        $sth->bindParam(':BeaconCompanyMapID', $BeaconCompanyMapID);
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