$(document).ready(function() {
	//load theme saved in cookie
	const mode = Cookies.get("theme");
	if(mode == "Dark Mode") { //then light mode is on.
		$(".navTop").removeClass("baseColor");
		$(".navTop,.baseColor").css({
			"background-image": "linear-gradient(62deg,#232526,#414345)"
		});
		$("body,.navTop,.link-btn").css({
			"background-image": "linear-gradient(62deg,#232526,#414345"
		});
		$("#mode").html("Light Mode");
	}
	$("body").on('click', '#change-theme', function() {
		$("#t-loader").show();
		const mode_txt = $("#mode").html();
		let new_mode = "";
		if(mode_txt == "Dark Mode") { //then light mode is on.
			$(".navTop").removeClass("baseColor");
			$(".navTop,.baseColor").css({
				"background-image": "linear-gradient(62deg,#232526,#414345)"
			});
			$("body,.navTop,.link-btn").css({
				"background-image": "linear-gradient(62deg,#232526,#414345"
			});
			$("#mode").html("Light Mode"); //change mode text
			new_mode = "Dark Mode";
			//save choice to cookies
			Cookies.set("theme", new_mode, {
				expires: 1,
				path: "/"
			});
			$("#change-theme").prop("checked", false);
			$("#t-loader").hide(1200);
		} else { //dark mode on change to default light mode
			$("#t-loader").show();
			$(".navTop,.baseColor,.link-btn").css({
				"background-image": "radial-gradient( circle farthest-corner at 10% 20%,  rgba(37,145,251,0.98) 0.1%, rgba(0,7,128,1) 99.8% )"
			});
			$("body").css({
				"background-image": "linear-gradient(64deg,white,white"
			});
			$("#mode").html("Dark Mode"); //mode text
			new_mode = "Light Mode";
			//save choice to cookies
			Cookies.set("theme", new_mode, {
				expires: 1,
				path: "/"
			});
			$("#change-theme").prop("checked", false);
			$("#t-loader").hide(1200);
		}
	});
	//Home sliding header images
	let count = 0; //initiate counter
	let slide = setInterval(function() {
		let images = ["i1.jpg", "i2.jpg", "i3.jpg", "i4.jpg", "i5.jpg"];
		//check if images count is exhausted and start from the first image.
		if(count >= images.length) {
			count = 0;
		}
		//change the img src attribute
		$("#prof").attr("src", "../../../public/img/" + images[count]);
		//increments counter
		count++;
	}, 1800);
	//details of transactions
	$("body").on("click", ".details", function() {
		let el = $(this);
		el.find('span').removeClass("fa-check").addClass("fa-spinner fa-spin");
		let ref = el.closest("tr").find("td").eq(1).html();
		const auth = $("#token").val();
		$.ajax({
			url: '../../../../user/details',
			type: 'post',
			data: {
				'reference': ref,
				'auth': auth
			},
			dataType: 'json',
			success: function(d) {
					$(".details").find("span").removeClass("fa-spinner fa-spin").addClass("fa-check");
					$(".details").removeClass("is-loading");
					$("#show_details").html(d.html);
					$("#currency-info,.currency-info").html(" " + d.currency);
					$("#details-modal").addClass("is-active");
				} //success
		}); ///ajax
	}); //click
	$("#details-close").click(function() {
		$("#details-modal").removeClass("is-active");
	});
	//load more receipts
	$("body").on("click", "#more", function() {
		const el = $(this);
		el.addClass("is-loading");
		let page = $("#page").val();
		let auth = $("#token").val();
		page = Number(page);
		$.ajax({
			url: '../../../../user/profile',
			type: 'post',
			data: {
				more: "more",
				page: page,
				auth: auth
			},
			success: function(d) {
					el.removeClass("is-loading");
					if(d == "0") {
						return;
					}
					$("#load-more").append(d);
					$("#page").val(page + 1);
				} //success
		}); ///ajax
	}); //click
});