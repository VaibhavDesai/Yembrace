<?php

$app->post('/broadcast/add(/)', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();
		//$BroadcastID=$allPostVars['BroadcastID'];
		$BeaconID=$allPostVars['BeaconID'];
		$RuleID=$allPostVars['RuleID'];
		$TriggerTypeID=$allPostVars['TriggerTypeID'];
		$ContentTypeID=$allPostVars['ContentTypeID'];
		$ProductID=$allPostVars['ProductID'];
		if($ProductID=="none"){
		$ProductID=0;
		}
		$OfferID=$allPostVars['OfferID'];
		if($OfferID=="none"){
		$OfferID=0;
		}
		$FormID=$allPostVars['FormID'];
		if($FormID=="none"){
		$FormID=0;
		}
		$TextAndImage=$allPostVars['TextAndImage'];
		$TextAndVideo=$allPostVars['TextAndVideo'];
		$Image1="0";
		$Image2="0";
		$Image3="0";
		$CompanyID=$allPostVars['CompanyID'];
		$ShopID=$allPostVars['ShopID'];
		$ParticularUserID=$allPostVars['ParticularUserID'];
		$BroadcastCreatedBy=$allPostVars['BroadcastCreatedBy'];
		$BroadcastIsActive=$allPostVars['BroadcastIsActive'];
		$BroadcastPublicKey=uniqid();

		$output_dir = "public/broadcast-image/".$BroadcastPublicKey."/";
		if (!is_dir($output_dir)) {
			mkdir($output_dir, 0777, true);       
			}
	$imgs = array();
		/*$sth = $db->prepare("SELECT * 
            FROM productcategory
            WHERE CategoryCompanyID = :CategoryCompanyID And CategoryName=:CategoryName");
 
        $sth->bindParam(':CategoryCompanyID', $CategoryCompanyID);
		$sth->bindParam(':CategoryName', $CategoryName);
		 $sth->execute();
       $Category = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($Category) {
		  $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 0,"message"=> "Category name already exists"));
		 }
		 else{*/
		 $imgs = array();
		 if(isset($_FILES['Image1']) && !empty($_FILE["Image1"]["name"])){
			$files = $_FILES['Image1'];
		$ImageName= str_replace(' ','-',strtolower($files['name']));        
            $ImageExt= substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt= str_replace('.','',$ImageExt);
			$name = 'img'.mt_rand_str(3, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').''.'.'.$ImageExt ;
			$Image1=$name;
            if (move_uploaded_file($files['tmp_name'], $output_dir . $name) === true) {
                $imgs[] = array("status" => "success", "code" => 1, 'url' => $output_dir . $name);
            }
		}
		if(isset($_FILES['Image2']) && !empty($_FILE["Image2"]["name"])){
			$files = $_FILES['Image2'];
		$ImageName= str_replace(' ','-',strtolower($files['name']));        
            $ImageExt= substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt= str_replace('.','',$ImageExt);
			$name2 = 'img'.mt_rand_str(3, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').''.'.'.$ImageExt ;
			$Image2=$name2;
            if (move_uploaded_file($files['tmp_name'], $output_dir . $name) === true) {
                $imgs[] = array("status" => "success", "code" => 1, 'url' => $output_dir . $name);
            }
		}
		if(isset($_FILES['Image3']) && !empty($_FILE["Image3"]["name"])){
			$files = $_FILES['Image3'];
			$ImageName= str_replace(' ','-',strtolower($files['name']));        
            $ImageExt= substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt= str_replace('.','',$ImageExt);
			$name3 = 'img'.mt_rand_str(3, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').''.'.'.$ImageExt ;
			$Image3=$name3;
            if (move_uploaded_file($files['tmp_name'], $output_dir . $name) === true) {
                $imgs[] = array("status" => "success", "code" => 1, 'url' => $output_dir . $name);
            }
		}

		$sth = $db->prepare("INSERT INTO Broadcasttable( BeaconID ,TriggerTypeID ,ContentTypeID ,ProductID ,OfferID ,FormID ,TextAndImage ,TextAndVideo ,Image1 ,Image2 ,Image3 ,CompanyID ,ShopID ,ParticularUserID ,BroadcastCreatedBy ,BroadcastIsActive,RuleID,BroadcastPublicKey ) VALUES(:BeaconID,:TriggerTypeID,:ContentTypeID,:ProductID,:OfferID,:FormID,:TextAndImage,:TextAndVideo,:Image1,:Image2,:Image3,:CompanyID,:ShopID,:ParticularUserID,:BroadcastCreatedBy,:BroadcastIsActive,:RuleID,:BroadcastPublicKey )");
//$sth->bindParam('BroadcastID',$BroadcastID);
		$sth->bindParam('BeaconID',$BeaconID);
		$sth->bindParam('TriggerTypeID',$TriggerTypeID);
		$sth->bindParam('ContentTypeID',$ContentTypeID);
		$sth->bindParam('ProductID',$ProductID);
		$sth->bindParam('OfferID',$OfferID);
		$sth->bindParam('FormID',$FormID);
		$sth->bindParam('TextAndImage',$TextAndImage);
		$sth->bindParam('TextAndVideo',$TextAndVideo);
		$sth->bindParam('Image1',$Image1);
		$sth->bindParam('Image2',$Image2);
		$sth->bindParam('Image3',$Image3);
		$sth->bindParam('CompanyID',$CompanyID);
		$sth->bindParam('ShopID',$ShopID);
		$sth->bindParam('ParticularUserID',$ParticularUserID);
		$sth->bindParam('BroadcastCreatedBy',$BroadcastCreatedBy);
		$sth->bindParam('BroadcastIsActive',$BroadcastIsActive);
		$sth->bindParam('RuleID',$RuleID);
		$sth->bindParam('BroadcastPublicKey',$BroadcastPublicKey);
        $sth->execute();
		$lastInsertId = $db->lastInsertId();
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
        echo json_encode(array("status" => "success", "code" => 1,"summary"=>$TextAndImage,"message"=> "Inserted Successfully!","id"=> $lastInsertId));
		
 
   } catch(PDOException $e) {
       $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}

});


$app->get('/broadcast/get/:shopID(/:pageno(/:pagelimit))', function ($shopID,$pageno=0,$pagelimit=20) {
 
    try 
    {
		$app = \Slim\Slim::getInstance();
		
		$Query="SELECT * from broadcast_public Where ShopID=".$shopID." order by BroadcastCreatedOn DESC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		 }
		$db = getDB();
 
        $sth = $db->prepare($Query);
		$sth->execute();
        $broadcast = $sth->fetchAll(PDO::FETCH_OBJ);

        if($broadcast) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $broadcast));
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

$app->post('/broadcast/delete(/)', function() use($app) {
 
    $allPostVars = $app->request->post();
	$BroadcastID=$allPostVars['BroadcastID'];
	$BroadcastIsActive=$allPostVars['BroadcastIsActive'];
 
    try 
    {
        $db = getDB();
 
		$sth = $db->prepare("Update Broadcasttable Set BroadcastIsActive=:BroadcastIsActive
            WHERE BroadcastID = :BroadcastID");
        $sth->bindParam(':BroadcastID', $BroadcastID);
		$sth->bindParam(':BroadcastIsActive', $BroadcastIsActive);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $BroadcastID));
		
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