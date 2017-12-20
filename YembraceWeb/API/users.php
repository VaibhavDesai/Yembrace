<?php 
$app->get('/users/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM users
            WHERE UserID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $users = $sth->fetchAll(PDO::FETCH_OBJ);
 
		if($users) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $users));
   
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

$app->get('/users/viewbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT u.*,c.CountryName,s.StateName,ct.CityName,cm.CompanyName FROM users u, country c, states s, city ct, company cm WHERE c.CountryID=u.CountryID AND s.StateID=u.StateID AND ct.CityID=u.CityID AND cm.CompanyID=u.CompanyID AND UserID = :id";
        
		$sth = $db->prepare($Query);
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $users = $sth->fetchAll(PDO::FETCH_OBJ);
 
		if($users) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $users));
   
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
$app->get('/users/get/bycompany/:id(/)(/:pageno(/:pagelimit))', function ($id,$pageno=0,$pagelimit=20) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT u.*,c.CountryName,s.StateName,ct.CityName,cm.CompanyName FROM users u, country c, states s, city ct, company cm WHERE c.CountryID=u.CountryID AND s.StateID=u.StateID AND ct.CityID=u.CityID AND cm.CompanyID=u.CompanyID and u.CompanyID=".$id."";
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		 }
		
        $sth = $db->prepare($Query);
		$sth->execute();
        $users = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($users) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $users));
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
$app->get('/users/get/', function () { 
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT u.*,c.CountryName,s.StateName,ct.CityName,cm.CompanyName FROM users u, country c, states s, city ct, company cm WHERE c.CountryID=u.CountryID AND s.StateID=u.StateID AND ct.CityID=u.CityID AND cm.CompanyID=u.CompanyID";
  
        $sth = $db->prepare($Query);
		$sth->execute();
        $users = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($users) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $users));
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

$app->get('/users/dash/get(/)', function () { 
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT * FROM users ORDER BY RegisteredOn DESC LIMIT 5";
  
        $sth = $db->prepare($Query);
		$sth->execute();
        $users = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($users) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $users));
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

