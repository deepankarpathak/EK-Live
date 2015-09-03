// JavaScript Document
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



  