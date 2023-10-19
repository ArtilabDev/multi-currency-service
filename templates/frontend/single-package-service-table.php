<?php
$text_before_price = get_post_meta($service_id, 'mcs_text_before_price', true) ?: get_mcs_translation('mcs_text_before_price');
$text_after_price = get_post_meta($service_id, 'mcs_text_after_price', true) ?: get_mcs_translation('mcs_text_after_price');
$more_button_text = get_post_meta($service_id, 'mcs_more_button_text', true) ?: get_mcs_translation('mcs_more_button_text') ?: __('More', 'unitalk');
$order_button_text = get_post_meta($service_id, 'mcs_order_button_text', true) ?: get_mcs_translation('mcs_order_button_text') ?: __('Order', 'unitalk');

$column_count -= 1;
for ($i = 0; $i < $column_count; $i++) {
    $service_name = isset($header_values[$i + 1]) ? $header_values[$i + 1] : '';
    $service_country_prices = isset($country_price_values[$i+1]) ? $country_price_values[$i+1] : '';
    $service_description = isset($description_single_values[$i + 1]) ? $description_single_values[$i + 1] : '';
    $more_link = isset($more_link_values[$i + 1]) ? $more_link_values[$i + 1] : '';
    $order_link = isset($order_link_values[$i + 1]) ? $order_link_values[$i + 1] : '';
    ?>
    <div class="col">
        <h3 class="title-small"><?= $service_name ?></h3>
        <div class="price circle-decor">
            <span><?php echo $text_before_price ?: __('from ', 'unitalk'); ?></span>
            <div class="text-gradient"><?php echo $service_country_prices; ?> <small><?php echo $currency; ?></small></div>
            <span><?php echo $text_after_price ?: __(' per month', 'unitalk'); ?> </span>
        </div>
        <div class="descript">
            <?= $service_description ?>
        </div>
        <ul class="descript price-list">
            <?php $row_counter = 1; ?>
            <?php foreach ($table_fields_data as $row_fields) : ?>
                <?php
                if (isset($row_fields["mcs_row_field_show_0"]) && $row_fields["mcs_row_field_show_0"] == 'on') :
                    $field_data = $row_fields["mcs_row_field_".($i + 1)];
                    $field_value_by_country = '';
                    if (isset($field_data[$country])) {
                        $field_value_by_country = $field_data[$country] ? $field_data[$country] : $field_data['US'];
                    }
                    $field_value = is_array($field_data) ? $field_value_by_country : $field_data;
                    $field_value = str_replace('[currency]', $currency, $field_value);
                    if (isset($row_fields['mcs_row_field_0']) && $row_fields['mcs_row_field_0']) :
                    ?>
                    <li>
                        <p><?= $row_fields["mcs_row_field_0"] ?></p>
                        <p><?= $field_value ?></p>
                    </li>
                <?php endif;
                endif;
                ?>
            <?php $row_counter++; ?>
            <?php endforeach; ?>
        </ul>
        <?php if ($more_link) : ?>
            <a href="<?= $more_link ?>" class="link-more-small" tabindex="0"><?php echo $more_button_text; ?></a>
        <?php endif; ?>

        <?php if ($order_link) : ?>
            <a href="<?= $order_link ?>" class="btn btn_red btn_small" tabindex="0"><?php echo $order_button_text; ?></a>
        <?php endif; ?>
    </div>
    <?php
}
?>
