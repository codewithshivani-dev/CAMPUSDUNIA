    /********  Sticky Header ********/
	$(window).on("scroll", function(){
		if ( $( '#site-header' ).hasClass( "sticky-header" ) ) {
			var site_header = $('#site-header').outerHeight() + 30;	
			
		    if ($(window).scrollTop() >= site_header) {	    	
		        $('.sticky-header .entritt-top-header, .mobile-header-sticky .header_mobile').addClass('fixed-header');	        
		    }else {
		        $('.sticky-header .entritt-top-header, .mobile-header-sticky .header_mobile').removeClass('fixed-header');		              
		    }
		}
	});
/*****search-icon js******/

$('.toggle_search').on("click", function(){
    $(this).toggleClass( "active" );
    $('.h-search-form-field').toggleClass('show');
    if ($(this).find('i').hasClass( "flaticon-search" )) {
        $('.toggle_search > i').removeClass( "flaticon-search" ).addClass("flaticon-close");
    }else{
        $('.toggle_search > i').removeClass( "flaticon-close" ).addClass("flaticon-search");
    }
    $('.h-search-form-inner > form > input.search-field').focus();
});

/***** client logo******/
    $('.client-logo-owl-carsoul').owlCarousel({
        loop:true,
        margin:80,
        nav:false,
        dots: false,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        responsive:{
            0:{
                items:2
            },
            480:{
                items:3
            },          
            767:{
                items:4
            },
            1000:{
                items:6
            }
        }
    });
    /*****projct slider*****/
    $(".gallary-slider").owlCarousel({
        stagePadding: 365,
        nav:false,
        dots: true,
        loop:true,
        navText: ['<i class="flaticon-back"></i>', '<i class="flaticon-right-arrow-1"></i>'],
        responsive:{
            1400:{
                stagePadding: 365,
                items:2
            },
            1200:{
                stagePadding: false,
                items:3
            },
            992:{
                stagePadding: false,
                items:2
            },
            767:{
                stagePadding: false,
                items:2
            },
            0:{
                stagePadding: false,
                items:1
            }
        }
     });

/* --------------------------------------------------
* Testimonial carousel
* --------------------------------------------------*/
 $(".entr-testm-slider").owlCarousel({
    nav:true,
    dots: false,
    loop:true,
    navText: ['<i class="flaticon-back"></i>', '<i class="flaticon-right-arrow-1"></i>'],
    responsive:{
        1000:{
            items:2
        },
        767:{
            nav:false,
            dots: true,
            items:2
        },
        0:{
            nav:false,
            dots: true,
            items:1
        }
    }
 });


$("#mmenu_toggle").click(function(){
    $(this).toggleClass("active");
 $(".mobile_nav").toggle();
});
    