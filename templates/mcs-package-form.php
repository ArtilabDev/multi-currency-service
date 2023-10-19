<?php global $post; ?>

<div id="mcs-meta-box-container">
	<div class="mcs-caption"><?php _e('Form Headers', 'mcs'); ?></div>


	<div id="mcs-table" class="mcs-table mcs-table-large widefat striped">
		<div class="mcs-table_row">
			<?php for ($i = 0; $i < 5; $i++) :
				$header_value = get_post_meta($post->ID, 'mcs_header_' . $i, true);
				$sub_header_value = get_post_meta($post->ID, 'mcs_sub_header_' . $i, true);
				$sub_header_single_value = get_post_meta($post->ID, 'mcs_sub_header_single_' . $i, true);
				$description_single_value = get_post_meta($post->ID, 'mcs_description_single_' . $i, true);
				$more_link_value = get_post_meta($post->ID, 'mcs_more_link_' . $i, true);
				$order_link_value = get_post_meta($post->ID, 'mcs_order_link_' . $i, true);
				?>
				<div class="mcs-header-cell">
					<label class="mcs-label">Title:</label>
					<input type="text" name="mcs_header[]"
					       class="widefat mcs-header-input"
					       value="<?php echo esc_attr($header_value); ?>" />
					<?php if ($i > 0) { ?>
						<label class="mcs-label">Country Prices:</label>
						<div class="mcs-country-info"></div>
						<div class="mcs-country-block">
							<div class="mcs-country-inner">
								<div class="mcs-country-block-close"></div>
								<?php foreach ($countries as $index => $country) : ?>
									<div class="mcs-country-wrapper">
										<label
											  class="mcs-country-label"><?= $country; ?></label>
										<input type="text"
										       data-input-index="<?php echo $index + 1; ?>"
										       name="mcs_country_price[<?php echo $i; ?>][<?= $country; ?>]"
										       class="widefat mcs-country-input"
										       value="<?php echo esc_attr(get_post_meta($post->ID, 'mcs_country_price_' . $i . '_' . $country, true)); ?>" />
									</div>
								<?php endforeach; ?>
								<div class="mcs-fields_save">Save</div>
							</div>
						</div>
						<label class="mcs-label">Description (Single):</label>
						<input type="text" name="mcs_description_single[]"
						       class="widefat mcs-description-single-input"
						       value="<?php echo esc_attr($description_single_value); ?>" />
						<label class="mcs-label">More Link:</label>
						<input type="text" name="mcs_more_link[]"
						       class="widefat mcs-more-link-input"
						       value="<?php echo esc_attr($more_link_value); ?>" />
						<label class="mcs-label">Order Link:</label>
						<input type="text" name="mcs_order_link[]"
						       class="widefat mcs-order-link-input"
						       value="<?php echo esc_attr($order_link_value); ?>" />
					<?php } ?>
				</div>
			<?php endfor; ?>
		</div>
	</div>


	<div class="mcs-caption"><?php _e('Form Fields', 'mcs'); ?></div>
	<div id="mcs-fields" class="mcs-fields widefat striped"
	     data-place-holder="<?php _e('Country name', 'mcs'); ?>">
		<div class="mcs-fields_wrap">
			<?php
			$table_fields_data = get_post_meta($post->ID, 'mcs_table_fields_', true);
			$table_fields_data = json_decode($table_fields_data, true);
			if (!empty($table_fields_data)) {
				foreach ($table_fields_data as $row_index => $row_fields) : ?>
					<div class="mcs-fields_row">
						<?php for ($j = 0; $j < 5; $j++) : ?>
							<?php if ($j === 0) :
								$mcs_row_field_show = $row_fields['mcs_row_field_show_' . $j] ?? '';
								?>
								<div class="mcs-fields_col">
									<span class="mcs-fields-delete"></span>
									<input type="checkbox"
									       class="mcs-fields_checkbox"
									       name="mcs_table_fields_[<?= $row_index; ?>][mcs_row_field_show_<?php echo $j; ?>]" <?php checked($mcs_row_field_show, 'on'); ?> />
									<div class="mcs-fields_close"></div>
									<div class="mcs-popup-info"></div>
									<div class="mcs-fields__popup">
										<div class="mcs-fields__input"
										     data-title-field="Title">
											<input type="text"
											       data-input-index="<?php echo $j + 1; ?>"
											       name="mcs_table_fields_[<?= $row_index; ?>][mcs_row_field_<?php echo $j; ?>]"
											       class="widefat mcs-field-input"
											       value="<?php echo esc_attr($row_fields['mcs_row_field_' . $j]); ?>" />
										</div>
										<div class="mcs-fields_save">Save</div>
									</div>
								</div>
							<?php else : ?>
								<div class="mcs-fields_col"
								     data-place-holder="<?php _e('Country name', 'mcs'); ?>">
									<div class="mcs-fields_close"></div>
									<div class="mcs-fields_info"></div>
									<div class="mcs-fields_countries">
										<?php foreach ($countries as $index => $country) :
											$country_value = isset($row_fields['mcs_row_field_' . $j][$country]) ? $row_fields['mcs_row_field_' . $j][$country] : ''; ?>
											<div class="mcs-fields__country">
												<label
													  class="mcs-fields__country_label"><?= $country; ?></label>
												<input type="text"
												       data-input-index="<?php echo $index + 1; ?>"
												       name="mcs_table_fields_[<?= $row_index; ?>][mcs_row_field_<?php echo $j; ?>][<?= $country; ?>]"
												       class="widefat mcs-fields__country_input mcs-country-input"
												       value="<?php echo esc_attr($country_value); ?>" />
											</div>
										<?php endforeach; ?>
										<div class="mcs-fields_save">Save</div>
									</div>
								</div>
							<?php endif; ?>
						<?php endfor; ?>
					</div>
				<?php endforeach;
			}
			?>
		</div>
	</div>
</div>

<button id="mcs-add-row" class="button button-secondary mcs-add-row"
        data-service-type="mcs_p_service"><?php _e('Add row', 'mcs'); ?></button>
<button id="mcs-delete-row"
        class="button button-secondary mcs-delete-row"><?php _e('Delete last row', 'mcs'); ?></button>
