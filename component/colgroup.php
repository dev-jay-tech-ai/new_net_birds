<?php
if($board_title === 'Review') {
  if(isset($_SESSION['userId']) && $result['status'] == 1) {
    echo "
    <col width='4%' />
    <col width='6%' />
    <col width='25%' />
    <col width='5%' />
    <col width='5%' />
    <col width='10%' />    
    <col width='10%' />    
    <col width='5%' />    
    <col width='10%' />";
  } else {
    echo "
    <col width='7%' />
    <col width='53%' />
    <col width='10%' />
    <col width='10%' />
    <col width='10%' />
    <col width='10%' />";
  }
} else {
  if(isset($_SESSION['userId']) && $result['status'] == 1) {
    echo "
    <col width='4%' />
    <col width='6%' />
    <col width='40%' />
    <col width='10%' />
    <col width='5%' />
    <col width='5%' />		
    <col width='10%' />		
    <col width='10%' />";	
  } else {
    echo "
    <col width='7%' />
    <col width='63%' />
    <col width='10%' />
    <col width='10%' />
    <col width='10%' />";
  }
}

?>