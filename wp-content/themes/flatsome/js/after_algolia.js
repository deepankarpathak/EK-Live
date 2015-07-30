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

		// Product Page description panels

		$( '.woocommerce-tabs ul.tabs li a' ).click( function() {

		var $tab = $( this ),
			$tabs_wrapper = $tab.closest( '.woocommerce-tabs' );

		$( 'ul.tabs li', $tabs_wrapper ).removeClass( 'active' );
		$( 'div.panel', $tabs_wrapper ).hide();
		$( 'div' + $tab.attr( 'href' ), $tabs_wrapper).show();
		$tab.parent().addClass( 'active' );

		return false;
	    });