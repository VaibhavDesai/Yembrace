<?php 
$app->get('/tagstable/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM tagstable
            WHERE TagsID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $tagstable = $sth->fetchAll(PDO::FETCH_OBJ);
		if($tagstable) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $tagstable));
   
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

$app->get('/tagstable/get/', function () { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT * FROM tagstable";
		//  
        $sth = $db->prepare($Query);
		$sth->execute();
        $tagstable = $sth->fetchAll(PDO::FETCH_OBJ);
		
        if($tagstable) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $tagstable));
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

$app->post('/tagstable/add/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		
		$TagName = $allPostVars['TagName'];
		
		$qry="SELECT * FROM tagstable WHERE TagName='".$TagName."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $tagstable = $sth->fetchAll(PDO::FETCH_OBJ);	
		
		if($tagstable){
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"address of user already exits"));
		}else{		
		$sth = $db->prepare("INSERT INTO tagstable (TagName) VALUES (:TagName)");
		
		$sth->bindParam(':TagName', $TagName);

		$sth->execute();
		
		$lastInsertedID = $db->lastInsertID();
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","TagsID"=> $lastInsertedID));
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

$app->post('/tagstable/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$TagsID = $allPostVars['TagsID'];
		$TagName = $allPostVars['TagName'];
		
		$db = getDB();
		$sth = $db->prepare("UPDATE tagstable SET TagName=:TagName WHERE TagsID = :TagsID");

		$sth->bindParam(':TagName', $TagName);
		$sth->bindParam(':TagsID', $TagsID);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","TagsID"=> $TagsID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/tagstable/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$TagsID=$allPostVars['TagsID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From tagstable 
            WHERE TagsID = :TagsID");
 
        $sth->bindParam(':TagsID', $TagsID);
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