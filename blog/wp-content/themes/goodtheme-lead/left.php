<div id="left">
<ul>
<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar(2) ) : ?>
        
			
        
<?php wp_list_categories('title_li=<h2>Categories</h2>'); ?>



<?php endif; ?>

</ul>

</div>

