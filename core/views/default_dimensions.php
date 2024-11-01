<tr valign="top">
	<th class="titledesc">
		<label for="woocommerce_ups_default_length"><?php _e('Default Package Size', 'woocommerce-ups') ?></label>
	</th>
	<td class="forminp">
		<fieldset>
			<label for="woocommerce_ups_default_length"><?php _e('Length', 'wpruby-ups-shipping-method'); ?></label> <input id="woocommerce_ups_default_length" class="input-text regular-input" name="woocommerce_ups_default_length" value="<?php echo esc_attr($length); ?>" style="width:70px; height:30px;" />
			<label for="woocommerce_ups_default_width"><?php _e('Width', 'wpruby-ups-shipping-method'); ?></label>  <input id="woocommerce_ups_default_width" class="input-text regular-input" name="woocommerce_ups_default_width" value="<?php echo esc_attr($width); ?>" style="width:70px; height:30px;" />
			<label for="woocommerce_ups_default_height"><?php _e('Height', 'wpruby-ups-shipping-method'); ?></label> <input id="woocommerce_ups_default_height" class="input-text regular-input" name="woocommerce_ups_default_height" value="<?php echo esc_attr($height); ?>" style="width:70px; height:30px;" />
			<p class="description">Size unit: <?php echo esc_html($dimensions_unit); ?><br> This dimension will only be used if the product\s dimensions are not set in the edit product's page.</p>
		</fieldset>
	</td>
</tr>
