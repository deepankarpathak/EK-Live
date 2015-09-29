<?php
/*
Template name: Guided Search
Use this for Cart, Checkout and thank you page.
*/
$scripts = array(
            '/wp-content/themes/flatsome-gh/js/algolia/algoliasearch.min.js',
        );

        foreach ($scripts as $script) {	
            wp_enqueue_script("guided",$script, get_site_url() . $script, array());
        }
get_header(); ?>

<div  class="page-wrapper page-guided-search">
<div class="row">
<div id="content" class="large-12 columns entry-content" role="main">

<div class="guided-search">
	<h2 class="guided-search-title">one search for education in india</h2>
	<div class="courses-offered-title">
		<div class="img-wrapper">
			<img src="http://edukart.com/wp-content/uploads/2015/01/1000o-plus-enrolled-students.png">
		</div>
		<span id="msg"><strong>Good Morning!</strong> Choose from variety of long term & short term courses we offer.</span>
	</div>
<!-- Course wrapper start-->
	<div class="loading-wait"><img src="<?php echo get_site_url();?>/wp-content/themes/flatsome-gh/images/loader.gif"></div>
	<div class="guided-block">
	
	 	<div class="offered-course-wrapper clearfix">
			<div class="col-sm-6">
				<div class="content-title col-sm-4" onclick="school_education(this)">
					<svg class="icon icon-SchoolEducation"><use xlink:href="<?php echo get_site_url();?>/wp-content/themes/flatsome-gh/images/svg/sprite.svg#icon-SchoolEducation"></use></svg>
					<div>School Education</div>
				</div>
				<div class="content-title col-sm-4" onclick="entrance_coaching(this)">
					<svg class="icon icon-EntranceCoching"><use xlink:href="<?php echo get_site_url();?>/wp-content/themes/flatsome-gh/images/svg/sprite.svg#icon-EntranceCoching"></use></svg>
					<div>Entrance Coaching</div>
				</div>
				<div class="content-title col-sm-4" onclick="degreeflow(this)">
					<svg class="icon icon-CertificateCourses"><use xlink:href="<?php echo get_site_url();?>/wp-content/themes/flatsome-gh/images/svg/sprite.svg#icon-CertificateCourses"></use></svg>
					<div>Degree Programs</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="content-title col-sm-4" onclick="certificate(this)">
					<svg class="icon icon-CertificateCourses"><use xlink:href="<?php echo get_site_url();?>/wp-content/themes/flatsome-gh/images/svg/sprite.svg#icon-CertificateCourses"></use></svg>
					<div>Certificate Courses</div>
				</div>
				<div class="content-title col-sm-4" onclick="diploma(this)" >
					<svg class="icon icon-DiplomaCourses"><use xlink:href="<?php echo get_site_url();?>/wp-content/themes/flatsome-gh/images/svg/sprite.svg#icon-DiplomaCourses"></use></svg>
					<div>Diploma Courses</div>
				</div>
				<div class="content-title col-sm-4" onclick="free_courses(this)">
					<svg class="icon icon-FreeCourses"><use xlink:href="<?php echo get_site_url();?>/wp-content/themes/flatsome-gh/images/svg/sprite.svg#icon-FreeCourses"></use></svg>
					<div>Free Courses</div>
				</div>
			</div>
		</div>

	<!-- ug-pg wrapper start-->

	
		<div class="ug-pg-wrapper clearfix" id="ug-pg-wrapper" style="display: none">
			<div class="content-title col-sm-offset-3 col-sm-3">
				<div class="ug-pg-course-title">UG</div>
				<div>Under<br>Graduate</div>
			</div>
			<div class="content-title col-sm-3">
				<div class="ug-pg-course-title">PG</div>
				<div>Post<br>Graduate</div>
			</div>
		</div>

 <!-- ug-pg wrapper end-->


	<!-- Exam-center wrapper start-->
	
		<div class="exam-center-wrapper" id="exam-center-wrapper" style="display: none">
			<ul>
				<li class="content-title">Delhi</li>					
				<li class="content-title">Mumbai</li>					
				<li class="content-title">Kolkata</li>					
				<li class="content-title">Chennai</li>					
				<li class="content-title">Bangalore</li>					
				<li class="content-title">Jaipur</li>					
				<li class="content-title">Lukhnow</li>					
			</ul>
			<div class="guided-search-bar-wrapper" id="exam-center-input">
				<input type="text" class="guided-search-bar" placeholder="or search your specialisation">
			</div>
		</div>
	
	<!-- Exam-center wrapper end-->

	<!-- Study mode wrapper start-->
	 
		<div class="study-mode-wrapper clearfix" id="study-mode-wrapper" style="display: none">
			<div class="content-title col-sm-offset-3 col-sm-3" id="study-mode-wrapper" style="display:none">
				<svg class="icon icon-FreeCourses"><use xlink:href="<?php echo get_site_url();?>/wp-content/themes/flatsome-gh/images/svg/sprite.svg#icon-FreeCourses"></use></svg>
				<div>Online<br>Mode</div>
			</div>
			<div class="content-title col-sm-3">
				<svg class="icon icon-FreeCourses"><use xlink:href="<?php echo get_site_url();?>/wp-content/themes/flatsome-gh/images/svg/sprite.svg#icon-FreeCourses"></use></svg>
				<div>Distance<br>Mode</div>
			</div>
		</div>
	 
	 <!-- Study mode wrapper end-->


	<!-- Degree wrapper start-->
	
		<div class="degree-wrapper clearfix"  id="degree-wrapper" style="display: none">
			<ul>
				<li><label class="degree-title" for="mscit">MSCIT<input id="mscit" type="radio" name="degree"><span></span></label></li>					
				<li><label class="degree-title" for="ma">MA<input id="ma" type="radio" name="degree"><span></span></label></li>					
				<li><label class="degree-title" for="mba">MBA<input id="mba" type="radio" name="degree"><span></span></label></li>					
				<li><label class="degree-title" for="mca">MCA<input id="mca" type="radio" name="degree"><span></span></label></li>					
				<li><label class="degree-title" for="mtech">Mtech<input id="mtech" type="radio" name="degree"><span></span></label></li>					
			</ul>
			<input type="text" class="guided-search-bar" placeholder="or search your specialisation">
		</div>
	
	 <!-- Degree wrapper end-->

	<!-- Specialisation wrapper start-->
	
		<div class="specialisation-wrapper clearfix" id="specialisation-wrapper" style="display:none">
			<ul>
				<li><label class="degree-title" for="mscit">MSCIT<input id="mscit" type="radio" name="degree"><span></span></label></li>					
				<li><label class="degree-title" for="ma">MA<input id="ma" type="radio" name="degree"><span></span></label></li>					
				<li><label class="degree-title" for="mba">MBA<input id="mba" type="radio" name="degree"><span></span></label></li>					
				<li><label class="degree-title" for="mca">MCA<input id="mca" type="radio" name="degree"><span></span></label></li>					
				<li><label class="degree-title" for="mtech">Mtech<input id="mtech" type="radio" name="degree"><span></span></label></li>					
				<li><label class="degree-title" for="mscit">MSCIT<input id="mscit" type="radio" name="degree"><span></span></label></li>					
				<li><label class="degree-title" for="ma">MA<input id="ma" type="radio" name="degree"><span></span></label></li>					
				<li><label class="degree-title" for="mba">MBA<input id="mba" type="radio" name="degree"><span></span></label></li>					
				<li><label class="degree-title" for="mca">MCA<input id="mca" type="radio" name="degree"><span></span></label></li>					
				<li><label class="degree-title" for="mtech">Mtech<input id="mtech" type="radio" name="degree"><span></span></label></li>					
			</ul>
			<input type="text" class="guided-search-bar" id="spec-input" placeholder="or search your specialisation">
		</div>
		<ul class="guided-bredcrums-wrapper">
		</ul>
		<span id="back"></span>
	 <!-- specialisation wrapper end-->
