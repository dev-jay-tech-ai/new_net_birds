<?php require_once 'includes/header.php'; ?>

<?php 

$sql = "SELECT * FROM product WHERE status = 1";
$query = $connect->query($sql);
$countProduct = $query->num_rows;

$orderSql = "SELECT * FROM orders WHERE order_status = 1";
$orderQuery = $connect->query($orderSql);
$countOrder = $orderQuery->num_rows;

$totalRevenue = "";
while ($orderResult = $orderQuery->fetch_assoc()) {
      $totalRevenue = $orderResult['paid'];
}

$lowStockSql = "SELECT * FROM product WHERE quantity <= 3 AND status = 1";
$lowStockQuery = $connect->query($lowStockSql);
$countLowStock = $lowStockQuery->num_rows;

$connect->close();

?>

<div class="row">

	<div class="main panel panel-default col-md-12 text-center" style='height: 200px'>
		<div class='panel-heading text-data'>
			Welcome to newnetbirds
		</div>
	</div>
	
	<div class="panel panel-default">
		<div class="panel-heading"><h3 class="panel-title"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">welcome!</font></font></h3></div>
		<div class="panel-body">
			<div class="global-text-box-wrapper">
				<div class="global-text-box-1">
					<p><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">This website is only for the UK, please be sure to comply with UK laws when posting.</font></font></p>
					<p><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Site Affairs Statement: </font></font><a href="https://www.uknetbirds.com/topic/113/%E7%AB%99%E5%8A%A1%E5%A3%B0%E6%98%8E"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Site Affairs Statement</font></font></a></p>
					<p><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
							To register, please visit: </font></font><a href="https://www.uknetbirds.com/register"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Quick Registration</font></font></a>
					</p>
					<p><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
					View all sections: </font></font><a href="https://www.uknetbirds.com/categories"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Sections</font></font></a>
					</p>
					<p><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Posting Tutorial: </font></font><a href="https://www.uknetbirds.com/topic/136/%E6%89%8B%E6%8A%8A%E6%89%8B%E6%95%99%E4%BD%A0%E4%BB%8E%E6%B3%A8%E5%86%8C%E5%88%B0%E5%8F%91%E5%B8%96"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Tutorial</font></font></a></p>
					<p></p>
				</div>
				<div class="global-text-box-2">
						<h3 style="
							margin-top: 0px;
							"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Welcome to join the customer Telegram discussion group:</font></font></h3>
						<a href="https://t.me/uknetbirds2"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Invitation link</font></font></a>
					<p><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Welcome to support the operation of this site through donations: </font></font><a href="/donation" target="_blank"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">donation link</font></font></a></p>
				</div>
			</div>
		</div>
	</div>

	
</div> <!--/row-->

<!-- fullCalendar 2.2.5 -->
<script src="assests/plugins/moment/moment.min.js"></script>
<script src="assests/plugins/fullcalendar/fullcalendar.min.js"></script>


<script type="text/javascript">
	$(function () {
			// top bar active
	$('#navDashboard').addClass('active');

      //Date for the calendar events (dummy data)
      var date = new Date();
      var d = date.getDate(),
      m = date.getMonth(),
      y = date.getFullYear();

      $('#calendar').fullCalendar({
        header: {
          left: '',
          center: 'title'
        },
        buttonText: {
          today: 'today',
          month: 'month'          
        }        
      });


    });
</script>

<?php require_once 'includes/footer.php'; ?>