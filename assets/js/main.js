(function () {
	'use strict';
	window.addEventListener('load', function () {
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.getElementsByClassName('needs-validation');
		// Loop over them and prevent submission
		var validation = Array.prototype.filter.call(forms, function (form) {
			form.addEventListener('submit', function (event) {
				if (form.checkValidity() === false) {
					event.preventDefault();
					event.stopPropagation();
				}
				form.classList.add('was-validated');
			}, false);
		});
	}, false);
})();

jQuery(window).load(function() {
	// Aviso cookie
	$("#aviso_cookie a").click(function(e) {
		e.preventDefault();
		$(this).hide();
		$.get($(this).attr("href") + "&ajax=1", function() {
			$("#aviso_cookie").remove();
		});
	});

	// Subir hacia arriba fijo
	// var num3 = 586;
	var num3 = window.innerHeight - 55;
	$(window).bind('scroll', function() {
		if ($(window).scrollTop() > num3) {
			$('#toTop').addClass('visible');
		} else {
			$('#toTop').removeClass('visible');
		}
	});

	// Scroll arriba footer
	$(".scroll").click(function(e) {
		e.preventDefault();
		$('html,body').animate({
			scrollTop: $(this.hash).offset().top
		}, 500);
	});
});