$(function () {

	var el = $(".learn > a");
	var intro = $("#intro");
	var loadAbout = function () {
		intro.animate({opacity: 0}, 250);
		intro.animate({height: "50px"}, 250);
		$("#content > .container").fadeOut(1000, function() {
			var $this = $(this);
			$.get("/about", function(data) {
				var h = $($(data).filter("#content")[0]).html();
				$this.html(h);
			});
		}).fadeIn(1000);
		window.location.hash = "about";
	};
	console.log(window.location.hash);
	if (window.location.hash === "#about") {
		loadAbout();
	}
	el.on("click", function (e) {
		e.preventDefault();
		loadAbout();
		$(this).remove();
	});

});
