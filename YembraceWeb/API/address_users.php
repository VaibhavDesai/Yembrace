<?php 
$app->get('/address_users/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM address_users
            WHERE AddressID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $address_users = $sth->fetchAll(PDO::FETCH_OBJ);
		if($address_users) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $address_users));
   
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

$app->get('/address_users/get/', function () { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT au.*,u.FullName FROM address_users au, users u WHERE u.UserID=au.UserID";
		//  
        $sth = $db->prepare($Query);
		$sth->execute();
        $address_users = $sth->fetchAll(PDO::FETCH_OBJ);
		
        if($address_users) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $address_users));
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

$app->post('/address_users/add/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		
		$UserID = $allPostVars['UserID'];
		$AddressTitle = $allPostVars['AddressTitle'];
		$FullAddress = $allPostVars['FullAddress'];
		$Pincode = $allPostVars['Pincode'];
		$IsDefaultAddress = $allPostVars['IsDefaultAddress'];
		
		$qry="SELECT * FROM address_users WHERE UserID='".$UserID."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $address_users = $sth->fetchAll(PDO::FETCH_OBJ);	
		
		if($address_users){
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"address of user already exits"));
		}else{		
		$sth = $db->prepare("INSERT INTO address_users (UserID,AddressTitle,FullAddress,Pincode,IsDefaultAddress) VALUES (:UserID,:AddressTitle,:FullAddress,:Pincode,:IsDefaultAddress)");
		
		$sth->bindParam(':UserID', $UserID);
		$sth->bindParam(':AddressTitle', $AddressTitle);
		$sth->bindParam(':FullAddress', $FullAddress);
		$sth->bindParam(':Pincode', $Pincode);
		$sth->bindParam(':IsDefaultAddress', $IsDefaultAddress);
		$sth->execute();
		
		$lastInsertedID = $db->lastInsertID();
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","AddressID"=> $lastInsertedID));
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

$app->post('/address_users/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$AddressID = $allPostVars['AddressID'];
		$UserID = $allPostVars['UserID'];
		$AddressTitle = $allPostVars['AddressTitle'];
		$FullAddress = $allPostVars['FullAddress'];
		$Pincode = $allPostVars['Pincode'];
		$IsDefaultAddress = $allPostVars['IsDefaultAddress'];
		
		$db = getDB();
		$sth = $db->prepare("UPDATE address_users SET UserID=:UserID,AddressTitle=:AddressTitle,FullAddress=:FullAddress,Pincode=:Pincode,IsDefaultAddress=:IsDefaultAddress WHERE AddressID = :AddressID");

		$sth->bindParam(':UserID', $UserID);
		$sth->bindParam(':AddressTitle', $AddressTitle);
		$sth->bindParam(':FullAddress', $FullAddress);
		$sth->bindParam(':Pincode', $Pincode);
		$sth->bindParam(':IsDefaultAddress', $IsDefaultAddress);
		$sth->bindParam(':AddressID', $AddressID);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","AddressID"=> $AddressID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/address_users/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$AddressID=$allPostVars['AddressID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From address_users 
            WHERE AddressID = :AddressID");
 
        $sth->bindParam(':AddressID', $AddressID);
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