$app->get('/users/get/count(/)', function () { 
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT * FROM users";
  
        $sth = $db->prepare($Query);
		$sth->execute();
		$users=$sth->rowCount();
       // $users = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($users) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $users));
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
$app->post('/users/add(/)', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		
		$allPostVars = $app->request->post();
	    $Email = $allPostVars['Email'];
		$Password =md5($allPostVars['Password']);
		$FullName = $allPostVars['FullName'];
		$CityID = $allPostVars['CityID'];
		$StateID = $allPostVars['StateID'];
		$CountryID = $allPostVars['CountryID'];
		//$ProfilePic = $allPostVars['ProfilePic'];
		$AboutMe = $allPostVars['AboutMe'];
		$Gender = $allPostVars['Gender'];
		$Mobile1 = $allPostVars['Mobile1'];
		$Mobile2 = $allPostVars['Mobile2'];
		$CompanyID = $allPostVars['CompanyID'];
		$GooglePlusProfileURL = $allPostVars['GooglePlusProfileURL'];
		$FacebookProfileURL = $allPostVars['FacebookProfileURL'];
		$TwitterProfileURL = $allPostVars['TwitterProfileURL'];
		$LinkedInProfileURL = $allPostVars['LinkedInProfileURL'];
		$UserIsActive = $allPostVars['UserIsActive'];
		$LoginPlatform = $allPostVars['LoginPlatform'];
		$UserDevice = $allPostVars['UserDevice'];

		$qry="SELECT * FROM users WHERE Email='".$Email."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $users = $sth->fetchAll(PDO::FETCH_OBJ);		
		
		if($users){
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"Email already exits"));
		}else{	
		$sth = $db->prepare("INSERT INTO users (Email,Password,FullName,CityID,StateID,CountryID,AboutMe,Gender,Mobile1,Mobile2,CompanyID,GooglePlusProfileURL,FacebookProfileURL,TwitterProfileURL,LinkedInProfileURL,UserIsActive,LoginPlatform,UserDevice) VALUES (:Email,:Password,:FullName,:CityID,:StateID,:CountryID,:AboutMe,:Gender,:Mobile1,:Mobile2,:CompanyID,:GooglePlusProfileURL,:FacebookProfileURL,:TwitterProfileURL,:LinkedInProfileURL,:UserIsActive,:LoginPlatform,:UserDevice)");
		
		$sth->bindParam(':Email', $Email);
		$sth->bindParam(':Password', $Password);
		$sth->bindParam(':FullName', $FullName);
		$sth->bindParam(':CityID', $CityID);
		$sth->bindParam(':StateID', $StateID);
		$sth->bindParam(':CountryID', $CountryID);
		$sth->bindParam(':AboutMe', $AboutMe);
		$sth->bindParam(':Gender', $Gender);
		$sth->bindParam(':Mobile1', $Mobile1);
		$sth->bindParam(':Mobile2', $Mobile2);
		$sth->bindParam(':CompanyID', $CompanyID);
		$sth->bindParam(':GooglePlusProfileURL', $GooglePlusProfileURL);
		$sth->bindParam(':FacebookProfileURL', $FacebookProfileURL);
		$sth->bindParam(':TwitterProfileURL', $TwitterProfileURL);
		$sth->bindParam(':LinkedInProfileURL', $LinkedInProfileURL);
		$sth->bindParam(':UserIsActive', $UserIsActive);
		$sth->bindParam(':LoginPlatform', $LoginPlatform);
		$sth->bindParam(':UserDevice', $UserDevice);

		$sth->execute();
		
		$lastInsertedID = $db->lastInsertID();		
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","UserID"=> $lastInsertedID));
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

$app->post('/users/update(/)', function() use($app) {
    try 
    {
		$allPostVars = $app->request->post();
        $db = getDB();
		$UserID = $allPostVars['UserID'];
		$Email = $allPostVars['Email'];
		//$isProfilePic=0;
		$isPassword=0;
		$QueryPassword="";
		$Password="";
		if(isset($allPostVars['Password']) && !empty($allPostVars['Password'])){
		$Password = $allPostVars['Password'];
		$Password=md5($Password);
		$isPassword=1;
		$QueryPassword=" Password = :Password,";
		}
		$FullName = $allPostVars['FullName'];
		$CityID = $allPostVars['CityID'];
		$StateID = $allPostVars['StateID'];
		$CountryID = $allPostVars['CountryID'];	
		$AboutMe=$allPostVars['AboutMe'];
		$Gender=$allPostVars['Gender'];
		$Mobile1 = $allPostVars['Mobile1'];
		$Mobile2 = $allPostVars['Mobile2'];
		$CompanyID = $allPostVars['CompanyID'];
		$GooglePlusProfileURL=  $allPostVars['GooglePlusProfileURL'];
		$FacebookProfileURL=$allPostVars['FacebookProfileURL'];
		$TwitterProfileURL=$allPostVars['TwitterProfileURL'];
		$LinkedInProfileURL=$allPostVars['LinkedInProfileURL'];
		$UserIsActive=$allPostVars['UserIsActive'];
		$LoginPlatform=$allPostVars['LoginPlatform'];
		$UserDevice=$allPostVars['UserDevice'];
		/*$ProfilePic="";
		$output_dir = "public/profile/";
			if (!is_dir($output_dir)) {
			mkdir($output_dir, 0777, true);       
			}
			$imgs = array();
	
		
		 if(!isset( $_FILES["ProfilePic"] ) && empty( $_FILES["ProfilePic"]["name"]) ) {
			
				
			}else{
			$files = $_FILES['ProfilePic'];
			$name= mt_rand_str(3, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').basename($_FILES["ProfilePic"]["name"]);
			$target_file = $output_dir . $name;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" ) {
					$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); 
			$app->response()->headers->set('Content-Type', 'application/json');
			echo json_encode(array("status" => "error", "code" => 0,"message"=> "File type not allowed!"));
				}else{
				if (move_uploaded_file($_FILES["ProfilePic"]["tmp_name"], $target_file)=== true) {
					$imgs[] = array("status" => "success", "code" => 1, 'url' => $target_file);
					$ProfilePic=$name;
					$isProfilePic=1;
				} else {
				$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
			echo json_encode(array("status" => "error", "code" => 0,"message"=> "Sorry, there was an error uploading your file."));
				}
				
				}
				/*
			$ImageName= str_replace(' ','-',strtolower($files['tmp_name']));        
            $ImageExt= substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt= str_replace('.','',$ImageExt);
			$name = 'img'.mt_rand_str(5, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').''.'.'.$ImageExt ;
			$ProfilePic=$name;
            if (move_uploaded_file($files['tmp_name'], $output_dir . $name) === true) {
                $imgs[] = array("status" => "success", "code" => 1, 'url' => $output_dir . $name);
            }
		}
		$QueryProfilePic="";		
		if($isProfilePic==1){
		$QueryProfilePic=" ProfilePic=:ProfilePic,";
		}*/
		
		$sth = $db->prepare("UPDATE users SET Email=:Email,".$QueryPassword." FullName =:FullName,CityID =:CityID,StateID =:StateID,CountryID =:CountryID,".$QueryProfilePic." AboutMe=:AboutMe,Gender=:Gender,Mobile1 =:Mobile1,Mobile2 =:Mobile2,CompanyID=:CompanyID,GooglePlusProfileURL=:GooglePlusProfileURL,FacebookProfileURL=:FacebookProfileURL,TwitterProfileURL=:TwitterProfileURL,LinkedInProfileURL=:LinkedInProfileURL,UserIsActive=:UserIsActive,LoginPlatform=:LoginPlatform,UserDevice=:UserDevice WHERE UserID=:UserID");
		$sth->bindParam(':UserID', $UserID);
        $sth->bindParam(':Email', $Email);
        if($isPassword==1){
			$sth->bindParam(':Password', $Password);
		}
		$sth->bindParam(':FullName', $FullName);
        $sth->bindParam(':CityID', $CityID);
		$sth->bindParam(':StateID', $StateID);
        $sth->bindParam(':CountryID', $CountryID);
		/*if($isProfilePic==1){
		$sth->bindParam(':ProfilePic', $ProfilePic);
		}*/
        $sth->bindParam(':AboutMe', $AboutMe);
        $sth->bindParam(':Gender', $Gender);
		$sth->bindParam(':Mobile1', $Mobile1);
        $sth->bindParam(':Mobile2', $Mobile2);
		$sth->bindParam(':CompanyID', $CompanyID);
		$sth->bindParam(':GooglePlusProfileURL', $GooglePlusProfileURL);
		$sth->bindParam(':FacebookProfileURL', $FacebookProfileURL);
		$sth->bindParam(':TwitterProfileURL', $TwitterProfileURL);
		$sth->bindParam(':LinkedInProfileURL', $LinkedInProfileURL);
		$sth->bindParam(':UserIsActive', $UserIsActive);
		$sth->bindParam(':LoginPlatform', $LoginPlatform);
		$sth->bindParam(':UserDevice', $UserDevice);
        $sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully!","UserID"=> $UserID));
   } catch(PDOException $e) {
       $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
});

$app->post('/users/delete(/)', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$UserID=$allPostVars['UserID'];
		$UserIsActive=$allPostVars['UserIsActive'];
		
        $db = getDB();
        $sth = $db->prepare("UPDATE users SET UserIsActive=:UserIsActive
            WHERE UserID = :UserID");
 
        $sth->bindParam(':UserID', $UserID);
		$sth->bindParam(':UserIsActive', $UserIsActive);
        $sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Action successfull"));
		
    } catch(PDOException $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		$db = null;
	} 
});


//Author : Vaibhav JD

$app->get('/users/mobile/get/:shopID/:PublicUserID',function($shop_id,$public_user_id) use($app){

	try{
		$app = \Slim\Slim::getInstance();
		$db = getDB();

		$response = array();

		$select_qry = "SELECT users.Email,users.FullName,users.ProfilePic,users.AboutMe,users.Gender,users.RegisteredOn,states.StateName,country.CountryName ";
		$where_qry = "WHERE users.PublicUserID=:PublicUserID AND users.StateID=states.StateID AND users.CountryID=country.CountryID ";
		$from_qry = "FROM users users,states states,country country ";

		$sth = $db->prepare($select_qry.$from_qry.$where_qry);
		$sth->bindParam(':PublicUserID',$public_user_id);
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		$data = $result[0];
		$response['PersonalInfo'] = array('Email'=>$data['Email'],
			'FullName'=>$data['FullName'],
			'ProfilePic'=>$data['ProfilePic'],
			'AboutMe' => $data['AboutMe'],
			'Gender' => $data['Gender'],
			'RegisteredOn' => date('d/m/Y',strtotime($data['RegisteredOn'])),
			'StateName' => $data['StateName'],
			'CountryName' => $data['CountryName']);

    	$response['Reviews'] = array();
		$review_qry = "SELECT reviews.ReviewType,reviews.ReviewTitle,reviews.ReviewDescription,reviews.ReviewWrittenOn,reviews.ReviewRating FROM reviews reviews WHERE reviews.ReviewShopID=:ShopID";
		$sth = $db->prepare($review_qry);
		$sth->bindParam(':ShopID',$shop_id);
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$data1['ReviewType'] = $row['ReviewType'];
			$data1['ReviewTitle'] = $row['ReviewTitle'];
			$data1['ReviewDescription'] = $row['ReviewDescription'];
			$data1['ReviewWrittenOn'] = date('d/m/Y',strtotime($row['ReviewWrittenOn']));
			$data1['ReviewRating'] = $row['ReviewRating'];
			array_push($response['Reviews'], $data1);
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

$app->post('/users/mobile/add/', function() use($app) {
	try {
    	$app = \Slim\Slim::getInstance();
		$db = getDB();
		
		$allPostVars = $app->request->post();
	    
		$Password = mt_rand_str(8);
		$FullName = $allPostVars['FullName'];
		$ProfilePic = $allPostVars['ProfilePic'];
		$Email = $allPostVars['Email'];
		$LoginPlatform = $allPostVars['LoginPlatform'];
		$GooglePlusProfileURL = null;
		$FacebookProfileURL = null;
		if($LoginPlatform == "google")
			$GooglePlusProfileURL = $allPostVars['ProfileUrl'];
		if($LoginPlatform == "facebook")
			$FacebookProfileURL = $allPostVars['ProfileUrl'];

		$UserIsActive = intval($allPostVars['UserIsActive']);
		$UserDevice = $allPostVars['UserDevice'];
		$PublicUserID = uniqid();
	
		
		$qry="SELECT * FROM users WHERE Email='".$Email."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $users = $sth->fetchAll(PDO::FETCH_ASSOC);
		$rowCount = $sth->rowCount();
		if($rowCount == 1){
		$UserID = $users[0]['PublicUserID'];
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		
		$sth = $db->prepare("UPDATE users SET UserIsActive=:UserIsActive,LoginPlatform=:LoginPlatform,UserDevice=:UserDevice WHERE PublicUserID=:PublicUserID");

		$sth->bindParam(':UserIsActive',$UserIsActive);
		$sth->bindParam(':LoginPlatform',$LoginPlatform);
		$sth->bindParam(':UserDevice',$UserDevice);
		$sth->bindParam(':PublicUserID',$UserID);

		$sth->execute();

		echo json_encode(array("status" => "success", "code" => 1,"message" => "ReLogin","UserID" => $UserID));

		}else{	
		$sth = $db->prepare("INSERT INTO users (Email,Password,FullName,ProfilePic,Gender,GooglePlusProfileURL,FacebookProfileURL,UserIsActive,LoginPlatform,UserDevice,PublicUserID) VALUES (:Email,:Password,:FullName,:ProfilePic,:Gender,:GooglePlusProfileURL,:FacebookProfileURL,:UserIsActive,:LoginPlatform,:UserDevice,:PublicUserID)");
		
		$sth->bindParam(':PublicUserID',$PublicUserID);
		$sth->bindParam(':Email', $Email);
		$sth->bindParam(':Password', $Password);
		$sth->bindParam(':FullName', $FullName);
		$sth->bindParam(':ProfilePic', $ProfilePic);
		$sth->bindParam(':Gender', $Gender);
		$sth->bindParam(':GooglePlusProfileURL',$GooglePlusProfileURL);
		$sth->bindParam(':FacebookProfileURL',$FacebookProfileURL);
		$sth->bindParam(':UserIsActive', $UserIsActive);
		$sth->bindParam(':LoginPlatform', $LoginPlatform);
		$sth->bindParam(':UserDevice', $UserDevice);

		$sth->execute();
		
		$lastInsertedID = $PublicUserID;		
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","UserID"=> $lastInsertedID));
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

$app->post('/users/mobile/GCMRegister/',function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
			
		$allPostVars = $app->request->post();
		$PublicUserID = $allPostVars['PublicUserID'];
		$GCMToken = $allPostVars['GCMRegistrationToken'];

		$sth = $db->prepare("UPDATE users SET GCMRegistrationToken=:GCMRegistrationToken WHERE PublicUserID=:PublicUserID");
 
        $sth->bindParam(':PublicUserID', $PublicUserID);
		$sth->bindParam(':GCMRegistrationToken', $GCMToken);
        $sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Action successfull"));

	} catch(Exception $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/users/mobile/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$UserID=$allPostVars['PublicUserID'];
		$UserIsActive=$allPostVars['UserIsActive'];
		
        $db = getDB();
        $sth = $db->prepare("UPDATE users SET UserIsActive=:UserIsActive WHERE PublicUserID=:PublicUserID");
 
        $sth->bindParam(':PublicUserID', $UserID);
		$sth->bindParam(':UserIsActive', $UserIsActive);
        $sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Action successfull"));
		
    } catch(PDOException $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		$db = null;
	} 
});

$app->post('/users/Auth/',function() use($app){
	try{

		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$company_name=$allPostVars['CompanyName'];
		$staff_username=$allPostVars['StaffUsername'];
		$staff_password=$allPostVars['StaffPassword'];
		
        $db = getDB();
        $sth = $db->prepare("SELECT shop_staffs.ShopStaffID,shop_staffs.StaffShopID FROM shop_staffs shop_staffs,company company WHERE shop_staffs.StaffUsername=:StaffUsername AND shop_staffs.StaffPassword=:StaffPassword AND company.CompanyName=:CompanyName AND shop_staffs.StaffCompanyID=company.CompanyID");
        $sth->bindParam(':StaffUsername', $staff_username);
		$sth->bindParam(':StaffPassword', $staff_password);
		$sth->bindParam(':CompanyName', $company_name);
        $result = $sth->execute();

        $app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
        $response = array();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row) {
       		$data['ShopStaffID'] = $row['ShopStaffID'];
        	$data['StaffShopID'] = $row['StaffShopID'];
        	array_push($response, $data);
        }
        if (!empty($response)) 
        	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Auth successfull","document"=>$response));	
        else
        	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Auth failed"));
	}
	catch(PDOException $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		$db = null;
	} 
})

?>