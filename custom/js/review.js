let rating_data = 0;

$('#add_review').click(function(){
		$('#review_modal').modal('show');
});

$(document).on('mouseenter', '.submit_star', function(){
		let rating = $(this).data('rating');
		reset_background();
		for(var count = 1; count <= rating; count++){
			$('#submit_star_'+count).addClass('text-warning');
		}
});

function reset_background() {
		for(let count = 1; count <= 5; count++) {
				$('#submit_star_'+count).addClass('star-light');
				$('#submit_star_'+count).removeClass('text-warning');
		}
}

$(document).on('mouseleave', '.submit_star', function(){
		reset_background();
		for(var count = 1; count <= rating_data; count++) {
				$('#submit_star_'+count).removeClass('star-light');
				$('#submit_star_'+count).addClass('text-warning');
		}
});

$(document).on('click', '.submit_star', function(){
		rating_data = $(this).data('rating');
		 // Set the value of the hidden input field
		 $('#id_rate').val(rating_data);
});
