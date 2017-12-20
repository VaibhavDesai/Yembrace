<?php 
$app->get('/products/offers/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM offers_view
            WHERE OfferID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $Color = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($Color) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Color));
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
$app->get('/products/offers/get/byshop/:shopId(/)(/:pageno(/:pagelimit))', function ($shopId,$pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    {
		$Query="SELECT * FROM offers_view Where ShopID=".$shopId." order by OfferCreatedOn DESC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
			$db = getDB();
 
        $sth = $db->prepare($Query);
        $sth->execute();
 
        $Color = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Color) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Color));
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
$app->get('/products/offers/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    {
		$Query="SELECT * FROM offers_view order by OfferCreatedOn DESC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
			$db = getDB();
 
        $sth = $db->prepare($Query);
        $sth->execute();
 
        $Color = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Color) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Color));
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
$app->post('/products/offers/add(/)', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();
		$CompanyID=$allPostVars['CompanyID'];
		$ProductID=$allPostVars['ProductID'];
		$ShopID=$allPostVars['ShopID'];
		$OfferTitle=$allPostVars['OfferTitle'];
		$OfferDescription=$allPostVars['OfferDescription'];
		//$Images=$allPostVars['Images'];
		$Videos=$allPostVars['Videos'];
		//$Tags=$allPostVars['Tags'];
		//$CategoryID=$allPostVars['CategoryID'];

		$OfferCreatedBy=$allPostVars['OfferCreatedBy'];
		//$OfferUpdatedOn=$allPostVars['OfferUpdatedOn'];
		//$OfferUpdatedBy=$allPostVars['OfferUpdatedBy'];
		$OfferStatusID=$allPostVars['OfferStatusID'];
		$OfferIsActive=$allPostVars['OfferIsActive'];
		$OfferQuantity=$allPostVars['OfferQuantity'];
		$OfferQuantityUsed="0";
		$DiscountPercentage=$allPostVars['DiscountPercentage'];
		$OfferCode=$allPostVars['OfferCode'];
		$ExpireTime=$allPostVars['ExpireTime'];
		$OfferPublicUrl=$allPostVars['OfferPublicUrl'];
		
      $strImage="0";
		$sth = $db->prepare("SELECT * 
            FROM offerstable
            WHERE OfferCode = :OfferCode Or OfferPublicUrl=:OfferPublicUrl");
 
        $sth->bindParam(':OfferCode', $OfferCode);
		    $sth->bindParam(':OfferPublicUrl', $OfferPublicUrl);
		 $sth->execute();
       $color = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($color) {
		  $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 0,"message"=> "Offer code/url already exists"));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO offerstable(CompanyID,ProductID,ShopID,OfferTitle,OfferDescription,Images,Videos,OfferCreatedBy,OfferStatusID,OfferIsActive,OfferQuantity,OfferQuantityUsed,DiscountPercentage,OfferCode,ExpireTime,OfferPublicUrl) VALUES (:CompanyID,:ProductID,:ShopID,:OfferTitle,:OfferDescription,:Images,:Videos,:OfferCreatedBy,:OfferStatusID,:OfferIsActive,:OfferQuantity,:OfferQuantityUsed,:DiscountPercentage,:OfferCode,:ExpireTime,:OfferPublicUrl);");
		$sth->bindParam(':CompanyID',$CompanyID);
		$sth->bindParam(':ProductID',$ProductID);
		$sth->bindParam(':ShopID',$ShopID);
		$sth->bindParam(':OfferTitle',$OfferTitle);
		$sth->bindParam(':OfferDescription',$OfferDescription);
		$sth->bindParam(':Images',$strImage);
		$sth->bindParam(':Videos',$Videos);
		

		$sth->bindParam(':OfferCreatedBy',$OfferCreatedBy);

		$sth->bindParam(':OfferStatusID',$OfferStatusID);
		$sth->bindParam(':OfferIsActive',$OfferIsActive);
		$sth->bindParam(':OfferQuantity',$OfferQuantity);
		$sth->bindParam(':OfferQuantityUsed',$OfferQuantityUsed);
		$sth->bindParam(':DiscountPercentage',$DiscountPercentage);
		$sth->bindParam(':OfferCode',$OfferCode);
		$sth->bindParam(':ExpireTime',$ExpireTime);
		$sth->bindParam(':OfferPublicUrl',$OfferPublicUrl);
		
        $sth->execute();
		$lastInsertId = $db->lastInsertId();
		
		$output_dir = "public/offers-images/".$lastInsertId."/";
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
		 $sth = $db->prepare("UPDATE offerstable 
            SET Images= :Images Where OfferID=:ProductID");
		$sth->bindParam(':Images', $strImage);
		$sth->bindParam(':OfferID', $lastInsertId);
		$sth->execute();
		}
		//Category
		$isCatList=0;
		$SqlCatInsert="INSERT INTO offercategorymapping (CategoryID,OfferID) VALUES ";
		$i=0;
		foreach ($_POST['CategoryID'] as $cid)
			{
			$isCatList=1;
				if($i == 0){
		$SqlCatInsert.="(".$cid.",".$lastInsertId.")";
		$isCatList=1;
		}
		else{
		$SqlCatInsert.=",(".$cid.",".$lastInsertId.")";
		$isCatList=1;
		}   
		$i++;
			}
/*
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
		}*/
		if($isCatList==1){
		$sth = $db->prepare($SqlCatInsert);
		$sth->execute();
		}
		
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted Successfully!","id"=> $lastInsertId,"CategoryID"=>$_POST['CategoryID']));
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

$app->post('/products/offers/update(/)', function() use($app) {
 
    $allPostVars = $app->request->post();
	$OfferID=$allPostVars['OfferID'];
		$CompanyID=$allPostVars['CompanyID'];
		$ProductID=$allPostVars['ProductID'];
		$ShopID=$allPostVars['ShopID'];
		$OfferTitle=$allPostVars['OfferTitle'];
		$OfferDescription=$allPostVars['OfferDescription'];
		$Images=$allPostVars['Images'];
		$Videos=$allPostVars['Videos'];
		$Tags=$allPostVars['Tags'];

		//$OfferCreatedBy=$allPostVars['OfferCreatedBy'];
		//$OfferUpdatedOn=$allPostVars['OfferUpdatedOn'];
		$OfferUpdatedBy=$allPostVars['OfferUpdatedBy'];
		$OfferStatusID=$allPostVars['OfferStatusID'];
		$OfferIsActive=$allPostVars['OfferIsActive'];
		$OfferQuantity=$allPostVars['OfferQuantity'];
		$OfferQuantityUser=$allPostVars['OfferQuantityUser'];
		$DiscountPercentage=$allPostVars['DiscountPercentage'];
		$OfferCode=$allPostVars['OfferCode'];
		$ExpireTime=$allPostVars['ExpireTime'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("UPDATE offerstable 
            SET CompanyID = :CompanyID,ProductID = :ProductID,ShopID = :ShopID,OfferTitle = :OfferTitle,
			OfferDescription = :OfferDescription,Images = :Images,Videos = :Videos,Tags = :Tags,OfferUpdatedOn = NOW(),OfferUpdatedBy = :OfferUpdatedBy,OfferStatusID = :OfferStatusID,OfferIsActive = :OfferIsActive,OfferQuantity = :OfferQuantity,OfferQuantityUser = :OfferQuantityUser,DiscountPercentage = :DiscountPercentage,OfferCode = :OfferCode,OfferCode = :OfferCode,ExpireTime = :ExpireTime WHERE OfferID =:OfferID");
		$sth->bindParam(':OfferID', $OfferID);
		$sth->bindParam(':CompanyID', $CompanyID);
		$sth->bindParam(':ProductID', $ProductID);
		$sth->bindParam(':ShopID', $ShopID);
		$sth->bindParam(':OfferTitle', $OfferTitle);
		$sth->bindParam(':OfferDescription', $OfferDescription);
		$sth->bindParam(':Images', $Images);
		$sth->bindParam(':Videos', $Videos);
		$sth->bindParam(':Tags', $Tags);
		
		$sth->bindParam(':OfferUpdatedBy', $OfferUpdatedBy);

		$sth->bindParam(':OfferStatusID', $OfferStatusID);
		$sth->bindParam(':OfferIsActive', $OfferIsActive);
		$sth->bindParam(':OfferQuantity', $OfferQuantity);
		$sth->bindParam(':OfferQuantityUser', $OfferQuantityUser);
		$sth->bindParam(':DiscountPercentage', $DiscountPercentage);
		$sth->bindParam(':OfferCode', $OfferCode);
		$sth->bindParam(':ExpireTime', $ExpireTime);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $ColorID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	} 
});

$app->post('/products/offers/delete(/)', function() use($app) {
 
    $allPostVars = $app->request->post();
	$OfferID=$allPostVars['OfferID'];
	$OfferIsActive=$allPostVars['OfferIsActive'];
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("UPDATE offerstable 
            SET OfferIsActive = :OfferIsActive Where OfferID=:OfferID");
 
        $sth->bindParam(':OfferIsActive', $OfferIsActive);
		  $sth->bindParam(':OfferID', $OfferID);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $ColorID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

/*
Author :Vaibhav JD
searchBy can take following values:
shopID,productID,categoryID,lat/lng,beaconID

compulsary parameters needed:
searchBy

default values returned are:
OfferID,OfferTitle,ExpireTime,ShopName,ProductTitle

*/

$app->get('/products/offers/details(/)',function() use($app){
try{
		$app = \Slim\Slim::getInstance();
		$db = getDB();

		$all_get_requests = $app->request()->get();
		$fields = array();
		$response = array();
		$max_result = 10;
		$is_active = 1;
		$offset = 0;
		$range=10;
		$query = "";

		$search_by = $all_get_requests['searchBy'];

		$select_qry = "SELECT OfferID,CompanyID,ProductID,ShopID,OfferTitle,OfferDescription,Images,Videos,Tags,OfferCode,CompanyName,ShopName,OfferUpdatedOn,OfferIsActive,ExpireTime,CompanyName,CompanyLogo,ShopName,ShopLatitude,ShopLongitude,CityName,StateName,CountryName,Currency";
		$select_qry1 = ",ProductTitle,ProductImages,SellingPrice,CategoryID,BrandID,SizeID,ManufacturersID,ColorName,CategoryName,CategoryLogo,BrandName,BrandLogo,SizeName,StatusName";
		$from_qry = " FROM offers_view";

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

			$where_qry = " WHERE ShopID=:ShopID LIMIT ".$max_result." OFFSET ".$offset.";";
			$query = $select_qry.$select_qry1.$from_qry.$where_qry;
			$sth = $db->prepare($query);
			$sth->bindParam(':ShopID',$shop_id);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $row) {
				$data['OfferID'] = $row['OfferID'];
				$data['OfferTitle'] = $row['OfferTitle'];
				$data['ExpireTime'] = $row['ExpireTime'];
				$data['ShopName'] = $row['ShopName'];
				$data['ProductTitle'] = $row['ProductTitle'];
				foreach ($fields as $field) 
					$data[$field] = $row[$field];
				array_push($response, $data);
    		}
		}

		else if($search_by == "lat/lng"){
			$geo_field = ",( 6371 * acos( cos( radians(ShopLatitude) ) * cos( radians(".$latitude.") ) * cos( radians(".$longitude.") - radians(ShopLongitude) ) + sin( radians(ShopLatitude) ) * sin( radians(".$latitude.") ) ) ) AS Distance ";
			$where_qry = " WHERE 1 HAVING distance < ".$range."  ORDER BY distance LIMIT ".$max_result." OFFSET ".$offset.";";
			$query = $select_qry.$select_qry1.$geo_field.$from_qry.$where_qry;
			$sth = $db->prepare($query);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $row) {
				$data['OfferID'] = $row['OfferID'];
				$data['OfferTitle'] = $row['OfferTitle'];
				$data['ExpireTime'] = $row['ExpireTime'];
				$data['ShopName'] = $row['ShopName'];
				$data['ProductTitle'] = $row['ProductTitle'];
				$data['Distance'] = $row['Distance'];
				foreach ($fields as $field) 
					$data[$field] = $row[$field];
				array_push($response, $data);
    		}

		}

		else if($search_by == "productIDs"){
			foreach ($product_ids as $product_id) {

				$where_qry = " WHERE ProductID=:ProductID LIMIT ".$max_result." OFFSET ".$offset.";";
				$query = $select_qry.$select_qry1.$from_qry.$where_qry;
				$sth = $db->prepare($query);
				$sth->bindParam(':ProductID',$product_id);
				$sth->execute();
				$result = $sth->fetchAll(PDO::FETCH_ASSOC);
				foreach($result as $row) {
					$data['OfferID'] = $row['OfferID'];
					$data['OfferTitle'] = $row['OfferTitle'];
					$data['ExpireTime'] = $row['ExpireTime'];
					$data['ShopName'] = $row['ShopName'];
					$data['ProductTitle'] = $row['ProductTitle'];
					foreach ($fields as $field) 
						$data[$field] = $row[$field];
					array_push($response, $data);
    			}		
			}
		}
		
		/*else if($search_by == "beaconIDs"){
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

		}*/

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


$app->get('/offers/get(/)', function() use($app){

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

		if(isset($all_get_requests['categoryID'])){
			$where_qry="WHERE categorytoplevel.CategoryID=:CategoryID AND categorytoplevel.CategoryID=products.CategoryID AND products.ShopID=company_shop.ShopID AND company.CompanyID=company_shop.ShopCompanyID AND offerstable.ProductID=products.ProductID AND offerstable.OfferIsActive=1 AND NOW()<=DATE_ADD(offerstable.OfferCreatedOn,INTERVAL offerstable.ExpireTime DAY) ";
			$query=$select_qry.$from_qry.$where_qry.$limit_qry;
		}

		if(isset($all_get_requests['latitude'])&&isset($all_get_requests['longitude'])){
			$geo_field = ",( 6371 * acos( cos( radians(ShopLatitude) ) * cos( radians(".$latitude.") ) * cos( radians(".$longitude.") - radians(ShopLongitude) ) + sin( radians(ShopLatitude) ) * sin( radians(".$latitude.") ) ) ) AS Distance ";
			$having_clause =" HAVING distance < ".$range."  ORDER BY distance ";
			$query=$select_qry.$geo_field.$from_qry.$where_qry.$having_clause.$limit_qry;
		}
		if(isset($all_get_requests['shopID'])){
			$where_qry="WHERE company_shop.ShopID=:ShopID AND products.ShopID=company_shop.ShopID AND company.CompanyID=company_shop.ShopCompanyID AND offerstable.ProductID=products.ProductID AND offerstable.OfferIsActive=1 AND NOW()<=DATE_ADD(offerstable.OfferCreatedOn,INTERVAL offerstable.ExpireTime DAY) ";
			$query=$select_qry.$from_qry.$where_qry.$limit_qry;
		}
			
		else
			$query=$select_qry.$from_qry.$where_qry.$limit_qry;

		$sth = $db->prepare($query);
		if(isset($all_get_requests['categoryID']))
			$sth->bindParam(':CategoryID',$category_id);
		if(isset($all_get_requests['shopID']))
			$sth->bindParam(':ShopID',$shop_id);
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