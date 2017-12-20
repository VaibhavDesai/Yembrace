<?php 
$app->get('/contenttype/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM contenttypetable
            WHERE ContentTypeID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $ContentType = $sth->fetchAll(PDO::FETCH_OBJ);
		if($ContentType) { 
			$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $ContentType));
   
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



$app->get('/contenttype/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    try 
    {
		$app = \Slim\Slim::getInstance();
		
		$Query="SELECT * from contenttypetable where ContentTypeIsActive=1 order by ContentTypeName ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		 }
		$db = getDB();
 
        $sth = $db->prepare($Query);
		$sth->execute();
        $trigger = $sth->fetchAll(PDO::FETCH_OBJ);

        if($trigger) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $trigger));
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

$app->post('/contenttype/add/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		$ContentTypeName = $allPostVars['ContentTypeName'];
		$ContentTypeIsActive = $allPostVars['ContentTypeIsActive'];
		
		$qry="SELECT * FROM contenttypetable WHERE ContentTypeName='".$ContentTypeName."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $ContentType = $sth->fetchAll(PDO::FETCH_OBJ);

		if($ContentType){
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"content type name already exits"));
		}else{		
		$sth = $db->prepare("INSERT INTO contenttypetable (ContentTypeName,ContentTypeIsActive) VALUES (:ContentTypeName,:ContentTypeIsActive)");
		$sth->bindParam(':ContentTypeName', $ContentTypeName);
		$sth->bindParam(':ContentTypeIsActive', $ContentTypeIsActive);
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

$app->post('/contenttype/update(/)', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$ContentTypeID = $allPostVars['ContentTypeID'];
	    $ContentTypeName = $allPostVars['ContentTypeName'];
		$ContentTypeIsActive = $allPostVars['ContentTypeIsActive'];
			
        $db = getDB();
        $sth = $db->prepare("UPDATE contenttypetable SET ContentTypeIsActive=:ContentTypeIsActive, ContentTypeName=:ContentTypeName WHERE ContentTypeID = :ContentTypeID");
			
		$sth->bindParam(':ContentTypeName', $ContentTypeName);
		$sth->bindParam(':ContentTypeIsActive', $ContentTypeIsActive);
		$sth->bindParam(':ContentTypeID', $ContentTypeID);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $ContentTypeID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/contenttype/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$ContentTypeID=$allPostVars['ContentTypeID'];
		$db = getDB();
        $sth = $db->prepare("Delete From contenttypetable 
            WHERE ContentTypeID = :ContentTypeID");
 
        $sth->bindParam(':ContentTypeID', $ContentTypeID);
        $sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully"));
		
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