</div>


</div>

</div><!-- end #content large-12 -->
</div><!-- end row -->
</div><!-- end page-right-sidebar container -->
<script type="text/javascript">
var client = algoliasearch('T265CTWUG6', '470b300485f387662f74b0d7d4b5bb10');
var index = client.initIndex('ekdpliveall');

var step=0;
var stepevents=new Array();
var cities=new Array();
var specialization=new Array();
var filter_array=new Array();
var query="";
var previous_step=new Array();
var ug_pg="";
var path=new Array();


function show_waiting()
{
	jQuery(".loading-wait").show();
}

function hide_waiting()
{
	jQuery(".loading-wait").hide();
}
function degreeflow(obj)
{
	step=1;
	query="";ug_pg="";
	msg("Choose from Post Graduate / Under Graduate ?");
	jQuery(".offered-course-wrapper").hide();
	jQuery("#ug-pg-wrapper").show();
	jQuery(".guided-bredcrums-wrapper").html('<li class="guided-bredcrums">'+jQuery(obj).find("div").html()+"</li>");
	jQuery("#ug-pg-wrapper .content-title").on("click",function(e){
			jQuery(this).find(".ug-pg-course-title").parent().addClass("active");
			show_waiting();
			if(jQuery(this).find(".ug-pg-course-title").html() == "UG"){
				ug_pg="UG";
				filter_array.push("product_cat:Under Graduate");
				index.search(query, {
		            facets: '*',facetFilters:filter_array}, ugpgflow);
			}
			else
			{
				ug_pg="PG";
				filter_array.push("product_cat:Post Graduate");
				index.search(query, {
		            facets: '*',facetFilters:filter_array}, ugpgflow);
			}
			jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).find(".ug-pg-course-title").html()+"</li>");
		});
		
}
function ugpgflow(err,content){
	m=content;
	if(content.facets['pa_exam-center']!=undefined){
		jQuery("#ug-pg-wrapper").hide();
		i=0;str="";
		msg("Choose Exam Center :");
		cities=new Array();
		for(center in content.facets['pa_exam-center'])
		{
			if(++i<6)
				str+='<li class="content-title">'+center+'</li>';
			cities.push(center);
		}
		hide_waiting();
		jQuery("#exam-center-wrapper ul").html(str);
		jQuery("#exam-center-wrapper").show();
		jQuery("#exam-center-input").autocomplete({
		    source: cities,
		    select: function (event, ui) {
		        var label = ui.item.label;
		        var value = ui.item.value;
		        show_waiting();
		       jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+value+"</li>");
		       filter_array.push("pa_exam-center:"+value);
		       index.search(query, {
		            facets: '*',facetFilters:filter_array}, ugpg_city);	
		    }
		});
		jQuery("#exam-center-wrapper ul li").on("click",function(){
			jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).html()+"</li>");
			filter_array.push("pa_exam-center:"+jQuery(this).html());
			show_waiting();
			index.search(query, {
	            facets: '*',facetFilters:filter_array}, ugpg_city);
			});
	}else
	{
		if(!content.nbHits)
			filter_array.pop();
		finalresult();
	}
}
function ugpg_city(err,content)
{
	if(content.facets['pa_exam-mode']!=undefined){
		msg("Choose Study Content :");
		jQuery("#exam-center-wrapper").hide();
		jQuery("#study-mode-wrapper").show();
		i=1;str="";
		for(mode in content.facets['pa_exam-mode'])
		{
			console.log(mode);
			if(i==1 && Object.keys(content.facets['pa_exam-mode']).length < 4)
				str+='<div class="content-title col-sm-offset-3 col-sm-3">'+mode+'</div>';
			else
				str+='<div class="content-title col-sm-3">'+mode+'</div>';
			i++;
		}
		hide_waiting();
		jQuery("#study-mode-wrapper").html(str);
		stepevents[++step]=jQuery("#study-mode-wrapper div").on("click",function(){
			jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).html()+"</li>");
			ugpg_course();});
	}else
	{
		if(!content.nbHits)
			filter_array.pop();
		finalresult();
	}
}

