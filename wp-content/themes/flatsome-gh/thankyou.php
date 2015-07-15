<?php 
/*
Template Name: Thankyou
*/
include_once "../../../wp-load.php";
get_header();



?>
<script type="text/javascript" charset="utf-8">
  window.setTimeout(function() {
    parent.location = "<?php echo site_url(); ?>/referral";
  }, 20000);
</script>
<div class="page-wrapper page-title-center">
<div class="row">

<div role="main" class="large-12 columns" id="content">
	<header class="entry-header text-center">
		<h1 class="entry-title">Thankyou</h1>
		<div class="tx-div medium"><?php //print_r($_POST); ?></div>
	</header>
<div class="entry-content">
</div>
</div>
					
<?php

get_footer();
session_destroy();
?>
