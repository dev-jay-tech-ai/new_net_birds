
<footer class="footer mt-auto">
	<div class='footer_container'>
		<div class='mb-5'><a class='footer_logo' href="/dashboard.php">
		<img class='logo_img' src='/assets/images/logo/logo_w.png' alt='logo' /></a></div>	
		<div class='copyright'>Copyright © Newnetbirds <span id="currentYear"></span>.</div>
		All Rights Reserved.</div>	
	</div>
	<div class="footer_right">
		<span class="text-muted"></span>
		This website is only for the UK, please be sure to comply with UK laws when posting.
		<br>	
		
		</span>
	</div>
</footer>

<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
<script>
    const currentYear = new Date().getFullYear();
    document.getElementById('currentYear').textContent = currentYear;
  </script>
</html>