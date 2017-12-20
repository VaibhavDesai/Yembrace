<?php

$app->get('/reviews/get(/)', function () use($app) {
    try 
    {
    	$app = \Slim\Slim::getInstance();
        $db = getDB();

		$all_get_requests = $app->request()->get();
		$fields = array();
		$response = array();
		$max_result = 2;
		$is_active = 1;
		$offset = 0;
		
		$search_by = $all_get_requests['searchBy'];

		//optional parameters
		if(isset($all_get_requests['maxResult']))
			$max_result = $all_get_requests['maxResult'];
		if(isset($all_get_requests['fields']))
			$fields = explode(",",$all_get_requests['fields']);
		if(isset($all_get_requests['offset']))
			$offset = $all_get_requests['offset'];
		if(isset($all_get_requests['shopID']))
			$shop_id = $all_get_requests['shopID'];
		if(isset($all_get_requests['productID']))
			$product_id = explode(",", $all_get_requests['productID']);
		if(isset($all_get_requests['offerID']))
			$offer_id = explode(",", $all_get_requests['offerID']);
		

		$select_qry = "SELECT reviews.ReviewTitle,reviews.ReviewDescription,reviews.ReviewWrittenOn,reviews.ReviewRating,users.FullName,users.ProfilePic ";
		$from_qry = "FROM reviews reviews,users users,reviewsentitymap reviewsentitymap ";
		$limit_qry = "LIMIT ".$max_result." OFFSET ".$offset.";";
		if($search_by == "shop")
			$where_qry = "WHERE reviewsentitymap.ShopID=:ShopID AND reviews.ReviewPublicUserID=users.PublicUserID AND reviews.ReviewID=reviewsentitymap.ReviewID ";
		if($search_by == "product")
			$where_qry = "WHERE reviewsentitymap.ProductID=:ProductID AND reviews.ReviewPublicUserID=users.PublicUserID AND reviews.ReviewID=reviewsentitymap.ReviewID ";
        if($search_by == "offer")
			$where_qry = "WHERE reviewsentitymap.OfferID=:OfferID AND reviews.ReviewPublicUserID=users.PublicUserID AND reviews.ReviewID=reviewsentitymap.ReviewID ";

		$query = $select_qry.$from_qry.$where_qry.$limit_qry;
        $sth = $db->prepare($query);
		if($search_by == "shop")
			$sth->bindParam(':ShopID',$shop_id);
		if($search_by == "product")
			$sth->bindParam(':ProductID',$product_id);
		if($search_by == "offer")
			$sth->bindParam(':OfferID',$offer_id);

		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$data['ReviewTitle'] = $row['ReviewTitle'];
			$data['ReviewDescription'] = $row['ReviewDescription'];
			$data['ReviewWrittenOn'] = $row['ReviewWrittenOn'];
			$data['ReviewRating'] = $row['ReviewRating'];
			$data['FullName'] = $row['FullName'];
			$data['ProfilePic'] = $row['ProfilePic'];
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

$app->post('/reviews/add(/)', function() use($app) {
 
    $all_post_vars = $app->request->post();
 
    try 
    {
        $db = getDB();

        $review_type = $all_post_vars['ReviewType'];
        $reviewed_object_id = $all_post_vars['ReviewedObjectID'];
        $reviewer_id = $all_post_vars['PublicUserID'];
        $review_title = $all_post_vars['ReviewTitle'];
        $review_description = $all_post_vars['ReviewDescription'];
        $review_rating = $all_post_vars['ReviewRating'];
		
		$query = "INSERT INTO reviews(ReviewType,ReviewTitle,ReviewDescription,ReviewPublicUserID,ReviewRating) VALUES(:ReviewType,:ReviewTitle,:ReviewDescription,:ReviewPublicUserID,:ReviewRating)";
		$sth = $db->prepare($query);
		$sth->bindParam(':ReviewType',$review_type);
		$sth->bindParam(':ReviewTitle',$review_title);
		$sth->bindParam(':ReviewDescription',$review_description);
		$sth->bindParam(':ReviewPublicUserID',$reviewer_id);
		$sth->bindParam(':ReviewRating',$review_rating);
		$sth->execute();
		$lastInsertId = $db->lastInsertId('ReviewID');

		$query1 = "INSERT INTO reviewsentitymap(ReviewID,ShopID,ProductID,OfferID) VALUES(:ReviewID,:ShopID,:ProductID,:OfferID)";
		$sth = $db->prepare($query1);
		$sth->bindParam(':ReviewID',$lastInsertId);
		$sth->bindParam(':ShopID',($review_type == "shop")?($reviewed_object_id):(0));
		$sth->bindParam(':ProductID',($review_type == "product")?($reviewed_object_id):(0));
		$sth->bindParam(':OfferID',($review_type == "offer")?($reviewed_object_id):(0));
		$sth->execute();

		$lastInsertId = $db->lastInsertId('ReviewEntityMapID');
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


?>