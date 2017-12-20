<?php 
$app->get('/forms/field/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT ff.*,dt.FormFieldDataTypeName,dt.FormFieldComment 
            FROM formfieldtable ff, formfielddatatype dt
            WHERE dt.FormFieldDataTypeID=ff.FormFieldDataTypeID
            and FormFieldID = :id order by ff.FormFieldName");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $formfieldtable = $sth->fetchAll(PDO::FETCH_OBJ);
		if($formfieldtable) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $formfieldtable));
   
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

$app->get('/forms/field/byform/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT ff.*,dt.FormFieldDataTypeName,dt.FormFieldComment 
            FROM formfieldtable ff, formfielddatatype dt
            WHERE dt.FormFieldDataTypeID=ff.FormFieldDataTypeID and ff.FormID = :id order by ff.FormFieldName");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $formfieldtable = $sth->fetchAll(PDO::FETCH_OBJ);
		if($formfieldtable) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $formfieldtable));
   
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


$app->get('/forms/field/get(/)', function () { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT ff.*,dt.FormFieldDataTypeName,dt.FormFieldComment 
            FROM formfieldtable ff, formfielddatatype dt
            WHERE dt.FormFieldDataTypeID=ff.FormFieldDataTypeID order by ff.FormFieldName";
		//  
        $sth = $db->prepare($Query);
		$sth->execute();
        $formfieldtable = $sth->fetchAll(PDO::FETCH_OBJ);
		
        if($formfieldtable) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $formfieldtable));
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

$app->post('/forms/field/add(/)', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		$FormID = $allPostVars['FormID'];
		$FormFieldName = $allPostVars['FormFieldName'];
		$FormFieldDataTypeID = $allPostVars['FormFieldDataTypeID'];
		$FormFieldValues = $allPostVars['FormFieldValues'];
		$FormFieldIsRequired = $allPostVars['FormFieldIsRequired'];
		
		$qry="SELECT * FROM formfieldtable WHERE FormFieldName='".$FormFieldName."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $formfieldtable = $sth->fetchAll(PDO::FETCH_OBJ);	
		
		if($formfieldtable){
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"Field name already exits"));
		}else{		
		$sth = $db->prepare("INSERT INTO formfieldtable (FormID,FormFieldName,FormFieldDataTypeID,FormFieldValues,FormFieldIsRequired) VALUES (:FormID,:FormFieldName,:FormFieldDataTypeID,:FormFieldValues,:FormFieldIsRequired)");
		$sth->bindParam(':FormID', $FormID);
		$sth->bindParam(':FormFieldName', $FormFieldName);
		$sth->bindParam(':FormFieldDataTypeID', $FormFieldDataTypeID);
		$sth->bindParam(':FormFieldValues', $FormFieldValues);
		$sth->bindParam(':FormFieldIsRequired', $FormFieldIsRequired);
		$sth->execute();
		
		$lastInsertedID = $db->lastInsertID();
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","FormFieldID"=> $lastInsertedID));
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

$app->post('/forms/field/update(/)', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$FormFieldID = $allPostVars['FormFieldID'];
		$FormFieldName = $allPostVars['FormFieldName'];
		$FormFieldDataTypeID = $allPostVars['FormFieldDataTypeID'];
		$FormFieldValues = $allPostVars['FormFieldValues'];
		$FormFieldIsRequired = $allPostVars['FormFieldIsRequired'];
		
		$db = getDB();
		$sth = $db->prepare("UPDATE formfieldtable SET FormFieldName=:FormFieldName,FormFieldDataTypeID=:FormFieldDataTypeID,FormFieldValues=:FormFieldValues,FormFieldIsRequired=:FormFieldIsRequired WHERE FormFieldID = :FormFieldID");

		$sth->bindParam(':FormFieldName', $FormFieldName);
		$sth->bindParam(':FormFieldDataTypeID', $FormFieldDataTypeID);
		$sth->bindParam(':FormFieldValues', $FormFieldValues);
		$sth->bindParam(':FormFieldIsRequired', $FormFieldIsRequired);
		$sth->bindParam(':FormFieldID', $FormFieldID);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","FormFieldID"=> $FormFieldID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/forms/field/delete(/)', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$FormFieldID=$allPostVars['FormFieldID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From formfieldtable 
            WHERE FormFieldID = :FormFieldID");
 
        $sth->bindParam(':FormFieldID', $FormFieldID);
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