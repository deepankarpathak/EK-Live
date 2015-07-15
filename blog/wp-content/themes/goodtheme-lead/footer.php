</div>
<!-- END wrapper -->

<!-- BEGIN footer -->

<div id="footer">

<div id="totop">
<a href="<?php echo get_settings('home'); ?>/#">Jump To Top</a>
</div>

<div id="footee1">
<ul>

<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar(4) ) : ?>
<li><h2>About EduKart</h2></li>
<li><a href="http://www.edukart.com/" target="_blank">EduKart.com </a> is India’s leading online education portal that provides access to high quality and industry relevant courses across multiple industries and functions so that working professionals and students pursuing higher education can easily learn relevant industry required skills and become a more valuable workforce.</li>      


<?php endif; ?>
</ul>
</div>

<div id="footee2">
<ul>

<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar(5) ) : ?>
<li><h2>Courses</h2></li>
<li>EduKart.com offers well designed and structured industry relevant certification courses in:<br/> - <a href="http://www.edukart.com/courses/retail-management" target="_blank">Retail Management
<br/> - <a href="http://www.edukart.com/courses/financial-management" target="_blank">Financial Management</a> 
<br/> - <a href="http://www.edukart.com/courses/digital-marketing" target="_blank"> Digital Marketing</a>
<br/> - <a href="http://www.edukart.com/courses/project-management" target="_blank"> Project Management</a>
<br/> - <a href="http://www.edukart.com/courses/programming-languages" target="blank"> Programming Languages</a>
<br/> - <a href="http://www.edukart.com/courses/career-enhancement" target="blank"> Career Enhancement</a>.  </li>




<?php endif; ?>
</ul>
  </div>

<div id="footee3">
<ul>

<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar(6) ) : ?>
 <li><h2>EduKart Advantage</h2></li>
<li> - Access to High quality industry relevant courses and certifications.
<br/> - Unlimited telephonic support for doubt solving.
<br/> - Course include exclusive video cases from corporates and domain experts.
<br/> - Get a CD if you find your internet connection to be slow.
<br/> - 100% Money back guarantee.</li>      


<?php endif; ?>
</ul>

  </div>




<div class="wrapper">
	<h2><?php bloginfo('description'); ?></h2>
 <a href="http://www.edukart.com/contact" target="_blank">Contact Us</a> | &copy; Earth Education Valley Pvt. Ltd., 2012

<!--<?php echo date("Y"); ?> <?php bloginfo('name'); ?> | Theme Design by <a href="http://goodtheme.org" title="Really Good Theme">GoodTheme.org</a>  -->

| <?php wp_loginout(); ?>
</div>	
</div>
</div>
<!-- END footer -->
<?php wp_footer(); ?>
</div>
</body>

</html>