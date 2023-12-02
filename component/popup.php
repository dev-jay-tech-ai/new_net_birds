<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title m-auto">Disclaimer!</h3>
      </div>
		<div class="modal-body">
        Newnetbirds provides advertising space for adult entertainment<br> 
        providers, and for adults seeking adult entertainment services.<br> 
        <br>
        Newnetbirds is not an escort agency and does not play any part <br>
        in the booking of any services.<br>
        <br>
        To access this category, you agree:<br>
        You are of legal age, as defined by the country or state from <br>
        where you are accessing the Site, to view sexually explicit material;<br>
        You will only use the Site for legal purposes;<br>
        You are accessing the Site from a country or state <br>
        where it is not illegal to enter adult websites <br>
        and/or view sexually explicit material;<br>
			</div>
			<div class="modal-footer d-flex justify-content-center gap=3">
        <div><a href="/dashboard.php" class="btn btn-secondary">Disagree</a></div>
				<div style="cursor: pointer;"> 
					<span class="btn btn-primary modal-today-close" data-dismiss="modal"> Agree </span> 
				</div>
			</div>
		</div>

	</div>
</div>

<script>
function setCookie(name, value, expiredays) {
    const today = new Date();
    today.setDate(today.getDate() + expiredays);
    document.cookie = name + '=' + escape(value) + '; expires=' + today.toGMTString();
}

function getCookie(name) {
    const cookie = document.cookie;
    if (document.cookie != "") {
        var cookie_array = cookie.split("; ");
        for (let index in cookie_array) {
            const cookie_name = cookie_array[index].split("=");
            if (cookie_name[0] == name) {
                return cookie_name[1];
            }
        }
    }
    return;
}

const pageName = getPageName(); // Implement a function to get the current page name
const cookieName = `${pageName}_mycookie`; // Append page name to the cookie name

$(".modal-today-close").click(function() {
	$("#myModal").modal("hide");
	setCookie(cookieName, 'popupEnd', 1);
})

const checkCookie = getCookie(cookieName);

if (checkCookie == 'popupEnd') {
    $("#myModal").modal("hide");
} else {
    $("#myModal").modal("show");
}

function getPageName() {
    const pathArray = window.location.pathname.split('/');
    return pathArray[pathArray.length - 1].replace('.php', ''); // Extract the last part of the path and remove '.php'
}

</script>