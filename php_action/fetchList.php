<?php 
	require 'fetchFunction.php';
	if ($code == 'agent') {
		$board = 'aboard';
	} elseif ($code == 'private') {
		$board = 'pboard';
	} elseif ($code == 'review') {
		$board = 'rboard';
	} elseif ($code == 'jobs') {
		$board = 'jboard';
	} elseif ($code == 'property') {
		$board = 'prboard';
	}
	$sqlCount = "SELECT COUNT(*) as cnt FROM $board WHERE code='$code'";
	if(!(isset($_SESSION['userId']) && $result['status'] == 1)) {
		$sqlCount .= " AND active = 1";
	}

	$selectedLocation = 0; 
	$param = '&code='.$code;
	if(isset($_GET['location'])) {
		$locationParam = $_GET['location'];
		$param .= '&location='.$locationParam;
		$locationMapping = ['london' => 0, 'manchester' => 1, 'glasgow' => 2, 'nottingham' => 3, 'birmingham' => 4, 'others' => 5];
		if(array_key_exists($locationParam, $locationMapping)) {
			$selectedLocation = $locationMapping[$locationParam];
			$sqlData = "SELECT a.*, u.user_image FROM $board a LEFT JOIN users u ON a.user_id = u.user_id WHERE code = '$code' AND location = $selectedLocation";		
			if(!(isset($_SESSION['userId']) && $result['status'] == 1)) {
				$sqlData .= " AND a.active = 1";
			}
			$sqlData = searchQuery($sqlData, $sn, $sf);
			$sqlData .= " ORDER BY is_pinned DESC, rdate DESC LIMIT $start, $limit";
			$rs = executeData($connect, $sqlData);
			$sqlCount .= " AND location = $selectedLocation";
		}
	} else {
		$sqlData = "SELECT a.*, u.user_image FROM $board a LEFT JOIN users u ON a.user_id = u.user_id WHERE code='$code'";
		if (!(isset($_SESSION['userId']) && $result['status'] == 1)) {
			$sqlData .= " AND a.active = 1";
		}
		$sqlData = searchQuery($sqlData, $sn, $sf);
		$sqlData .= " ORDER BY is_pinned DESC, rdate DESC LIMIT $start, $limit";
		$rs = executeData($connect, $sqlData); 
	}

	$sqlCount = searchQuery($sqlCount, $sn, $sf);
	$total = executeCnt($connect, $sqlCount);

	$totalRows = count($rs);
	$activeRowCount = ($page - 1) * $limit;
?>