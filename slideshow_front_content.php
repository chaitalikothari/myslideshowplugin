<?php
$url = plugins_url();
?>
<!--<link rel="stylesheet" href="<?php echo $url; ?>/slideshow/css/example.css">-->
<link rel="stylesheet" href="<?php echo $url; ?>/slideshow/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo $url; ?>/slideshow/css/slideshow.css">
<div class="container">
	<div id="slides">
	<?php
		$args = array(
			'post_type' => 'attachment', 
			'posts_per_page' => -1, 
			'post_status' => 'open',
			'meta_query' => array(
				array(
					'key' => 'slideshow_slider',
					'value' => 'yes',
				)
			),
			'meta_key' => 'slideshow_order',
			'orderby'  => 'meta_value_num',
			'order' => 'ASC',
		); 
		$attachments = get_posts( $args );
		if ( $attachments ) {
			foreach ( $attachments as $post ) {
					$datapid = get_post_meta( $post->ID, 'slideshow_order', true );
				?>
					<img src="<?php echo wp_get_attachment_url( $post->ID );?>">
				<?php
			}
			wp_reset_postdata();
			?>
			<a href="#" class="slidesjs-previous slidesjs-navigation"><i class="icon-chevron-left"></i></a>
			<a href="#" class="slidesjs-next slidesjs-navigation"><i class="icon-chevron-right"></i></a>
			<?php
		}
	?>
	</div>
</div>
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="<?php echo $url; ?>/slideshow/js/jquery.slides.min.js"></script>
<script>
    $(function() {
      $('#slides').slidesjs({
        width: 940,
        height: 528,
		navigation :false,
        play: {
			effect: "slide",
			interval: 3000,
			auto: true,
		}
      });
    });
</script>