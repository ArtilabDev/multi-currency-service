    <div class="table-block_head">
        <div class="row">
            <?php
            for ($i = 0; $i < $column_count; $i++) :
                if (isset($header_values[$i])) : ?>
                    <div class="col">
                        <?php echo $header_values[$i]; ?>
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
                        $link = isset($row_fields['mcs_row_field_link_'.$j]) && $row_fields['mcs_row_field_link_'.$j]
                            ? $row_fields['mcs_row_field_link_'.$j] : '';
                        $field_data = $row_fields['mcs_row_field_'.$j];
                        $field_value_by_country = '';
                        if (isset($field_data[$country])) {
                            $field_value_by_country = $field_data[$country] ? $field_data[$country] : $field_data['US'];
                        }
                        $field_value = is_array($field_data) ? $field_value_by_country : $field_data;
                        $field_value = str_replace('[currency]', $currency, $field_value);
                        ?>
                        <div class="col">
                            <?php if ($link) : ?>
                                <a  class="country-link" href="<?= $link; ?>" >
                            <?php endif; ?>
                                <?php echo $field_value; ?>
                            <?php if ($link) : ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php
                    endif;
                endfor;
                ?>
            </div>
        <?php
        endif;
        endforeach;
        ?>
    </div>
