<?php 
$app->get('/offertagmapping/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM offertagmapping
            WHERE OfferTagMappingID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $offertagmapping = $sth->fetchAll(PDO::FETCH_OBJ);
		if($offertagmapping) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $offertagmapping));
   
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

$app->get('/offertagmapping/get/', function () { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		
		$Query="SELECT o.*,ot.OfferTitle,t.TagName FROM offertagmapping o, offerstable ot, tagstable t WHERE ot.OfferID=o.OfferID AND t.TagsID=o.TagID";
				
        $sth = $db->prepare($Query);
		$sth->execute();
        $offertagmapping = $sth->fetchAll(PDO::FETCH_OBJ);
		
        if($offertagmapping) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $offertagmapping));
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

$app->post('/offertagmapping/add/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		
		$TagID = $allPostVars['TagID'];
		$OfferID = $allPostVars['OfferID'];
		
		$qry="SELECT * FROM offertagmapping WHERE TagID='".$TagID."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $offertagmapping = $sth->fetchAll(PDO::FETCH_OBJ);	
		
		if($offertagmapping){
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"address of user already exits"));
		}else{		
		$sth = $db->prepare("INSERT INTO offertagmapping (TagID,OfferID) VALUES (:TagID,:OfferID)");
		
		$sth->bindParam(':TagID', $TagID);
		$sth->bindParam(':OfferID', $OfferID);
		$sth->execute();
		
		$lastInsertedID = $db->lastInsertID();
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","OfferTagMappingID"=> $lastInsertedID));
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

$app->post('/offertagmapping/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$OfferTagMappingID = $allPostVars['OfferTagMappingID'];
		$TagID = $allPostVars['TagID'];
		$OfferID = $allPostVars['OfferID'];
		
		$db = getDB();
		$sth = $db->prepare("UPDATE offertagmapping SET TagID=:TagID,OfferID=:OfferID WHERE OfferTagMappingID = :OfferTagMappingID");

		$sth->bindParam(':TagID', $TagID);
		$sth->bindParam(':OfferID', $OfferID);
		$sth->bindParam(':OfferTagMappingID', $OfferTagMappingID);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","OfferTagMappingID"=> $OfferTagMappingID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/offertagmapping/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$OfferTagMappingID=$allPostVars['OfferTagMappingID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From offertagmapping 
            WHERE OfferTagMappingID = :OfferTagMappingID");
 
        $sth->bindParam(':OfferTagMappingID', $OfferTagMappingID);
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