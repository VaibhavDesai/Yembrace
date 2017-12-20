<?php 

$app->get('/categorytoplevel/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * FROM categorytoplevel WHERE CategoryID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $categorytoplevel = $sth->fetchAll(PDO::FETCH_OBJ);
		if($categorytoplevel) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $categorytoplevel));
   
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

$app->get('/categorytoplevel/get(/)', function () { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT * FROM categorytoplevel";
		//  
        $sth = $db->prepare($Query);
		$sth->execute();
        $categorytoplevel = $sth->fetchAll(PDO::FETCH_OBJ);
		
        if($categorytoplevel) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $categorytoplevel));
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

$app->post('/categorytoplevel/add(/)', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		
		$CategoryName = $allPostVars['CategoryName'];
		$CategoryDescription = $allPostVars['CategoryDescription'];
		//$CategoryLogo = $allPostVars['CategoryLogo'];
		$CategoryIsActive = $allPostVars['CategoryIsActive'];
		
		$qry="SELECT * FROM categorytoplevel WHERE CategoryName='".$CategoryName."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $categorytoplevel = $sth->fetchAll(PDO::FETCH_OBJ);	
		
		if($categorytoplevel){
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"address of user already exits"));
		}else{		
		$sth = $db->prepare("INSERT INTO categorytoplevel (CategoryName,CategoryDescription,CategoryIsActive) VALUES (:CategoryName,:CategoryDescription,:CategoryIsActive)");
		
		$sth->bindParam(':CategoryName', $CategoryName);
		$sth->bindParam(':CategoryDescription', $CategoryDescription);
		//$sth->bindParam(':CategoryLogo', $CategoryLogo);
		$sth->bindParam(':CategoryIsActive', $CategoryIsActive);
		$sth->execute();
		
		$lastInsertedID = $db->lastInsertID();
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","CategoryID"=> $lastInsertedID));
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

$app->post('/categorytoplevel/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$CategoryID = $allPostVars['CategoryID'];
		$CategoryName = $allPostVars['CategoryName'];
		$CategoryDescription = $allPostVars['CategoryDescription'];
		//$CategoryLogo = $allPostVars['CategoryLogo'];
		$CategoryIsActive = $allPostVars['CategoryIsActive'];
		
		$db = getDB();
		$sth = $db->prepare("UPDATE categorytoplevel SET CategoryName=:CategoryName,CategoryDescription=:CategoryDescription,CategoryIsActive=:CategoryIsActive WHERE CategoryID = :CategoryID");

		$sth->bindParam(':CategoryName', $CategoryName);
		$sth->bindParam(':CategoryDescription', $CategoryDescription);
		//$sth->bindParam(':CategoryLogo', $CategoryLogo);
		$sth->bindParam(':CategoryIsActive', $CategoryIsActive);
		$sth->bindParam(':CategoryID', $CategoryID);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","CategoryID"=> $CategoryID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/categorytoplevel/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$CategoryID=$allPostVars['CategoryID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From categorytoplevel 
            WHERE CategoryID = :CategoryID");
 
        $sth->bindParam(':CategoryID', $CategoryID);
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

//Author Vaibhav JD

$app->get('/categorytoplevel/shops(/)', function() use($app){
	try{
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$all_get_requests = $app->request()->get();
		$fields = array();
		$response = array();
		$max_result = 10;
		$query ="";
		$range =10;
		$offset=0;

		if(isset($all_get_requests['categoryID']))
			$category_id=$all_get_requests['categoryID'];
		if(isset($all_get_requests['latitude']))
			$latitude = $all_get_requests['latitude'];
		if(isset($all_get_requests['longitude']))
			$longitude = $all_get_requests['longitude'];
		if(isset($all_get_requests['range']))
			$range = $all_get_requests['range'];
		if(isset($all_get_requests['maxResult']))
			$max_result = $all_get_requests['maxResult'];
		


		$select_qry="SELECT company_shop.ShopID,company.CompanyLogo,company_shop.ShopName,company_shop.ShopArea,company_shop.ShopLandMark ";
		$from_qry="FROM company_shop company_shop,products products,categorytoplevel categorytoplevel,company company ";
		$where_qry="WHERE categorytoplevel.CategoryID=products.CategoryID AND products.ShopID=company_shop.ShopID AND company.CompanyID=company_shop.ShopCompanyID ";
		$limit_qry = "LIMIT ".$max_result." OFFSET ".$offset.";";
		if(isset($all_get_requests['categoryID']))
			$where_qry="WHERE categorytoplevel.CategoryID=:CategoryID AND categorytoplevel.CategoryID=products.CategoryID AND products.ShopID=company_shop.ShopID AND company.CompanyID=company_shop.ShopCompanyID ";

		if(isset($all_get_requests['latitude'])&&isset($all_get_requests['longitude'])){
			$geo_field = ",( 6371 * acos( cos( radians(ShopLatitude) ) * cos( radians(".$latitude.") ) * cos( radians(".$longitude.") - radians(ShopLongitude) ) + sin( radians(ShopLatitude) ) * sin( radians(".$latitude.") ) ) ) AS Distance ";
			$having_clause =" HAVING distance < ".$range."  ORDER BY distance ";
			$query=$select_qry.$geo_field.$from_qry.$where_qry.$having_clause.$limit_qry;
		}
			
		else
			$query=$select_qry.$from_qry.$where_qry.$limit_qry;

		$sth = $db->prepare($query);
		if(isset($all_get_requests['categoryID']))
			$sth->bindParam(':CategoryID',$category_id);
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$data['ShopID'] = $row['ShopID'];
			$data['CompanyLogo'] = $row['CompanyLogo'];
			$data['ShopArea'] = $row['ShopArea'];
			$data['ShopName'] = $row['ShopName'];
			$data['ShopLandMark'] = $row['ShopLandMark'];
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

$app->get('/categorytoplevel/products(/)', function() use($app){

	try{
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$all_get_requests=$app->request()->get();
		$fields=array();
		$response=array();
		$max_result=10;
		$query="";
		$range=10;
		$offset=0;

		if(isset($all_get_requests['categoryID']))
			$category_id=$all_get_requests['categoryID'];
		if(isset($all_get_requests['latitude']))
			$latitude = $all_get_requests['latitude'];
		if(isset($all_get_requests['longitude']))
			$longitude = $all_get_requests['longitude'];
		if(isset($all_get_requests['range']))
			$range = $all_get_requests['range'];
		if(isset($all_get_requests['maxResult']))
			$max_result = $all_get_requests['maxResult'];
		if(isset($all_get_requests['offset']))
			$offset = $all_get_requests['offset'];
		

		$select_qry="SELECT products.ProductID,products.ProductTitle,products.Images,products.SellingPrice ";
		$from_qry="FROM company_shop company_shop,products products,categorytoplevel categorytoplevel ";
		$where_qry="WHERE categorytoplevel.CategoryID=products.CategoryID AND products.ShopID=company_shop.ShopID ";
		$limit_qry = "LIMIT ".$max_result." OFFSET ".$offset.";";
		if(isset($all_get_requests['categoryID']))
			$where_qry="WHERE categorytoplevel.CategoryID=:CategoryID AND categorytoplevel.CategoryID=products.CategoryID AND products.ShopID=company_shop.ShopID ";

		if(isset($all_get_requests['latitude'])&&isset($all_get_requests['longitude'])){
			$geo_field = ",( 6371 * acos( cos( radians(ShopLatitude) ) * cos( radians(".$latitude.") ) * cos( radians(".$longitude.") - radians(ShopLongitude) ) + sin( radians(ShopLatitude) ) * sin( radians(".$latitude.") ) ) ) AS Distance ";
			$having_clause =" HAVING distance < ".$range."  ORDER BY distance ";
			$query=$select_qry.$geo_field.$from_qry.$where_qry.$having_clause.$limit_qry;
		}

		else
			$query=$select_qry.$from_qry.$where_qry.$limit_qry;

		$sth = $db->prepare($query);
		if(isset($all_get_requests['categoryID']))
			$sth->bindParam(':CategoryID',$category_id);
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$data['ProductID'] = $row['ProductID'];
			$data['ProductTitle'] = $row['ProductTitle'];
			$data['Images'] = $row['Images'];
			$data['SellingPrice'] = $row['SellingPrice'];
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

$app->get('/categorytoplevel/offers(/)', function() use($app){

	try{
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$all_get_requests=$app->request()->get();
		$fields=array();
		$response=array();
		$max_result=10;
		$query="";
		$range=10;
		$offset=0;
		

		if(isset($all_get_requests['shopID']))
			$shop_id = $all_get_requests['shopID'];
		if(isset($all_get_requests['categoryID']))
			$category_id=$all_get_requests['categoryID'];
		if(isset($all_get_requests['latitude']))
			$latitude = $all_get_requests['latitude'];
		if(isset($all_get_requests['longitude']))
			$longitude = $all_get_requests['longitude'];
		if(isset($all_get_requests['range']))
			$range = $all_get_requests['range'];
		if(isset($all_get_requests['maxResult']))
			$max_result = $all_get_requests['maxResult'];
		if(isset($all_get_requests['offset']))
			$offset = $all_get_requests['offset'];

		

		$select_qry="SELECT DISTINCT company_shop.ShopName,company_shop.ShopArea,offerstable.OfferID,offerstable.OfferTitle,offerstable.Images,offerstable.DiscountPercentage,products.ProductTitle,offerstable.OfferCode,DATEDIFF(DATE_ADD(offerstable.OfferCreatedOn,INTERVAL offerstable.ExpireTime DAY),NOW()) AS ExpireDate ";
		$from_qry="FROM company_shop company_shop,products products,categorytoplevel categorytoplevel,company company ,offerstable offerstable ";
		$where_qry="WHERE products.ShopID=company_shop.ShopID AND company.CompanyID=company_shop.ShopCompanyID AND offerstable.ProductID=products.ProductID AND offerstable.OfferIsActive=1 AND NOW()<=DATE_ADD(offerstable.OfferCreatedOn,INTERVAL offerstable.ExpireTime DAY) ";
		$limit_qry = "LIMIT ".$max_result." OFFSET ".$offset.";";

		if(isset($all_get_requests['categoryID']))
			$where_qry="WHERE categorytoplevel.CategoryID=:CategoryID AND categorytoplevel.CategoryID=products.CategoryID AND products.ShopID=company_shop.ShopID AND company.CompanyID=company_shop.ShopCompanyID AND offerstable.ProductID=products.ProductID AND offerstable.OfferIsActive=1 AND NOW()<=DATE_ADD(offerstable.OfferCreatedOn,INTERVAL offerstable.ExpireTime DAY) ";

		if(isset($all_get_requests['latitude'])&&isset($all_get_requests['longitude'])){
			$geo_field = ",( 6371 * acos( cos( radians(ShopLatitude) ) * cos( radians(".$latitude.") ) * cos( radians(".$longitude.") - radians(ShopLongitude) ) + sin( radians(ShopLatitude) ) * sin( radians(".$latitude.") ) ) ) AS Distance ";
			$having_clause =" HAVING distance < ".$range."  ORDER BY distance ";
			$query=$select_qry.$geo_field.$from_qry.$where_qry.$having_clause.$limit_qry;
		}
		if(isset($all_get_requests['shopID']))
			$where_qry="WHERE company_shop.ShopID=:ShopID AND products.ShopID=company_shop.ShopID AND company.CompanyID=company_shop.ShopCompanyID AND offerstable.ProductID=products.ProductID AND offerstable.OfferIsActive=1 AND NOW()<=DATE_ADD(offerstable.OfferCreatedOn,INTERVAL offerstable.ExpireTime DAY) ";
			
		else
			$query=$select_qry.$from_qry.$where_qry.$limit_qry;

		$sth = $db->prepare($query);
		if(isset($all_get_requests['categoryID']))
			$sth->bindParam(':CategoryID',$category_id);
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$data['OfferID'] = $row['OfferID'];
			$data['ShopName'] = $row['ShopName'];
			$data['Images'] = $row['Images'];
			$data['OfferTitle'] = $row['OfferTitle'];
			$data['DiscountPercentage'] = $row['DiscountPercentage'];
			$data['OfferCode'] = $row['OfferCode'];
			$data['ProductTitle'] = $row['ProductTitle'];
			$data['ExpireDate'] = $row['ExpireDate'];
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