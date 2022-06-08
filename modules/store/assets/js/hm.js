/*MT Ecommerce web app Developed by Abel Mayowa May 2022*/
/*This is the JavaScript required to control the store home. The endpoint of the ajax request used here is located inside the Controller file for store. Store.php*/
//This handles the spinner to allow page load completely before displaying page




document.onreadystatechange = function() {
  
document.querySelector("#preloader").style.visibility="hidden";//hide preloader for light mode


document.querySelector("#preloader2").style.visibility="hidden";//hide preloader for dark mode
    
    
	const mode = Cookies.get("theme");
	
   
	
	let t = "#preloader";
	
	//page not loaded completely
	if(document.readyState !== "complete") {
	    
		document.querySelector("body").style.visibility = "hidden";
		if(mode == "Dark Mode") { //dark mode is the users choice from cookie
			t = "#preloader2"; //we are going to load preloader2
			
	
			
			document.querySelector(t).style.visibility= "visible";
		} else { //light mode is users choice.
			t = "#preloader"; //we are going to load preloader
			
//	document.querySelector("#preloader2").style.visibility="hidden";//hide preloader for dark mode
			
			
			document.querySelector(t).style.visibility = "visible";
		}
	} else { //page is fully loaded. delay 5secs before showing body content
		setTimeout(function() {
			document.querySelector("#preloader").style.visibility = "hidden"; //hide preloader
			document.querySelector("#preloader2").style.visibility = "hidden"; //hide preloader
			document.querySelector("body").style.visibility = "visible";
		}, 100);
	} //else
}; //state change
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
	//hides profile and logout button if no active user session   
	let status = $("#online-status").val();
	if(status !== "online") {
		$("#nav-profile,#profile,#logout,#nav-logout").hide();
		$("#online-info").html("Login to enable some features");
		$("#online-info").addClass("tag is-warning block");
	};
	(function() {
		var src = '//cdn.jsdelivr.net/npm/eruda';
		if(!/eruda=true/.test(window.location) && localStorage.getItem('active-eruda') != 'true') return;
		document.write('<scr' + 'ipt src="' + src + '"></scr' + 'ipt>');
		document.write('<scr' + 'ipt>eruda.init();</scr' + 'ipt>');
	})();
	/*This saves the user cart table into cookies. Note that it wont be used directly to calculte the items choosen. This is just allow the site retain the cart even when the page is reloaded*/
	function save_choice(sum = "") {
		//get users ip as a way to recognize them
		$.ajax({
			url: 'https://www.cloudflare.com/cdn-cgi/trace',
			type: 'get',
			success: function(data) {
					// Convert key-value pamirs to JSON
					data = data.trim().split('\n').reduce(function(obj, pair) {
						pair = pair.split('=');
						return obj[pair[0]] = pair[1], obj;
					}, {});
					let d = $("table").html(); //product table
					let d2 = sum; //total no. of items in cart
					//the cart table saved into cookie against the users ip
					Cookies.set(data.ip, d, {
						expires: 1,
						path: '/'
					});
					//total no of items in cart saved also against users ip
					Cookies.set(data.ip + "item", d2, {
						expires: 1,
						path: '/'
					});
				} //success
		}); //ajax  
	}
	// function to save new choice of currency
	function save_currency(choice) {
		$.ajax({
			url: '../../../../store/save_currency',
			type: 'get',
			data: {
				currency_choice: choice
			},
			timeout: 10000,
			success: function(d) {}
		}); //ajax
	}
	/*This displays a success message when item get added to cart*/
	function display_msg(el, id) {
		setTimeout(function() {
			el.removeClass("is-loading");
			el.closest(".column").find("#cartbtn").css({
				"opacity": "1"
			});
			el.parents().find("#" + id).show();
			$("#" + id).after().html(" added");
			$("#" + id).hide(2800);
		}, 300);
	} // func display_msg()
	//stripe payment
	function stripe_pay(amount, auth, currency, email, phone, reference) {
		const pk = 'pk_test_51L6dsXIpord8yPxo0JgOO1uyhgLttcCnCwYp7aHlFhKgJIXBJbrCtQXiiPIbbfXFKC9OtmRjLrB3O34FUOYN8gcX00dV2zu9bF';
		const stripe = Stripe(pk);
		$.ajax({
			url: "../../../../stripe/pay",
			method: 'post',
			data: {
				amount: amount,
				email: email,
				reference: reference,
				phone: phone,
				currency: currency,
				auth: auth
			},
			dataType: "json",
			success: function(r) {
				if(r.id) {
					$("#inf").remove();
					stripe.redirectToCheckout({
						sessionId: r.id
					});
				}
			}
		}); //ajax
		//}//token
		//});
	} //pay
	//hides all span to show success message when item added to cart 
	$("body #msg").each(function() {
		$(this).hide();
	});
	/*Get cookie data on page load if any exist in order to update the cart table*/
	//get user ip
	$.ajax({
		url: 'https://www.cloudflare.com/cdn-cgi/trace',
		type: 'get',
		success: function(data) {
			// Convert key-value pairs to JSON
			data = data.trim().split('\n').reduce(function(obj, pair) {
				pair = pair.split('=');
				return obj[pair[0]] = pair[1], obj;
			}, {});
			let cookie_data = Cookies.get(data.ip); //cart table
			let cookie_data2 = Cookies.get(data.ip + "item"); //no_of items in cart
			//update cart if cookies exist
			if(cookie_data && cookie_data2) {
				$("table").siblings().remove();
				$("table").html(cookie_data); //display the cart data
				$("#items").html(cookie_data2); //display total no of items on shopping bag.
			}
		}
	});
	/*Handles home sliding images*/
	let count = 0; //initiate counter
	let slide = setInterval(function() {
		let images = ["i1.jpg", "i2.jpg", "i3.jpg", "i4.jpg", "i5.jpg"];
		//check if images count is exhausted and start from the first image.
		if(count >= images.length) {
			count = 0;
		}
		//change the img src attribute
		$("#headerImg").attr("src", "../../../public/img/" + images[count]);
		//increments counter
		count++;
	}, 1800);
	//load electronics category
	$("body").on('click', '#electronics', function(e) {
		$("#page").html("1");
		$(".navbar-burger").removeClass("is-active");
		$(".navbar-menu").removeClass("is-active");
		const auth = $("#category-auth").val();
		const category = "electronics";
		$.ajax({
			url: '../../../../store/load_category',
			type: 'get',
			data: {
				auth: auth,
				category: category
			},
			success: function(d) {
				$("#category").html("Category/Electronics");
				const pos = $("#products").offset().right;
				window.scroll(0, pos);
				$("#products").html(d);
			}
		});
	});
	//load men category
	$("body").on('click', '#men', function(e) {
		$("#page").html("1");
		$(".navbar-burger").removeClass("is-active");
		$(".navbar-menu").removeClass("is-active");
		const auth = $("#category-auth").val();
		const category = "men";
		$.ajax({
			url: '../../../../store/load_category',
			type: 'get',
			data: {
				auth: auth,
				category: category
			},
			success: function(d) {
				const pos = $("#products").offset();
				window.scroll(0, pos.right);
				$("#category").scrollTop();
				$("#category").html("Category/Men"); //category name.
				$("#products").html(d);
			}
		});
	});
	//load women category
	$("body").on('click', '#women', function(e) {
		$("#page").html("1");
		$(".navbar-burger").removeClass("is-active");
		$(".navbar-menu").removeClass("is-active");
		const auth = $("#category-auth").val();
		const category = "women";
		$.ajax({
			url: '../../../../store/load_category',
			type: 'get',
			data: {
				auth: auth,
				category: category
			},
			success: function(d) {
				const pos = $("#products").offset();
				window.scroll(0, pos.right);
				$("#category").scrollTop();
				$("#category").html("Category/Women"); //category name.
				$("#products").html(d);
			}
		});
	});
	//load children category
	$("body").on('click', '#children', function(e) {
		$("#page").html("1");
		$(".navbar-burger").removeClass("is-active");
		$(".navbar-menu").removeClass("is-active");
		const auth = $("#category-auth").val();
		const category = "children";
		$.ajax({
			url: '../../../../store/load_category',
			type: 'get',
			data: {
				auth: auth,
				category: category
			},
			success: function(d) {
				const pos = $("#products").offset();
				window.scroll(0, pos.right);
				$("#category").scrollTop();
				$("#category").html("Category/Children"); //category name.
				$("#products").html(d);
			}
		});
	});
	//load phone category
	$("body").on('click', '#phone', function(e) {
		$("#page").html("1");
		$(".navbar-burger").removeClass("is-active");
		$(".navbar-menu").removeClass("is-active");
		const auth = $("#category-auth").val();
		const category = "phone";
		$.ajax({
			url: '../../../../store/load_category',
			type: 'get',
			data: {
				auth: auth,
				category: category
			},
			success: function(d) {
				const pos = $("#products").offset();
				window.scroll(0, pos.right);
				$("#category").scrollTop();
				$("#category").html("Category/Phone/Tablets"); //category name.
				$("#products").html(d);
			}
		});
	});
	// navbar toggle click handler
	$(".navbar-burger").click(function() {
		$(".navbar-burger").toggleClass("is-active");
		$(".navbar-menu").toggleClass("is-active");
	});
	//display only the product prices in default currency
	const c = $("#active-currency").val();
	if(c == "naira") {
		$("#price2,#old_price2,#price3,#old_price3").css("display", "none");
	}
	if(c == "dollar") {
		$("#price,#old_price,#price3,#old_price3").css("display", "none");
	}
	if(c == "pounds") {
		$("#price,#old_price,#price2,#old_price2").css("display", "none");
	}
	/*Handles user change of choice of currency*/
	$(".navbar #change_currency").on("click", "div", function() {
		//cookies need to be cleared to avoid displaying cart table data which shows the items price in a currency which was choosen before.
		if(typeof cookies !== "undefined") {
			let cookies = document.cookie.split(";");
			const deleteAllCookies = () => {
				for(let val of cookies) {
					const eqPos = val.indexOf("=");
					const name = eqPos > -1 ? val.substr(0, eqPos) : cookie;
					document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
				}
			};
			deleteAllCookies();
		}
		let choice = $(this).attr('id'); //choice of currency
		//change currency to dollar
		if(choice == "dollar") {
			$("#price, #old_price, #price3, #old_price3, #flash-amount, #flash-amount3").css("display", "none");
			$("#price2,#old_price2,#flash-amount2").show();
			//close navbar menu after change
			$(".navbar-menu,.navbar-burger").removeClass("is-active");
			//clear the present data in cart
			$("tbody").empty();
			$("#items").html("0");
			$("#total").html("-"); //total clear
			save_currency(choice);
		}
		if(choice == "pounds") {
			$("#price,#old_price,#price2,#old_price2,#flash-amount,#flash-amount2").css("display", "none");
			$("#price3,#old_price3,#flash-amount3").show();
			//close the navbar menu
			$(".navbar-menu,.navbar-burger").removeClass("is-active");
			$("tbody").empty(); //clear cart 
			$("#items").html("0");
			$("#total").html("-"); //total clear
			save_currency(choice);
		}
		//naira choice
		if(choice == "naira") {
			$("#price2,#old_price2,#price3,#old_price3").css("display", "none");
			$("#price,#old_price").show();
			//close the navbar menu
			$(".navbar-menu,.navbar-burger").removeClass("is-active");
			$("tbody").empty(); //clear cart
			$("#items").html("0");
			$("#total").html("-"); //total clear
			save_currency(choice);
		}
	}); //end of currency change
	//all modals close
	$(".delete").click(function() {
		$(this).closest(".modal").removeClass("is-active");
	});
	/*Handles the checking of more details about a product*/
	$("body").on('click', '#get_details,#flash', function() {
		$("#pic1,#p2,#p3").attr("src", "loading..."); //loading alt to avoid displaying the last picture that was there
		$(this).closest("div").find("#loading").show(); //show loading icon
		//hide loading icon after 4s
		$(this).closest("div").find("#loading").hide(4000);
		//close search modal if description is triggered from it
		$("#search-modal").removeClass("is-active");
		let pid = $(this).closest("div").find("input").val(); //product id
		let current_currency = $(".price:visible").find("span").html(); //users choice of currency
		//get product details from back end
		$.ajax({
			url: "../../../../store/product_info",
			type: "get",
			dataType: "json",
			data: {
				product_id: pid,
				curr: current_currency
			},
			success: function(reply) {
				$("#pic1").attr("src", "../../../../public/products/" + reply.pic1);
				$("#p2").attr("src", "../../../../public/products/" + reply.pic2);
				$("#p3").attr("src", "../../../../public/products/" + reply.pic3);
				let p = reply.price.toLocaleString(); //number format
				let curr = reply.curr; //currency symbol
				$("#product_name").html(reply.product);
				$("#desc").html(reply.description);
				$("#modal_price").html(curr + p);
				$("#info-pid").val(pid);
				$("#info-modal").addClass("is-active");
			}
		}); //ajax end
	}); //end product details.
	/*Handles adding to cart of product*/
	$("body").on("click", "#cartbtn", function() {
		let el = $(this);
		let id = el.closest("div").parent().find(".fa-check").attr("id"); // id of tick icon
		el.addClass("is-loading");
		//details of product on cart
		let product_choice = el.closest(".column").find("#productName").html(); //picked productnl name
		let choice_id = el.closest(".column").find("#pid").val(); //picked product id
		//get  the price of products based on users choice of currency by checking which price is currently visible to them
		let p = el.closest(".column").find("#price").is(":visible");
		let p2 = el.closest(".column").find("#price2").is(":visible");
		let p3 = el.closest(".column").find("#price3").is(":visible");
		//naira price
		let ch = el.closest(".column").find("#price").html();
		let curr = ch.charAt(26); //currency type
		ch = ch.substr(34);
		//dollar price
		let ch2 = el.closest(".column").find("#price2").html();
		let curr2 = ch2.charAt(26); //currency type
		ch2 = ch2.substr(34);
		//pounds price
		let ch3 = el.closest(".column").find("#price3").html();
		let curr3 = ch3.charAt(26); //currency type
		ch3 = ch3.substr(34);
		let price; //required choice of product price
		let currency;
		if(p == true) {
			price = ch;
			currency = curr; //appropriate currency
		}
		if(p2 == true) {
			price = ch2;
			currency = curr2; //appropriate currency
		}
		if(p3 == true) {
			//let choice_price = ch;
			price = ch3;
			currency = curr3; //appropriate currency
		}
		let quantity = 1; //each product qty
		//checking if product already in cart
		let check = $("tbody td:contains(" + product_choice + ")").html();
		let sum = 0; //no of items in cart
		//increase quantity of product if choosen before.
		if(check == product_choice) { //if product chosen before
			//get quantity of product presently
			let q = $("td:contains(" + product_choice + ")").closest("tr").find("td").eq(4).html(); //.find("td").eq(0).html();
			q = Number(q); // q turned from string to number
			quantity = Number(q + 1); //replace with new quantity
			$("td:contains(" + product_choice + ")").closest("tr").find("td").eq(4).html(quantity);
			//display no of items in cart
			$("tbody tr").each(function() {
				let a = $(this).find("td").eq(4).html(); //each quantity in table
				a = Number(a);
				sum += a;
			});
			$("#items").html(sum); //display no of items in cart
			let total = 0; // initial sum of items in cart.
			//get total amount of items selected
			$("tbody tr").each(function() {
				let item_price = $(this).closest("tr").find("td").eq(3).html();
				let qty = $(this).closest("tr").find("td").eq(4).html();
				item_price = Number(item_price.replace(",", "")); //price per unit
				qty = Number(qty); //quantity
				let unit_product_price = qty * item_price;
				total += unit_product_price; //sum of all items in cart
				$("#total").html(total.toLocaleString());
			});
			save_choice(sum);
			display_msg(el, id); //sucfesful pick notification
			return;
		} //if
		else {
			//add product
			$("tbody").append('<tr><td><button class="ml-1 button  is-danger is-small is-rounded is-outlined remProd">x</button></td><td>' + product_choice + '</td><td>' + choice_id + '</td><td>' + price + '</td><td>' + quantity + '</td> <td><div class="container white"><span style="color:white;" id="minus" class="tag baseColor  fa fa-minus"></span><input type="number" class="input is-link is-small" id="qty" value="1"> <span style="color:white;" id="plus" class="tag baseColor  fa fa-plus  white"></span> </div></td></tr>');
			$("#c").html("(" + currency + ")");
			//display no. of items in shoppin bag
			$("tbody tr").each(function() {
				let b = $(this).find("td").eq(4).html(); //each quantity in table
				b = Number(b);
				sum += b;
			});
			$("#items").html(sum); //display no of items on shopping bag
			let total = 0; // initial sum of items in cart.
			//get total amount of items selected
			$("tbody tr").each(function() {
				let item_price = $(this).closest("tr").find("td").eq(3).html();
				let qty = $(this).closest("tr").find("td").eq(4).html();
				item_price = Number(item_price.replace(",", "")); //price per unit
				qty = Number(qty); //quantity
				let unit_product_price = qty * item_price;
				total += unit_product_price; //sum of all items in cart
				$("#total").html(total.toLocaleString());
			});
			save_choice(sum);
			display_msg(el, id);
		} //else 
	}); //add to cart end.
	/*handles the increase of product quantity on cart table using the plus icon...*/
	$('body').on('click', '#plus', function() {
		let sum = 0;
		let qty = $(this).closest(".container").find("#qty").val();
		let new_qty = Number(qty) + 1;
		$(this).closest("tr").find("td").eq(4).html(new_qty);
		$("tbody tr").each(function() {
			let b = $(this).find("td").eq(4).html(); //each quantity in table
			b = Number(b);
			sum += b;
		});
		$("#items").html(sum); //new qty of product
		//calculate sum
		let total = 0;
		let e = $("tbody tr").each(function() {
			let e = $(this).closest("tr").find("td").eq(4).html();
			let p = $(this).closest("tr").find("td").eq(3).html();
			p = p.replace(",", "");
			total += Number(e) * Number(p);
		})
		$("#total").html(total.toLocaleString());
		$(this).closest(".container").find("#qty").val(new_qty);
		save_choice(sum);
	}); //plus click
	//minus click
	$('body').on('click', '#minus', function() {
		let sum = 0;
		let qty = $(this).closest(".container").find("#qty").val();
		let new_qty = Number(qty) - 1;
		if(qty == 1) {
			return;
		}
		$(this).closest("tr").find("td").eq(4).html(new_qty);
		$("tbody tr").each(function() {
			let b = $(this).find("td").eq(4).html(); //each quantity in table
			b = Number(b);
			sum += b;
		});
		$("#items").html(sum); //new qty of product
		//calculate sum
		let total = 0;
		let e = $("tbody tr").each(function() {
			let e = $(this).closest("tr").find("td").eq(4).html();
			let p = $(this).closest("tr").find("td").eq(3).html();
			p = p.replace(",", "");
			total += Number(e) * Number(p);
		})
		$("#total").html(total.toLocaleString());
		$(this).closest(".container").find("#qty").val(new_qty);
		save_choice(sum);
	}); //plus click
	//set value of input on cart to 1 if left empty
	$("body").on('blur', '#qty', function() {
		let input = $(this).val();
		if(input == "") {
			$(this).closest("tr").find("td").eq(4).html(1);
			$(this).val(1);
		}
		//calculate new no of items and total sum of products
		let sum = 0;
		$("tbody tr").each(function() {
			let b = $(this).find("td").eq(4).html(); //each quantity in table
			b = Number(b);
			sum += b;
		});
		$("#items").html(sum); //new qty of product
		//calculate sum
		let total = 0;
		let e = $("tbody tr").each(function() {
			let e = $(this).closest("tr").find("td").eq(4).html();
			let p = $(this).closest("tr").find("td").eq(3).html();
			p = p.replace(",", "");
			total += Number(e) * Number(p);
		})
		$("#total").html(total.toLocaleString());
		save_choice(sum);
	}); //on blur
	//change quantity in input of value on the cart table
	$("body").on('change', '#qty', function() {
		let sum = 0;
		let input = Number($(this).val());
		let qty = $(this).closest("tr").find("td").eq(4).html(input);
		//update no of items and total amount
		$("tbody tr").each(function() {
			let b = $(this).find("td").eq(4).html(); //each quantity in table
			b = Number(b);
			sum += b;
		});
		$("#items").text(Number(sum)); //new qty of product
		//calculate total sum of products
		let total = 0;
		let e = $("tbody tr").each(function() {
			let e = $(this).closest("tr").find("td").eq(4).html();
			let p = $(this).closest("tr").find("td").eq(3).html();
			p = p.replace(",", "");
			total += Number(e) * Number(p);
		})
		$("#total").html(total.toLocaleString());
		save_choice(sum);
	}); //key up
	//check cart items
	$("body").on("click", "#check_items", function() {
		$("#basket").addClass("is-active").show();
	}); //check items
	/*Handles the removal of product on cart table*/
	$("body").on("click", ".remProd", function() {
		let p = $(this).closest("tr").find("td").eq(3).html();
		p = Number(p.replace(",", "")); //product price
		let t = $("#total").html(); //current total amount of all products picked
		t = Number(t.replace(",", ""));
		let q = $(this).closest("tr").find("td").eq(4).html();
		q = Number(q); //qty of an item/product
		let i = Number($("#items").html()); //no of current items on shopping bag.
		let num_new_items;
		let new_total;
		num_new_items = i - q; //removing quantity from current items.
		new_total = (t - q * p).toLocaleString();
		$("#total").html(new_total);
		$("#items").html(num_new_items);
		$(this).closest("tr").remove();
		save_choice(num_new_items);
	}); //product removal
	/* Handles the image view in a new tab on product description modal*/
	$(".modal-card-body img").click(function() {
		let s = $(this).attr("src");
		window.open(s);
	});
	/*handles payment processing*/
	$("body").on('click', '#pay', function() {
		let el = $(this);
		el.addClass("is-loading");
		//check login status as only logged in users can pay
		$.ajax({
			url: '../../../../store/check_login',
			type: 'post',
			success: function(r) {
					if(r == "0") {
						//user not logged in.
						$("#basket").removeClass("is-active");
						$("#reg-modal").addClass("is-active");
						el.removeClass("is-loading");
					} else {
						let data = [];
						$("tbody tr").each(function(j, v) {
							//j is array index   
							let i = $(this).find("td").eq(2).html(); //product id
							let q = $(this).find("td").eq(4).html(); //quantity
							let s = i + q;
							s = s.replace(" ", ""); //remove whitespace
							data[j] = s; //put all product id with respective qty into array.
						}); //each
						//put all datas in one string
						let fdata = data.join("-");
						let delivery = $("input[type=radio]:checked").val(); //delivery method
						let currency = $("thead tr").find("#c").html();
						currency = currency.substr(1, 1);
						let auth = $("#auth").val();
						//set delivery to bus stop if none is chooses
						if(typeof(delivery) === "undefined") {
							delivery = "bus park";
							let stat;
						}
						const location = $("#location").val();
						//send products to backend for processing
						$.ajax({
							url: '../../../../store/pay',
							type: 'post',
							dataType: 'json',
							data: {
								pid: fdata,
								delivery: delivery,
								curr: currency,
								location: location,
								auth: auth
							},
							success: function(r) {
								el.removeClass("is-loading");
								if(r.status !== "1") {
									Swal.fire('Ooops!!', r, 'info');
									return;
								}
								//use stripe for payment with usd and pounds.
								if(r.currency == "USD" || r.currency == "GBP") {
									$("#pay").after().html('<p id="inf" class="ml-2 tag is-light is-link">Please wait...</p>');
									stripe_pay(r.amount, auth, r.currency, r.email, r.phone, r.reference);
									return;
								} //
								if(r.status == "1") {
									//close basket
									$("#basket").removeClass("is-active");
									//open payment gateway  
									var handler = PaystackPop.setup({
										key: r.token, // Replace with your public key
										email: r.email,
										amount: r.amount * 100, // the amount value is multiplied by 100 to convert to the lowest currency unit.
										currency: r.currency, // Use GHS for Ghana Cedis or USD for US Dollars
										ref: r.reference, // Replace with a reference you generated.
										callback: function(response) {
											//this happens after the payment is completed successfully
											var reference = response.reference;
											Swal.fire('<span class="fa fa-tick"></span>', 'Order Succesful. Transactions details can be found on your profile', 'success')
												// Make an AJAX call to your server with the reference to verify the transaction
											$.ajax({
												url: '../../../../store/pay?reference=' + reference + 'verify="verify"',
												type: 'post',
												data: {
													auth: auth,
													reference: reference,
													verify: 'verify'
												},
												success: function(response) {
														if(response.data.status !== "success") {
															$.ajax({
																url: '../../../../store/payment_failed',
																type: 'post',
																success: function(r) {
																	alert('Payment Failed!!');
																}
															});
														} //if status success.
													} //success call back func
											}); //ajax
										},
										onClose: function() {
											Swal.fire('Oops!!!', 'Payment Closed', 'error')
												// alert('Payment Closed!!');
										},
									});
									handler.openIframe();
								} //if r.status is 1
							}, //success
							error: function(jqXHR, status, error) {
								el.removeClass("is-loading");
							}
						}); //ajax to get variables to be hard coded into payment gateway.
					} //else
				} //success
		}); //ajax
	}); //pay click
	/*Handles registration of users*/
	$("body").on('click', '#register', function(e) {
		e.preventDefault();
		let el = $(this);
		el.addClass("is-loading");
		let email = $("#email").val();
		let fname = $("#fname").val();
		let oname = $("#oname").val();
		let phone = $("#Phone").val();
		let country = $("#country").val();
		let password = $("#password").val();
		let auth = $("#form_token").val();
		//request to backend for processing
		$.ajax({
			url: '../../../../user/register',
			type: 'post',
			data: {
				fname: fname,
				oname: oname,
				phone: phone,
				password: password,
				email: email,
				country: country,
				auth: auth
			},
			dataType: 'json',
			success: function(d) {
				el.removeClass("is-loading");
				//populate form token  
				$("#form_token").val(d.token);
				//display error msg     
				if(d.status !== "1") {
					const pos = $("#products").offset();
					window.scroll(0, pos.right);
					$("#error").html(d.msg).css({
						"display": "block"
					});
				} else {
					//hide navbar menu
					$(".navbar-burger,.navbar-menu").removeClass("is-active");
					//hide reg modal
					el.parents().find(".modal").removeClass("is-active");
					$("#online-badge").addClass("button is-success online-badge");
					$("#online-info, #nav-login,#nav-reg").hide();
					$("#nav-profile,#nav-logout,#profile,#logout").show();
				}
			}
		});
	});
	$("body").on("click", "#nav-login", function() {
		$("#login-modal").addClass("is-active");
	});
	/*Handles user login*/
	$("body").on('click', "#login", function(e) {
		e.preventDefault()
		let el = $(this);
		el.addClass("is-loading");
		let email = $("#l_email").val();
		let password = $("#l_password").val();
		let auth = $("#form_token").val();
		if(email == "" && password == "") {
			el.removeClass("is-loading");
			alert("Please enter your credentials!!");
			return;
		}
		$.ajax({
				url: '../../../../user/login',
				type: 'post',
				data: {
					email: email,
					password: password,
					auth: auth
				},
				dataType: 'json',
				success: function(r) {
					//display error occured when registration fails.
					//repopulate form token
					$("#form_token").val(r.token);
					$("#login").removeClass("is-loading");
					if(r.status !== "1") {
						const pos = $("#error").offset();
						window.scroll(0, pos.right);
						$("#login-error").html("error: " + d.msg).css({
							"display": "block"
						});
					} else {
						//user logged in.. hide  modal
						$(".navbar-burger,.navbar-menu").removeClass("is-active");
						$("#login").parents().find(".modal").removeClass("is-active").hide();
						$("#online-badge").addClass("button is-success online-badge");
						$("#online-info, #nav-login,#nav-reg").hide();
						$("#nav-profile,#nav-logout,#profile,#logout").show();
					}
				}
			}) //ajax to login  
	}); //login click
	$("body").on('click', '#sign-in', function() {
		$("#reg-modal").removeClass("is-active");
		$("#login-modal").addClass("is-active");
	});
	$("body").on('click', '#nav-reg', function() {
		$("#reg-modal").addClass("is-active");
	}); //nav register click
	/*Handles search of product*/
	$("body").on('focus', '#search-keyword', function() {
		$(this).closest("div").find("span").after("<button id='spin' class='button  is-link is-loading is-small '></button>");
	});
	$("body").on('blur', '#search-keyword', function() {
		$("#spin").remove();
	});
	//function to delay on input type
	var delay = (function() {
		var timer = 0;
		return function(callback, ms) {
			clearTimeout(timer);
			timer = setTimeout(callback, ms);
		};
	})()
	$("body").on('keydown', '#search-keyword', function() {
		let keyword = $("#search-keyword").val();
		let currency = $("#change_currency").html();
		delay(function() {
			$.ajax({
					url: '../../../../store/search',
					type: 'get',
					timeout: 6000,
					data: {
						keyword: keyword
					},
					success: function(r) {
						$("#results").html(r);
						$("#search-modal").addClass("is-active");
					}, //success
					error: function(a, b, c) {
							if(b === "timeout") {
								$("#search-modal").before("<p class='notification is-danger is-light block'> OOPS!!! It looks like you have a slow connection!! Please retry </p>");
							} //end of if 
						} //error func    
				}) //ajax
		}, 500);
	});
	$("body").on('click', '#searched', function() {
		let p = $("#spid").val();
		$("#info-modal").show();
	});
	//load more products
	$("body").on('click', '#more', function() {
		// let cache = $("#cache").html();
		let category = $("#category").html();
		let split = category.split('/');
		category = split[1];
		$(this).addClass("is-loading");
		$(this).css({
			"opacity": "0.9"
		});
		let current_page = $('#page').html();
		let action = "next";
		$.ajax({
			url: '../../../../store/load_page',
			type: 'get',
			timeout: 10000,
			data: {
				current: current_page,
				action: action,
				category: category
			},
			success: function(r) {
				$("#more").removeClass("is-loading");
				if(r == "0") {
					Swal.fire('Oops!!', 'No more results!! You can use search to find products', 'info')
					return;
				}
				const pos = $("#products").offset();
				window.scroll(0, pos.right);
				$("#products").html(r);
				let new_page = Number(current_page) + 1;
				$("#page").html(new_page);
			}, //success
			error: function(a, b, c) {
					if(b === "timeout") {
						Swal.fire('Oops!!!', 'Your seems to have a poor connection..', '')
					} //end of if 
				} //error func 
		}); //ajax
	}); //more
	//load less page
	$("body").on('click', '#less', function() {
		const current_page = $('#page').html();
		if(current_page == "1") {
			return;
		}
		let category = $("#category").html();
		category = category.split('/');
		category = category[1];
		$(this).css({
			"opacity": "0.9"
		});
		$(this).addClass("is-loading");
		let action = "previous";
		$.ajax({
			url: '../../../../store/load_page',
			type: 'get',
			timeout: 6000,
			data: {
				current: current_page,
				action: action,
				category: category
			},
			success: function(r) {
				$("#less").removeClass("is-loading");
				if(r !== "0") {
					$("#products").html(r);
					const pos = $("#products").offset();
					window.scroll(0, pos.right);
					let new_page = Number(current_page) - 1;
					$("#page").html(new_page);
				}
			}, //success
			error: function(a, b, c) {
					if(b === "timeout") {
						$("#products").before().html("<p class='notification is-danger is-light'> OOPS!!! It looks like you have a slow connection!! Please retry </p>");
					} //end of if 
				} //error func
		}); //ajax
	}); //load less
	//logout user
	$("body").on('click', '#nav-logout,#logout', function() {
		$.ajax({
			url: '../../../../user/logout',
			type: 'get',
			success: function(r) {
				if(r == "1") {
					$("#online-badge").removeClass("button is-success online-badge");
					$("#online-info, #nav-login,#nav-reg").show();
					$("#nav-profile,#nav-logout,#profile,#logout").hide();
					window.location.href = '';
				}
			}
		});
	}); //
	/*Handles change of theme*/
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
	//rating of products
	$("body").on('click', '#submit-rating', function() {
		let vote = $("#rate-value").val();
		let pid = $(this).parents().find("#info-pid").val();
		$.ajax({
			url: '../../../../store/rate',
			type: 'post',
			//timeout:4000,
			data: {
				vote: vote,
				pid: pid
			},
			success: function(r) {
					if(r == "1") {
						Swal.fire('Thanks', 'Thanks for rating!! 😊', 'success');
						$(this).css({
							"opacity": "1"
						});
					} else {
						Swal.fire('', r, 'info');
						$(this).css({
							"opacity": "1"
						});
					}
				} //success
		}); //ajax
	}); //rating
	// flash product time counter
	let time = $("#flash-timer").val();
	[d, h, i, s] = time.split(":");
	let day;
	let hour;
	let minutes;
	let seconds;
	let timer = setInterval(function() {
		if(s < 1 && i >= 1) {
			s = 59;
			i--;
			//add leading zero to number less than 10
			if(i < 10) {
				i = "0" + i;
			}
		}
		if(i < 1 && s < 1) {
			if(h >= 1) {
				h--;
				i = 59;
				s = 59;
			}
		}
		if(i < 1 && h < 1) {
			if(d >= 1) {
				d--;
				i = 59;
				s = 59;
			}
		}
		if(s <= 10) {
			$(".z4").css({
				"display": "block"
			});
		} else {
			$(".z4").css({
				"display": "none"
			});
		}
		if(m <= 10) {
			$(".z3").css({
				"display": "block"
			});
		} else {
			$(".z3").css({
				"display": "none"
			});
		}
		if(h <= 10) {
			$(".z2").css({
				"display": "block"
			});
		} else {
			$(".z2").css({
				"display": "none"
			});
		}
		if(d <= 10) {
			$(".z").css({
				"display": "block"
			});
		} else {
			$(".z").css({
				"display": "none"
			});
		}
		s--;
		day = d;
		hour = h;
		minutes = i;
		seconds = s;
		//send request to backend when timer is expired
		if(day == 0 && hour == 0 && minutes == 0 && seconds == 0) {
			clearInterval(timer);
			$("#flash").closest(".box").hide();
			let pid = $("#pid").val();
			$.ajax({
				url: '../../../../store/change_flashProduct_status',
				type: 'post',
				data: {
					pid: pid
				},
				success: function(r) {}
			});
		}
		$("#d").html(day);
		$("#h").html(hour);
		$("#m").html(minutes);
		$("#s").html(seconds);
	}, 1000);
	//handles addition of flash product to cart
	$("body").on("click", "#flashcart", function() {
			const el = $(this);
			el.addClass("is-loading");
			let pid = $(this).parents().find("#pid").val();
			let sum = 0;
			$.ajax({
				url: '../../../../store/add_flash_product',
				type: 'post',
				dataType: 'json',
				data: {
					pid: pid
				},
				success: function(r) {
						el.removeClass("is-loading");
						let check = $("tbody td:contains(" + r.name + ")").html();
						if(check == r.name) { //if product chosen before do not append again.
							Swal.fire('Ooops!!!', 'Flash Product already in cart. Open cart and adjust to the quantity you want!!!', 'info');
							return;
						}
						$("#basket_table tbody").append('<tr><td><button class="ml-1 button  is-danger is-small is-rounded is-outlined remProd">x</button></td><td>' + r.name + '</td><td>' + r.pid + '</td><td>' + r.price + '</td><td>' + r.qty + '</td> <td><div class="container white"><span id="minus" class="tag baseColor white fa fa-minus"></span><input type="number" class="input is-link is-small" id="qty" value="1"> <span id="plus" class="tag baseColor  fa fa-plus white"></span> </div></td></tr>');
						$("#c").html("(" + r.icon + ")");
						//display no. of items in shoppin bag
						$("tbody tr").each(function() {
							let b = $(this).find("td").eq(4).html(); //each quantity in table
							b = Number(b);
							sum += b;
						});
						$("#items").html(sum); //display no of items on shopping bag
						let total = 0; // initial sum of items in cart.
						//get total amount of items selected
						$("tbody tr").each(function() {
							let item_price = $(this).closest("tr").find("td").eq(3).html();
							let qty = $(this).closest("tr").find("td").eq(4).html();
							item_price = Number(item_price.replace(",", "")); //price per unit
							qty = Number(qty); //quantity
							let unit_product_price = qty * item_price;
							total += unit_product_price; //sum of all items in cart
							$("#total").html(total.toLocaleString());
						});
						save_choice(sum);
					} //success
			}); //ajax
		}) //add go cart on flash
}); //doc end