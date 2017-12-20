<?php 
$app->get('/company/shops/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM company_shop
            WHERE ShopID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $Shop = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($Shop) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Shop));
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

$app->get('/company/shops/byCompany/:id1', function ($id1) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM company_shop
            WHERE ShopCompanyID = :id1");
 
        $sth->bindParam(':id1', $id1);
        $sth->execute();
 
        $Shop = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($Shop) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Shop));
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

$app->get('/company/shops/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    {
		$db = getDB();
		//$Query="SELECT * FROM company_shop order by ShopName ASC";
		$qry="SELECT s.*,c.CityName,a.CompanyName FROM company_shop s, city c, company a where c.CityID=s.ShopCityID AND a.CompanyID=s.ShopCompanyID";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
		
		$sth = $db->prepare($qry);
        $sth->execute();
 
        $Shop = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Shop) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Shop));
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
$app->post('/company/shops/add/', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();
		
		$ShopCompanyID=$allPostVars['ShopCompanyID'];
		$ShopName=$allPostVars['ShopName'];
		$ShopFullAddress=$allPostVars['ShopFullAddress'];
		$ShopArea=$allPostVars['ShopArea'];
		$ShopLandMark=$allPostVars['ShopLandMark'];
		$ShopPincode=$allPostVars['ShopPincode'];
		$ShopLongitude=$allPostVars['ShopLongitude'];
		$ShopLatitude=$allPostVars['ShopLatitude'];
		$ShopCityID=$allPostVars['ShopCityID'];	
		$ShopIsActive=$allPostVars['ShopIsActive'];		
		
		$sth = $db->prepare("SELECT * 
            FROM company_shop
            WHERE ShopName = :ShopName And ShopCompanyID=:ShopCompanyID");
 
        $sth->bindParam(':ShopName', $ShopName);
		$sth->bindParam(':ShopCompanyID', $ShopCompanyID);
		$sth->execute();
        $Shop = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($Shop) {
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Shop name already exists"));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO company_shop(ShopCompanyID ,ShopName ,ShopFullAddress,ShopArea,ShopLandMark,ShopPincode ,ShopLatitude ,ShopLongitude ,ShopCityID ,ShopIsActive ) VALUES(:ShopCompanyID,:ShopName,:ShopFullAddress,:ShopArea,:ShopLandMark,:ShopPincode,:ShopLatitude,:ShopLongitude,:ShopCityID,:ShopIsActive)");
		
        $sth->bindParam(':ShopCompanyID', $ShopCompanyID);
		$sth->bindParam(':ShopName', $ShopName);
		$sth->bindParam(':ShopFullAddress', $ShopFullAddress);
		$sth->bindParam(':ShopArea', $ShopArea);
		$sth->bindParam(':ShopLandMark', $ShopLandMark);
		$sth->bindParam(':ShopPincode', $ShopPincode);
		$sth->bindParam(':ShopLatitude', $ShopLatitude);
		$sth->bindParam(':ShopLongitude', $ShopLongitude);
		$sth->bindParam(':ShopCityID', $ShopCityID);
		$sth->bindParam(':ShopIsActive', $ShopIsActive);
        $sth->execute();
		$lastInsertId = $db->lastInsertId();
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted Successfully!","ShopID"=> $lastInsertId));
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
$app->post('/company/shops/update/', function() use($app) {
 
    $allPostVars = $app->request->post();
		$ShopID=$allPostVars['ShopID'];
		$ShopCompanyID=$allPostVars['ShopCompanyID'];
		$ShopName=$allPostVars['ShopName'];
		$ShopFullAddress=$allPostVars['ShopFullAddress'];
		$ShopArea=$allPostVars['ShopArea'];
		$ShopLandMark=$allPostVars['ShopLandMark'];
		$ShopPincode=$allPostVars['ShopPincode'];
		$ShopLongitude=$allPostVars['ShopLongitude'];
		$ShopLatitude=$allPostVars['ShopLatitude'];
		$ShopCityID=$allPostVars['ShopCityID'];	
		$ShopIsActive=$allPostVars['ShopIsActive'];		
	
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("UPDATE company_shop 
            SET ShopCompanyID=:ShopCompanyID, ShopName = :ShopName, ShopFullAddress = :ShopFullAddress,ShopArea=:ShopArea,ShopLandMark=:ShopLandMark, ShopPincode = :ShopPincode, ShopLatitude = :ShopLatitude, ShopLongitude=:ShopLongitude, ShopCityID=:ShopCityID, ShopIsActive=:ShopIsActive WHERE ShopID = :ShopID");
 
        $sth->bindParam(':ShopCompanyID', $ShopCompanyID);
		$sth->bindParam(':ShopName', $ShopName);
		$sth->bindParam(':ShopFullAddress', $ShopFullAddress);
		$sth->bindParam(':ShopArea', $ShopArea);
		$sth->bindParam(':ShopLandMark', $ShopLandMark);
		$sth->bindParam(':ShopPincode', $ShopPincode);
		$sth->bindParam(':ShopLatitude', $ShopLatitude);
		$sth->bindParam(':ShopLongitude', $ShopLongitude);
		$sth->bindParam(':ShopCityID', $ShopCityID);
		$sth->bindParam(':ShopIsActive', $ShopIsActive);
        $sth->bindParam(':ShopID', $ShopID);
        $sth->execute();
 
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","ShopID"=> $ShopID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/company/shops/delete/', function() use($app) {
    try 
    {
		$allPostVars = $app->request->post();
		$ShopID=$allPostVars['ShopID'];
		$ShopIsActive=$allPostVars['ShopIsActive'];
        $db = getDB();
 
        $sth = $db->prepare("UPDATE company_shop SET ShopIsActive=:ShopIsActive
            WHERE ShopID = :ShopID");
 
        $sth->bindParam(':ShopID', $ShopID);
		$sth->bindParam(':ShopIsActive', $ShopIsActive);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deactivated successfully"));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});



//Author: Vaibhav.

$app->get('/company/getAll(/)',function() {

	try{

		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$sth = $db->prepare("SELECT company.CompanyName from company company ORDER by company.CompanyName ASC");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		$response = array();
		foreach ($result as $row) {
			array_push($response,$row['CompanyName']);
		}
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		if($response){
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Records found","document"=> $response ));
		}else{
		echo json_encode(array("status" => "success", "code" => 0,"message"=> "No records found"));
		}

	}catch(Exception $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->get('/company/shop/details/:shop_id', function($shop_id) use($app){

	try{
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$all_get_requests = $app->request()->get();
		$fields = array();
		$response = array();
		$max_result = 10;
		$query ="";
		
		$select_qry="SELECT ShopName,ShopFullAddress,ShopLandMark,ShopLatitude,ShopLongitude,shopImage,shopPhoneNumber,shopEmailID ";
		$from_qry="FROM company_shop ";
		$where_qry="WHERE ShopID=:ShopID AND ShopIsActive=1 ";

		$query = $select_qry.$from_qry.$where_qry;
		$sth = $db->prepare($query);
		$sth->bindParam(':ShopID',$shop_id);
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$data['ShopName'] = $row['ShopName'];
			$data['ShopFullAddress'] = $row['ShopFullAddress'];
			$data['ShopLandMark'] = $row['ShopLandMark'];
			$data['ShopLatitude'] = $row['ShopLatitude'];
			$data['ShopLongitude'] = $row['ShopLongitude'];
			$data['shopImage'] = $row['shopImage'];
			$data['ShopFullAddress'] = $row['ShopFullAddress'];
			$data['shopPhoneNumber'] = $row['shopPhoneNumber'];
			$data['shopEmailID'] = $row['shopEmailID'];
			array_push($response, $data);
	    }
	    $app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		if($response){
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Records found","document"=> $response ));
		}else{
		echo json_encode(array("status" => "success", "code" => 0,"message"=> "No records found"));
		}
	}catch(Exception $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});
?>