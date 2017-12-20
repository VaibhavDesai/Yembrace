<?php 
$app->get('/forms/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM formstable
            WHERE FormID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $formstable = $sth->fetchAll(PDO::FETCH_OBJ);
		if($formstable) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $formstable));
   
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
$app->get('/forms/get/byshop/:shopID(/)(/:pageno(/:pagelimit))', function ($shopID,$pageno=0,$pagelimit=20) {
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT * FROM formstable where FormShopID=".$shopID." Order by FormCreatedOn DESC";
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
        $sth = $db->prepare($Query);
		$sth->execute();
        $formstable = $sth->fetchAll(PDO::FETCH_OBJ);
		
        if($formstable) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $formstable));
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

$app->get('/forms/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT * FROM formstable order by FormCreatedOn DESC";
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
        $sth = $db->prepare($Query);
		$sth->execute();
        $formstable = $sth->fetchAll(PDO::FETCH_OBJ);
		
        if($formstable) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $formstable));
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

$app->post('/forms/add(/)', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		
		$FormCompanyID = $allPostVars['FormCompanyID'];
		$FormName = $allPostVars['FormName'];
		$FormDescription = $allPostVars['FormDescription'];
		$FormCreatedBy = $allPostVars['FormCreatedBy'];
		$FormShopID = $allPostVars['FormShopID'];

		
		$qry="SELECT * FROM formstable WHERE FormName ='".$FormName."' and FormCompanyID='".$FormCompanyID."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $formstable = $sth->fetchAll(PDO::FETCH_OBJ);	
		
		if($formstable){
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"form name already exits"));
		}else{		
		$sth = $db->prepare("INSERT INTO formstable (FormCompanyID,FormName,FormDescription,FormCreatedBy,FormShopID) VALUES (:FormCompanyID,:FormName,:FormDescription,:FormCreatedBy,:FormShopID)");
		
		$sth->bindParam(':FormCompanyID', $FormCompanyID);
		$sth->bindParam(':FormName', $FormName);
		$sth->bindParam(':FormDescription', $FormDescription);
		$sth->bindParam(':FormCreatedBy', $FormCreatedBy);
		$sth->bindParam(':FormShopID', $FormShopID);
		$sth->execute();
		
		$lastInsertedID = $db->lastInsertID();
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","FormID"=> $lastInsertedID));
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

$app->post('/forms/update(/)', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$FormID = $allPostVars['FormID'];
		$FormCompanyID = $allPostVars['FormCompanyID'];
		$FormName = $allPostVars['FormName'];
		$FormDescription = $allPostVars['FormDescription'];
		$FormCreatedBy = $allPostVars['FormCreatedBy'];
		
		$db = getDB();
		$sth = $db->prepare("UPDATE formstable SET FormCompanyID=:FormCompanyID,FormName=:FormName,FormDescription=:FormDescription,FormCreatedBy=:FormCreatedBy WHERE FormID = :FormID");

		$sth->bindParam(':FormCompanyID', $FormCompanyID);
		$sth->bindParam(':FormName', $FormName);
		$sth->bindParam(':FormDescription', $FormDescription);
		$sth->bindParam(':FormCreatedBy', $FormCreatedBy);
		$sth->bindParam(':FormID', $FormID);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","FormID"=> $FormID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/forms/delete(/)', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$FormID=$allPostVars['FormID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From formstable 
            WHERE FormID = :FormID");
 
        $sth->bindParam(':FormID', $FormID);
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