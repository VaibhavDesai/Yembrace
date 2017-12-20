
<?php

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
	
//This is the logic for generating push notifications.
$app->post('/notifications/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$UserID=$allPostVars['PublicUserID'];
		$OfferID=$allPostVars['OfferID'];
		$ProductID=$allPostVars['ProductID'];
		$FormID = $allPostVars['FromID'];

		
		$response = payLoad($UserID);
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=>$response));
	}
	catch(Exception $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
});

	
	function payLoad($UserID){
	try 
    {
		$db = getDB();
		$qry="SELECT * FROM users WHERE PublicUserID=:PublicUserID limit 1";
		$sth = $db->prepare($qry);
		$sth->bindParam(':PublicUserID', $UserID);
		$sth->execute();
        $users = $sth->fetchAll(PDO::FETCH_ASSOC);
        if($sth->rowCount() == 1){
	        $to = $users[0]['GCMRegistrationToken'];
	        $data  = array('score'=> "5x1",'time'=> "15:10");
	        $payload = array('data'=>$data,'to'=>$to);
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
		}
		$result = "No Record";
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
