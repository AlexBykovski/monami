$(document).ready(function () {

	// Header show all contacts dropdown
	$('.header-show-all-contacts .show-contacts-head').click(function () {
		var headerShowAllContacts = $(this).parent();

		console.log(headerShowAllContacts)		

		function showContactsBackdropClick () {
			$(headerShowAllContacts).removeClass('active');
			$('.header-show-all-contacts .show-contacts-backdrop').off('click', showContactsBackdropClick);
		}

		$(headerShowAllContacts).addClass('active');
		$('.header-show-all-contacts .show-contacts-backdrop').on('click', showContactsBackdropClick);
	});


	// Mobile menu
	$('#toggleMenuBtn').click(function () {
		$('#mobileMenu').fadeIn(300);
		$(".mobile-nav .nav-item a, .mobile-menu .header-search-bar-form").addClass("fadeInUp animated");
	});

	$('#closeMobileMenuBtn').click(function () {
		$('#mobileMenu').fadeOut(150);
		$(".mobile-nav .nav-item a, .mobile-menu .header-search-bar-form").removeClass("fadeInUp animated");
	});


	// Carousels
	$('.our-workers-carousel').owlCarousel({
    items:    4,
    loop:     true,
    // autoplay: true,
    nav:      true,
		navText:  ['<i class="fa fa-caret-left"></i>', '<i class="fa fa-caret-right"></i>'],
		responsive: {
			0: {
				items : 1
			},
			480: {
				items : 2
			},
			768: {
				items: 3
			},
			992: {
				items : 4
			}
		}
  });


	// Product quantity decrement
	$('.product-quantity-input-wrap .decrem-quant').click(function () {
		var quantVal = Number($(this).parent().find('.quant-input').val());

		console.log(quantVal)

		if (quantVal == 0) {
			return false;
		} else {
			$(this).parent().find('.quant-input').val(quantVal -  1)			
		}
	});

	// Product quantity increment
	$('.product-quantity-input-wrap .increm-quant').click(function () {
		var quantVal = Number($(this).parent().find('.quant-input').val());

		$(this).parent().find('.quant-input').val(quantVal +  1)			
	});


	// Product list view change
	$('#products-list-tiles-select').click(function () {
		$('.products-list').attr('class', 'products-list products-list-tiles');
	});

	$('#products-list-standart-select').click(function () {
		$('.products-list').attr('class', 'products-list products-list-standart');
	});


	//Chrome Smooth Scroll
	try {
		$.browserSelector();
		if($("html").hasClass("chrome")) {
			$.smoothScroll();
		}
	} catch (err) {};

});
