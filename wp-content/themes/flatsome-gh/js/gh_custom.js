// JavaScript Document
$=jQuery.noConflict();

function change_grid_view_url(){
	var str = document.URL;
	if(str.indexOf("?") > 0){
		var txt = "view=grid";
	}else{
		var txt = "?view=grid";
	}
	var str2 = str.replace("view=grid",'');
	var str3 = str2.replace("view=list",'');
	console.log(str3);
	var lastChar = str3.substr(str3.length - 1);
	if(lastChar == "&"){
			var t = "";
		}else{
			var t = "&";
			}
	var str4 = str3 +t+ txt;
str4 = 	str4.replace("&?", "?");
	window.location=str4;
}

function change_list_view_url(){
	var str = document.URL;
	if(str.indexOf("?") > 0){
		var txt = "view=list";
	}else{
		var txt = "?view=list";
	}
	var str2 = str.replace("view=grid",'');
	var str3 = str2.replace("view=list",'');
	console.log(str3);
	var lastChar = str3.substr(str3.length - 1);
	if(lastChar == "&"){
			var t = "";
		}else{
			var t = "&";
			}
	var str4 = str3 +t+ txt;
str4 = 	str4.replace("&?", "?");
	
	
	window.location=str4;
}

// Popular Courses //
jQuery(document).ready(function (){
	jQuery('#edu_popular_courses .tab a').click(function(){ 	
	var data_url = jQuery(this).attr("data-url");	
	jQuery('#edu_topcourses').text(this.text);
	jQuery("#see-all-courses").attr("href", data_url);		 
	
 	});
 });
// Popular Courses //
// enquiry-popup-start //
jQuery(document).ready(function (){	
    jQuery('.bxslider').bxSlider({
				  auto: true,
				  autoControls: true
	});	
 });
	


// enquiry-popup-end //
jQuery(document).ready(function() {
    jQuery(".edu_tabs-menu a").click(function(event) {
        event.preventDefault();
        jQuery(this).parent().addClass("current");
        jQuery(this).parent().siblings().removeClass("current");
        var tab = jQuery(this).attr("href");
        jQuery(".edu_tab-content").not(tab).css("display", "none");
        jQuery(tab).fadeIn();
    });
});


// tabs-ondetail-page //
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
 // tabs-ondetail-page-end //
 
  // logo-slider //
$(window).load(function(){

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
 			
});
  // logo-slider-end //
// As in press slider
jQuery(document).ready(function () {
	var count = $(".press_slider ul li").length;
	var li_width = $(".press_slider ul li").width();
	var ul_width = count * li_width;
	$(".press_slider ul li").css("width",(ul_width/4-3));
	$('.press_slider ul').css({ width: ul_width});
	setInterval(function () {
        moveRight();
    }, 5000);
	function moveRight() {
        $('.press_slider ul').animate({
            left: - li_width
        }, 1000, function () {
            $('.press_slider ul li:first-child').appendTo('.press_slider ul');
            $('.press_slider ul').css('left', '');
        });
    }
});  
// grayslale-effact//
// $(".item img").css({"display":"none");
// On window load. This waits until images have loaded which is essential
	$(window).load(function(){
// Fade in images so there isn't a color "pop" document load and then on window load
	 $("#best_education .column-inner img").animate({opacity:1},100);
// clone image
	$('#best_education .column-inner img').each(function(){
			var el = $(this);
			el.css({"position":"absolute"}).wrap("<div class='img_wrapper' style='display: inline-block'>").clone().addClass('img_grayscale').css({"position":"absolute","z-index":"998","opacity":"0"}).insertBefore(el).queue(function(){
				var el = $(this);
				el.parent().css({"width":this.width,"height":this.height});
				el.dequeue();
			});
			this.src = grayscale(this.src);
		});
// Fade image 
	$('#best_education .column-inner img').mouseover(function(){
	$(this).parent().find('img:first').stop().animate({opacity:1}, 500);
		})
	$('.img_grayscale').mouseout(function(){
	$(this).stop().animate({opacity:0}, 500);
		});
// name-change
		jQuery("#best_education img").mouseover(function(){
 		jQuery(".edu-best").text(jQuery (this).attr("alt"));
		jQuery(".edu-best-name").text(jQuery (this).attr("name"));
 	});		
	});
