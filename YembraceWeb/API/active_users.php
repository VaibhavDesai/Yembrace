<?php

//get apis
$app->get('/users/active/get(/)',function() use($app){

	try{
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$all_get_requests = $app->request()->get();
		$fields = array();
		$response = array();
		if(isset($all_get_requests['shopID'])){
		$shop_id = $all_get_requests['shopID'];
		}else{
		$shop_id=0;
		}
		$max_result = 10;
		$query ="";
		$range =3;

		$select_fields = "SELECT users.Email,users.UserID,users.FullName,users.ProfilePic,users.AboutMe,users.Mobile1,users.GooglePlusProfileURL,users.FacebookProfileURL,users.TwitterProfileURL,users.LinkedInProfileURL,users.RegisteredOn,users.PublicUserID,users.UserDevice,users.LoginPlatform,city.CityName,country.CountryName,states.StateName,activeusers.*";
		
		$geo_field = ",( 6371 * acos( cos( radians(company_shop.ShopLatitude) ) * cos( radians( activeusers.Latitude ) ) * cos( radians( activeusers.Longitude ) - radians(company_shop.ShopLongitude) ) + sin( radians(company_shop.ShopLatitude) ) * sin( radians( activeusers.Latitude ) ) ) ) AS distance ";
		
		
		$from_fields = " FROM users users,city city,country country,states states,activeusers activeusers,company_shop company_shop ";

		//optional parameters
		if(isset($all_get_requests['maxResult']))
			$max_result = $all_get_requests['maxResult'];
		if (isset($all_get_requests['beaconIDs']))
			$beacon_ids = explode(",",$all_get_requests['beaconIDs']);
		if(isset($all_get_requests['fields']))
			$fields = explode(",",$all_get_requests['fields']);
		if(isset($all_get_requests['range']))
			$range = $all_get_requests['range'];


		if(!isset($all_get_requests['beaconIDs'])){
			$where_fields = "WHERE activeusers.ShopID=:ShopID AND activeusers.PublicUserID=users.PublicUserID AND users.CityID=city.CityID AND users.StateID=states.StateID AND users.CountryID=country.CountryID AND activeusers.ShopID=company_shop.ShopID HAVING distance < ".$range."  ORDER BY Distance LIMIT ".$max_result." OFFSET ".$offset.";";
			$query = $select_fields.$geo_field.$from_fields.$where_fields;
			$sth = $db->prepare($query);
			$sth->bindParam(':ShopID',$shop_id);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $row) {
				$data['PublicUserID'] = $row['PublicUserID'];
				$data['FullName'] = $row['FullName'];
				$data['Email'] = $row['Email'];
				$data['UserDevice'] = $row['UserDevice'];
				$data['ProfilePic'] = $row['ProfilePic'];
				$data['Latitude'] = $row['Latitude'];
				$data['Longitude'] = $row['Longitude'];
				$data['Distance'] = $row['Distance'];
				foreach ($fields as $field) 
					$data[$field] = $row[$field];
				array_push($response, $data);
    		}
		}
		else{	
			foreach ($beacon_ids as $beacon_id) {

				$where_fields = "WHERE activeusers.ShopID=:ShopID AND activeusers.PublicUserID=users.PublicUserID AND users.CityID=city.CityID AND users.StateID=states.StateID AND users.CountryID=country.CountryID AND activeusers.BeaconPrimaryKeyID=:BeaconPrimaryKeyID AND activeusers.ShopID=company_shop.ShopID HAVING distance < ".$range."  ORDER BY distance LIMIT ".$max_result." OFFSET ".$offset.";";	
				$query = $select_fields.$geo_field.$from_fields.$where_fields;
				$sth = $db->prepare($query);
				$sth->bindParam(':ShopID',$shop_id);
				$sth->bindParam(':BeaconPrimaryKeyID',$beacon_id);
				$sth->execute();
				$result = $sth->fetchAll(PDO::FETCH_ASSOC);
				foreach($result as $row) {
					$data['PublicUserID'] = $row['PublicUserID'];
					$data['FullName'] = $row['FullName'];
					$data['Email'] = $row['Email'];
					$data['UserDevice'] = $row['UserDevice'];
					$data['ProfilePic'] = $row['ProfilePic'];
					$data['Latitude'] = $row['Latitude'];
					$data['Longitude'] = $row['Longitude'];
					$data['Distance'] = $row['Distance'];
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


//Create apis
$app->post('/users/active/add/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();

		$public_user_id = $allPostVars['PublicUserID'];
	    $connection_type = $allPostVars['ConnectionType'];
		$user_device = $allPostVars['UserDevice'];
	    
	    if (isset($allPostVars['BeaconUUID']))
	    	$beacon_uuid = $allPostVars['BeaconUUID'];
	    if (isset($allPostVars['Latitude']))
			$user_latitude = $allPostVars['Latitude'];
		if (isset($allPostVars['Longitude']))
			$user_longitude = $allPostVars['Longitude'];

		if($connection_type == "Beacon"){

			$source_type_qry = "SELECT beaconscompanymap.ShopID FROM beaconscompanymap beaconscompanymap,beaconmaster beaconmaster WHERE beaconmaster.BeaconUUID=:BeaconUUID AND beaconmaster.BeaconPrimaryKeyID=beaconscompanymap.BeaconPrimaryKeyID;";
			$sth = $db->prepare($source_type_qry);
			$sth->bindParam(':BeaconUUID',$beacon_uuid);
			$sth->execute();
			$shop_id_result = $sth->fetchAll(PDO::FETCH_OBJ);
	        $shop_id = $shop_id_result[0]->ShopID;

	        $shop_location_qry = "SELECT ShopLatitude,ShopLongitude FROM company_shop WHERE ShopID=:ShopID;";
			$sth = $db->prepare($shop_location_qry);
			$sth->bindParam(':ShopID',$shop_id);
			$sth->execute();
			$shop_location_result = $sth->fetchAll(PDO::FETCH_OBJ);
	        $shop_lat = $shop_location_result[0]->ShopLatitude;
	        $shop_lng = $shop_location_result[0]->ShopLongitude;


			$get_beacon_id = "SELECT BeaconPrimaryKeyID FROM beaconmaster WHERE BeaconUUID=:BeaconUUID;";
			$sth = $db->prepare($get_beacon_id);
			$sth->bindParam(':BeaconUUID',$beacon_uuid);
			$sth->execute();
			$beacon_id_result = $sth->fetchAll(PDO::FETCH_OBJ);
	        $beacon_primary_key_id = $beacon_id_result[0]->BeaconPrimaryKeyID;
	        	
	        	
	        $insert_qry = "INSERT INTO activeusers (PublicUserID,BeaconPrimaryKeyID,ConnectionType,ShopID,UserDevice,Latitude,Longitude) VALUES (:PublicUserID,:BeaconPrimaryKeyID,:ConnectionType,:ShopID,:UserDevice,:Latitude,:Longitude) ON DUPLICATE KEY UPDATE UpdateTime=CURRENT_TIMESTAMP;";
		    $sth = $db->prepare($insert_qry);
		    $sth->bindParam(':PublicUserID', $public_user_id);
		    $sth->bindParam(':BeaconPrimaryKeyID', $beacon_primary_key_id);
		    $sth->bindParam(':ConnectionType', $connection_type);
		   	$sth->bindParam(':ShopID',$shop_id);
		    $sth->bindParam(':UserDevice',$user_device);
		    $sth->bindParam(':Latitude',$shop_lat);
		    $sth->bindParam(':Longitude',$shop_lng);
		    $sth->execute();
		}

		if($connection_type == "GPS"){
			$insert_qry = "INSERT INTO activeusers (PublicUserID,ConnectionType,Latitude,Longitude,UserDevice) VALUES (:PublicUserID,:ConnectionType,:Latitude,:Longitude,:UserDevice) ON DUPLICATE KEY UPDATE UpdateTime=CURRENT_TIMESTAMP; AND Latitude=:Latitude,Longitude=:Longitude";
	        $sth = $db->prepare($insert_qry);
	        $sth->bindParam(':PublicUserID', $public_user_id);
			$sth->bindParam(':ConnectionType', $connection_type);
		    $sth->bindParam(':Latitude',$user_latitude);
		    $sth->bindParam(':Longitude',$user_longitude);
		    $sth->bindParam(':UserDevice',$user_device);
		    $sth->execute();
		}

		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully"));

    } catch(Exception $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});
?>