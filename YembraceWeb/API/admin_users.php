<?php
$app->post('/admin_users/validate/', function() use($app) {
    try 
    {
	    $allPostVars = $app->request->post();
		$AdminEmail = $allPostVars['AdminEmail'];
		$AdminPassword = $allPostVars['AdminPassword'];
		$AdminPassword=md5($AdminPassword);
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM admin_users
            WHERE AdminEmail = :AdminEmail And AdminPassword = :AdminPassword");
 
        $sth->bindParam(':AdminEmail', $AdminEmail);
        $sth->bindParam(':AdminPassword', $AdminPassword);
		 $sth->execute();
       $admin_users = $sth->fetchAll(PDO::FETCH_OBJ);
  
        if($admin_users) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $admin_users));
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

$app->post('/admin_users/forget(/)', function () {
    try 
    {
        $db = getDB();
		$allPostVars = $app->request->post();
		$AdminEmail=$allPostVars['AdminEmail'];
		$pass= mt_rand_str(7, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd');
		$AdminPassword = md5($pass);
        $sth = $db->prepare("SELECT * 
            FROM admin_users
            WHERE AdminEmail = :AdminEmail");
 
        $sth->bindParam(':AdminEmail', $AdminEmail);
        $sth->execute();
 
        $user = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($user) {
		$sql = "UPDATE admin_users SET  AdminPassword = :AdminPassword  WHERE AdminEmail = :AdminEmail ";
		 $sth = $db->prepare($sql);
			  $sth->bindParam(':AdminEmail', $AdminEmail);
			  $sth->bindParam(':AdminPassword', $AdminPassword);
			$resultUpdate = $sth->execute();
			if($resultUpdate){
			$from ="care@spacingo.com";
			$subject="Spacingo.com : Your password has been reset!";
			$message="Your password has been reset to :".$pass."";
			send_mail($from,"",$message,$subject,$AdminEmail);
			  $app->response->setStatus(200);
            $app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
             echo json_encode(array("status" => "success", "code" => 1,"message"=> "Password updated and sent to the email id."));
			}else{
			  $app->response->setStatus(200);
            $app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
             echo json_encode(array("status" => "error", "code" => 0,"message"=> "Password not updated, try again!"));
			}
          
          
        } else {
             $app->response->setStatus(200);
            $app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "error", "code" => 0,"message"=> "Email not found!"));
           
        }
  $db = null;
    } catch(PDOException $e) {
        $app->response()->setStatus(404);
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
});

$app->post('/admin_users/lastaccess/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$AdminUserID = $allPostVars['AdminUserID'];
	
		$db = getDB();
		$sth = $db->prepare("UPDATE admin_users SET AdminLastAccess=now() WHERE AdminUserID = :AdminUserID");

		$sth->bindParam(':AdminUserID', $AdminUserID);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","AdminUserID"=> $AdminUserID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->get('/admin_users/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM admin_users
            WHERE AdminUserID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $admin_users = $sth->fetchAll(PDO::FETCH_OBJ);
		if($admin_users) { 
			$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $admin_users));
   
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

$app->get('/admin_users/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT au.*,ar.AdminRoleName FROM admin_users au, admin_role ar where ar.AdminRoleID=au.AdminRoleID";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		 }
 
        $sth = $db->prepare($Query);
		$sth->execute();
        $admin_users = $sth->fetchAll(PDO::FETCH_OBJ);

        if($admin_users) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $admin_users));
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

$app->post('/admin_users/add/', function() use($app) {
	try 
    {	
		$db = getDB();
		$allPostVars = $app->request->post();
		$AdminUserName = $allPostVars['iAdminUserName'];
		$AdminEmail = $allPostVars['iAdminEmail'];
		$AdminPassword =md5($allPostVars['iAdminPassword']);
		$AdminMobile = $allPostVars['iAdminMobile'];
		$AdminRoleID = $allPostVars['iAdminRoleID'];
		$AdminProfilePic = "";
		$output_dir = "public/AdminProfilePic/";
		if (!is_dir($output_dir)) {
			mkdir($output_dir, 0777, true);       
		}
		$imgs = array();

		$qry="SELECT * FROM admin_users WHERE AdminEmail='".$AdminEmail."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $admin_users = $sth->fetchAll(PDO::FETCH_OBJ);
		
		if($admin_users){
		$app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"admin_users name already exits"));
		}else{		
		
		$imgs = array();
		 if(isset($_FILES['AdminProfilePic'])){
			$files = $_FILES['AdminProfilePic'];
			$ImageName= str_replace(' ','-',strtolower($files['name']));        
            $ImageExt= substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt= str_replace('.','',$ImageExt);
			$name = 'img'.mt_rand_str(3, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').''.'.'.$ImageExt ;
			$AdminProfilePic=$name;
            if (move_uploaded_file($files['tmp_name'], $output_dir . $name) === true) {
                $imgs[] = array("status" => "success", "code" => 1, 'url' => $output_dir . $name);
            }
		}
		
		$sth = $db->prepare("INSERT INTO admin_users (AdminUserName,AdminEmail,AdminPassword,AdminMobile,AdminRoleID,AdminProfilePic) VALUES (:AdminUserName,:AdminEmail,:AdminPassword,:AdminMobile,:AdminRoleID,:AdminProfilePic)");
		$sth->bindParam(':AdminUserName', $AdminUserName);
		$sth->bindParam(':AdminEmail', $AdminEmail);
		$sth->bindParam(':AdminPassword', $AdminPassword);
		$sth->bindParam(':AdminMobile', $AdminMobile);
		$sth->bindParam(':AdminRoleID', $AdminRoleID);
		$sth->bindParam(':AdminProfilePic', $AdminProfilePic);
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

$app->post('/admin_users/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$AdminUserID = $allPostVars['iAdminUserID'];
		$AdminUserName = $allPostVars['iAdminUserName'];
		$AdminEmail = $allPostVars['iAdminEmail'];
		$isPassword=0;
		$isPic=0;
		$QueryPassword="";
		$AdminPassword="";
		if(isset($allPostVars['AdminPassword']) && !empty($allPostVars['AdminPassword'])){
		$AdminPassword = $allPostVars['AdminPassword'];
		$AdminPassword=md5($AdminPassword);
		$isPassword=1;
		$QueryPassword=" AdminPassword = :AdminPassword,";
		}
		//$AdminPassword = $allPostVars['iAdminPassword'];
		$AdminMobile = $allPostVars['iAdminMobile'];
		$AdminRoleID = $allPostVars['iAdminRoleID'];
		$AdminProfilePic = "";
		$output_dir = "public/AdminProfilePic/";
		if (!is_dir($output_dir)) {
			mkdir($output_dir, 0777, true);       
		}
		$imgs = array();
		
        $db = getDB();
		$imgs = array();
		 if(isset($_FILES['AdminProfilePic'])){
			$files = $_FILES['AdminProfilePic'];
			$ImageName= str_replace(' ','-',strtolower($files['name']));        
            $ImageExt= substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt= str_replace('.','',$ImageExt);
			$name = 'img'.mt_rand_str(3, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').''.'.'.$ImageExt ;
			$AdminProfilePic=$name;
			$isPic=1;
            if (move_uploaded_file($files['tmp_name'], $output_dir . $name) === true) {
                $imgs[] = array("status" => "success", "code" => 1, 'url' => $output_dir . $name);
            }
		}
		$QueryProfile="";
		
		if($isPic==1){
		$QueryProfile="AdminProfilePic=:AdminProfilePic";
		}
		
		$sth = $db->prepare("UPDATE admin_users SET AdminUserName=:AdminUserName, AdminEmail=:AdminEmail,".$QueryPassword." AdminMobile=:AdminMobile, AdminRoleID=:AdminRoleID,".$QueryProfile." WHERE AdminUserID = :AdminUserID");
		
		$sth->bindParam(':AdminUserID', $AdminUserID);
		$sth->bindParam(':AdminUserName', $AdminUserName);
		$sth->bindParam(':AdminEmail', $AdminEmail);
		if($isPassword==1){
			$sth->bindParam(':AdminPassword', $AdminPassword);
		}
		$sth->bindParam(':AdminMobile', $AdminMobile);
		$sth->bindParam(':AdminRoleID', $AdminRoleID);
		if($isPic==1)
		{
			$sth->bindParam(':AdminProfilePic', $AdminProfilePic);
		}

		$sth->execute();

		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","AdminUserID"=> $AdminUserID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/admin_users/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$AdminUserID=$allPostVars['AdminUserID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From admin_users WHERE AdminUserID = :AdminUserID");
 
        $sth->bindParam(':AdminUserID', $AdminUserID);
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
?>