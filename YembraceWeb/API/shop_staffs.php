<?php 
$app->get('/shopstaffs/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM staff_view
            WHERE ShopStaffID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $shop_staffs = $sth->fetchAll(PDO::FETCH_OBJ);
		if($shop_staffs) { 
			$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $shop_staffs));
   
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

$app->get('/shopstaffs/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT * FROM staff_view";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		 }
 
        $sth = $db->prepare($Query);
		$sth->execute();
        $shop_staffs = $sth->fetchAll(PDO::FETCH_OBJ);

        if($shop_staffs) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $shop_staffs));
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
$app->post('/shopstaffs/validate/', function() use($app) {
 
   
    try 
    { $allPostVars = $app->request->post();
    $StaffMobile = $allPostVars['Mobile'];
    $Password = $allPostVars['Password'];
	$Password=md5($Password);
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM staff_view
            WHERE StaffMobile = :StaffMobile And StaffPassword = :StaffPassword");
 
        $sth->bindParam(':StaffMobile', $StaffMobile);
        $sth->bindParam(':StaffPassword', $Password);
		 $sth->execute();
       $shop_staffs = $sth->fetchAll(PDO::FETCH_OBJ);
  
        if($shop_staffs) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $shop_staffs));
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

$app->post('/shopstaffs/add/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
	
		$StaffCompanyID = $allPostVars['StaffCompanyID'];
		$StaffUsername = $allPostVars['StaffUsername'];
		$StaffPassword = md5($allPostVars['StaffPassword']);
		//$StaffProfilePic = $allPostVars['StaffProfilePic'];
		$StaffRoleID = $allPostVars['StaffRoleID'];
		$StaffFullName = $allPostVars['StaffFullName'];
		$StaffMobile = $allPostVars['StaffMobile'];
		$StaffShopID = $allPostVars['StaffShopID'];
		$StaffCreatedBy = $allPostVars['StaffCreatedBy'];
		$StaffEmail = $allPostVars['StaffEmail'];
		
		$qry="SELECT * FROM shop_staffs WHERE StaffUsername='".$StaffUsername."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $shop_staffs = $sth->fetchAll(PDO::FETCH_OBJ);
		
		if($shop_staffs){
		$app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"shop_staffs username already exits"));
		}else{		
		
		$sth = $db->prepare("INSERT INTO shop_staffs (StaffCompanyID,StaffUsername,StaffPassword,StaffRoleID,StaffFullName,StaffMobile,StaffShopID,StaffCreatedBy,StaffEmail) VALUES (:StaffCompanyID,:StaffUsername,:StaffPassword,:StaffRoleID,:StaffFullName,:StaffMobile,:StaffShopID,:StaffCreatedBy,:StaffEmail)");

		$sth->bindParam(':StaffCompanyID', $StaffCompanyID);
		$sth->bindParam(':StaffUsername', $StaffUsername);
		$sth->bindParam(':StaffPassword', $StaffPassword);
		$sth->bindParam(':StaffRoleID', $StaffRoleID);
		$sth->bindParam(':StaffFullName', $StaffFullName);
		$sth->bindParam(':StaffMobile', $StaffMobile);
		$sth->bindParam(':StaffShopID', $StaffShopID);
		$sth->bindParam(':StaffCreatedBy', $StaffCreatedBy);
		$sth->bindParam(':StaffEmail', $StaffEmail);
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

$app->post('/shopstaffs/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$ShopStaffID = $allPostVars['ShopStaffID'];
		$StaffCompanyID = $allPostVars['StaffCompanyID'];
		$StaffUsername = $allPostVars['StaffUsername'];
		$StaffPassword = md5($allPostVars['StaffPassword']);
		//$StaffProfilePic = $allPostVars['StaffProfilePic'];
		$StaffRoleID = $allPostVars['StaffRoleID'];
		$StaffFullName = $allPostVars['StaffFullName'];
		$StaffMobile = $allPostVars['StaffMobile'];
		$StaffShopID = $allPostVars['StaffShopID'];
		$StaffCreatedBy = $allPostVars['StaffCreatedBy'];
		$StaffEmail = $allPostVars['StaffEmail'];
		
        $db = getDB();
		$sth = $db->prepare("UPDATE shop_staffs SET StaffCompanyID=:StaffCompanyID, StaffUsername=:StaffUsername,StaffPassword=:StaffPassword, StaffRoleID=:StaffRoleID,StaffFullName=:StaffFullName, StaffMobile=:StaffMobile,StaffShopID=:StaffShopID,StaffCreatedBy=:StaffCreatedBy,StaffEmail=:StaffEmail WHERE ShopStaffID = :ShopStaffID");
		
		$sth->bindParam(':StaffCompanyID', $StaffCompanyID);
		$sth->bindParam(':StaffUsername', $StaffUsername);
		$sth->bindParam(':StaffPassword', $StaffPassword);
		$sth->bindParam(':StaffRoleID', $StaffRoleID);
		$sth->bindParam(':StaffFullName', $StaffFullName);
		$sth->bindParam(':StaffMobile', $StaffMobile);
		$sth->bindParam(':StaffShopID', $StaffShopID);
		$sth->bindParam(':StaffCreatedBy', $StaffCreatedBy);
		$sth->bindParam(':StaffEmail', $StaffEmail);
		$sth->bindParam(':ShopStaffID', $ShopStaffID);
		
		$sth->execute();

		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","ShopStaffID"=> $ShopStaffID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/shopstaffs/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$ShopStaffID=$allPostVars['ShopStaffID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From shop_staffs 
            WHERE ShopStaffID = :ShopStaffID");
 
        $sth->bindParam(':ShopStaffID', $ShopStaffID);
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

//Author Vaibhav JD
$app->get('/shopstaffs/mobile/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM staff_view
            WHERE ShopStaffID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $shop_staffs = $sth->fetchAll(PDO::FETCH_ASSOC);
        $response['StaffUsername'] = $shop_staffs[0]['StaffUsername'];
        $response['StaffProfilePic'] = $shop_staffs[0]['StaffProfilePic'];
        $response['StaffFullName'] = $shop_staffs[0]['StaffFullName'];
        $response['StaffUsername'] = $shop_staffs[0]['StaffUsername'];
        $response['StaffMobile'] = $shop_staffs[0]['StaffMobile'];
        $response['StaffEmail'] = $shop_staffs[0]['StaffEmail'];
        $response['StaffRoleName'] = $shop_staffs[0]['StaffRoleName'];
        $response['StaffUsername'] = $shop_staffs[0]['StaffUsername'];
        $response['ShopName'] = $shop_staffs[0]['ShopName'];
        $response['ShopFullAddress'] = $shop_staffs[0]['ShopFullAddress'];
        $response['ShopImage'] = $shop_staffs[0]['ShopImage'];
        $response['CompanyLogo'] = $shop_staffs[0]['CompanyLogo'];
        $response['CompanyName'] = $shop_staffs[0]['CompanyName'];


		if($shop_staffs) { 
			$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $response));
   
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





?>