function ugpg_course()
{
		var courses=new Array();
		jQuery("#study-mode-wrapper").hide();
		jQuery("#degree-wrapper").show();
		i=1;str="";
		
		if(ug_pg=="PG")
		{
			msg("Choose Post Graduate Course:");
			courses=['MBA','MCA','MSc','MA','MCom','LLM'];
		}
		else{
			msg("Choose Under Graduate Course:");
			courses=['BBA','BCA','BSc','BA','BCom'];
		}
		for(i in courses)
		{
				str+='<li><label class="degree-title" for="'+courses[i]+'">'+courses[i]+'<input id="'+courses[i]+'" type="radio" name="degree"><span></span></label></li>';
		}
		jQuery("#degree-wrapper ul").html(str);

		stepevents[++step]=jQuery("#degree-wrapper ul li input").on("click",function(e){
			jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+courses[jQuery(this).parents("li").index()]+"</li>");
			query=courses[jQuery(this).parents("li").index()];
			show_waiting();
			index.search(courses[jQuery(this).parents("li").index()], {
	            facets: '*',facetFilters:filter_array}, ugpc_specialization);
			});
}

function ugpc_specialization(err,content)
{
	mm=content;
	if(content.facets['pa_specialization']!=undefined){
		msg("Choose Specialization:");
		jQuery("#degree-wrapper").hide();
		jQuery("#specialisation-wrapper").show();
		i=1;str="";
		specialization=new Array();
		for(spec in content.facets['pa_specialization'])
		{
			if(++i<5)
				str+='<li><label class="degree-title" for="'+spec+'">'+spec+'<input id="'+spec+'" type="radio" name="degree"><span></span></label></li>';
			specialization.push(spec);
		}
		hide_waiting();
		jQuery("#specialisation-wrapper ul").html(str);

		stepevents[++step]=jQuery("#specialisation-wrapper ul li input").on("click",function(e){
			jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).attr("id")+"</li>");
			filter_array.push("pa_specialization:"+jQuery(this).attr("id"));
			finalresult();
		});
		jQuery("#spec-input").autocomplete({
		    source: specialization,
		    select: function (event, ui) {
		        var label = ui.item.label;
		        var value = ui.item.value;
		        filter_array.push("pa_specialization:"+value);
		       	jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+value+"</li>");
		       	show_waiting();
		       	finalresult();
		    }
		});			
	}
	else {
		if(!content.nbHits)
			filter_array.pop();
		finalresult();
		}
}

