<?php 
$app->get('/users/notifications/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM notifications
            WHERE NotificationID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $notifications = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($notifications) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $notifications));
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

$app->get('/users/notifications/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    
    try 
    {
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT * FROM notifications order by NotificationCreatedOn DESC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
		$sth = $db->prepare($Query);
 
        $sth->execute();
 
        $notifications = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($notifications) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $notifications));
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
$app->post('/users/notifications/add(/)', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();
		//$NotificationID=$allPostVars['NotificationID'];
		$BroadcastID=$allPostVars['BroadcastID'];
		$Summary=$allPostVars['Summary'];
		//$NotificationCreatedOn=$allPostVars['NotificationCreatedOn'];
		$ShopID=$allPostVars['ShopID'];
		$PublicUserID=$allPostVars['PublicUserID'];
		$OfferID=$allPostVars['OfferID'];
		$ProductID=$allPostVars['ProductID'];
		$Seen=0;
		$FormID=$allPostVars['FormID'];
		$ContentTypeID=$allPostVars['ContentTypeID'];

		$sth = $db->prepare("INSERT INTO notifications(BroadcastID,Summary,ShopID,PublicUserID,OfferID,ProductID,Seen,FormID,ContentTypeID ) VALUES (:BroadcastID,:Summary,:ShopID,:PublicUserID,:OfferID,:ProductID,:Seen,:FormID,:ContentTypeID)");
		//$sth->bindParam(':NotificationID', $NotificationID);
		$sth->bindParam(':BroadcastID', $BroadcastID);
		$sth->bindParam(':Summary', $Summary);
		//$sth->bindParam(':NotificationCreatedOn', $NotificationCreatedOn);
		$sth->bindParam(':ShopID', $ShopID);
		$sth->bindParam(':PublicUserID', $PublicUserID);
		$sth->bindParam(':OfferID', $OfferID);
		$sth->bindParam(':ProductID', $ProductID);
		$sth->bindParam(':Seen', $Seen);
		$sth->bindParam(':FormID', $FormID);
		$sth->bindParam(':ContentTypeID', $ContentTypeID);

        $sth->execute();
		$lastInsertId = $db->lastInsertId();
		$data ="No notification";
		switch($ContentTypeID){
		case "1": $data =sendNotificationToUser($ContentType,$PublicUserID,$NotificationID,$ProductID);//Product
		break;
		case "2": $data =sendNotificationToUser($ContentType,$PublicUserID,$NotificationID,$OfferID);//Offer
		break;
		case "3": $data =sendNotificationToUser($ContentType,$PublicUserID,$NotificationID,$Summary);//Summary
		break;
		case "4": $data =sendNotificationToUser($ContentType,$PublicUserID,$NotificationID,$FormID);//Form
		break;
		case "5": //Video
		break;
		}
		payLoad($PublicUserID,$data);
		
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted Successfully!","id"=> $lastInsertId));
		
 
   } catch(PDOException $e) {
       $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}

});
$app->post('/users/notifications/seen(/)', function() use($app) {
try 
    {
        $db = getDB();
		$allPostVars = $app->request->post();
		$NotificationID=$allPostVars['NotificationID'];
		$PublicUserID=$allPostVars['PublicUserID'];
		$Seen=$allPostVars['Seen'];
		$sth = $db->prepare("Update notifications Set Seen=:Seen
            WHERE NotificationID = :NotificationID");
		//$sth->bindParam(':PublicUserID', $PublicUserID);
		$sth->bindParam(':Seen', $Seen);
		$sth->bindParam(':NotificationID', $NotificationID);
		
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $NotificationID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/users/notifications/delete(/)', function() use($app) {
 
    $allPostVars = $app->request->post();
	$NotificationID=$allPostVars['NotificationID'];
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("delete from notifications 
            WHERE NotificationID = :NotificationID");
 
        $sth->bindParam(':NotificationID', $NotificationID);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $ManufacturerID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});

function sendNotificationToUser($ContentType,$PublicUserID,$NotificationID,$RecordID){
$apiPublicUrl="";
$Query = "Select * from notifications where NotificationID=".$NotificationID."";
 $db = getDB();
 $isSend=0;
switch($ContentTypeID){
		case "1": 
		//Product
		$Query="select ProductID as UID,ProductTitle as Title,SUBSTRING_INDEX(SUBSTRING_INDEX(Images, ',', 1), ' ', -1)  as Images,Videos from products_view where ProductID=".$RecordID.""; 
        $sth = $db->prepare($Query);
		$sth->execute();
		$products = $sth->fetchAll(PDO::FETCH_OBJ);
		if($products){
		 $isSend=1;
		 $products[0]['ContentType']=$ContentType;
		 if($products[0]['Images']==0){
		 $products[0]['Images']=$apiPublicUrl."product-images/noimage.png";
		 }else{
		 
		 $products[0]['Images']=$apiPublicUrl."product-images/"+$products[0]['ProductID']."/".$products[0]['Images'];
		 }
		 return $products;
		}
     
		
		break;
		case "2": 
		//Offer
		$Query="select date(DATE_ADD(OfferCreatedOn, Interval ExpireTime Day)) as ExpireDate, OfferID as UID,OfferCode,ProductID,OfferTitle as Title,SUBSTRING_INDEX(SUBSTRING_INDEX(Images, ',', 1), ' ', -1)  as Images,Videos from offers_view where OfferID=".$RecordID.""; 
        $sth = $db->prepare($Query);
		$sth->execute();
		$offers = $sth->fetchAll(PDO::FETCH_OBJ);
		if($offers){
		 $isSend=1;
		 $offers[0]['ContentType']=$ContentType;
		 if($offers[0]['Images']==0){
		 $offers[0]['Images']=$apiPublicUrl."offers-images/noimage.png";
		 }else{
		 $offers[0]['Images']=$apiPublicUrl."offers-images/"+$offers[0]['OfferID']."/".$offers[0]['Images'];
		 }
		 return $offers;
		}
		
		
		break;
		case "3": 
		//Summary
		$Query = "Select NotificationID as UID,Summary as Title,NotificationCreatedOn from notifications where NotificationID=".$NotificationID."";
		 
		$sth = $db->prepare($Query);
		$sth->execute();
		$summary = $sth->fetchAll(PDO::FETCH_OBJ);
		$summary[0]['ContentType']=$ContentType;
		return $summary;
		break;
		case "4": 
		
		//Form
		$Query = "Select FormID as UID,FormName as Title,FormDescription from formstable where FormID=".$RecordID.""; 
		$sth = $db->prepare($Query);
		$sth->execute();
		$form = $sth->fetchAll(PDO::FETCH_OBJ);
		if($form){
		$form[0]['ContentType']=$ContentType;
		return $form;
		}
		break;
		case "5": //Video
		break;
		return "No Notification";
		}
		
}


$app->get('/notifications/mobile',function() use($app){

	try{
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$broadcastID_qry = "SELECT BroadcastID,NotificationText from notificationstable WHERE PublicUserID=:PublicUserID AND Status=:Status";
		$sth = $db->prepare($qry);
        $sth->bindParam(':PublicUserID',$PublicUserID);
        
        $sth->bindParam(':Status',$Status);
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach($data as $row) {
        	

    	}

		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=>$data));

	}catch(Exception $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }

});

function payLoad($UserID,$Data){
		try 
    {
		$db = getDB();
		$qry="SELECT * FROM users WHERE PublicUserID=:PublicUserID limit 1";
		$sth = $db->prepare($qry);
		$sth->bindParam(':PublicUserID', $UserID);
		$sth->execute();
        $users = $sth->fetch(PDO::FETCH_OBJ);
        $to = $users['GCMRegistrationToken'];
        //$data  = array('score'=> "5x1",'time'=> "15:10");
        $payload = array('data'=>$Data,'to'=>$to);
        $payload_json = json_encode($payload);
        var_dump($payload_json);
       
        $ch = curl_init('https://gcm-http.googleapis.com/gcm/send');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    		'Content-Type: application/json',
    		'Authorization: key=AIzaSyCLPBau29Z9pkzv9RLtC11x9M3ydAEzjJ8'));
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		
		//execute post
		$result = curl_exec($ch);
		
		var_dump($result);
		//close connection
		curl_close($ch);
		return $result;
	}
	catch(Exception $e) {
		return $e->getMessage();
    }
	finally {
		 $db = null;
	}
}

?>