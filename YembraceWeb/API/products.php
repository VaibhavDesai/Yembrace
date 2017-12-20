<?php 
$app->get('/products/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM products
            WHERE ProductID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $Product = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($Product) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Product));
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

$app->get('/products/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    { 	$db = getDB();
 
		$Query="SELECT * FROM products order by ProductCreatedOn DESC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
		$sth = $db->prepare($Query);
        $sth->execute();
 
        $Product = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Product) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Product));
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

$app->get('/products/get/count/', function () {
 
    $app = \Slim\Slim::getInstance();
    try 
    { 	$db = getDB();
 
		$Query="SELECT * FROM products";
		
		$sth = $db->prepare($Query);
        $sth->execute();
		$Product = $sth->rowCount();
       // $Product = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Product) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Product));
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

$app->get('/products/get/datatable(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    { 	$db = getDB();
 
		$Query="SELECT * FROM products order by ProductCreatedOn DESC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
		$sth = $db->prepare($Query);
        $sth->execute();
 
        $Product = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Product) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Product));
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


$app->post('/products/add/', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();

		//$ProductID=$allPostVars['ProductID'];
		$CompanyID=$allPostVars['CompanyID'];
		$ShopID=$allPostVars['ShopID'];
		$ProductTitle=$allPostVars['ProductTitle'];
		$ProductDescription=$allPostVars['ProductDescription'];
		$Images=$allPostVars['Images'];
		$Videos=$allPostVars['Videos'];
		$Tags=$allPostVars['Tags'];
		$SellingPrice=$allPostVars['SellingPrice'];
		$CostPrice=$allPostVars['CostPrice'];
		$CategoryID=$allPostVars['CategoryID'];
		$BrandID=$allPostVars['BrandID'];
		$SizeID=$allPostVars['SizeID'];
		$ManufacturersID=$allPostVars['ManufacturersID'];
		$ColorID=$allPostVars['ColorID'];
		$StatusID=$allPostVars['StatusID'];
		$Quantity=$allPostVars['Quantity'];
		$ProductCreatedBy=$allPostVars['ProductCreatedBy'];
		$ProductUpdatedBy=$allPostVars['ProductUpdatedBy'];
		$ProductIsActive=$allPostVars['ProductIsActive'];
		$ProductBardCode=$allPostVars['ProductBardCode'];

		$sth = $db->prepare("SELECT * 
            FROM products
            WHERE ProductTitle = :ProductTitle And CompanyID=:CompanyID");
 
        $sth->bindParam(':ProductTitle', $ProductTitle);
		$sth->bindParam(':CompanyID', $CompanyID);
		 $sth->execute();
       $color = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($color) {
		  $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Product title already exists"));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO products(CompanyID ,ShopID ,ProductTitle ,ProductDescription ,Images ,Videos ,Tags ,SellingPrice ,CostPrice ,CategoryID ,BrandID ,SizeID ,ManufacturersID ,ColorID ,StatusID ,Quantity ,ProductCreatedOn ,ProductCreatedBy ,ProductUpdatedOn ,ProductUpdatedBy ,ProductIsActive,ProductBardCode ) VALUES ( :CompanyID ,:ShopID ,:ProductTitle ,:ProductDescription ,:Images ,:Videos ,:Tags ,:SellingPrice ,:CostPrice ,:CategoryID ,:BrandID ,:SizeID ,:ManufacturersID ,:ColorID ,:StatusID ,:Quantity ,:ProductCreatedOn ,:ProductCreatedBy ,:ProductUpdatedOn ,:ProductUpdatedBy ,:ProductIsActive,:ProductBardCode)");
			

       //$sth->bindParam(':ProductID', $ProductID);
		$sth->bindParam(':CompanyID	', $CompanyID);							
		$sth->bindParam(':ShopID', $ShopID);								
		$sth->bindParam(':ProductTitle', $ProductTitle);								
		$sth->bindParam(':ProductDescription', $ProductDescription);								
		$sth->bindParam(':Images', $Images);								
		$sth->bindParam(':Videos', $Videos);								
		$sth->bindParam(':Tags', $Tags);								
		$sth->bindParam(':SellingPrice', $SellingPrice);								
		$sth->bindParam(':CostPrice', $CostPrice);								
		$sth->bindParam(':CategoryID', $CategoryID);								
		$sth->bindParam(':BrandID', $BrandID);								
		$sth->bindParam(':SizeID', $SizeID);								
		$sth->bindParam(':ManufacturersID', $ManufacturersID);								
		$sth->bindParam(':ColorID', $ColorID);								
		$sth->bindParam(':StatusID', $StatusID);								
		$sth->bindParam(':Quantity', $Quantity);								
		//$sth->bindParam(':ProductCreatedOn', $ProductCreatedOn);								
		$sth->bindParam(':ProductCreatedBy', $ProductCreatedBy);								
		//$sth->bindParam(':ProductUpdatedOn', $ProductUpdatedOn);								
		$sth->bindParam(':ProductUpdatedBy', $ProductUpdatedBy);								
		$sth->bindParam(':ProductIsActive', $ProductIsActive);
		$sth->bindParam(':ProductBardCode', $ProductBardCode);
        $sth->execute();
		$lastInsertId = $db->lastInsertId();
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted Successfully!","id"=> $lastInsertId));
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
$app->post('/products/update/', function() use($app) {
 
    $allPostVars = $app->request->post();
    $ProductID=$allPostVars['ProductID'];
		$CompanyID=$allPostVars['CompanyID'];
		$ShopID=$allPostVars['ShopID'];
		$ProductTitle=$allPostVars['ProductTitle'];
		$ProductDescription=$allPostVars['ProductDescription'];
		$Images=$allPostVars['Images'];
		$Videos=$allPostVars['Videos'];
		$Tags=$allPostVars['Tags'];
		$SellingPrice=$allPostVars['SellingPrice'];
		$CostPrice=$allPostVars['CostPrice'];
		$CategoryID=$allPostVars['CategoryID'];
		$BrandID=$allPostVars['BrandID'];
		$SizeID=$allPostVars['SizeID'];
		$ManufacturersID=$allPostVars['ManufacturersID'];
		$ColorID=$allPostVars['ColorID'];
		$StatusID=$allPostVars['StatusID'];
		$Quantity=$allPostVars['Quantity'];
		//$ProductCreatedBy=$allPostVars['ProductCreatedBy'];
		$ProductUpdatedBy=$allPostVars['ProductUpdatedBy'];
		$ProductIsActive=$allPostVars['ProductIsActive'];
		$ProductBardCode=$allPostVars['ProductBardCode'];
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("UPDATE products 
            SET CompanyID= :CompanyID, ShopID= :ShopID, ProductTitle= :ProductTitle, ProductDescription= :ProductDescription, Images= :Images, Videos= :Videos, Tags= :Tags, SellingPrice= :SellingPrice, CostPrice= :CostPrice, CategoryID= :CategoryID, BrandID= :BrandID, SizeID= :SizeID, ManufacturersID= :ManufacturersID, ColorID= :ColorID, StatusID= :StatusID, Quantity= :Quantity,   ProductUpdatedBy= :ProductUpdatedBy, ProductIsActive= :ProductIsActive,ProductBardCode=:ProductBardCode
            WHERE ProductID = :ProductID");
 
       $sth->bindParam(':CompanyID	', $CompanyID);							
		$sth->bindParam(':ShopID', $ShopID);								
		$sth->bindParam(':ProductTitle', $ProductTitle);								
		$sth->bindParam(':ProductDescription', $ProductDescription);								
		$sth->bindParam(':Images', $Images);								
		$sth->bindParam(':Videos', $Videos);								
		$sth->bindParam(':Tags', $Tags);								
		$sth->bindParam(':SellingPrice', $SellingPrice);								
		$sth->bindParam(':CostPrice', $CostPrice);								
		$sth->bindParam(':CategoryID', $CategoryID);								
		$sth->bindParam(':BrandID', $BrandID);								
		$sth->bindParam(':SizeID', $SizeID);								
		$sth->bindParam(':ManufacturersID', $ManufacturersID);								
		$sth->bindParam(':ColorID', $ColorID);								
		$sth->bindParam(':StatusID', $StatusID);								
		$sth->bindParam(':Quantity', $Quantity);								
		//$sth->bindParam(':ProductCreatedOn', $ProductCreatedOn);								
		//$sth->bindParam(':ProductCreatedBy', $ProductCreatedBy);								
		//$sth->bindParam(':ProductUpdatedOn', $ProductUpdatedOn);								
		$sth->bindParam(':ProductUpdatedBy', $ProductUpdatedBy);								
		$sth->bindParam(':ProductIsActive', $ProductIsActive);
        $sth->bindParam(':ProductID', $ProductID);
		$sth->bindParam(':ProductBardCode', $ProductBardCode);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $ProductID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/products/delete/', function() use($app) {
 
    $allPostVars = $app->request->post();
	$ProductID=$allPostVars['ProductID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("Delete From products 
            WHERE ProductID = :ProductID");
 
        $sth->bindParam(':ProductID', $ProductID);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $ProductID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

//Author : Vaibhav JD

$app->get('/products/details(/)',function() use($app){

	try{
		$app = \Slim\Slim::getInstance();
		$db = getDB();

		$all_get_requests = $app->request()->get();
		$fields = array();
		$response = array();
		$max_result = 2;
		$is_active = 1;
		$offset = 0;

		$search_by = $all_get_requests['searchBy'];

		$select_qry = "SELECT products.ProductID,products.CompanyID,products.ProductTitle,products.ProductDescription,products.Images,products.Videos,products.Tags,products.SellingPrice,products.CostPrice,products.Quantity";
		$select_qry1 = ",productcolors.ColorName,productcategory.CategoryName,productbrands.BrandName,productbrands.BrandLogo,productsizes.SizeName,productmanufacturer.ManufacturerName,productmanufacturer.ManufacturerLogo,productcolors.ColorName,productcolors.ColorCode,productcolors.ColorID,company_shop.ShopName ";
		$from_qry = "FROM products products,productcategory productcategory,productbrands productbrands,productsizes productsizes,productmanufacturer productmanufacturer,productcolors productcolors, company_shop company_shop ";

		//optional parameters
		if(isset($all_get_requests['maxResult']))
			$max_result = $all_get_requests['maxResult'];
		if(isset($all_get_requests['fields']))
			$fields = explode(",",$all_get_requests['fields']);
		if(isset($all_get_requests['range']))
			$range = $all_get_requests['range'];
		if(isset($all_get_requests['active']))
			$is_active = $all_get_requests['active'];
		if(isset($all_get_requests['offset']))
			$offset = $all_get_requests['offset'];
		if(isset($all_get_requests['shopID']))
			$shop_id = $all_get_requests['shopID'];
		if(isset($all_get_requests['productIDs']))
			$product_ids = explode(",", $all_get_requests['productIDs']);
		if(isset($all_get_requests['beaconIDs']))
			$beacon_ids = explode(",", $all_get_requests['beaconIDs']);
		if(isset($all_get_requests['latitude']))
			$latitude = $all_get_requests['latitude'];
		if(isset($all_get_requests['longitude']))
			$longitude = $all_get_requests['longitude'];


		if($search_by == "shopID"){

			$where_qry = "WHERE products.ShopID=:ShopID AND products.CategoryID=productcategory.CategoryID AND products.BrandID=productbrands.BrandID AND products.SizeID=productsizes.SizeID AND products.ManufacturersID=productmanufacturer.ManufacturerID  AND products.ColorID=productcolors.ColorID AND products.ShopID=company_shop.ShopID LIMIT ".$max_result." OFFSET ".$offset.";";

			$sth = $db->prepare($select_qry.$select_qry1.$from_qry.$where_qry);
			$sth->bindParam(':ShopID',$shop_id);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $row) {
				$data['ProductID'] = $row['ProductID'];
				foreach ($fields as $field) 
					$data[$field] = $row[$field];
				array_push($response, $data);
    		}
		}

		else if($search_by == "lat/lng"){
			$geo_field = ",( 6371 * acos( cos( radians(company_shop.ShopLatitude) ) * cos( radians(".$latitude.") ) * cos( radians(".$longitude.") - radians(company_shop.ShopLongitude) ) + sin( radians(company_shop.ShopLatitude) ) * sin( radians(".$latitude.") ) ) ) AS distance ";
			$where_qry = "WHERE products.ShopID=company_shop.ShopID AND products.CategoryID=productcategory.CategoryID AND products.BrandID=productbrands.BrandID AND products.SizeID=productsizes.SizeID AND products.ManufacturersID=productmanufacturer.ManufacturerID  AND products.ColorID=productcolors.ColorID HAVING distance < ".$range."  ORDER BY distance LIMIT ".$max_result." OFFSET ".$offset.";";
			$sth = $db->prepare($select_qry.$select_qry1.$geo_field.$from_qry.$where_qry);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $row) {
				$data['ProductID'] = $row['ProductID'];
				foreach ($fields as $field) 
					$data[$field] = $row[$field];
				array_push($response, $data);
    		}

		}

		else if($search_by == "productIDs"){
			foreach ($product_ids as $product_id) {

				$where_qry = "WHERE products.ProductID=:ProductID AND products.CategoryID=productcategory.CategoryID AND products.BrandID=productbrands.BrandID AND products.SizeID=productsizes.SizeID AND products.ManufacturersID=productmanufacturer.ManufacturerID  AND products.ColorID=productcolors.ColorID AND products.ShopID=company_shop.ShopID LIMIT ".$max_result." OFFSET ".$offset.";";

				$sth = $db->prepare($select_qry.$select_qry1.$from_qry.$where_qry);
				$sth->bindParam(':ProductID',$product_id);
				$sth->execute();
				$result = $sth->fetchAll(PDO::FETCH_ASSOC);
				foreach($result as $row) {
					$data['ProductID'] = $row['ProductID'];
					foreach ($fields as $field) 
						$data[$field] = $row[$field];
					array_push($response, $data);
    			}		
			}
		}
		
		else if($search_by == "beaconIDs"){
			foreach ($beacon_ids as $beacon_id) {
				$from_qry_additional = ",broadcasttable broadcasttable "; 
				$where_qry = "WHERE broadcasttable.BeaconID=:BeaconID AND broadcasttable.ProductID=products.ProductID AND products.CategoryID=productcategory.CategoryID AND products.BrandID=productbrands.BrandID AND products.SizeID=productsizes.SizeID AND products.ManufacturersID=productmanufacturer.ManufacturerID  AND products.ColorID=productcolors.ColorID AND products.ShopID=company_shop.ShopID LIMIT ".$max_result." OFFSET ".$offset.";";
				$sth = $db->prepare($select_qry.$select_qry1.$from_qry.$from_qry_additional.$where_qry);
				$sth->bindParam(':BeaconID',$beacon_id);
				$sth->execute();
				$result = $sth->fetchAll(PDO::FETCH_ASSOC);
				foreach($result as $row) {
					$data['ProductID'] = $row['ProductID'];
					foreach ($fields as $field) 
						$data[$field] = $row[$field];
					array_push($response, $data);
    			}		
			}

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




$app->get('/products/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    { 	$db = getDB();
 
		$Query="SELECT * FROM products_view order by ProductCreatedOn DESC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
		$sth = $db->prepare($Query);
        $sth->execute();
 
        $Product = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Product) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Product));
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

$app->get('/products/get/count(/)', function () {
 
    $app = \Slim\Slim::getInstance();
    try 
    { 	$db = getDB();
 
		$Query="SELECT ProductID FROM products";
		
		$sth = $db->prepare($Query);
        $sth->execute();
		$Product = $sth->rowCount();
       // $Product = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Product) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Product));
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

$app->get('/products/get/bycompany/:companyID(/:pageno(/:pagelimit))', function ($companyID,$pageno=0,$pagelimit=20) {
 
    try 
    {
		$app = \Slim\Slim::getInstance();
		
		$Query="SELECT * from products_view Where CompanyID=".$companyID." order by ProductCreatedOn DESC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		 }
		$db = getDB();
 
        $sth = $db->prepare($Query);
		$sth->execute();
        $products = $sth->fetchAll(PDO::FETCH_OBJ);

        if($products) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $products));
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


$app->get('/products/get/byshop/:shopID(/:pageno(/:pagelimit))', function ($shopID,$pageno=0,$pagelimit=20) {
 
    try 
    {
		$app = \Slim\Slim::getInstance();
		
		$Query="SELECT * from products_view Where ShopID=".$shopID." order by ProductTitle ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		 }
		$db = getDB();
 
        $sth = $db->prepare($Query);
		$sth->execute();
        $products = $sth->fetchAll(PDO::FETCH_OBJ);

        if($products) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $products));
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
$app->get('/products/get/datatable(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    { 	$db = getDB();
 
		$Query="SELECT * FROM products order by ProductCreatedOn DESC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
		$sth = $db->prepare($Query);
        $sth->execute();
 
        $Product = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Product) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Product));
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
$app->post('/products/add(/)', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();
		$lastInsertId=0;
		//$ProductID=$allPostVars['ProductID'];
		$CompanyID=$allPostVars['CompanyID'];
		$ShopID=$allPostVars['ShopID'];
		$ProductTitle=$allPostVars['ProductTitle'];
		$ProductDescription=$allPostVars['ProductDescription'];
		//$Images=$allPostVars['Images'];
		$Videos=$allPostVars['Videos'];
		//$Tags=$allPostVars['Tags'];
		$SellingPrice=$allPostVars['SellingPrice'];
		$CostPrice=$allPostVars['CostPrice'];
		$CategoryID=$allPostVars['CategoryID'];
		$BrandID=$allPostVars['BrandID'];
		$SizeID=$allPostVars['SizeID'];
		$ManufacturersID=$allPostVars['ManufacturersID'];
		$ColorID=$allPostVars['ColorID'];
		$StatusID=$allPostVars['StatusID'];
		$Quantity=$allPostVars['Quantity'];
		$ProductCreatedBy=$allPostVars['ProductCreatedBy'];
		//$ProductUpdatedBy=$allPostVars['ProductUpdatedBy'];
		$ProductIsActive=$allPostVars['ProductIsActive'];
		$ProductBarCode=$allPostVars['ProductBarCode'];
		$ProductUrl=$allPostVars['ProductUrl'];
		$sth = $db->prepare("SELECT * 
            FROM products
            WHERE ProductUrl = :ProductUrl");
 
        $sth->bindParam(':ProductUrl', $ProductUrl);
		$sth->execute();
       $product = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($product) {
		  $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 0,"message"=> "Product URL already exists"));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO products(CompanyID,ShopID,ProductTitle,ProductDescription,Videos,SellingPrice,CostPrice,StatusID,Quantity,ProductCreatedBy,ProductIsActive,ProductBarCode,ProductUrl) VALUES ( :CompanyID,:ShopID,:ProductTitle,:ProductDescription,:Videos,:SellingPrice,:CostPrice,:StatusID,:Quantity,:ProductCreatedBy,:ProductIsActive,:ProductBarCode,:ProductUrl)");
			

       //$sth->bindParam(':ProductID', $ProductID);
		$sth->bindParam(':CompanyID', $CompanyID);							
		$sth->bindParam(':ShopID', $ShopID);								
		$sth->bindParam(':ProductTitle', $ProductTitle);								
		$sth->bindParam(':ProductDescription', $ProductDescription);								
		$sth->bindParam(':Videos', $Videos);															
		$sth->bindParam(':SellingPrice', $SellingPrice);								
		$sth->bindParam(':CostPrice', $CostPrice);								
		$sth->bindParam(':StatusID', $StatusID);								
		$sth->bindParam(':Quantity', $Quantity);														
		$sth->bindParam(':ProductCreatedBy', $ProductCreatedBy);								
		$sth->bindParam(':ProductIsActive', $ProductIsActive);
		$sth->bindParam(':ProductBarCode', $ProductBarCode);
		$sth->bindParam(':ProductUrl', $ProductUrl);
        $sth->execute();
		$lastInsertId = $db->lastInsertId();
		$strImage="0";
		$output_dir = "public/product-images/".$lastInsertId."/";
			if (!is_dir($output_dir)) {
			mkdir($output_dir, 0777, true);       
			}
			$imgs = array();
		if(isset($_FILES['Image1']) && !empty($_FILE["Image1"]["name"])){
			$files = $_FILES['Image1'];
		$ImageName= str_replace(' ','-',strtolower($files['name']));        
            $ImageExt= substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt= str_replace('.','',$ImageExt);
			$name = 'img'.mt_rand_str(3, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').''.'.'.$ImageExt ;
            if (move_uploaded_file($files['tmp_name'], $output_dir . $name) === true) {
                $imgs[] = array("status" => "success", "code" => 1, 'url' => $output_dir . $name);
				$strImage.=$name.",";
            }
		}
		if(isset($_FILES['Image2']) && !empty($_FILE["Image2"]["name"])){
			$files = $_FILES['Image2'];
		$ImageName= str_replace(' ','-',strtolower($files['name']));        
            $ImageExt= substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt= str_replace('.','',$ImageExt);
			$name = 'img'.mt_rand_str(3, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').''.'.'.$ImageExt ;
            if (move_uploaded_file($files['tmp_name'], $output_dir . $name) === true) {
                $imgs[] = array("status" => "success", "code" => 1, 'url' => $output_dir . $name);
				$strImage.=$name.",";
            }
		}
		if(isset($_FILES['Image3']) && !empty($_FILE["Image3"]["name"])){
			$files = $_FILES['Image3'];
		$ImageName= str_replace(' ','-',strtolower($files['name']));        
            $ImageExt= substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt= str_replace('.','',$ImageExt);
			$name = 'img'.mt_rand_str(3, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').''.'.'.$ImageExt ;
            if (move_uploaded_file($files['tmp_name'], $output_dir . $name) === true) {
                $imgs[] = array("status" => "success", "code" => 1, 'url' => $output_dir . $name);
				$strImage.=$name.",";
            }
		}
		
		if(is_array($imgs) && count($imgs) > 1){
		$strImage = rtrim($strImage, ",");
		 $sth = $db->prepare("UPDATE products 
            SET Images= :Images Where ProductID=:ProductID");
		$sth->bindParam(':Images', $strImage);
		$sth->bindParam(':ProductID', $lastInsertId);
		$sth->execute();
		}
		//Manufacturer
		$isManufList=0;
		$SqlManufInsert="INSERT INTO productmanufacturermap (ManufacturerID,ProductID) VALUES ";
		for ($i = 0; $i < count($ManufacturersID); ++$i) {
        $mid= $ManufacturersID[$i];
		if($i == 0){
		$SqlManufInsert.="(".$mid.",".$lastInsertId.")";
		$isManufList=1;
		}
		else{
		$SqlManufInsert.=",(".$mid.",".$lastInsertId.")";
		$isManufList=1;
		}
		}
		if($isManufList==1){
		$sth = $db->prepare($SqlManufInsert);
		$sth->execute();
		}
		//Category
		$isCatList=0;
		$SqlCatInsert="INSERT INTO productcategorymap (CategoryID,ProductID) VALUES ";
		for ($i = 0; $i < count($CategoryID); ++$i) {
        $cid= $CategoryID[$i];
		if($i == 0){
		$SqlCatInsert.="(".$cid.",".$lastInsertId.")";
		$isCatList=1;
		}
		else{
		$SqlCatInsert.=",(".$cid.",".$lastInsertId.")";
		$isCatList=1;
		}
		}
		if($isCatList==1){
		$sth = $db->prepare($SqlCatInsert);
		$sth->execute();
		}
		
		//Color
		$isColorList=0;
		$SqlColorInsert="INSERT INTO productcolormap (ColorID,ProductID) VALUES ";
		for ($i = 0; $i < count($ColorID); ++$i) {
        $colorid= $ColorID[$i];
		if($i == 0){
		$SqlColorInsert.="(".$colorid.",".$lastInsertId.")";
		$isColorList=1;
		}
		else{
		$SqlColorInsert.=",(".$colorid.",".$lastInsertId.")";
		$isColorList=1;
		}
		}
		if($isColorList==1){
		$sth = $db->prepare($SqlColorInsert);
		$sth->execute();
		}
		
		//Size
		$isSizeList=0;
		$SqlSizeInsert="INSERT INTO productsizemap (SizeID,ProductID) VALUES ";
		for ($i = 0; $i < count($SizeID); ++$i) {
        $sizeid= $SizeID[$i];
		if($i == 0){
		$SqlSizeInsert.="(".$sizeid.",".$lastInsertId.")";
		$isSizeList=1;
		}
		else{
		$SqlSizeInsert.=",(".$sizeid.",".$lastInsertId.")";
		$isSizeList=1;
		}
		}
		if($isSizeList==1){
		$sth = $db->prepare($SqlSizeInsert);
		$sth->execute();
		}
		
		
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); 
		$app->response()->headers->set('Content-Type', 'application/json');
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted Successfully!","id"=> $lastInsertId,"img"=>$strImage));
		}
 
   } catch(PDOException $e) {
       $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		if($lastInsertId>0)
		{
		 $sth = $db->prepare("Delete From products 
            WHERE ProductID = :ProductID");
        $sth->bindParam(':ProductID', $lastInsertId);
        $sth->execute();
		}
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}

});
$app->post('/products/update(/)', function() use($app) {
 
    $allPostVars = $app->request->post();
    $ProductID=$allPostVars['ProductID'];
		$CompanyID=$allPostVars['CompanyID'];
		$ShopID=$allPostVars['ShopID'];
		$ProductTitle=$allPostVars['ProductTitle'];
		$ProductDescription=$allPostVars['ProductDescription'];
		$Images=$allPostVars['Images'];
		$Videos=$allPostVars['Videos'];
		$Tags=$allPostVars['Tags'];
		$SellingPrice=$allPostVars['SellingPrice'];
		$CostPrice=$allPostVars['CostPrice'];
		$CategoryID=$allPostVars['CategoryID'];
		$BrandID=$allPostVars['BrandID'];
		$SizeID=$allPostVars['SizeID'];
		$ManufacturersID=$allPostVars['ManufacturersID'];
		$ColorID=$allPostVars['ColorID'];
		$StatusID=$allPostVars['StatusID'];
		$Quantity=$allPostVars['Quantity'];
		//$ProductCreatedBy=$allPostVars['ProductCreatedBy'];
		$ProductUpdatedBy=$allPostVars['ProductUpdatedBy'];
		$ProductIsActive=$allPostVars['ProductIsActive'];
		$ProductBardCode=$allPostVars['ProductBardCode'];
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("UPDATE products 
            SET CompanyID= :CompanyID, ShopID= :ShopID, ProductTitle= :ProductTitle, ProductDescription= :ProductDescription, Images= :Images, Videos= :Videos, Tags= :Tags, SellingPrice= :SellingPrice, CostPrice= :CostPrice, CategoryID= :CategoryID, BrandID= :BrandID, SizeID= :SizeID, ManufacturersID= :ManufacturersID, ColorID= :ColorID, StatusID= :StatusID, Quantity= :Quantity,   ProductUpdatedBy= :ProductUpdatedBy, ProductIsActive= :ProductIsActive,ProductBardCode=:ProductBardCode
            WHERE ProductID = :ProductID");
 
       $sth->bindParam(':CompanyID	', $CompanyID);							
		$sth->bindParam(':ShopID', $ShopID);								
		$sth->bindParam(':ProductTitle', $ProductTitle);								
		$sth->bindParam(':ProductDescription', $ProductDescription);								
		$sth->bindParam(':Images', $Images);								
		$sth->bindParam(':Videos', $Videos);								
		$sth->bindParam(':Tags', $Tags);								
		$sth->bindParam(':SellingPrice', $SellingPrice);								
		$sth->bindParam(':CostPrice', $CostPrice);								
		$sth->bindParam(':CategoryID', $CategoryID);								
		$sth->bindParam(':BrandID', $BrandID);								
		$sth->bindParam(':SizeID', $SizeID);								
		$sth->bindParam(':ManufacturersID', $ManufacturersID);								
		$sth->bindParam(':ColorID', $ColorID);								
		$sth->bindParam(':StatusID', $StatusID);								
		$sth->bindParam(':Quantity', $Quantity);								
		//$sth->bindParam(':ProductCreatedOn', $ProductCreatedOn);								
		//$sth->bindParam(':ProductCreatedBy', $ProductCreatedBy);								
		//$sth->bindParam(':ProductUpdatedOn', $ProductUpdatedOn);								
		$sth->bindParam(':ProductUpdatedBy', $ProductUpdatedBy);								
		$sth->bindParam(':ProductIsActive', $ProductIsActive);
        $sth->bindParam(':ProductID', $ProductID);
		$sth->bindParam(':ProductBardCode', $ProductBardCode);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $ProductID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});

$app->post('/products/delete(/)', function() use($app) {
 
    $allPostVars = $app->request->post();
	$ProductID=$allPostVars['ProductID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("Delete From products 
            WHERE ProductID = :ProductID");
 
        $sth->bindParam(':ProductID', $ProductID);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $ProductID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});
?>