function school_education(obj)
{
	step=1;
	query="";
	filter_array=new Array();
	jQuery(".offered-course-wrapper").hide();
	jQuery(".guided-bredcrums-wrapper").html('<li class="guided-bredcrums">'+jQuery(obj).find("div").html()+"</li>");
	show_waiting();
	filter_array.push("product_cat:School Education");
	index.search(query, {facets: '*',facetFilters:filter_array}, school_education_step2);
}
function school_education_step2(err,content)
{
	mm=content;
	if(content.facets['pa_specialization']!=undefined){
		msg("Choose Specialization:");
		jQuery("#degree-wrapper").hide();
		jQuery("#specialisation-wrapper").show();
		i=1;str="";
		specialization=new Array();
		for(spec in content.facets['pa_specialization'])
		{
			if(i++<5)
				str+='<li><label class="degree-title" for="'+spec+'">'+spec+'<input id="'+spec+'" type="radio" name="degree"><span></span></label></li>';
			specialization.push(spec);
		}
		hide_waiting();
		jQuery("#specialisation-wrapper ul").html(str);

		jQuery("#specialisation-wrapper ul li input").on("click",function(e){
			jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).attr("id")+"</li>");
			filter_array.push("pa_specialization:"+jQuery(this).attr("id"));
			index.search(query, {facets: '*',facetFilters:filter_array}, school_education_step3);
		});
		jQuery("#spec-input").autocomplete({
		    source: specialization,
		    select: function (event, ui) {
		        var label = ui.item.label;
		        var value = ui.item.value;
		        filter_array.push("pa_specialization:"+value);
		       	jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+value+"</li>");
		       	show_waiting();
		       	index.search(query, {facets: '*',facetFilters:filter_array}, school_education_step3);
		    }
		});			
	}
	else {
		    if(!content.nbHits)
			filter_array.pop();
			finalresult();
		}

}
function school_education_step3(err,content){

	if(content.facets['pa_study-content']!=undefined){
		msg("Choose Study Mode:");
		a=content;
		jQuery("#specialisation-wrapper").hide();
		jQuery("#study-mode-wrapper").show();
		i=1;str="";
		for(mode in content.facets['pa_study-content'])
		{
			console.log(mode);
			if(i==1 && Object.keys(content.facets['pa_study-content']).length < 4)
				str+='<div class="content-title col-sm-offset-3 col-sm-3">'+mode+'</div>';
			else
				str+='<div class="content-title col-sm-3">'+mode+'</div>';
			i++;
		}
		hide_waiting();
		jQuery("#study-mode-wrapper").html(str);
		jQuery("#study-mode-wrapper div").on("click",function(){
		jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).html()+"</li>");
		show_waiting();
		filter_array.push("pa_study-content:"+jQuery(this).html());
		index.search(query, {facets: '*',facetFilters:filter_array}, school_education_step4);	

			});
	}else
	{
		if(!content.nbHits)
			filter_array.pop();
		finalresult();
	}
	
}

