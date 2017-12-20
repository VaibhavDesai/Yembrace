<?php 
$app->get('/formresponse/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM formresponse
            WHERE FormResponseID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $formresponse = $sth->fetchAll(PDO::FETCH_OBJ);
		if($formresponse) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $formresponse));
   
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

$app->get('/formresponse/get/', function () { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT * FROM formresponse";
		//  
        $sth = $db->prepare($Query);
		$sth->execute();
        $formresponse = $sth->fetchAll(PDO::FETCH_OBJ);
		
        if($formresponse) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $formresponse));
        } else {
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 0,"message"=> "No record found"));
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

$app->post('/formresponse/add/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		
		$FormCompanyID = $allPostVars['FormCompanyID'];
		$FormUserID = $allPostVars['FormUserID'];
		$FormShopID = $allPostVars['FormShopID'];
		$FormResponseValue = $allPostVars['FormResponseValue'];
		
		$qry="SELECT * FROM formresponse WHERE FormCompanyID='".$FormCompanyID."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $formresponse = $sth->fetchAll(PDO::FETCH_OBJ);	
		
		if($formresponse){
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"address of user already exits"));
		}else{		
		$sth = $db->prepare("INSERT INTO formresponse (FormCompanyID,FormUserID,FormShopID,FormResponseValue) VALUES (:FormCompanyID,:FormUserID,:FormShopID,:FormResponseValue)");
		
		$sth->bindParam(':FormCompanyID', $FormCompanyID);
		$sth->bindParam(':FormUserID', $FormUserID);
		$sth->bindParam(':FormShopID', $FormShopID);
		$sth->bindParam(':FormResponseValue', $FormResponseValue);
		$sth->execute();
		
		$lastInsertedID = $db->lastInsertID();
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","FormResponseID"=> $lastInsertedID));
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

$app->post('/formresponse/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$FormResponseID = $allPostVars['FormResponseID'];
		$FormCompanyID = $allPostVars['FormCompanyID'];
		$FormUserID = $allPostVars['FormUserID'];
		$FormShopID = $allPostVars['FormShopID'];
		$FormResponseValue = $allPostVars['FormResponseValue'];
		
		$db = getDB();
		$sth = $db->prepare("UPDATE formresponse SET FormCompanyID=:FormCompanyID,FormUserID=:FormUserID,FormShopID=:FormShopID,FormResponseValue=:FormResponseValue WHERE FormResponseID = :FormResponseID");

		$sth->bindParam(':FormCompanyID', $FormCompanyID);
		$sth->bindParam(':FormUserID', $FormUserID);
		$sth->bindParam(':FormShopID', $FormShopID);
		$sth->bindParam(':FormResponseValue', $FormResponseValue);
		$sth->bindParam(':FormResponseID', $FormResponseID);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","FormResponseID"=> $FormResponseID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/formresponse/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$FormResponseID=$allPostVars['FormResponseID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From formresponse 
            WHERE FormResponseID = :FormResponseID");
 
        $sth->bindParam(':FormResponseID', $FormResponseID);
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