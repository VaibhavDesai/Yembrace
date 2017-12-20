<?php 
$app->get('/admin_role/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM admin_role
            WHERE AdminRoleID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $admin_role = $sth->fetchAll(PDO::FETCH_OBJ);
		if($admin_role) { 
			$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $admin_role));
   
        } else {
			$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "No record found"));
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

$app->get('/admin_role/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    try 
    {
		$app = \Slim\Slim::getInstance();
		
		$Query="SELECT * FROM admin_role order by AdminRoleName ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		 }
		$db = getDB();
 
        $sth = $db->prepare($Query);
		$sth->execute();
        $admin_role = $sth->fetchAll(PDO::FETCH_OBJ);

        if($admin_role) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $admin_role));
        } else {
			$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "No record found"));
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

$app->post('/admin_role/add/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		$AdminRoleName = $allPostVars['AdminRoleName'];
		$AdminRoleIsActive = $allPostVars['AdminRoleIsActive'];
		
		$qry="SELECT * FROM admin_role WHERE AdminRoleName='".$AdminRoleName."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $admin_role = $sth->fetchAll(PDO::FETCH_OBJ);

		if($admin_role){
		$app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"admin_role name already exits"));
		}else{		
		$sth = $db->prepare("INSERT INTO admin_role (AdminRoleName,AdminRoleIsActive) VALUES (:AdminRoleName,:AdminRoleIsActive)");
		$sth->bindParam(':AdminRoleName', $AdminRoleName);
		$sth->bindParam(':AdminRoleIsActive', $AdminRoleIsActive);
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

$app->post('/admin_role/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$AdminRoleID = $allPostVars['AdminRoleID'];
	    $AdminRoleName = $allPostVars['AdminRoleName'];
		$AdminRoleIsActive = $allPostVars['AdminRoleIsActive'];
			
        $db = getDB();
        $sth = $db->prepare("UPDATE admin_role SET AdminRoleName=:AdminRoleName, AdminRoleIsActive=:AdminRoleIsActive WHERE AdminRoleID = :AdminRoleID");
			
		$sth->bindParam(':AdminRoleName', $AdminRoleName);
		$sth->bindParam(':AdminRoleIsActive', $AdminRoleIsActive);
		$sth->bindParam(':AdminRoleID', $AdminRoleID);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","AdminRoleID"=> $AdminRoleID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/admin_role/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$AdminRoleID=$allPostVars['AdminRoleID'];
		$db = getDB();
        $sth = $db->prepare("Delete From admin_role 
            WHERE AdminRoleID = :AdminRoleID");
 
        $sth->bindParam(':AdminRoleID', $AdminRoleID);
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