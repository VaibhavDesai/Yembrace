<?php 
$app->get('/staffroles/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM staffroles
            WHERE StaffRoleID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $staffroles = $sth->fetchAll(PDO::FETCH_OBJ);
		if($staffroles) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $staffroles));
   
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

$app->get('/staffroles/byshop/:id2', function ($id2) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM staffroles
            WHERE RoleShopID = :id2");
 
        $sth->bindParam(':id2', $id2);
        $sth->execute();
 
        $staffroles = $sth->fetchAll(PDO::FETCH_OBJ);
		if($staffroles) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $staffroles));
   
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

$app->get('/staffroles/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		//$Query="SELECT * FROM staffroles";
		
		$Query="SELECT s.*,c.CompanyName,cs.ShopName FROM staffroles s, company c, company_shop cs where c.CompanyID=s.RoleCompanyID AND cs.ShopID=s.RoleShopID";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		 }
 
        $sth = $db->prepare($Query);
		$sth->execute();
        $staffroles = $sth->fetchAll(PDO::FETCH_OBJ);

        if($staffroles) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $staffroles));
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

$app->post('/staffroles/add/', function() use($app) {
	try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
	
		$StaffRoleName = $allPostVars['StaffRoleName'];
		$StaffRoleIsActive = $allPostVars['StaffRoleIsActive'];
		$RoleShopID = $allPostVars['RoleShopID'];
		$RoleCompanyID = $allPostVars['RoleCompanyID'];
		
		$qry="SELECT * FROM staffroles WHERE StaffRoleName='".$StaffRoleName."' AND RoleShopID='".$RoleShopID."' ";
		$sth = $db->prepare($qry);
		$sth->execute();
        $staffroles = $sth->fetchAll(PDO::FETCH_OBJ);
		
		if($staffroles){
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"Role already exits"));
		}else{		
		
		$sth = $db->prepare("INSERT INTO staffroles (StaffRoleName,StaffRoleIsActive,RoleShopID,RoleCompanyID) VALUES (:StaffRoleName,:StaffRoleIsActive,:RoleShopID,:RoleCompanyID)");
		$sth->bindParam(':StaffRoleName', $StaffRoleName);
		$sth->bindParam(':StaffRoleIsActive', $StaffRoleIsActive);
		$sth->bindParam(':RoleShopID', $RoleShopID);
		$sth->bindParam(':RoleCompanyID', $RoleCompanyID);

		$sth->execute();
		
		$lastInsertedID = $db->lastInsertID();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","StaffRoleID"=> $lastInsertedID));
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

$app->post('/staffroles/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$StaffRoleID = $allPostVars['StaffRoleID'];
		$StaffRoleName = $allPostVars['StaffRoleName'];
		$StaffRoleIsActive = $allPostVars['StaffRoleIsActive'];
		$RoleShopID = $allPostVars['RoleShopID'];
		$RoleCompanyID = $allPostVars['RoleCompanyID'];

        $db = getDB();
		$sth = $db->prepare("UPDATE staffroles SET StaffRoleName=:StaffRoleName, StaffRoleIsActive=:StaffRoleIsActive,RoleShopID=:RoleShopID,RoleCompanyID=:RoleCompanyID WHERE StaffRoleID = :StaffRoleID");
		
		$sth->bindParam(':StaffRoleName', $StaffRoleName);
		$sth->bindParam(':StaffRoleIsActive', $StaffRoleIsActive);
		$sth->bindParam(':RoleShopID', $RoleShopID);
		$sth->bindParam(':RoleCompanyID', $RoleCompanyID);
		$sth->execute();

		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","StaffRoleID"=> $StaffRoleID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/staffroles/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$StaffRoleID=$allPostVars['StaffRoleID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From staffroles 
            WHERE StaffRoleID = :StaffRoleID");
 
        $sth->bindParam(':StaffRoleID', $StaffRoleID);
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