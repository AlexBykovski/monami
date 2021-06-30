$(document).ready(function () {

	// Header show all contacts dropdown
	$('.header-show-all-contacts .show-contacts-head').click(function () {
		var headerShowAllContacts = $(this).parent();

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

	// Product list view change

	// if (JSON.parse(sessionStorage.getItem('product-list'))){
	// 	$('#products-list-tiles-select').click(function () {JSON.parse(sessionStorage.getItem('product-list'))};
	// }

	$('#products-list-tiles-select').click(function () {
		$('.products-list').attr('class', 'products-list products-list-tiles');
		sessionStorage.setItem('product-list', 'titles');
		sessionStorage.setItem('locationFoType', location.pathname);
	});

	$('#products-list-standart-select').click(function () {
		$('.products-list').attr('class', 'products-list products-list-standart');
		sessionStorage.setItem('product-list', 'standart');
		sessionStorage.setItem('locationFoType', location.pathname);

	});

	if (sessionStorage.getItem('product-list') === 'titles' && sessionStorage.getItem('locationFoType') === location.pathname){
		$('#products-list-tiles-select').click();
	} else if (sessionStorage.getItem('product-list') === 'standart' && sessionStorage.getItem('locationFoType') === location.pathname){
		$('#products-list-standart-select').click();
	}

	var thumbnailSliderOptions = {
		sliderId: "thumbnail-slider",
		orientation: "vertical",
		thumbWidth: "140px",
		thumbHeight: "50px",
		showMode: 2,
		autoAdvance: true,
		selectable: true,
		slideInterval: 3000,
		transitionSpeed: 900,
		shuffle: false,
		startSlideIndex: 0,
		pauseOnHover: true,
		initSliderByCallingInitFunc: false,
		rightGap: 0,
		keyboardNav: false,
		before: function (currentIdx, nextIdx, manual) {
			$('#header-main-slider').flexslider(nextIdx);
		},
		license: "mylicense"
	};

	var mcThumbnailSlider = new ThumbnailSlider(thumbnailSliderOptions);

	$('.prethumb').click(function () {
		$('.prethumb').removeClass('active');
		$(this).addClass('active');
		$('#header-main-slider').flexslider($(this).index());
	});

	$("#thumbnail-slider-next").click(function () {
		$('#header-main-slider').flexslider($('.active').index());
	});

	$('#header-main-slider').flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		touch: true,
		start: function (slider) {
			$('body').removeClass('loading');
		},
		before: function (e) {
			mcThumbnailSlider.display(e.currentSlide + 1)
		}
	});

	//Chrome Smooth Scroll
	try {
		$.browserSelector();
		if($("html").hasClass("chrome")) {
			$.smoothScroll();
		}
	} catch (err) {};
});