function school_education_step4(err,content){

	if(content.facets['university']!=undefined){
		msg("Choose Institute:");
		jQuery("#exam-center-wrapper").hide();
		jQuery("#study-mode-wrapper").show();
		i=1;str="";
		for(mode in content.facets['university'])
		{
			console.log(mode);
			if(i==1 && Object.keys(content.facets['university']).length < 3)
				str+='<div class="content-title col-sm-offset-3 col-sm-3">'+mode+'</div>';
			else
				str+='<div class="content-title col-sm-3">'+mode+'</div>';
			i++;
		}
		jQuery("#study-mode-wrapper").html(str);
		hide_waiting();
		jQuery("#study-mode-wrapper div").on("click",function(){
		jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).html()+"</li>");
		show_waiting();
		finalresult();
			});
	}else
	{
		if(!content.nbHits)
			filter_array.pop();
		finalresult();
	}
	
}
function entrance_coaching(obj)
{
	step=1;
	query="";
	filter_array=new Array();
	jQuery(".offered-course-wrapper").hide();
	jQuery(".guided-bredcrums-wrapper").html('<li class="guided-bredcrums">'+jQuery(obj).find("div").html()+"</li>");
	show_waiting();
	filter_array.push("product_cat:Entrance Coaching");
	index.search(query, {facets: '*',facetFilters:filter_array}, entrance_coaching_step2);
	
}

function entrance_coaching_step2(err,content)
{
	if(content.facets['pa_specialization']!=undefined){
		msg("Choose Specialization:");
		jQuery("#degree-wrapper").hide();
		jQuery("#specialisation-wrapper").show();
		i=1;str="";
		specialization=new Array();
		for(spec in content.facets['pa_specialization'])
		{
			if(i++<5)
				str+='<li><label class="degree-title" for="'+spec+'">'+spec+'<input id="'+spec+'" type="radio" name="degree"><span></span></label></li>';
			specialization.push(spec);
		}
		hide_waiting();
		jQuery("#specialisation-wrapper ul").html(str);

		jQuery("#specialisation-wrapper ul li input").on("click",function(e){
			jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).attr("id")+"</li>");
			filter_array.push("pa_specialization:"+jQuery(this).attr("id"));
			index.search(query, {facets: '*',facetFilters:filter_array}, entrance_coaching_step3);
		});
		jQuery("#spec-input").autocomplete({
		    source: specialization,
		    select: function (event, ui) {
		        var label = ui.item.label;
		        var value = ui.item.value;
		        filter_array.push("pa_specialization:"+value);
		       	jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+value+"</li>");
		       	show_waiting();
		       	index.search(query, {facets: '*',facetFilters:filter_array}, entrance_coaching_step3);
		    }
		});			
	}
	else {
		    if(!content.nbHits)
			filter_array.pop();
			finalresult();
		}
}

function entrance_coaching_step3(err,content){

	if(content.facets['pa_study-content']!=undefined){
		a=content;
		msg("Choose Study Content:");
		jQuery("#specialisation-wrapper").hide();
		jQuery("#study-mode-wrapper").show();
		i=1;str="";
		for(mode in content.facets['pa_study-content'])
		{
			if(i==1 && Object.keys(content.facets['pa_study-content']).length < 3)
				str+='<div class="content-title col-sm-offset-3 col-sm-3">'+mode+'</div>';
			else
				str+='<div class="content-title col-sm-3">'+mode+'</div>';
			i++;
		}
		hide_waiting();
		jQuery("#study-mode-wrapper").html(str);
		jQuery("#study-mode-wrapper div").on("click",function(){
		jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).html()+"</li>");
		show_waiting();
		filter_array.push("pa_study-content:"+jQuery(this).html());
		index.search(query, {facets: '*',facetFilters:filter_array}, entrance_coaching_step4);	

			});
	}else
	{
		if(!content.nbHits)
			filter_array.pop();
		finalresult();
	}

}

