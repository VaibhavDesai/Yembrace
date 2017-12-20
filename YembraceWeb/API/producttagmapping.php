<?php 
$app->get('/producttagmapping/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM producttagmapping
            WHERE ProductTagMappingID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $producttagmapping = $sth->fetchAll(PDO::FETCH_OBJ);
		if($producttagmapping) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $producttagmapping));
   
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

$app->get('/producttagmapping/get/', function () { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		
		$Query="SELECT o.*,p.ProductTitle,t.TagName FROM producttagmapping o, products p, tagstable t WHERE p.ProductID=o.ProductID AND t.TagsID=o.TagID";
		
        $sth = $db->prepare($Query);
		$sth->execute();
        $producttagmapping = $sth->fetchAll(PDO::FETCH_OBJ);
		
        if($producttagmapping) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $producttagmapping));
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

$app->post('/producttagmapping/add/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		
		$TagID = $allPostVars['TagID'];
		$ProductID = $allPostVars['ProductID'];
		
		$qry="SELECT * FROM producttagmapping WHERE TagID='".$TagID."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $producttagmapping = $sth->fetchAll(PDO::FETCH_OBJ);	
		
		if($producttagmapping){
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"address of user already exits"));
		}else{		
		$sth = $db->prepare("INSERT INTO producttagmapping (TagID,ProductID) VALUES (:TagID,:ProductID)");
		
		$sth->bindParam(':TagID', $TagID);
		$sth->bindParam(':ProductID', $ProductID);
		$sth->execute();
		
		$lastInsertedID = $db->lastInsertID();
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","ProductTagMappingID"=> $lastInsertedID));
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

$app->post('/producttagmapping/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$ProductTagMappingID = $allPostVars['ProductTagMappingID'];
		$TagID = $allPostVars['TagID'];
		$ProductID = $allPostVars['ProductID'];
		
		$db = getDB();
		$sth = $db->prepare("UPDATE producttagmapping SET TagID=:TagID,ProductID=:ProductID WHERE ProductTagMappingID = :ProductTagMappingID");

		$sth->bindParam(':TagID', $TagID);
		$sth->bindParam(':ProductID', $ProductID);
		$sth->bindParam(':ProductTagMappingID', $ProductTagMappingID);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","ProductTagMappingID"=> $ProductTagMappingID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/producttagmapping/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$ProductTagMappingID=$allPostVars['ProductTagMappingID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From producttagmapping 
            WHERE ProductTagMappingID = :ProductTagMappingID");
 
        $sth->bindParam(':ProductTagMappingID', $ProductTagMappingID);
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