<?php
$text_before_price = get_post_meta($service_id, 'mcs_text_before_price', true) ?: get_mcs_translation('mcs_text_before_price');
$text_after_price = get_post_meta($service_id, 'mcs_text_after_price', true) ?: get_mcs_translation('mcs_text_after_price');
$more_button_text = get_post_meta($service_id, 'mcs_more_button_text', true) ?: get_mcs_translation('mcs_more_button_text') ?: __('More', 'unitalk');
$order_button_text = get_post_meta($service_id, 'mcs_order_button_text', true) ?: get_mcs_translation('mcs_order_button_text') ?: __('Order', 'unitalk');

?>

<div class="table-block_head sticky-content">
  <div class="row">
    <?php for ($i = 0; $i < $column_count; $i++) :
            if (isset($header_values[$i])) : ?>
    <div class="col">
      <?php echo esc_html($header_values[$i]); ?>
      <?php if (isset($country_price_values[$i])) : ?>
      <div class="head-label"> <?= $text_before_price ?: __('from ', 'unitalk'); ?>
        <strong><?= $country_price_values[$i]; ?> <?= $currency; ?> </strong>
      </div>
      <span class="month"><?= $text_after_price ?: __(' /month', 'unitalk'); ?></span>
      <?php endif; ?>
    </div>
    <?php endif;
        endfor; ?>
  </div>
</div>

<div class="table-block_body">
  <?php
    foreach ($table_fields_data as $row_fields) : ?>
  <?php if (isset($row_fields['mcs_row_field_0']) && $row_fields['mcs_row_field_0']) : ?>
  <div class="row">
    <?php
            for ($j = 0; $j < $column_count; $j++) :
                if (isset($row_fields['mcs_row_field_'.$j])) :
                    $field_data = $row_fields['mcs_row_field_'.$j];
                    $field_value_by_country = '';
                    if (isset($field_data[$country])) {
                        $field_value_by_country = $field_data[$country] ? $field_data[$country] : $field_data['US'];
                    }
                    $field_value = is_array($field_data) ? $field_value_by_country : $field_data;
                    $field_value = str_replace('[currency]', $currency, $field_value);
                    ?>
    <div class="col"><?php echo $field_value; ?></div>
    <?php
                endif;
            endfor;
            ?>
  </div>
  <?php endif; ?>
  <?php
    endforeach;
    ?>

  <div class="row">
    <div class="col"></div>
    <?php
        for ($i = 1; $i <= $column_count-1; $i++) :
            $link = $order_link_values[$i] ?? false; ?>
    <div class="col">
      <?php if($link){ ?>
      <a href="<?= $link ?>" target="_blank" class="btn btn_red btn_small">
        <?php echo $order_button_text; ?>
      </a>
      <?php } ?>
    </div>
    <?php
        endfor;
    ?>
  </div>
</div>