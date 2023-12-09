<?php 
if($code == 'agent') {
	/** query */
	$sqlCount = "SELECT COUNT(*) as cnt FROM aboard WHERE code='$code'";
	if(!(isset($_SESSION['userId']) && $result['status'] == 1)) {
		$sqlCount .= " AND active = 1";
	}

	
	/** common part start */
	$sqlData = "SELECT a.*, u.user_image FROM aboard a LEFT JOIN users u ON a.user_id = u.user_id WHERE code='$code'";
	if(!(isset($_SESSION['userId']) && $result['status'] == 1)) {
		$sqlData .= " AND a.active = 1";
	}
	$sqlData = searchQuery($sqlData, $sn, $sf);
	$sqlData .= " ORDER BY is_pinned DESC, idx DESC LIMIT $start, $limit";
	$stmtData = $connect->prepare($sqlData);
  if($stmtData) {
		$stmtData->execute();
		$resultData = $stmtData->get_result();
		$rs = [];
		while($row = $resultData->fetch_assoc()) {
			$rs[] = $row;
		}
  } else die($connect->error);
	/** common part end */
	
	/** count excute */
	$sqlCount = searchQuery($sqlCount, $sn, $sf);

	$stmtCount = $connect->prepare($sqlCount);
  if($stmtCount) {
		$stmtCount->execute();
		$resultCount = $stmtCount->get_result();
		$row = $resultCount->fetch_assoc();
		$total = $row['cnt'];
  } else {
      die($connect->error);
  }


} elseif($code == 'private') {
	
	/** query */
	$sqlCount = "SELECT COUNT(*) as cnt FROM pboard WHERE code='$code'";
	if(!(isset($_SESSION['userId']) && $result['status'] == 1)) {
		$sqlCount .= " AND active = 1";
	}



	/** query */
	$selectedLocation = 0; 
	$param = '&code='.$code;
	if(isset($_GET['location'])) {
		$locationParam = $_GET['location'];
		$param .= '&location='.$locationParam;
		$locationMapping = ['london' => 0, 'manchester' => 1, 'glasgow' => 2, 'nottingham' => 3, 'birmingham' => 4, 'others' => 5];
		if(array_key_exists($locationParam, $locationMapping)) {
			$selectedLocation = $locationMapping[$locationParam];
			
			/** common part start */
			$sqlData = "SELECT a.*, u.user_image FROM pboard a LEFT JOIN users u ON a.user_id = u.user_id WHERE code = '$code' AND location = $selectedLocation";		
			if(!(isset($_SESSION['userId']) && $result['status'] == 1)) {
				$sqlData .= " AND a.active = 1";
			}
			$sqlData = searchQuery($sqlData, $sn, $sf);
			$sqlData .= " ORDER BY is_pinned DESC, idx DESC LIMIT $start, $limit";
			$stmtData = $connect->prepare($sqlData);
			if($stmtData) {
				$stmtData->execute();
				$resultData = $stmtData->get_result();
				$rs = [];
				while($row = $resultData->fetch_assoc()) {
					$rs[] = $row;
				}	
			} else die($connect->error);
			/** common part end */
			$sqlCount .= " AND location = $selectedLocation";
		}
	} else {
		
		/** qurey */
		$sqlData = "SELECT a.*, u.user_image FROM pboard a LEFT JOIN users u ON a.user_id = u.user_id WHERE code='$code'";
		if (!(isset($_SESSION['userId']) && $result['status'] == 1)) {
			$sqlData .= " AND a.active = 1";
		}
		$sqlData = searchQuery($sqlData, $sn, $sf);
		$sqlData .= " ORDER BY is_pinned DESC, idx DESC LIMIT $start, $limit";
		
		/** excute */
		$stmtData = $connect->prepare($sqlData);
		if($stmtData) {
			$stmtData->execute();
			$resultData = $stmtData->get_result();
			$rs = $resultData->fetch_all(MYSQLI_ASSOC);
		} else {
			die($connect->error);
		}   
	}

	/** count excute */
	$sqlCount = searchQuery($sqlCount, $sn, $sf);
	$stmtCount = $connect->prepare($sqlCount);
	if ($stmtCount) {
		$stmtCount->execute();
		$resultCount = $stmtCount->get_result();
		$row = $resultCount->fetch_assoc();
		$total = $row['cnt'];
	} else {
		die($connect->error);
	}







} elseif($code == 'review') {
	
	/** qurey */
  $sqlCount = "SELECT COUNT(*) as cnt FROM rboard";
	if (!(isset($_SESSION['userId']) && $result['status'] == 1)) {
		$sqlCount .= " WHERE active = 1";
	}



	$selectedLocation = 0; 
	$param = '&code='.$code;
	if(isset($_GET['location'])) {
		$locationParam = $_GET['location'];
		$param = '&location='.$locationParam;
		$locationMapping = ['london' => 0, 'manchester' => 1,'glasgow' => 2, 'nottingham' => 3, 'birmingham' => 4, 'others' => 5];
		if(array_key_exists($locationParam, $locationMapping)) {
			$selectedLocation = $locationMapping[$locationParam];
			
			/** common part start */
			$sqlData = "SELECT a.*, u.user_image FROM rboard a LEFT JOIN users u ON a.user_id = u.user_id WHERE location = $selectedLocation";
			if(!(isset($_SESSION['userId']) && $result['status'] == 1)) {
				$sqlData = searchQuery($sqlData, $sn, $sf);
				$sqlData .= " AND a.active = 1 ORDER BY is_pinned DESC, idx DESC LIMIT $start, $limit";
				$sqlCount .= " AND location = $selectedLocation";
			} else {
				$sqlData = searchQuery($sqlData, $sn, $sf);
				$sqlData .= " ORDER BY is_pinned DESC, idx DESC LIMIT $start, $limit";
				$sqlCount .= " WHERE location = $selectedLocation";			
			}
			$stmtData = $connect->prepare($sqlData);
			if($stmtData) {
				$stmtData->execute();
				$resultData = $stmtData->get_result();
				$rs = [];
				while ($row = $resultData->fetch_assoc()) {
					$rs[] = $row;
				}
			} else die($connect->error);
			/** common part end */
		}
	} else {
		/** qurey */
		$sqlData = "SELECT a.*, u.user_image FROM rboard a LEFT JOIN users u ON a.user_id = u.user_id";
		if (!(isset($_SESSION['userId']) && $result['status'] == 1)) {
			$sqlData .= " WHERE a.active = 1";
		}
		$sqlData = searchQuery($sqlData, $sn, $sf);
		$sqlData .= " ORDER BY a.is_pinned DESC, a.idx DESC LIMIT $start, $limit";
		
		/** excute */
		$stmtData = $connect->prepare($sqlData);
		if($stmtData) {
			$stmtData->execute();
			$resultData = $stmtData->get_result();
			$rs = [];
			while ($row = $resultData->fetch_assoc()) {
				$rs[] = $row;
			}
		} else {
				die($connect->error);
		}
	}

	/** count excute */
	$sqlCount = searchQuery($sqlCount, $sn, $sf);

	$stmtCount = $connect->prepare($sqlCount);
	if($stmtCount) {
		$stmtCount->execute();
		$resultCount = $stmtCount->get_result();
		$row = $resultCount->fetch_assoc();
		$total = $row['cnt'];
	} else {
		die($connect->error);
	}

} elseif($code == 'jobs') {

} elseif($code == 'property') {

}

$totalRows = count($rs);
$activeRowCount = ($page - 1) * $limit;

?>