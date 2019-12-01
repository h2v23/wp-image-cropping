<div class="wrap">
	<h1><?php _e('Image Cropping Setting', 'wpic'); ?></h1>
	<?php wpic_notification(); ?>
	<form id="wpic_configform" method="post" action="<?php echo wpic_admin_url() ; ?>">
		<?php wp_nonce_field( 'wpic_update_field' ); ?>
		<input type="hidden" name="wpic_update_field" value="1">
		<table class="form-table">
			<tbody>
				<tr>
					<td></td>
				</tr>
				<tr>
					<th scope="row"><label for="wpic_cropping_mode"><?php _e('Image cropping type', 'wpic'); ?></label></th>
					<td>
						<ul>
							<li>
								<input name="wpic_cropping_mode" type="radio" value="CROPCENTER" <?php echo (sgb_app('WPIC_Options')->getCroppingMode()==='CROPCENTER') ? 'checked=""' : ''; ?> class="tog"> CROPCENTER <br>
								<em>In the case you have an image of 400px × 600px and you want to crop it to 200px × 200px the image will be resized down to 200px × 300px, then you can indicate how you want to handle those 100px exceeding passing the value of the crop mode you want to use.	</em>
							</li>
							<li>
								<input name="wpic_cropping_mode" type="radio" value="CROPTOP" <?php echo (sgb_app('WPIC_Options')->getCroppingMode()==='CROPTOP') ? 'checked=""' : ''; ?>  class="tog"> CROPTOP <br>
								<em>For instance passing the crop mode CROPTOP will result as 100px taken off the bottom leaving you with 200px × 200px.</em>
							</li>
							<li>
								<input name="wpic_cropping_mode" type="radio" value="CROPBOTTOM" <?php echo (sgb_app('WPIC_Options')->getCroppingMode()==='CROPBOTTOM') ? 'checked=""' : ''; ?>  class="tog"> CROPBOTTOM <br>
								<em>On the contrary passing the crop mode CROPBOTTOM will result as 100px taken off the top leaving you with 200px × 200px.</em>
							</li>
						</ul>
					</td>

				</tr>
				<tr>
					<th><label><label for=""><?php _e('Default image if not found', 'wpic'); ?></label></label></th>
					<td>
						<img style="width: 300px;max-width: 100%;" id="wpic_image_if_not_found" src="<?php echo sgb_app('WPIC_Options')->getData('image_url_if_not_found'); ?>">
						<input type="hidden" value="<?php echo sgb_app('WPIC_Options')->getData('image_id_if_not_found'); ?>"  name="wpic_image_id_if_not_found" id="wpic_image_id_if_not_found">
						<br>
						<button id="set_custom_images" class="button"><?php _e('Set Image', 'wpic'); ?></button>
					</td>
				</tr>
			</tbody>
		</table>


		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'wpic') ?>"></p>
	</form>
</div>
<script type="text/javascript">
    jQuery(function($){

        // Set all variables to be used in scope
        var frame,
            metaBox = $('#wpic_configform'), // Your meta box id here
            addImgLink = metaBox.find('#set_custom_images'),
            imgContainer = metaBox.find( '#wpic_image_if_not_found'),
            imgIdInput = metaBox.find( '#wpic_image_id_if_not_found' );

        // ADD IMAGE LINK
        addImgLink.on( 'click', function( event ){
            event.preventDefault();
            // If the media frame already exists, reopen it.
            if ( frame ) {
                frame.open();
                return;
            }
            frame = wp.media({
                multiple: false
            });

            // When an image is selected in the media frame...
            frame.on( 'select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                imgContainer.attr('src', attachment.url);
                imgIdInput.val( attachment.id );
            });

            // Finally, open the modal on click
            frame.open();
        });
    });
</script>