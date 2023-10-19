jQuery(function ($) {

	 let $mcsFields = $('.mcs-fields .mcs-fields_wrap');
	 let rowCount = $mcsFields.find('.mcs-fields_row').length;
	 if (rowCount === 1) {
			$('#mcs-delete-row').fadeOut(0);
	 } else {
			$('#mcs-delete-row').show();
	 }

	 if ($('.mcs-fields_row').length === 1) {
			$('.mcs-fields-delete').hide();
	 }

	 function initCountry() {
			$('.mcs-fields_countries').each(function () {
				 $(this).find('.mcs-fields__country').each(function (index) {
						const country = $(this).find('.mcs-fields__country_label').text();
						const price = $(this).find('.mcs-fields__country_input').val();
						$(this).closest('.mcs-fields_col').find('.mcs-fields_info').append("<div><span>" + country
							 + "</span>: <span data-price-index=" + (index + 1) + ">" + price + "</span></div>");
				 })
			});

			$('.mcs-fields__popup').each(function () {
				 $(this).find('.mcs-field-input').each(function (index) {
						const title = $(this).val();
						$(this).closest('.mcs-fields_col').find('.mcs-popup-info').append("<div><span data-title-index=" + (index + 1) + ">" + title + "</span></div>");
				 })
			});

			$('.mcs-country-block').each(function () {
				 let $this = $(this);
				 $this.find('.mcs-country-wrapper').each(function (index) {
						const country = $(this).find('.mcs-country-label').text();
						const price = $(this).find('.mcs-country-input').val();
						$this.closest('.mcs-header-cell').find('.mcs-country-info').append("<div><span>" + country
							 + "</span>: <span data-package-index=" + (index + 1) + ">" + price + "</span></div>");
				 })
			});
	 }

	 initCountry();

	 $(document).on('click', '.mcs-fields_info', function () {
			$(this).parent().addClass('active');
	 });

	 $('.mcs-fields .mcs-fields_row').each(function (index) {
			$(this).find('.mcs-fields_col:first').attr('data-index', (index + 1));
	 });

	 $('#mcs_column_count').on('change', function () {
			const columnCount = $(this).val();

			$('.mcs-header-cell, .mcs-fields_col').fadeOut(0);

			$('.mcs-table_row .mcs-header-cell').slice(0, columnCount).fadeIn(0);
			$('.mcs-fields .mcs-fields_row').each(function () {
				 $('.mcs-fields_col', this).slice(0, columnCount).fadeIn(0);
			});

	 }).change();

	 $('#mcs-add-row').on('click', function (e) {
			e.preventDefault();

			let $mcsFields = $('.mcs-fields .mcs-fields_wrap');
			let $row = $('<div class="mcs-fields_row" />');
			let serviceType = $(this).data('service-type');

			let rowIndex = $mcsFields.find('.mcs-fields_row').length;

			if (rowIndex === 1) {
				 $('#mcs-delete-row').fadeIn(500);
			}

			for (let i = 0; i < 5; i++) {
				 const $coll = $('<div class="mcs-fields_col" />');
				 const $countries = $('<div class="mcs-fields_countries" />');

				 if (i === 0) {
						const $input = $('<input type="text" name="mcs_table_fields_[' + rowIndex + '][mcs_row_field_' + i + ']" data-input-index="1" class="widefat mcs-field-input" />');
						$coll.append('<span class="mcs-fields-delete"/>');
						$coll.append('<div class="mcs-fields_close" />');
						$coll.append('<div class="mcs-popup-info" />');
						const popup = $('<div class="mcs-fields__popup"></div>');
						const popupInput = $('<div class="mcs-fields__input" data-title-field="Title">')
						popupInput.append($input);
						popup.append(popupInput)
						$coll.append(popup);

						if (serviceType === 'mcs_p_service') {
							 const $checkbox = $('<input type="checkbox" autocomplete="nope" class="mcs-fields_checkbox"' +
									' name="mcs_table_fields_[' + rowIndex + '][mcs_row_field_show_' + i + ']"' +
									' />');
							 $coll.append($checkbox);
							 $coll.find('.mcs-fields__popup').append("<div class='mcs-fields_save'>Save</div>");
						} else {
							 const $input2 = $('<input type="text" name="mcs_table_fields_[' + rowIndex + '][mcs_row_field_link_' + i + ']" data-input-index="2" class="widefat mcs-field-input" />');
							 const popupInput2 = $('<div class="mcs-fields__input" data-title-field="Link">')
							 popupInput2.append($input2);
							 popup.append(popupInput2);
							 $coll.append(popup);
							 $coll.find('.mcs-fields__popup').append("<div class='mcs-fields_save'>Save</div>");
						}

				 } else {
						$coll.append("<div class='mcs-fields_close' />");
						$coll.append("<div class='mcs-fields_info' />");

						mcs.countries.forEach(function (country, index) {
							 const $label = $('<label class="mcs-fields__country_label">' + country + '</label>');
							 const $input = $('<input type="text" autocomplete="nope" name="mcs_table_fields_[' + rowIndex + '][mcs_row_field_' + i + '][' + country + ']" value="0" data-input-index="' + (index + 1) + '" class="widefat mcs-fields__country_input" />');
							 let $country = $('<div class="mcs-fields__country" />');
							 $country.append($label);
							 $country.append($input);
							 $countries.append($country)
							 $coll.append($countries);
						});

						$coll.find('.mcs-fields_countries').append("<div class='mcs-fields_save'>Save</div>");

				 }
				 $row.append($coll);
			}

			$mcsFields.append($row);

			setTimeout(() => {
				 $('.mcs-fields .mcs-fields_row').each(function (index) {
						$(this).find('.mcs-fields_col:first').attr('data-index', (index + 1))
						.find('input').focus();
				 });

				 $('.mcs-fields_row:last').find('.mcs-fields_countries').each(function () {
						$(this).find('.mcs-fields__country').each(function (index) {
							 const country = $(this).find('.mcs-fields__country_label').text();
							 const price = $(this).find('.mcs-fields__country_input').val() || 0;
							 $(this).closest('.mcs-fields_col').find('.mcs-fields_info').append("<div><span>" + country
									+ "</span>: <span data-price-index=" + (index + 1) + ">" + price + "</span></div>");
						})
				 });

				 $('.mcs-fields_row:last').find('.mcs-fields__popup').each(function () {
						$(this).find('.mcs-field-input').each(function (index) {
							 const title = $(this).val();
							 $(this).closest('.mcs-fields_col').find('.mcs-popup-info').append("<div><span data-title-index=" + (index + 1) + ">" + title + "</span></div>");
						})
				 });

				 updateIndex();

			}, 30);

			if ($('.mcs-fields_row').length > 1) {
				 $('.mcs-fields-delete').show();
			}

			$('#mcs_column_count').change();
	 });

	 $('#mcs-delete-row').on('click', function (e) {
			e.preventDefault();

			let $mcsFields = $('.mcs-fields .mcs-fields_wrap');
			$mcsFields.find('.mcs-fields_row:last').remove();

			checkButtonDelete();
			updateIndex();
			$('#mcs_column_count').change();
	 });

	 let arrInputs;
	 $(document).on('click', '.mcs-popup-info, .mcs-fields_info', function () {
			arrInputs = [];
			let $this = $(this);
			$this.parent().addClass('active');
			$('.mcs-fields_checkbox').addClass('hidden');
			$('.mcs-fields .mcs-fields_wrap').sortable("disable");

			if ($this.parent().find('.mcs-fields__input')) {
				 mcsFieldInput($this);
			}

			if ($this.parent().find('.mcs-fields__country')) {
				 mcsCountryInput($this);
			}
	 });

	 $(document).on('click', '.mcs-country-info', function () {
			arrInputs = [];
			let $this = $(this);

			$this.parent().find('.mcs-country-block').addClass('active');
			$('.mcs-fields_checkbox').addClass('hidden');
			$('.mcs-fields .mcs-fields_wrap').sortable("disable");

			if ($this.parent().find('.mcs-country-wrapper')) {
				 mcsPackageInput($this);
			}
	 });

	 function mcsCountryInput(input) {
			input.closest('.mcs-fields_col.active').find('.mcs-fields__country').each(function () {
				 const $value = $(this).find('.mcs-fields__country_input').val() || 0;
				 let index = $(this).find('.mcs-fields__country_input').attr('data-input-index');
				 input.closest('.mcs-fields_col.active').find('[data-price-index="' + index + '"]').empty().append($value);
				 arrInputs.push($value);
			});
	 }

	 function mcsFieldInput(input) {
			input.closest('.mcs-fields_col.active').find('.mcs-fields__input').each(function () {
				 const $value = $(this).find('.mcs-field-input').val() || 0;
				 let index = $(this).find('.mcs-field-input').attr('data-input-index');
				 input.closest('.mcs-fields_col.active').find('[data-title-index="' + index + '"]').empty().append($value);
				 arrInputs.push($value);
			})
	 }

	 function mcsPackageInput(input) {
			input.closest('.mcs-header-cell').find('.mcs-country-wrapper').each(function () {
				 const $value = $(this).find('.mcs-country-input').val() || 0;
				 let index = $(this).find('.mcs-country-input').attr('data-input-index');
				 input.closest('.mcs-header-cell').find('[data-package-index="' + index + '"]').empty().append($value);
				 arrInputs.push($value);
			})
	 }

	 $(document).on('click', '.mcs-fields_save', function (e) {
			let $this = $(this);

			if ($this.parent().find('.mcs-fields__input')) {
				 mcsFieldInput($this);
			}

			if ($this.parent().find('.mcs-fields__country_input')) {
				 mcsCountryInput($this);
			}

			if ($this.parent().find('.mcs-country-wrapper')) {
				 mcsPackageInput($this);
			}

			if (e.target === this) {
				 $('.mcs-fields_col').removeClass('active');
				 $('.mcs-fields_checkbox').removeClass('hidden');
				 $('.mcs-fields .mcs-fields_wrap').sortable("enable");
				 $('.mcs-country-block').removeClass('active');
			}
	 });

	 $(document).on('click', '.mcs-fields_close, .mcs-country-block-close', function (e) {
			let $this = $(this);

			if ($this.parent().find('.mcs-fields__input')) {
				 $this.closest('.mcs-fields_col.active').find('.mcs-fields__input').each(function (index) {
						$(this).children('.mcs-field-input').val(arrInputs[index]).trigger('change');
						$this.closest('.mcs-fields_col.active').find('[data-title-index="' + (index + 1) + '"]').empty().append(arrInputs[index]);
				 })
			}

			if ($this.parent().find('.mcs-fields__country_input')) {
				 $this.closest('.mcs-fields_col.active').find('.mcs-fields__country').each(function (index) {
						$(this).children('.mcs-fields__country_input').val(arrInputs[index]).trigger('change')
						$this.closest('.mcs-fields_col.active').find('[data-price-index="' + (index + 1) + '"]').empty().append(arrInputs[index]);
				 });
			}

			if ($this.parent().find('.mcs-country-wrapper')) {
				 $this.closest('.mcs-header-cell').find('.mcs-country-wrapper').each(function (index) {
						$(this).children('.mcs-country-input').val(arrInputs[index]).trigger('change');
						$this.closest('.mcs-header-cell').find('[data-package-index="' + (index + 1) + '"]').empty().append(arrInputs[index]);
				 })
			}

			if (e.target === this) {
				 $('.mcs-fields_col').removeClass('active');
				 $('.mcs-fields_checkbox').removeClass('hidden');
				 $('.mcs-fields .mcs-fields_wrap').sortable("enable");
				 $('.mcs-country-block').removeClass('active');
			}
	 });

	 function updateIndex() {
			$('.mcs-fields .mcs-fields_row').each(function (index) {
				 $(this).find('.mcs-fields_col:first').attr('data-index', index + 1);

				 let nameInput = $(this).find('.mcs-fields_col:first [data-input-index="1"]').attr('name').split('');
				 let startInput = nameInput.splice(0, 18);
				 let endInput = nameInput.splice(1, nameInput.length - 1);

				 $(this).find('.mcs-fields_col:first [data-input-index="1"]').attr('name',
						startInput.join('') + index + endInput.join(''));

				 if ($(this).find('[data-title-index="2"]').length > 0) {
						let nameInput2 = $(this).find('.mcs-fields_col:first [data-input-index="2"]').attr('name').split('');
						let startInput2 = nameInput2.splice(0, 18);
						let endInput2 = nameInput2.splice(1, nameInput2.length - 1);

						$(this).find('.mcs-fields_col:first [data-input-index="2"]').attr('name',
							 startInput2.join('') + index + endInput2.join(''));
				 }

				 if ($(this).find('.mcs-fields_col .mcs-fields_checkbox').length > 0) {
						let nameInputCheckbox = $(this).find('.mcs-fields_col:first .mcs-fields_checkbox').attr('name').split('');
						let startInputCheckbox = nameInputCheckbox.splice(0, 18);
						let endInputCheckbox = nameInputCheckbox.splice(1, nameInputCheckbox.length - 1);

						$(this).find('.mcs-fields_col:first').children('.mcs-fields_checkbox').attr('name',
							 startInputCheckbox.join('') + index + endInputCheckbox.join(''));
				 }

				 $(this).find('.mcs-fields__country').each(function () {
						let nameInputs = $(this).children('.mcs-fields__country_input').attr('name').split('');
						let startCountryName = nameInputs.splice(0, 18);
						let endCountryName = nameInputs.splice(1, nameInputs.length - 1);
						$(this).children('.mcs-fields__country_input').attr('name',
							 startCountryName.join('') + index + endCountryName.join(''));
				 })
			});
	 }

	 function checkButtonDelete() {
			if ($('.mcs-fields_row').length === 1) {
				 $('.mcs-fields-delete').hide();
				 $('.mcs-delete-row').fadeOut(300);
			} else {
				 $('.mcs-fields-delete').show();
				 $('.mcs-delete-row').fadeIn(300);
			}
	 }

	 $(document).on('click', '.mcs-fields-delete', function () {
			$(this).closest('.mcs-fields_row').remove();
			checkButtonDelete();
	 });

	 $('.mcs-fields .mcs-fields_wrap').sortable({
			stop: function (event) {
				 if (event.currentTarget.readyState === 'complete') {
						updateIndex();
				 }
			}
	 });
});



