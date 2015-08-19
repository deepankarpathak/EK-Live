       //Home Page Slider
        jQuery('.bxslider').bxSlider({
				  auto: true,
				  autoControls: true
	    });	

        // Logo Slider
        var $c = $('#edu-carousel');
		$w = $(window);
			$c.carouFredSel({
			align: false,
			items: 10,
			scroll: {
				items: 1,
				duration: 2500,
				timeoutDuration: 0,
				easing: 'linear',
				pauseOnHover: 'immediate'
			}
		});
		// Home Page Horizontol slider
		$('.shortcode_tabgroup ul.tabs li a').click(function(e){
		  e.preventDefault();
		  $(this).parent().parent().parent().find('ul li').removeClass('active');
		  $(this).parent().addClass('active');
		  var currentTab = $(this).attr('href');
		  $(this).parent().parent().parent().find('div.panel').removeClass('active');
		  $(currentTab).addClass('active');
		  $(currentTab).find('p script').unwrap();

		  // Find iosSliders and update them and go to slide 1.
		  var iOS = ( navigator.userAgent.match(/(Android|webOS|iPhone|iPad|iPod|BlackBerry)/g) ? true : false );
		  if($(currentTab).find('.iosSlider') && iOS) {
		    $(currentTab).find('.iosSlider').each(function(){
		      var id = $(this).attr('id');
		      $('#'+id).iosSlider('update').iosSlider('goToSlide', 1);
		    });
		  }
		  $(window).resize();
		  return false;
		});


		$('.product-details .tabbed-content .tabs a').click(function(){
		  var panel = $(this).attr('href');
		  $(panel).addClass('active');
		  return false;
		});
		
		// Home Page Vertical slider
		$('.shortcode_tabgroup_vertical ul.tabs-nav li a').click(function(e){
		  e.preventDefault();
		  $(this).parent().parent().parent().find('ul li').removeClass('current-menu-item');
		  $(this).parent().addClass('current-menu-item');
		  var currentTab = $(this).attr('href');
		  $(this).parent().parent().parent().parent().find('div.tabs-inner').removeClass('active');
		  $(currentTab).addClass('active');
		  $(window).resize();
		  return false;
		});

		// Product Page description  and related products slider

		$( '.woocommerce-tabs ul.tabs li a' ).click( function() {
			var $tab = $( this ),
				$tabs_wrapper = $tab.closest( '.woocommerce-tabs' );

			$( 'ul.tabs li', $tabs_wrapper ).removeClass( 'active' );
			$( 'div.panel', $tabs_wrapper ).hide();
			$( 'div.panel').addClass('active');
			$( 'div' + $tab.attr( 'href' ), $tabs_wrapper).show();
			$tab.parent().addClass( 'active' );

			return false;
	    });

	    // Tab content course structure

	    jQuery(document).ready(function() {

			jQuery(".tab_content").hide();
			jQuery(".tab_content:first").show(); 

			jQuery("ul.tabs-in li").click(function() {
				jQuery("ul.tabs-in li").removeClass("active");
				jQuery(this).addClass("active");
				jQuery(".tab_content").hide();
				var activeTab = jQuery(this).attr("rel"); 
				jQuery("#"+activeTab).fadeIn(); 
			});
		});
		
		$("#slider_related").resize();
	    $(".product_detail_referral").css("float","left");

