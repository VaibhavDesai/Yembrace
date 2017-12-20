<?php 
$app->get('/forms/field/datatype/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM formfielddatatype
            WHERE FormFieldDataTypeID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $formfielddatatype = $sth->fetchAll(PDO::FETCH_OBJ);
		if($formfielddatatype) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $formfielddatatype));
   
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

$app->get('/forms/field/datatype/get(/)', function () { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT * FROM formfielddatatype";
		//  
        $sth = $db->prepare($Query);
		$sth->execute();
        $formfielddatatype = $sth->fetchAll(PDO::FETCH_OBJ);
		
        if($formfielddatatype) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $formfielddatatype));
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

$app->post('/forms/field/datatype/add(/)', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		
		$FormFieldDataTypeName = $allPostVars['FormFieldDataTypeName'];
		$FormFieldComment = $allPostVars['FormFieldComment'];
		
		$qry="SELECT * FROM formfielddatatype WHERE FormFieldDataTypeName='".$FormFieldDataTypeName."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $formfielddatatype = $sth->fetchAll(PDO::FETCH_OBJ);	
		
		if($formfielddatatype){
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"Form Field Data already exits"));
		}else{		
		$sth = $db->prepare("INSERT INTO formfielddatatype (FormFieldDataTypeName,FormFieldComment) VALUES (:FormFieldDataTypeName,:FormFieldComment)");
		
		$sth->bindParam(':FormFieldDataTypeName', $FormFieldDataTypeName);
		$sth->bindParam(':FormFieldComment', $FormFieldComment);
		$sth->execute();
		
		$lastInsertedID = $db->lastInsertID();
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","FormFieldDataTypeID"=> $lastInsertedID));
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

$app->post('/forms/field/datatype/update(/)', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$FormFieldDataTypeID = $allPostVars['FormFieldDataTypeID'];
		$FormFieldDataTypeName = $allPostVars['FormFieldDataTypeName'];
		$FormFieldComment = $allPostVars['FormFieldComment'];
		
		$db = getDB();
		$sth = $db->prepare("UPDATE formfielddatatype SET FormFieldDataTypeName=:FormFieldDataTypeName,FormFieldComment=:FormFieldComment WHERE FormFieldDataTypeID = :FormFieldDataTypeID");

		$sth->bindParam(':FormFieldDataTypeName', $FormFieldDataTypeName);
		$sth->bindParam(':FormFieldComment', $FormFieldComment);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","FormFieldDataTypeID"=> $FormFieldDataTypeID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/forms/field/datatype/delete(/)', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$FormFieldDataTypeID=$allPostVars['FormFieldDataTypeID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From formfielddatatype 
            WHERE FormFieldDataTypeID = :FormFieldDataTypeID");
 
        $sth->bindParam(':FormFieldDataTypeID', $FormFieldDataTypeID);
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