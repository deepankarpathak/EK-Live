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
//echo "somesh";die;
//wp_enqueue_script('guided_search');

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
		<strong>Good Morning!</strong> Choose from variety of long term & short term courses we offer.
	</div>
<!-- Course wrapper start-->
	
<!--
 	<div class="offered-course-wrapper clearfix">
		<div class="col-sm-6">
			<div class="content-title col-sm-4">School Education</div>
			<div class="content-title col-sm-4">Entrance Coaching</div>
			<div class="content-title col-sm-4">Degree Programs</div>
		</div>
		<div class="col-sm-6">
			<div class="content-title col-sm-4">Certificate Courses</div>
			<div class="content-title col-sm-4">Diploma Courses</div>
			<div class="content-title col-sm-4">Free Courses</div>
		</div>
	</div>
 -->

<!-- Course wrapper end-->

<!-- ug-pg wrapper start-->

<!-- 
	<div class="ug-pg-wrapper clearfix">
		<div class="content-title col-sm-offset-3 col-sm-3">
			<div class="ug-pg-course-title">UG</div>
			<div>Under<br>Graduate</div>
		</div>
		<div class="content-title col-sm-3">
			<div class="ug-pg-course-title">PG</div>
			<div>Post<br>Graduate</div>
		</div>
	</div>
 -->

 <!-- ug-pg wrapper end-->


<!-- Exam-center wrapper start-->
<!-- 
	<div class="exam-center-wrapper">
		<ul>
			<li class="content-title">Delhi</li>					
			<li class="content-title">Mumbai</li>					
			<li class="content-title">Kolkata</li>					
			<li class="content-title">Chennai</li>					
			<li class="content-title">Bangalore</li>					
			<li class="content-title">Jaipur</li>					
			<li class="content-title">Lukhnow</li>					
		</ul>
		<input type="text" class="guided-search-bar" placeholder="or search your specialisation">
	</div>
 -->
<!-- Exam-center wrapper end-->

<!-- Study mode wrapper start-->
<!-- 
	<div class="study-mode-wrapper clearfix">
		<div class="content-title col-sm-offset-3 col-sm-3">Online<br>Mode</div>
		<div class="content-title col-sm-3">Distance<br>Mode</div>
	</div>
 -->
 <!-- Study mode wrapper end-->


<!-- Degree wrapper start-->
<!-- 
	<div class="degree-wrapper clearfix">
		<ul>
			<li><label class="degree-title" for="mscit">MSCIT<input id="mscit" type="radio" name="degree"><span></span></label></li>					
			<li><label class="degree-title" for="ma">MA<input id="ma" type="radio" name="degree"><span></span></label></li>					
			<li><label class="degree-title" for="mba">MBA<input id="mba" type="radio" name="degree"><span></span></label></li>					
			<li><label class="degree-title" for="mca">MCA<input id="mca" type="radio" name="degree"><span></span></label></li>					
			<li><label class="degree-title" for="mtech">Mtech<input id="mtech" type="radio" name="degree"><span></span></label></li>					
		</ul>
		<input type="text" class="guided-search-bar" placeholder="or search your specialisation">
	</div>
 -->
 <!-- Degree wrapper end-->

<!-- Specialisation wrapper start-->

	<div class="specialisation-wrapper clearfix">
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
		<input type="text" class="guided-search-bar" placeholder="or search your specialisation">
	</div>
	<ul class="guided-bredcrums-wrapper">
		<li class="guided-bredcrums">Degree</li>
		<li class="guided-bredcrums">Degree</li>
	</ul>

 <!-- specialisation wrapper end-->



</div>
</div><!-- end #content large-12 -->
</div><!-- end row -->
</div><!-- end page-right-sidebar container -->
<?php get_footer(); ?>
