/*
    File Name: tpcrn_scripts.js
    Author : Raja CRN
	by ThemePacific
 */
 jQuery(document).ready(function () {
  	  jQuery('.tabs_container2 .tab_content2:first').show();
        jQuery('.tabs2 li:first').addClass('active');
        jQuery.each(jQuery('.tabs2 li'),function(i,el){
            jQuery(el).click(function(){
                jQuery('.tabs_container2 .tab_content2').slideUp();
                jQuery('.tabs_container2 .tab_content2').eq(i).slideDown();
                jQuery('.tabs2 li').removeClass('active');
                jQuery(this).addClass('active');
                 return false;
            });
         });
 /*Create the dropdown bases thanks to @chriscoyier*/
jQuery("#catnav.secondary").append('<select class="resp-nav container">');
	/* Create default option "Go to..."*/
	jQuery("<option />", {
	"selected": "selected",
	"value"   : "",
	"text"    : "Select Menu"
	}).appendTo("#catnav.secondary select");
/* Populate dropdowns with the first menu items*/
jQuery("#catnav.secondary li ").each(function() {
	var href = jQuery(this).children('a').attr('href');
	var text = jQuery(this).children('a').html();
	var depth = jQuery(this).parents('ul').length;
	text = (depth > 1) ?   ' &nbsp;&mdash; ' + text : text;
	text = (depth > 2) ?   '&nbsp;&nbsp;'+ text : text;
	text = (depth > 3) ?   '&nbsp;&nbsp;&nbsp;&mdash;'+ text : text;
	 jQuery("#catnav.secondary select").append('<option value="' + href + '">' + text + '</option>');
});
/*make responsive dropdown menu actually work			*/
jQuery("#catnav.secondary select").change(function() {
	window.location = jQuery(this).find("option:selected").val();
});

/*cat nav menu*/
 
jQuery("#catnav ul li:has(ul)").addClass("parent"); 
 jQuery(".catnav li").hover(function () {
 jQuery(this).has('ul').addClass("dropme");
 jQuery(this).find('ul:first').css({display: "none"}).stop(true, true).slideDown(500);}, function () {
 jQuery(this).removeClass("dropme");
 jQuery(this).find('ul:first').css({display: "block"}).stop(true, true).slideUp(1000);
 });
});