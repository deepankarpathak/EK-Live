/**
 * function to add click event to filter icon
 */
function addToggleEvent() {
	// Append the mobile icon nav
	jQuery('.fkr_nav').append(jQuery('<div class="fkr_nav-mobile"></div>'));
	// Add a <span> to every .fkr_nav-item that has a <ul> inside
	jQuery('.fkr_nav-item').has('.fk_d2_main_menu').prepend('<span class="fkr_nav-click"><i class="fkr_nav-arrow"></i></span>');
	// Click to reveal the nav
	jQuery('.fkr_nav-mobile').click(function(){
		jQuery('.fkr_nav-list').toggle();
		jQuery(this).siblings('.fkr_nav-list').toggleClass('flt_nav-mobileactive');
	});
	// Dynamic binding to on 'click'
	jQuery('.fkr_nav-list').on('click', '.fkr_nav-click', function(){
		// Toggle the nested fkr_nav
		jQuery(this).siblings('.fkr_nav-submenu').toggleClass('fkr_nav-active');
		// Toggle the arrow using CSS3 transforms
		jQuery(this).children('.fkr_nav-arrow').toggleClass('fkr_nav-rotate');
	});
}
/**
 * function to create filter icon
 */
function createFilterIcon() {
	// Append the mobile icon nav
	jQuery('.flt_nav').append(jQuery('<div class="flt_nav-mobile">filter</div>'));
	jQuery('.flt_nav-mobile').click(function(){
		jQuery('.flt_nav-list').toggleClass('flt_nav-listactive');
		jQuery('.flt_nav-mobile').toggleClass('flt_nav-mobileactive');
		jQuery('.filter-bg').toggleClass('filter-bg-active');
 	});	
	jQuery('.filter-bg').click(function(){
		jQuery('.flt_nav-list').removeClass('flt_nav-listactive');
		jQuery('.flt_nav-mobile').removeClass('flt_nav-mobileactive');
		jQuery('.filter-bg').removeClass('filter-bg-active');
	})
	jQuery('.ajax-layered li label').click(function(){
		jQuery('.flt_nav-list').removeClass('flt_nav-listactive');
		jQuery('.flt_nav-mobile').removeClass('flt_nav-mobileactive');
		jQuery('.filter-bg').removeClass('filter-bg-active');
	})
  
	
		
}

jQuery(window).load(function (){
	createFilterIcon();
	addToggleEvent();
 	});
	
	