function entrance_coaching_step4(err,content){
	
	if(content.facets['shop_vendor']!=undefined){
		msg("Choose Course Provider:");
		jQuery("#study-mode-wrapper").show();
		i=1;str="";
		for(mode in content.facets['shop_vendor'])
		{
			if(i==1 && Object.keys(content.facets['shop_vendor']).length < 3)
				str+='<div class="content-title col-sm-offset-3 col-sm-3">'+mode+'</div>';
			else
				str+='<div class="content-title col-sm-3">'+mode+'</div>';
			i++;
		}
		hide_waiting();
		jQuery("#study-mode-wrapper").html(str);
		jQuery("#study-mode-wrapper div").on("click",function(){
		jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).html()+"</li>");
			show_waiting();
			filter_array.push("shop_vendor:"+jQuery(this).html());
			finalresult();
		});
	}else
	{
		if(!content.nbHits)
			filter_array.pop();
		finalresult();
	}
}
/*Certificate*/
 function certificate(obj)
 {
 	step=1;
 	query="";
 	filter_array=new Array();
 	jQuery(".offered-course-wrapper").hide();
 	jQuery(".guided-bredcrums-wrapper").html('<li class="guided-bredcrums">'+jQuery(obj).find("div").html()+"</li>");
 	show_waiting();
 	filter_array.push("product_cat:Certificate");
 	index.search(query, {facets: '*',facetFilters:filter_array}, certificate_step2);
 	
 }

 function certificate_step2(err,content)
 {
 	if(content.facets['pa_specialization']!=undefined){
 		msg("Choose Specialization:");
 		jQuery("#degree-wrapper").hide();
 		jQuery("#specialisation-wrapper").show();
 		i=1;str="";
 		specialization=new Array();
 		for(spec in content.facets['pa_specialization'])
 		{
 			if(i++<5)
 				str+='<li><label class="degree-title" for="'+spec+'">'+spec+'<input id="'+spec+'" type="radio" name="degree"><span></span></label></li>';
 			specialization.push(spec);
 		}
 		hide_waiting();
 		jQuery("#specialisation-wrapper ul").html(str);

 		jQuery("#specialisation-wrapper ul li input").on("click",function(e){
 			jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).attr("id")+"</li>");
 			filter_array.push("pa_specialization:"+jQuery(this).attr("id"));
 			index.search(query, {facets: '*',facetFilters:filter_array}, certificate_step3);
 		});
 		jQuery("#spec-input").autocomplete({
 		    source: specialization,
 		    select: function (event, ui) {
 		        var label = ui.item.label;
 		        var value = ui.item.value;
 		        filter_array.push("pa_specialization:"+value);
 		       	jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+value+"</li>");
 		       	show_waiting();
 		       	index.search(query, {facets: '*',facetFilters:filter_array}, certificate_step3);
 		    }
 		});			
 	}
 	else {
 		    if(!content.nbHits)
 			filter_array.pop();
 			finalresult();
 		}
 }

 function certificate_step3(err,content){

 	if(content.facets['pa_study-content']!=undefined){
 		a=content;
 		msg("Choose Study Content:");
 		jQuery("#specialisation-wrapper").hide();
 		jQuery("#study-mode-wrapper").show();
 		i=1;str="";
 		for(mode in content.facets['pa_study-content'])
 		{
 			if(i==1 && Object.keys(content.facets['pa_study-content']).length < 3)
 				str+='<div class="content-title col-sm-offset-3 col-sm-3">'+mode+'</div>';
 			else
 				str+='<div class="content-title col-sm-3">'+mode+'</div>';
 			i++;
 		}
 		hide_waiting();
 		jQuery("#study-mode-wrapper").html(str);
 		jQuery("#study-mode-wrapper div").on("click",function(){
 		jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).html()+"</li>");
 		show_waiting();
 		filter_array.push("pa_study-content:"+jQuery(this).html());
 		index.search(query, {facets: '*',facetFilters:filter_array}, certificate_step4);	

 			});
 	}else
 	{
 		if(!content.nbHits)
 			filter_array.pop();
 		finalresult();
 	}

 }

 function certificate_step4(err,content){
 	
 	if(content.facets['pa_exam-mode']!=undefined){
 		msg("Choose Exam Mode:");
 		jQuery("#study-mode-wrapper").show();
 		i=1;str="";
 		for(mode in content.facets['pa_exam-mode'])
 		{
 			if(i==1 && Object.keys(content.facets['pa_exam-mode']).length < 3)
 				str+='<div class="content-title col-sm-offset-3 col-sm-3">'+mode+'</div>';
 			else
 				str+='<div class="content-title col-sm-3">'+mode+'</div>';
 			i++;
 		}
 		hide_waiting();
 		jQuery("#study-mode-wrapper").html(str);
 		jQuery("#study-mode-wrapper div").on("click",function(){
 		jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).html()+"</li>");
 			show_waiting();
 			filter_array.push("pa_exam-mode:"+jQuery(this).html());
 			finalresult();
 		});
 	}else
 	{
 		if(!content.nbHits)
 			filter_array.pop();
 		finalresult();
 	}
 }

 /*diploma*/
  function diploma(obj)
  {
  	step=1;
  	query="";
  	filter_array=new Array();
  	jQuery(".offered-course-wrapper").hide();
  	jQuery(".guided-bredcrums-wrapper").html('<li class="guided-bredcrums">'+jQuery(obj).find("div").html()+"</li>");
  	show_waiting();
  	query="diploma";
  	index.search("diploma", {facets: '*',facetFilters:filter_array}, diploma_step2);
  	
  }

  function diploma_step2(err,content)
  {
  	if(content.facets['pa_specialization']!=undefined){
  		msg("Choose Specialization:");
  		jQuery("#degree-wrapper").hide();
  		jQuery("#specialisation-wrapper").show();
  		i=1;str="";
  		specialization=new Array();
  		for(spec in content.facets['pa_specialization'])
  		{
  			if(i++<5)
  				str+='<li><label class="degree-title" for="'+spec+'">'+spec+'<input id="'+spec+'" type="radio" name="degree"><span></span></label></li>';
  			specialization.push(spec);
  		}
  		hide_waiting();
  		jQuery("#specialisation-wrapper ul").html(str);

  		jQuery("#specialisation-wrapper ul li input").on("click",function(e){
  			jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).attr("id")+"</li>");
  			filter_array.push("pa_specialization:"+jQuery(this).attr("id"));
  			index.search(query, {facets: '*',facetFilters:filter_array}, diploma_step3);
  		});
  		jQuery("#spec-input").autocomplete({
  		    source: specialization,
  		    select: function (event, ui) {
  		        var label = ui.item.label;
  		        var value = ui.item.value;
  		        filter_array.push("pa_specialization:"+value);
  		       	jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+value+"</li>");
  		       	show_waiting();
  		       	index.search(query, {facets: '*',facetFilters:filter_array}, diploma_step3);
  		    }
  		});			
  	}
  	else {
  		    if(!content.nbHits)
  			filter_array.pop();
  			finalresult();
  		}
  }

  function diploma_step3(err,content){

  	if(content.facets['pa_study-content']!=undefined){
  		a=content;
  		msg("Choose Study Content:"); 
  		jQuery("#specialisation-wrapper").hide();
  		jQuery("#study-mode-wrapper").show();
  		i=1;str="";
  		for(mode in content.facets['pa_study-content'])
  		{
  			if(i==1 && Object.keys(content.facets['pa_study-content']).length < 3)
  				str+='<div class="content-title col-sm-offset-3 col-sm-3">'+mode+'</div>';
  			else
  				str+='<div class="content-title col-sm-3">'+mode+'</div>';
  			i++;
  		}
  		hide_waiting();
  		jQuery("#study-mode-wrapper").html(str);
  		jQuery("#study-mode-wrapper div").on("click",function(){
  		jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).html()+"</li>");
  		show_waiting();
  		filter_array.push("pa_study-content:"+jQuery(this).html());
  		index.search(query, {facets: '*',facetFilters:filter_array}, diploma_step4);	

  			});
  	}else
  	{
  		if(!content.nbHits)
  			filter_array.pop();
  		finalresult();
  	}

  }

  function diploma_step4(err,content){
  	
  	if(content.facets['university']!=undefined){
  		msg("Choose Institute:");
  		jQuery("#study-mode-wrapper").show();
  		i=1;str="";
  		for(mode in content.facets['university'])
  		{
  			if(i==1 && Object.keys(content.facets['university']).length < 3)
  				str+='<div class="content-title col-sm-offset-3 col-sm-3">'+mode+'</div>';
  			else
  				str+='<div class="content-title col-sm-3">'+mode+'</div>';
  			i++;
  		}
  		hide_waiting();
  		jQuery("#study-mode-wrapper").html(str);
  		jQuery("#study-mode-wrapper div").on("click",function(){
  		jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).html()+"</li>");
  			show_waiting();
  			filter_array.push("university:"+jQuery(this).html());
  			finalresult();
  		});
  	}else
  	{
  		if(!content.nbHits)
  			filter_array.pop();
  		finalresult();
  	}
  }
