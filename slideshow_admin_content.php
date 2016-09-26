<?php
$url = plugins_url();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="<?php echo $url; ?>/slideshow/css/slideshow.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
  <script>
	$(document).ready(function(){
		$( "#sortable1" ).sortable({
			connectWith: ".connectedSortable"
		}).disableSelection();
		$("#submit_my_image_upload").click(function(){
			var btnid = "btn_upload_image";
			var file_data = $('#my_image_upload').prop('files')[0];
			var form_data = new FormData();
			form_data.append( 'btnid1', btnid);
			form_data.append( 'file', file_data);
			$.ajax({
				type: "POST",
				url: "<?php echo $url; ?>/slideshow/functionpage.php",
				data: form_data,
				beforeSend: function(){
					$('.ajaxoverlay').css("display","block");
				},
				complete: function(){
					$('.ajaxoverlay').css("display","none");
					$('#blah').removeAttr("src");
					$("#my_image_upload").css("color","transparent");
					$('#submit_my_image_upload').attr('disabled',true);
				},
				processData: false,
				contentType: false,
				success: function(data){
					var d = $.parseJSON(data);
					var cntli = $( "#sortable1 li" ).size();
					if(cntli != 0) {
						$("#sortable1").append("<li class=ui-state-default id="+d['0']+" data-pid="+d['2']+"><img src="+d['1']+" height=80 width=120><a href=javascript:void(0) data-did="+d['2']+" class=deleteslide><img src=<?php echo $url; ?>/slideshow/img/delete.png></a></li>");
					}
					else if(cntli == 0) {
						$(".imagetable").html("<ul id=sortable1 class=connectedSortable><li class=ui-state-default id="+d['0']+" data-pid="+d['2']+"><img src="+d['1']+" height=80 width=120><a href=javascript:void(0) data-did="+d['2']+" class=deleteslide><img src=<?php echo $url; ?>/slideshow/img/delete.png></a></li></ul>");
						$(".saveli").html("<button id=btn_saveli class=btnsave name=btn_saveli>Save</button>");
						$( "#sortable1" ).sortable({
							connectWith: ".connectedSortable"
						}).disableSelection();
					}
				}
			});
			return false;
		});
		$(document).on('click', '.btnsave', function(){
			var pidarray = new Array();
			var abc;
			$('.ui-state-default').each(function(){
				abc = $(this).data('pid');
				pidarray.push(abc);
			});
			var btnid = "btn_orderarray";
			var form_data = new FormData();
			form_data.append( 'btnid1', btnid);
			form_data.append( 'pidarray1', pidarray);
			$.ajax({
				type: "POST",
				url: "<?php echo $url; ?>/slideshow/functionpage.php",
				data: form_data,
				processData: false,
				contentType: false,
				success: function(data){
					location.reload();
				}
			});
			return false;
		});
		$(document).on('click', '.deleteslide', function(){
			var t = $(this);
			var did = $(this).data('did');
			var btnid = "btn_deleteslide";
			var form_data = new FormData();
			form_data.append( 'btnid1', btnid);
			form_data.append( 'did1', did);
			$.ajax({
				type: "POST",
				url: "<?php echo $url; ?>/slideshow/functionpage.php",
				data: form_data,
				processData: false,
				contentType: false,
				success: function(data){
					var cntli = $( "#sortable1 li" ).size();
					if(cntli == 1)
					{
						$(".btnsave").css("display","none");
					}
					console.log(data);
					t.parent('li').remove();
				}
			});
			return false;
		});
	});
	function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(150);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
	$(document).ready(
		function(){
			$('input:file').change(
			function(){
				if ($(this).val()) {
					$('#submit_my_image_upload').attr('disabled',false); 
				} 
			}
		);
	});
  </script>
</head>
<body>
<h3><em>Upload Image For Slider !</em></h3>Use Shortcode <b><em>[myslideshow]</em></b> for displaying slider.
<form id="featured_upload" method="post" action="#" enctype="multipart/form-data">
	<input type="file" name="my_image_upload" id="my_image_upload"  multiple="false" accept="image/*" onchange="readURL(this);" style="color:transparent;" /><br/>
		<img id="blah" /><br/>
		<div class="ajaxoverlay" style="display:none;">
			<div class="price-load">
				<img src="<?php echo $url; ?>/slideshow/img/loader3.gif">
			</div>
		</div>
	<button id="submit_my_image_upload" name="submit_my_image_upload" disabled>Submit</button>
</form>
<div class="imagetable">
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
			?>
			<ul id="sortable1" class="connectedSortable">
			<?php
			foreach ( $attachments as $post ) {
					$datapid = get_post_meta( $post->ID, 'slideshow_order', true );
				?>
					<li class="ui-state-default" id="<?php echo $datapid; ?>" data-pid="<?php echo $post->ID; ?>"><img src="<?php echo wp_get_attachment_url( $post->ID );?>" height="80" width="120"><a href="javascript:void(0);" data-did="<?php echo $post->ID; ?>" class="deleteslide"><img src="<?php echo $url; ?>/slideshow/img/delete.png" ></a></li>
				<?php
			}
			wp_reset_postdata();
			?>
			</ul>
			<div>
				<button id="btn_saveli" class="btnsave" name="btn_saveli">Save</button>
			</div>
			<?php
		}
	?>
</div>
<div class="saveli">
</div>
</body>
</html>