// Grayscale w canvas method
	function grayscale(src){
        var canvas = document.createElement('canvas');
		var ctx = canvas.getContext('2d');
        var imgObj = new Image();
		imgObj.src = src;
		canvas.width = imgObj.width;
		canvas.height = imgObj.height; 
		ctx.drawImage(imgObj, 0, 0); 
		var imgPixels = ctx.getImageData(0, 0, canvas.width, canvas.height);
		for(var y = 0; y < imgPixels.height; y++){
			for(var x = 0; x < imgPixels.width; x++){
				var i = (y * 4) * imgPixels.width + x * 4;
				var avg = (imgPixels.data[i] + imgPixels.data[i + 1] + imgPixels.data[i + 2]) / 3;
				imgPixels.data[i] = avg; 
				imgPixels.data[i + 1] = avg; 
				imgPixels.data[i + 2] = avg;
			}
		}
		ctx.putImageData(imgPixels, 0, 0, 0, 0, imgPixels.width, imgPixels.height);
		return canvas.toDataURL();
}
	function submitLead_gh(data){
        $.ajax({
            url: "/wp-admin/admin-ajax.php",
            type: 'POST',
            data: {action: 'connect_form', Data:data},
            success: function() {

            }
        });
    }
jQuery(document).ready(function(){
	
	$('.product_detail_referral a, .coupon_successful a').click(function() {
		$(".referal_tc").dialog({
		title: "Terms & Conditions",
		width: 500,
		height: 400,
		top:200,
		modal: true,
		});
		});
	
	setTimeout(function(){var a=document.createElement("script");
	var b=document.getElementsByTagName("script")[0];
	a.src=document.location.protocol+"//script.crazyegg.com/pages/scripts/0028/1146.js?"+Math.floor(new Date().getTime()/3600000);
	a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 2000);
	
	jQuery('.nav-top-link').tooltipster({
		offsetY: 3,
	});
	
	(function() {
	  var con = document.createElement('script'); con.type = 'text/javascript';
	  var host = (document.location.protocol === 'http:') ? 'http://cdn' : 'https://server';
	  con.src = host + '.connecto.io/javascripts/connect.prod.min.js';
	  var s = document.getElementsByTagName('script')[0];
	  s.parentNode.insertBefore(con, s);
	})();
	
	setTimeout(function(){var a=document.createElement("script");
	var b=document.getElementsByTagName("script")[0];
	a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0028/1146.js?"+Math.floor(new Date().getTime()/3600000);
	a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 3000);

	 $(".side-menu-icon").click(function(){
	        $("#masthead").addClass("slide-menu");
	        $(".header-wrapper").css("position","initial");
	        $("html").css("overflow","hidden");
	    });
	    $(".overlay").click(function(){
	        $("#masthead").removeClass("slide-menu");
	        $(".header-wrapper").css("position","relative");
	        $("html").css("overflow","initial");
	    });
	    $(".mobile-side-menu .edu_mainnave > li").click(function(e){
			if($(e.target).parents().hasClass("menu_drop") && $(e.target).parents().hasClass("fkr_nav-active")) return;
			$(".edu_mainnave > li > div").removeClass("fkr_nav-active");
			$(".edu_mainnave > li > span > i").removeClass("fkr_nav-rotate");
		});
		$(".mobile-side-menu .edu_subnave > li").click(function(e){
			if($(e.target).parents().hasClass("edu_subnavebar")) return;
			$(".edu_subnave > li > div").removeClass("fkr_nav-active");
			$(".edu_subnave > li > span > i").removeClass("fkr_nav-rotate");
		});
		$("#browse-cat").click(function(){
			$("#mega-menu").slideToggle();
		});

		/*Algolia filter menu*/
		$("body").on("click", ".filter-icon", function () {
	        $("#algolia_instant_selector").addClass("toggle-filter");
	        $(".header-wrapper").css("display","none");
	        $(".apply").show();
	        $("body").css("overflow","hidden");
	        $(".jPanelMenu").css("overflow","hidden");
	    });
	    $("body").on("click", ".close_filter, .apply ", function () {
	        $("#algolia_instant_selector").removeClass("toggle-filter");
	        $(".header-wrapper").css("display","block");
	        $(".apply").hide();
	        $("body").css("overflow","initial");
	        $(".jPanelMenu").css("overflow","initial");
	    });


	    /*Algolia scrips start*/
		
		//  Labels and refinement
		$("body").on("click", ".labels .close_label", function () {
		    var data_tax = $(this).parent().attr("data-tax");
		    var data_name = $(this).parent().attr("data-name");
		    $(".sub_facet").find("input[type='checkbox']").each(function (i) {
		        if($(this).attr("data-tax") == data_tax && $(this).attr("data-name") == data_name){
		            $(this).prop("checked", false);
		            engine.helper.toggleRefine($(this).attr("data-tax"), $(this).attr("data-name"));
		        }
		    });
		    engine.helper.search(engine.helper.state.query, function(){});
		    $(".raw_labels").find($(".label")).each(function(){
		        if(data_name == $(this).attr("data-name")){
		            $(this).remove();
		        }
		    });
		});
		
		/* Algolia labels AND Banner Images AND University Logo and description*/
		$("body").on("click", ".sub_facet", function () {
			var facet = $(this).find("input[type='checkbox']");
		    facet.each(function (i) {
		        var data_name = $(this).attr("data-name");
		        var data_tax = $(this).attr("data-tax");
		        if($(this).is(':checked') == true){
		            var raw_label_html = $(".raw_labels").html();
		            $(".raw_labels").html(raw_label_html+"<div class='label' data-tax='"+data_tax+"' data-name='"+data_name+"'>"+data_name+"<span class='close_label'>x</span></div>");
		        }
		        else{
		        	$(".raw_labels").find($(".label")).each(function(){
		        		if(data_name == $(this).attr("data-name")){
		        			$(this).remove();
		        		}
		        	});
		        }
		    });

		    if(facet.attr("data-tax") == "product_cat"){
		    	getCategoryBanner(facet);
		    }
		    if(facet.attr("data-tax") == "university"){
		    	getUniversityLogoDesc(facet);
		    }

		});

	        /* Third Party Apis */
	        /*AdRoll code starts */
	        adroll_adv_id = "KFQPYGFXBVFOVMVN7J3KS2";
	        adroll_pix_id = "MZT422LAGFGSDDRJV3RYWM";
	        (function () {
	        var oldonload = window.onload;
	        window.onload = function(){
	           __adroll_loaded=true;
	           var scr = document.createElement("script");
	           var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
	           scr.setAttribute('async', 'true');
	           scr.type = "text/javascript";
	           scr.src = host + "/j/roundtrip.js";
	           ((document.getElementsByTagName('head') || [null])[0] ||
	            document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
	           if(oldonload){oldonload()}};
	        }());
	        /*AdRoll code ends */	
	        var invite_referrals = window.invite_referrals || {}; (function() { 
	        	invite_referrals.auth = { bid_e : 'E1B12D0338B46F598D8123D7C78E9598', bid : '1576', t : '420', email : '', userParams : {'fname': ''}};	
	        var script = document.createElement('script');script.async = true;
	        script.src = (document.location.protocol == 'https:' ? "//d11yp7khhhspcr.cloudfront.net" : "//cdn.invitereferrals.com") + '/js/invite-referrals-1.0.js';
	        var entry = document.getElementsByTagName('script')[0];entry.parentNode.insertBefore(script, entry); })();
	        /*invite*/
	        var invite_referrals = window.invite_referrals || {}; (function() { 
	        	invite_referrals.auth = { 
	        		bid_e : 'E1B12D0338B46F598D8123D7C78E9598', 
	        		bid : '1576', 
	        		t : '420', 
	        		email : '', 
	        		mobile : '', 
	        		userParams : {'fname': '', 'lname': '', 'birthday': '', 'gender': ''}, 
	        		referrerCode : '',
	        		orderID : '', 
	        		purchaseValue : '', 
	        		userCustomParams : {'customValue': '', 'shareLink': '', 'shareTitle': '', 'shareDesc': '', 'shareImg': ''}, 
	        		showWidget : '' 
	        	};
	        	var script = document.createElement('script');script.async = true; script.src = (document.location.protocol == 'https:' ? "//d11yp7khhhspcr.cloudfront.net" : "//cdn.invitereferrals.com") + '/js/invite-referrals-1.0.js'; var entry = document.getElementsByTagName('script')[0];entry.parentNode.insertBefore(script, entry); })(); 
});

// Algolia Functions
        function clear_all(){
		    $(".facets").find("input[type='checkbox']").each(function (i) {
		        $(this).prop("checked", false);
		        engine.helper.clearRefinements($(this).attr("data-tax"));
		        var data_name = $(this).attr("data-name");
		        var data_tax = $(this).attr("data-tax");
		        	$(".raw_labels").find($(".label")).each(function(){
		        		if(data_name == $(this).attr("data-name")){
		        			$(this).remove();
		        		}
		        });
		    });
		    clear_price_slider();
		    engine.helper.search(engine.helper.state.query, function(){});  
		    // Remove banner and university logo and description when clear all filters
		    $(".raw_university_logo_desc .univ_logo img").attr("src", "");
		    $(".raw_university_logo_desc .univ_description").text("");
		    $(".raw_banner_image img").attr("src", default_banner);
		}

		function clear_filter(args){
		    var slider = jQuery(args).nextAll('.scroll-pane').find(".algolia-slider");
		    if(slider[0]){
		        clear_price_slider();
		        engine.helper.search(engine.helper.state.query, function(){});
		    }
		    else{
		        $(args).nextAll('.scroll-pane').find("input[type='checkbox']").each(function (i) {
		            $(this).prop("checked", false);
                    engine.helper.clearRefinements($(this).attr("data-tax"));
                });
		        engine.helper.search(engine.helper.state.query, function(){});
		    } 

		    var facet = jQuery(args).nextAll('.scroll-pane').find("input[type='checkbox']");
		    facet.each(function (i) {
		        var data_name = $(this).attr("data-name");
		        var data_tax = $(this).attr("data-tax");
		        	$(".raw_labels").find($(".label")).each(function(){
		        		if(data_name == $(this).attr("data-name")){
		        			$(this).remove();
		        		}
		        	});
		    });   
		    $(".raw_banner_image img").attr("src", default_banner);	 	
		}

		function filter(args){
		    var course_search = $(args).parent();
		    var scroll_pane = $(course_search).next(".scroll-pane");
		    var len = scroll_pane.find(".options").length;
		    var i=0;
		    var arr = [];
		    var ch = ($(args).val()).trim().toLowerCase();
		    
		    for(i=0; i<len; i++){
		        var child = $(scroll_pane.children(".options")[i]);
		        arr[i] = child['context'].textContent.toLowerCase();
		    }
		    
		    for(i=0; i<len; i++){
		      if(ch.length > 0){  
		          if(arr[i].indexOf(ch) > 0){
		            $(scroll_pane.children(".options")[i]).show();
		          }
		          else{
		            $(scroll_pane.children(".options")[i]).hide();
		          }
		      }
		      else{
		        $(scroll_pane.children(".options")).show();
		      }    
		    }
		}

		function clear_price_slider(){
		    var slide_dom = $(".algolia-slider");
		    engine.helper.removeNumericRefinement(slide_dom.attr("data-tax"), ">=");
		    engine.helper.removeNumericRefinement(slide_dom.attr("data-tax"), "<=");
		}
		$('button').on('click',function(e) {
		    if ($(this).hasClass('grid')) {
		        $('#view li').removeClass('list').addClass('grid');
		    }
		    else if($(this).hasClass('list')) {
		        $('#view li').removeClass('grid').addClass('list');
		    }
		});

		function dock_undock(args){
		    $(args).next('.dock_this').slideToggle();
		    $(args).children('.dock_undock').toggleClass("dock_down");;
		}

		// Category banner
		function getCategoryBanner(facet){
			var parent = facet.parent().parent().parent();
		    var checkboxes = parent.find("input[type='checkbox']");
		    if(facet.is(':checked') == true){
	   			var data_name = facet.attr("data-name");
		        for(var i=0; i<cat_banners.length; i++){
		            if(cat_banners[i].name == data_name){
		                if(cat_banners[i].banner.substr(cat_banners[i].banner.length - 3) == "jpg" || cat_banners[i].banner.substr(cat_banners[i].banner.length - 3) == "png"){
		                    $(".raw_banner_image img").attr("src", cat_banners[i].banner);
		                    $(".raw_university_logo_desc .univ_logo img").attr("src", "");
		   					$(".raw_university_logo_desc .univ_description").text("");
		                }
		                else{
		                	$(".raw_banner_image img").attr("src", default_banner);	
		                }
		            }
		        }
		    }else{
		    	var count = 0;
		    	var data_name;
		    	checkboxes.each(function (i) {
		    		if($(this).is(':checked')){
		    			data_name = $(this).attr("data-name");
		    			count++;
		    		}
		    	});
		    	if(count == 1){
		    		for(var i=0; i<cat_banners.length; i++){
			            if(cat_banners[i].name == data_name){
			                if(cat_banners[i].banner.substr(cat_banners[i].banner.length - 3) == "jpg" || cat_banners[i].banner.substr(cat_banners[i].banner.length - 3) == "png"){
			                    $(".raw_banner_image img").attr("src", cat_banners[i].banner);
			                    $(".raw_university_logo_desc .univ_logo img").attr("src", "");
			   					$(".raw_university_logo_desc .univ_description").text("");
			                }
			                else{
			                	$(".raw_banner_image img").attr("src", default_banner);	
			                }
			            }
		        	}
		        }
		        else{
		      		$(".raw_banner_image img").attr("src", default_banner);	
		      	}
		    }
		}
		// University LOGO and Description
		function getUniversityLogoDesc(facet){
			var parent = facet.parent().parent().parent(); 
		    var checkboxes = parent.find("input[type='checkbox']");
		    if(facet.is(':checked') == true){
	   			var data_name = facet.attr("data-name");
		        for(var i=0; i<university_data.length; i++){
		            if(university_data[i].name == data_name){
		                if((university_data[i].logo.substr(university_data[i].logo.length - 3) == "jpg" || university_data[i].logo.substr(university_data[i].logo.length - 3) == "png") && university_data[i].description != ""){
		                    $(".raw_university_logo_desc .univ_logo img").attr("src", university_data[i].logo);
		                    $(".raw_university_logo_desc .univ_description").html("<h2 class='univ_name'>"+university_data[i].name+"</h2>"+university_data[i].description);
		                    $(".raw_banner_image img").attr("src", "");
		                }
	                	else{
	                		$(".raw_university_logo_desc .univ_logo img").attr("src", "");
		                    $(".raw_university_logo_desc .univ_description").text("");
	                	    $(".raw_banner_image img").attr("src", default_banner);
	                	}	
		            }
		        }
		    }
		    else{
		    	$(".raw_university_logo_desc .univ_logo img").attr("src", "");
		        $(".raw_university_logo_desc .univ_description").text("");
	            $(".raw_banner_image img").attr("src", default_banner);
		    	$(".raw_banner_image img").attr("src", default_banner);
		    }
		}
// Algolia functions end
