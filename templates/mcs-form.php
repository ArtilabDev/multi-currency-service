<?php global $post; ?>

<div id="mcs-meta-box-container">
	<div class="mcs-caption"><?php _e('Form Headers', 'mcs'); ?></div>

	<div id="mcs-table" class="mcs-table widefat striped">
		<div class="mcs-table_row">
			<?php for ($i = 0; $i < 5; $i++) :
				$header_value = get_post_meta($post->ID, 'mcs_header_' . $i, true);
				?>
				<div class="mcs-header-cell">
					<input type="text" name="mcs_header[]"
					       placeholder="<?php _e('Form header name', 'mcs'); ?>"
					       class="widefat mcs-header-input"
					       value="<?php echo esc_attr($header_value); ?>" />
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
							<?php if ($j === 0) : ?>
								<div class="mcs-fields_col">
									<span class="mcs-fields-delete"></span>
									<div class="mcs-fields_close"></div>
									<div class="mcs-popup-info"></div>
									<div class="mcs-fields__popup">
										<div class="mcs-fields__input"
										     data-title-field="Title">
											<input type="text"
											       placeholder="<?php _e('Country name', 'mcs'); ?>"
											       name="mcs_table_fields_[<?= $row_index; ?>][mcs_row_field_<?php echo $j; ?>]"
											       class="widefat mcs-field-input "
											       data-input-index="<?php echo $j + 1; ?>"
											       value="<?php echo esc_attr($row_fields['mcs_row_field_' . $j]); ?>" />
										</div>
										<div class="mcs-fields__input"
										     data-title-field="Link">
											<input type="text"
											       placeholder="<?php _e('Country link', 'mcs'); ?>"
											       name="mcs_table_fields_[<?= $row_index; ?>][mcs_row_field_link_<?php echo $j; ?>]"
											       class="widefat mcs-field-input "
											       data-input-index="<?php echo $j + 2; ?>"
											       value="<?php echo esc_attr($row_fields['mcs_row_field_link_' . $j]); ?>" />
										</div>
										<div class='mcs-fields_save'>Save</div>
									</div>
								</div>
							<?php else : ?>
								<div class="mcs-fields_col">
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
        data-service-type="mcs_service"><?php _e('Add row', 'mcs'); ?></button>
<button id="mcs-delete-row"
        class="button button-secondary mcs-delete-row"><?php _e('Delete last row', 'mcs'); ?></button>
