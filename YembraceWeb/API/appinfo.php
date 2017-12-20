<?php
$app->get('/app_info/get/:platform',function($Platform) use($app){
	try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$qry = "SELECT * FROM app_info WHERE Platform=:Platform ORDER BY AddedOn DESC LIMIT 1";
		$sth = $db->prepare($qry);
		$sth->bindParam(':Platform',$Platform);
        $sth->execute();
        $app_info = $sth->fetchAll(PDO::FETCH_OBJ);
 
		if($app_info) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $app_info));
   
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

$app->get('/app_info/getbyid/:id',function($id) use($app){
	try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$qry = "SELECT * FROM app_info WHERE AppID=:id";
		$sth = $db->prepare($qry);
		$sth->bindParam(':id',$id);
        $sth->execute();
        $app_info = $sth->fetchAll(PDO::FETCH_OBJ);
 
		if($app_info) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $app_info));
   
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

$app->get('/app_info/get(/)',function() use($app){
	try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
		$qry = "SELECT * FROM app_info ORDER BY Version ASC";
		$sth = $db->prepare($qry);
        $sth->execute();
        $app_info = $sth->fetchAll(PDO::FETCH_OBJ);
 
		if($app_info) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $app_info));
   
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

$app->post('/app_info/add(/)', function() use($app) {
	try 
    {
    	$app = \Slim\Slim::getInstance();
		$db = getDB();
		
		$allPostVars = $app->request->post();
		$Version = $allPostVars['Version'];
		$Platform = $allPostVars['Platform'];
		$TermsAndConditions = $allPostVars['TermsAndConditions'];
		$PrivacyPolicy = $allPostVars['PrivacyPolicy'];
		$AppPath = $allPostVars['AppPath'];
		$HostedUrl = $allPostVars['HostedUrl'];
		$WebsiteUrl = $allPostVars['WebsiteUrl'];
		$FacebookUrl = $allPostVars['FacebookUrl'];
		$GoogleUrl = $allPostVars['GoogleUrl'];
		$TwitterUrl = $allPostVars['TwitterUrl'];
		$EmailID = $allPostVars['EmailID'];
		

		$qry = "INSERT INTO app_info (Version,Platform,TermsAndConditions,PrivacyPolicy,AppPath,HostedUrl,WebsiteUrl,FacebookUrl,GoogleUrl,TwitterUrl,EmailID) VALUES(:Version,:Platform,:TermsAndConditions,:PrivacyPolicy,:AppPath,:HostedUrl,:WebsiteUrl,:FacebookUrl,:GoogleUrl,:TwitterUrl,:EmailID);";
		$sth = $db->prepare($qry);
		
		$sth->bindParam(':Version', $Version);
		$sth->bindParam(':Platform', $Platform);
		$sth->bindParam(':TermsAndConditions', $TermsAndConditions);
		$sth->bindParam(':PrivacyPolicy', $PrivacyPolicy);
		$sth->bindParam(':AppPath', $AppPath);	
		$sth->bindParam(':HostedUrl', $HostedUrl);
		$sth->bindParam(':WebsiteUrl', $WebsiteUrl);
		$sth->bindParam(':FacebookUrl', $FacebookUrl);
		$sth->bindParam(':GoogleUrl', $GoogleUrl);
		$sth->bindParam(':TwitterUrl', $TwitterUrl);
		$sth->bindParam(':EmailID', $EmailID);
		$sth->execute();
		$lastInsertedID = $db->lastInsertID();

		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","AppID"=> $lastInsertedID));
	}
	catch(Exception $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
});

$app->post('/app_info/update(/)', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$AppID = $allPostVars['AppID'];
		$Version = $allPostVars['Version'];
		$Platform = $allPostVars['Platform'];
		$TermsAndConditions = $allPostVars['TermsAndConditions'];
		$PrivacyPolicy = $allPostVars['PrivacyPolicy'];
		$AppPath = $allPostVars['AppPath'];
		$HostedUrl = $allPostVars['HostedUrl'];
		$WebsiteUrl = $allPostVars['WebsiteUrl'];
		$FacebookUrl = $allPostVars['FacebookUrl'];
		$GoogleUrl = $allPostVars['GoogleUrl'];
		$TwitterUrl = $allPostVars['TwitterUrl'];
		$EmailID = $allPostVars['EmailID'];
		
        $db = getDB();
		$sth = $db->prepare("UPDATE app_info SET Version=:Version, Platform=:Platform,TermsAndConditions=:TermsAndConditions,PrivacyPolicy=:PrivacyPolicy, AppPath=:AppPath,HostedUrl=:HostedUrl,WebsiteUrl=:WebsiteUrl,FacebookUrl=:FacebookUrl,GoogleUrl=:GoogleUrl,TwitterUrl=:TwitterUrl,EmailID=:EmailID WHERE AppID = :AppID");
		
		$sth->bindParam(':AppID', $AppID);
		$sth->bindParam(':Version', $Version);
		$sth->bindParam(':Platform', $Platform);
		$sth->bindParam(':TermsAndConditions', $TermsAndConditions);
		$sth->bindParam(':PrivacyPolicy', $PrivacyPolicy);
		$sth->bindParam(':AppPath', $AppPath);	
		$sth->bindParam(':HostedUrl', $HostedUrl);
		$sth->bindParam(':WebsiteUrl', $WebsiteUrl);
		$sth->bindParam(':FacebookUrl', $FacebookUrl);
		$sth->bindParam(':GoogleUrl', $GoogleUrl);
		$sth->bindParam(':TwitterUrl', $TwitterUrl);
		$sth->bindParam(':EmailID', $EmailID);
		$sth->execute();

		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","AppID"=> $AppID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/app_info/delete(/)', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$AppID=$allPostVars['AppID'];
		
        $db = getDB();
        $sth = $db->prepare("DELETE FROM app_info WHERE AppID = :AppID");
 
        $sth->bindParam(':AppID', $AppID);
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