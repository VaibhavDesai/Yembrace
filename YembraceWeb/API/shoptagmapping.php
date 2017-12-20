<?php 
$app->get('/shoptagmapping/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM shoptagmapping
            WHERE ShopTagMappingID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $shoptagmapping = $sth->fetchAll(PDO::FETCH_OBJ);
		if($shoptagmapping) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $shoptagmapping));
   
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

$app->get('/shoptagmapping/get/', function () { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		
		$Query="SELECT o.*,ot.ShopName,t.TagName FROM shoptagmapping o, company_shop ot, tagstable t WHERE ot.ShopID=o.ShopID AND t.TagsID=o.TagID"; 
        
		$sth = $db->prepare($Query);
		$sth->execute();
        $shoptagmapping = $sth->fetchAll(PDO::FETCH_OBJ);
		
        if($shoptagmapping) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $shoptagmapping));
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

$app->post('/shoptagmapping/add/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		
		$TagID = $allPostVars['TagID'];
		$ShopID = $allPostVars['ShopID'];
		
		$qry="SELECT * FROM shoptagmapping WHERE TagID='".$TagID."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $shoptagmapping = $sth->fetchAll(PDO::FETCH_OBJ);	
		
		if($shoptagmapping){
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"address of user already exits"));
		}else{		
		$sth = $db->prepare("INSERT INTO shoptagmapping (TagID,ShopID) VALUES (:TagID,:ShopID)");
		
		$sth->bindParam(':TagID', $TagID);
		$sth->bindParam(':ShopID', $ShopID);
		$sth->execute();
		
		$lastInsertedID = $db->lastInsertID();
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","ShopTagMappingID"=> $lastInsertedID));
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

$app->post('/shoptagmapping/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$ShopTagMappingID = $allPostVars['ShopTagMappingID'];
		$TagID = $allPostVars['TagID'];
		$ShopID = $allPostVars['ShopID'];
		
		$db = getDB();
		$sth = $db->prepare("UPDATE shoptagmapping SET TagID=:TagID,ShopID=:ShopID WHERE ShopTagMappingID = :ShopTagMappingID");

		$sth->bindParam(':TagID', $TagID);
		$sth->bindParam(':ShopID', $ShopID);
		$sth->bindParam(':ShopTagMappingID', $ShopTagMappingID);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","ShopTagMappingID"=> $ShopTagMappingID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/shoptagmapping/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$ShopTagMappingID=$allPostVars['ShopTagMappingID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From shoptagmapping 
            WHERE ShopTagMappingID = :ShopTagMappingID");
 
        $sth->bindParam(':ShopTagMappingID', $ShopTagMappingID);
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