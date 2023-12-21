<?php 
function searchbar_ui($sn, $sf) {
  echo "
  <div class='searchbar d-flex justify-content-center mb-3 p-2'>
    <div class='d-flex justify-content-between gap-2'>
      <select id='sn' class='form-select'>
        <option value='1'" . (($sn == 1) ? ' selected' : '') . ">标题</option>
        <option value='2'" . (($sn == 2) ? ' selected' : '') . ">标题栏</option>
        <option value='3'" . (($sn == 3) ? ' selected' : '') . ">联系方式</option>
        <option value='4'" . (($sn == 4) ? ' selected' : '') . ">用户编辑</option>
      </select>
      <input type='text' class='search form-control' id='sf' value='" . $sf . "'>
      <button class='btn btn-secondary' id='btn_search'><i class='fa-solid fa-magnifying-glass'></i></button>		
    </div>
  </div>";
}

function searchQuery($sqlData, $sn, $sf) {
	if($sn != '' && $sf != '') {
		$where = '';
		switch($sn) {
			case 1: $where .= " AND (subject LIKE '%".$sf."%' OR content LIKE '&".$sf."%' )"; break; // sub + con
			case 2: $where .= " AND (subject LIKE '%".$sf."%')"; break; // sub
			case 3: $where .= " AND (content LIKE '%".$sf."%')"; break; // con
			case 4: $where .= " AND (name= '".$sf."')"; break; // name
		}
		$sqlData .= " $where";
	}
  return $sqlData;
}
?>