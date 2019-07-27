<div class="wrap">
	<h1><?php _e('Image Cropping Setting', 'wpic'); ?></h1>
	<?php wpic_notification(); ?>
	<form method="post" action="<?php echo wpic_admin_url() ; ?>">
		<?php wp_nonce_field( 'wpic_update_field' ); ?>
		<input type="hidden" name="wpic_update_field" value="1">
		<table class="form-table">
			<tbody>
				<tr>
					<td></td>
				</tr>
				<tr>
					<th scope="row"><label for="wpic_default_width"><?php _e('Image default dimension', 'wpic'); ?></label></th>
					<td>
						<ul>
							<li>
								<input name="wpic_default_width" type="number" step="1" min="1" id="wpic_default_width" value="<?php echo wpic_get('default_width'); ?>" class="small-text"> <?php _e('width', 'wpic'); ?>
							</li>
							<li>
								<input name="wpic_default_height" type="number" step="1" min="1" id="wpic_default_height" value="<?php echo wpic_get('default_height'); ?>" class="small-text"> <?php _e('height', 'wpic'); ?></td>
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="wpic_cropping_mode"><?php _e('Image cropping type', 'wpic'); ?></label></th>

					
					<td>
						<ul>
							<li>
								<input name="wpic_cropping_mode" type="radio" value="CROPCENTER" <?php echo (wpic_get('cropping_mode')==='CROPCENTER') ? 'checked=""' : ''; ?> class="tog"> CROPCENTER <br>
								<em>In the case you have an image of 400px × 600px and you want to crop it to 200px × 200px the image will be resized down to 200px × 300px, then you can indicate how you want to handle those 100px exceeding passing the value of the crop mode you want to use.	</em>
							</li>
							<li>
								<input name="wpic_cropping_mode" type="radio" value="CROPTOP" <?php echo (wpic_get('cropping_mode')==='CROPTOP') ? 'checked=""' : ''; ?>  class="tog"> CROPTOP <br>
								<em>For instance passing the crop mode CROPTOP will result as 100px taken off the bottom leaving you with 200px × 200px.</em>
							</li>
							<li>
								<input name="wpic_cropping_mode" type="radio" value="CROPBOTTOM" <?php echo (wpic_get('cropping_mode')==='CROPBOTTOM') ? 'checked=""' : ''; ?>  class="tog"> CROPBOTTOM <br>
								<em>On the contrary passing the crop mode CROPBOTTOM will result as 100px taken off the top leaving you with 200px × 200px.</em>
							</li>
						</ul>
					</td>

				</tr>
				<tr>
					<th><label><label for=""><?php _e('Default image if not found', 'wpic'); ?></label></label></th>
					<td>
						<img style="width: 300px;max-width: 100%;" id="wpic_image_if_not_found" src="<?php echo wpic_get('image_if_not_found'); ?>">
						<input type="hidden" value="<?php echo wpic_get('image_if_not_found'); ?>"  name="wpic_image_if_not_found">
						<br>
						<button class="set_custom_images button"><?php _e('Set Image', 'wpic'); ?></button>
					</td>
				</tr>
			</tbody>
		</table>


		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'wpic') ?>"></p>
	</form>
</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		var $ = jQuery;
		if ($('.set_custom_images').length > 0) {
			if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
				$(document).on('click', '.set_custom_images', function(e) {
					e.preventDefault();
					var button = $(this);
					var id = $('input[name="wpic_image_if_not_found"]');
					wp.media.editor.send.attachment = function(props, attachment) {
						id.val(attachment.url);
						$('#wpic_image_if_not_found').attr('src', attachment.url);
					};
					wp.media.editor.open(button);
					return false;
				});
			}
		}
	});	
</script>