function free_courses(obj){

	step=1;
 	query="";
 	filter_array=new Array();
 	jQuery(".offered-course-wrapper").hide();
 	jQuery(".guided-bredcrums-wrapper").html('<li class="guided-bredcrums">'+jQuery(obj).find("div").html()+"</li>");
 	show_waiting();
 	filter_array.push("_price:0");
 	index.search(query, {facets: '*',facetFilters:filter_array}, free_courses_step2);
}

function free_courses_step2(err,content){

	if(content.facets['university']!=undefined){
		msg("Choose Institute:");
		jQuery("#study-mode-wrapper").show();
  		i=1;str="";
  		for(mode in content.facets['university'])
  		{
  			if(i==1 && Object.keys(content.facets['university']).length < 3)
  				str+='<div class="content-title col-sm-offset-3 col-sm-3">'+mode+'</div>';
  			else
  				str+='<div class="content-title col-sm-3">'+mode+'</div>';
  			i++;
  		}
  		hide_waiting();
  		jQuery("#study-mode-wrapper").html(str);
  		jQuery("#study-mode-wrapper div").on("click",function(){
  		jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).html()+"</li>");
  			show_waiting();
  			filter_array.push("university:"+jQuery(this).html());
  			index.search(query, {facets: '*',facetFilters:filter_array}, free_courses_step3);
  		});
  	}else
  	{
  		if(!content.nbHits)
  			filter_array.pop();
  		finalresult();
  	}
}
function free_courses_step3(err,content){

	if(content.facets['pa_specialization']!=undefined){
		msg("Choose Specialization:");
  		jQuery("#study-mode-wrapper").hide();
  		jQuery("#specialisation-wrapper").show();
  		i=1;str="";
  		specialization=new Array();
  		for(spec in content.facets['pa_specialization'])
  		{
  			if(i++<5)
  				str+='<li><label class="degree-title" for="'+spec+'">'+spec+'<input id="'+spec+'" type="radio" name="degree"><span></span></label></li>';
  			specialization.push(spec);
  		}
  		hide_waiting();
  		jQuery("#specialisation-wrapper ul").html(str);

  		jQuery("#specialisation-wrapper ul li input").on("click",function(e){
  			jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+jQuery(this).attr("id")+"</li>");
  			filter_array.push("pa_specialization:"+jQuery(this).attr("id"));
  			finalresult();
  		});
  		jQuery("#spec-input").autocomplete({
  		    source: specialization,
  		    select: function (event, ui) {
  		        var label = ui.item.label;
  		        var value = ui.item.value;
  		        filter_array.push("pa_specialization:"+value);
  		       	jQuery(".guided-bredcrums-wrapper").append('<li class="guided-bredcrums">'+value+"</li>");
  		      	finalresult();
  		    }
  		});			
  	}
  	else {
  		    if(!content.nbHits)
  			filter_array.pop();
  			finalresult();
  		}
}
 
function back()
{
	

}
function msg(txt)
{
	jQuery("#msg").html(txt);
}
function finalresult()
{
	show_waiting();
	filter_str="";
	for(i in filter_array){
		obj={};
		filter=filter_array[i].split(":");
		obj[filter[0]]=filter[1];
		if(i==filter_array.length-1)
			filter_str+=JSON.stringify(obj);
		else
			filter_str+=JSON.stringify(obj)+",";
	}
	window.location="http://www.edukart.com/#q="+query+"%20&page=0&refinements=["+encodeURIComponent(filter_str)+"]&numerics_refinements=%7B%7D&index_name=%22ekdpliveall%22";
}


</script>


<?php get_footer(); ?>
