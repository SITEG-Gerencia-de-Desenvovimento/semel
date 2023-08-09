/* eslint-disable camelcase */
// get the localised values
const text_drop_down_columns = itsg_listdropdown_admin_js_settings.text_drop_down_columns;
const text_drop_down_columns_instructions = itsg_listdropdown_admin_js_settings.text_drop_down_columns_instructions;
const text_make_drop_down = itsg_listdropdown_admin_js_settings.text_make_drop_down;
const text_drop_down_options = itsg_listdropdown_admin_js_settings.text_drop_down_options;
const text_enable_enhanced = itsg_listdropdown_admin_js_settings.text_enable_enhanced;
const text_enable_add_options = itsg_listdropdown_admin_js_settings.text_enable_add_options;

// ADD drop down options to list field in form editor - hooks into existing GetFieldChoices function.
( function( w ) {
	const GetFieldChoicesOldDD = w.GetFieldChoices;

	w.GetFieldChoices = function() {
		let str = GetFieldChoicesOldDD.apply( this, [ field ] );

		if ( ! ( field.choices ) || Object.keys( field.choices ).length === 0 ) {
			return '';
		}

		for ( let i = 0; i < field.choices.length; i++ ) {
			const inputType = GetInputType( field );
			const isDropDown = field.choices[ i ].isDropDown ? 'checked' : '';
			const isDropDownEnhanced = field.choices[ i ].isDropDownEnhanced ? 'checked' : '';
			//const isDropDownEnhancedOther = field.choices[ i ].isDropDownEnhancedOther ? 'checked' : '';
			const value = field.enableChoiceValue ? String( field.choices[ i ].value ) : field.choices[ i ].text;

			if ( inputType == 'list' ) {
				if ( i == 0 ) {
					str += '<p><strong>' + text_drop_down_columns + '</strong><br>' + text_drop_down_columns_instructions + '</p>';
				}
				str += "<div id='list_choice_dropdown_options' class=gwp_list_drop_down_settings>";

				// option for enable down down column
				str += "<input type='checkbox' name='choice_dropdown_enable' id='list_choice_dropdown_enable_" + i + "' " + isDropDown + ' onclick="SetFieldChoiceDropDown( ' + i + ' );itsg_gf_list_drop_down_init();" /> ';
				str += "<label class='inline' for='list_choice_dropdown_enable_" + i + "'>" + value + ' - ' + text_make_drop_down + '</label>';
				str += "<div style='display:none; background: rgb(244, 244, 244) none repeat scroll 0px 0px; padding: 10px; border-bottom: 1px solid grey; margin: 10px 0;' class='list_choice_dropdown_options_" + i + "'>";
				str += '<br>';

				// option for drop down enhanced
				str += "<input type='checkbox' name='choice_dropdown_enhanced' id='list_choice_dropdown_enhanced_" + i + "' " + isDropDownEnhanced + ' onclick="SetFieldChoiceDropDown( ' + i + ' );itsg_gf_list_drop_down_init();"  >';
				str += "<label class='inline' for='list_choice_dropdown_enhanced_" + i + "'>";
				str += text_enable_enhanced + '</label>';
				str += '<br>';

				// option for drop down enhanced other
				//str += "<input type='checkbox' name='choice_dropdown_enhanced_other' id='list_choice_dropdown_enhanced_other_" + i + "' " + isDropDownEnhancedOther + ' onclick="SetFieldChoiceDropDown( ' + i + ' );"  >';
				//str += "<label class='inline' for='list_choice_dropdown_enhanced_other_" + i + "'>";
				//str += text_enable_add_options + '</label>';

				str += '</div>';
				str += '</div>';
			}
		}

		itsg_gf_list_drop_down_init();

		return str;
	};
}( window || {} ) );

function SetFieldChoicesList( choice, choice_index ) {
	let str = '';

	const new_isDropDownChoices = new Array( {
		value: 'First Choice',
		text: 'First Choice',
	}, {
		value: 'Second Choice',
		text: 'Second Choice',
	}, {
		value: 'Third Choice',
		text: 'Third Choice',
	}
	);

	var columns = 'undefined' === typeof field.enableColumns || ! field.enableColumns ? 'single' : 'multi';

	if ( 'multi' == columns ) {
		var choice = field.choices[ choice_index ];
		var isDropDownChoices = 'undefined' === typeof choice.isDropDownChoices ? new_isDropDownChoices : choice.isDropDownChoices;
		var enableChoiceValue = 'undefined' !== typeof choice.isDropDownEnableChoiceValue && choice.isDropDownEnableChoiceValue ? true : false;
		// create blank choices if there aren't any
		if ( ! isDropDownChoices.length ) {
			isDropDownChoices = new_isDropDownChoices;
			field.choices[ choice_index ].isDropDownChoices = new_isDropDownChoices;
		}

		if ( 'string' === typeof isDropDownChoices ) {
			var isDropDownChoices = itsg_gf_list_drop_down_maybe_string_to_array( isDropDownChoices );
			field.choices[ choice_index ].isDropDownChoices = isDropDownChoices;
		}
	} else {
		var isDropDownChoices = 'undefined' === typeof field.itsg_list_field_drop_down_options ? new_isDropDownChoices : field.itsg_list_field_drop_down_options;
		var enableChoiceValue = 'undefined' !== typeof field.isDropDownEnableChoiceValue && field.isDropDownEnableChoiceValue ? true : false;
		// create blank choices if there aren't any
		if ( 'undefined' === typeof field.itsg_list_field_drop_down_options ) {
			isDropDownChoices = new_isDropDownChoices;
			field.itsg_list_field_drop_down_options = new_isDropDownChoices;
		}

		if ( 'string' === typeof isDropDownChoices ) {
			var isDropDownChoices = itsg_gf_list_drop_down_maybe_string_to_array( isDropDownChoices );
			field.itsg_list_field_drop_down_options = isDropDownChoices;
		}
	}
	console.log( isDropDownChoices );

	// for each drop down option create a row
	// TO DO - add sortable and default option
	for ( let drop_down_index = 0; drop_down_index < isDropDownChoices.length; drop_down_index++ ) {
		const value = enableChoiceValue ? String( isDropDownChoices[ drop_down_index ].value ) : String( isDropDownChoices[ drop_down_index ].text );
		const text = String( isDropDownChoices[ drop_down_index ].text );

		const inputType = 'list_choice_drop_down';
		const type = 'radio';

		str += "<li class='field-choice-row-drop-down' data-input_type='" + inputType + "' data-index='" + drop_down_index + "'>";

		str += "<input type='text' id='" + inputType + '_choice_text_' + drop_down_index + "' value=\"" + text.replace( /"/g, '&quot;' ) + "\" class='field-choice-input-dropdown field-choice-text' onblur=\"SetFieldChoiceDropDown( " + choice_index + ' );" />';
		str += "<input type='text' id='" + inputType + '_choice_value_' + drop_down_index + "' value=\"" + value.replace( /"/g, '&quot;' ) + "\" class='field-choice-input-dropdown field-choice-value' onblur=\"SetFieldChoiceDropDown( " + choice_index + ' );" />';

		str += "<button class='field-choice-button field-choice-button--insert gf_insert_field_choice gform-choice__button gform-choice__button--add gform-st-icon gform-st-icon--circle-plus' onclick=\"InsertFieldChoiceisDropDown( " + choice_index + ',' + drop_down_index + ');" onkeypress="InsertFieldChoiceisDropDown( ' + choice_index + ',' + drop_down_index + ');"></button>';

		// we only want the delete option if there is more than one choices
		if ( isDropDownChoices.length > 1 ) {
			str += "<button class='field-choice-button field-choice-button--delete gf_delete_field_choice gform-choice__button gform-choice__button--add gform-st-icon gform-st-icon--circle-minus' onclick=\"DeleteFieldChoiceisDropDown( " + choice_index + ',' + drop_down_index + ');" onkeypress="DeleteFieldChoiceisDropDown( ' + choice_index + ',' + drop_down_index + ');"></button>';
		}
		str += '</li>';
	}

	//set field selections
	var columns = 'undefined' === typeof field.enableColumns || ! field.enableColumns ? 'single' : 'multi';

	if ( 'single' == columns ) {
		choice_index = 'single';
	}

	// add HTML str to field settings
	jQuery( '.choices_setting_' + choice_index + ' #field_choices_' + choice_index ).html( str );

	// again - we only want the delete option if there is more than one choices
	const choices_length = isDropDownChoices.length;

	if ( choices_length == 1 ) {
		jQuery( '#field_choices_' + choice_index + ' .gf_delete_field_choice' ).hide();
	} else {
		jQuery( '#field_choices_' + choice_index + ' .gf_delete_field_choice' ).show();
	}
}

function itsg_gf_list_drop_down_maybe_string_to_array( isDropDownChoices ) {
	// LEGACY - string to array conversion
	if ( 'string' === typeof isDropDownChoices ) {
		// replace the legacy escaped comma delimiter with temporary string
		var isDropDownChoices = isDropDownChoices.replace( '\\,', 'ITSG_TEMP_DELIM' );
		// split into an array
		var isDropDownChoices = isDropDownChoices.split( ',' );

		const newArr = [];
		for ( let i = 0; i < isDropDownChoices.length; i++ ) {
			// replace legatic temporary string with a comma
			const value = isDropDownChoices[ i ].replace( 'ITSG_TEMP_DELIM', ',' );
			// push each option as an object into the array
			newArr.push( {
				value,
				text: value,
			} );
		}
		// assign the new array
		isDropDownChoices = newArr;
	}
	return isDropDownChoices;
}

// add new choice to drop down options - adds empty option to NEXT position in array and regenerates options HTML
function InsertFieldChoiceisDropDown( choice_index, drop_down_index ) {
	const new_dropdown = {
		value: '',
		text: '',
	};

	const columns = 'undefined' === typeof field.enableColumns || ! field.enableColumns ? 'single' : 'multi';

	if ( 'multi' == columns ) {
		const choice = field.choices[ choice_index ];
		var isDropDownChoices = 'undefined' === typeof choice.isDropDownChoices ? new_dropdown : choice.isDropDownChoices;
		isDropDownChoices = itsg_gf_list_drop_down_maybe_string_to_array( isDropDownChoices );
		isDropDownChoices.splice( drop_down_index + 1, 0, new_dropdown );
		SetFieldChoicesList( field.choices[ choice_index ], choice_index );
	} else {
		var isDropDownChoices = 'undefined' === typeof field.itsg_list_field_drop_down_options ? new_dropdown : field.itsg_list_field_drop_down_options;
		isDropDownChoices = itsg_gf_list_drop_down_maybe_string_to_array( isDropDownChoices );
		isDropDownChoices.splice( drop_down_index + 1, 0, new_dropdown );
		//field.itsg_list_field_drop_down_options = isDropDownChoices;
		SetFieldChoicesList( field, choice_index );
	}
}

// remove choice from drop down options - removes option from array and regenerates options HTML
function DeleteFieldChoiceisDropDown( choice_index, drop_down_index ) {
	const columns = 'undefined' === typeof field.enableColumns || ! field.enableColumns ? 'single' : 'multi';

	if ( 'multi' == columns ) {
		const choice = field.choices[ choice_index ];
		var isDropDownChoices = 'undefined' === typeof choice.isDropDownChoices ? '' : choice.isDropDownChoices;
		isDropDownChoices = itsg_gf_list_drop_down_maybe_string_to_array( isDropDownChoices );
		isDropDownChoices.splice( drop_down_index, 1 );
		//field.choices[ choice_index ] = isDropDownChoices;
		SetFieldChoicesList( field.choices[ choice_index ], choice_index );
	} else {
		var isDropDownChoices = 'undefined' === typeof field.itsg_list_field_drop_down_options ? '' : field.itsg_list_field_drop_down_options;
		isDropDownChoices = itsg_gf_list_drop_down_maybe_string_to_array( isDropDownChoices );
		isDropDownChoices.splice( drop_down_index, 1 );
		//field.itsg_list_field_drop_down_options = isDropDownChoices;
		SetFieldChoicesList( field, choice_index );
	}
}

// handles the 'make drop down' checkbox and option fields
function SetFieldChoiceDropDown( index ) {
	//set field selections
	const columns = 'undefined' === typeof field.enableColumns || ! field.enableColumns ? 'single' : 'multi';

	if ( 'single' == columns ) {
		index = 'single';
	}
	const isDropDown = jQuery( '#list_choice_dropdown_enable_' + index ).is( ':checked' );
	const isDropDownEnhanced = jQuery( '#list_choice_dropdown_enhanced_' + index ).is( ':checked' );
	//const isDropDownEnhancedOther = jQuery( '#list_choice_dropdown_enhanced_other_' + index ).is( ':checked' );
	const isDropDownEnableChoiceValue = jQuery( '#list_choice_values_enabled_' + index ).is( ':checked' );

	field = GetSelectedField();

	// get the drop down choices
	const isDropDownChoices = [];
	jQuery( '.choices_setting_' + index + ' li.field-choice-row-drop-down' ).each( function() {
		const dropdown_text = jQuery( this ).find( 'input.field-choice-text' ).val();
		const dropdown_value = jQuery( this ).find( 'input.field-choice-value' ).val();
		isDropDownChoices.push( {
			value: dropdown_value,
			text: dropdown_text,
		} );
	} );

	if ( 'multi' == columns ) {
		const choice = field.choices[ index ];
		choice.isDropDown = isDropDown;
		choice.isDropDownChoices = isDropDownChoices;
		choice.isDropDownEnhanced = isDropDownEnhanced;
		//choice.isDropDownEnhancedOther = isDropDownEnhancedOther;
		choice.isDropDownEnableChoiceValue = isDropDownEnableChoiceValue;
	} else {
		field.itsg_list_field_drop_down = isDropDown;
		field.itsg_list_field_drop_down_options = isDropDownChoices;
		field.itsg_list_field_drop_down_enhanced = isDropDownEnhanced;
		//field.list_choice_drop_down_enhanced_other = isDropDownEnhancedOther;
		field.isDropDownEnableChoiceValue = isDropDownEnableChoiceValue;
	}

	LoadBulkChoices( field );

	UpdateFieldChoices( GetInputType( field ) );

	itsg_gf_list_drop_down_preview( field );

	itsg_gf_list_drop_down_displayed_options( index );
}

// format the field preview inputs for multi-column list field
function itsg_gf_list_drop_down_preview( field ) {
	setTimeout( function() {
		if ( field.enableColumns ) {
			for ( let index = 0; index < field.choices.length; index++ ) {
				var isDropDown = jQuery( '#list_choice_dropdown_enable_' + index ).is( ':checked' );
				if ( isDropDown ) {
					var new_input = jQuery( '<select style="width:100%" disabled="">' );
					const column = index + 1;
					jQuery( 'li#field_' + field.id + ' table.gfield_list_container tbody tr td:nth-child(' + column + ')' ).html( new_input );
				}
			}
		} else {
			var isDropDown = field.itsg_list_field_drop_down;
			if ( isDropDown ) {
				var new_input = jQuery( '<td><select style="width:100%" disabled=""></td>' );
			} else {
				var new_input = jQuery( '<td><input type="text" style="width:100%" disabled=""></td>' );
			}
			jQuery( 'li#field_' + field.id + ' table.gfield_list_container tbody tr select' ).remove();
			jQuery( 'li#field_' + field.id + ' table.gfield_list_container tbody tr input' ).remove();
			jQuery( 'li#field_' + field.id + ' table.gfield_list_container tbody tr' ).prepend( new_input );
		}
	}, 50 );
}

// format available options
function itsg_gf_list_drop_down_displayed_options( index ) {
	const isDropDown = jQuery( '#list_choice_dropdown_enable_' + index ).is( ':checked' );
	const isDropDownEnhanced = jQuery( '#list_choice_dropdown_enhanced_' + index ).is( ':checked' );
	const isDropDownEnableChoiceValue = jQuery( '#list_choice_values_enabled_' + index ).is( ':checked' );

	//const isDropDownEnhancedOther_label = jQuery( 'label[for="list_choice_dropdown_enhanced_other_' + index + '"]' );
	//const isDropDownEnhancedOther_input = jQuery( '#list_choice_dropdown_enhanced_other_' + index );

	if ( isDropDownEnhanced ) {
	//	window.SetFieldAccessibilityWarning( 'enable_enhanced_ui_setting', 'above' );
		// show other option
		//isDropDownEnhancedOther_label.show();
		//isDropDownEnhancedOther_label.addClass( 'inline' );
		//isDropDownEnhancedOther_input.show();
	} else {
		//window.ResetFieldAccessibilityWarning( 'enable_enhanced_ui_setting', 'below' );
		// hide other option
		//isDropDownEnhancedOther_label.hide();
		//isDropDownEnhancedOther_label.removeClass( 'inline' );
		//isDropDownEnhancedOther_input.hide();
	}

	if ( isDropDownEnableChoiceValue ) {
		jQuery( '.choices_setting_' + index ).addClass( 'choice_with_value' );
	} else {
		jQuery( '.choices_setting_' + index ).removeClass( 'choice_with_value' );
	}
}

function itsg_gf_list_drop_down_init() {
	setTimeout( function() {
		itsg_gf_list_drop_down_preview( field );

		const field_type = GetInputType( field );
		if ( 'list' == field_type ) {
			if ( field.enableColumns ) {
				// hide single column options
				jQuery( '.list_drop_down_settings' ).hide();

				// set up mulit-column options
				for ( var choice_index = 0; choice_index < field.choices.length; choice_index++ ) {
					var isDropDown = field.choices[ choice_index ].isDropDown;
					if ( isDropDown ) {
						if ( ! jQuery( '#list_choice_dropdown_options .choices_setting_' + choice_index ).length ) {
							// create input, change callback
							const new_input = jQuery( '.choices_setting' ).first().prop( 'outerHTML' ).replace( /"choices_setting/g, '"choices_setting_' + choice_index ).replace( /"field_choices"/g, "'field_choices_" + choice_index + "' data-index='" + choice_index + "'" ).replace( 'gfield_settings_choices_container', 'gfield_settings_choices_container_' + choice_index ).replace( 'ToggleChoiceValue();', '' ).replace( 'SetFieldChoices();', '' ).replace( "SetFieldProperty('enableChoiceValue', this.checked)", 'SetFieldChoiceDropDown(' + choice_index + ')' ).replace( /"field_choice_values_enabled"/g, 'list_choice_values_enabled_' + choice_index );

							// add input to end of column options
							jQuery( '.list_choice_dropdown_options_' + choice_index ).prepend( new_input );

							// remove tooltip
							jQuery( '.choices_setting_' + choice_index + ' a.gf_tooltip' ).remove();

							// remove pre-fill options
							jQuery( '.choices_setting_' + choice_index ).find( '.choices-ui__section[data-type="bulk-choices"]' ).remove();
							// add options
							SetFieldChoicesList( field.choices[ choice_index ], choice_index );

							// display
							jQuery( '.choices_setting_' + choice_index ).show();
							// display options
						}
						jQuery( '.list_choice_dropdown_options_' + choice_index ).show();

						// set values
						var isDropDown = 'undefined' !== typeof field.choices[ choice_index ].isDropDown ? field.choices[ choice_index ].isDropDown : false;
						var isDropDownChoices = 'undefined' !== typeof field.choices[ choice_index ].isDropDownChoices ? field.choices[ choice_index ].isDropDownChoices : '';
						var isDropDownEnhanced = 'undefined' !== typeof field.choices[ choice_index ].isDropDownEnhanced ? field.choices[ choice_index ].isDropDownEnhanced : false;
						//var isDropDownEnhancedOther = 'undefined' !== typeof field.choices[ choice_index ].isDropDownEnhancedOther ? field.choices[ choice_index ].isDropDownEnhancedOther : false;
						var isDropDownEnableChoiceValue = 'undefined' !== typeof field.choices[ choice_index ].isDropDownEnableChoiceValue ? field.choices[ choice_index ].isDropDownEnableChoiceValue : false;

						jQuery( '#field_columns #list_choice_dropdown_enable_' + choice_index ).prop( 'checked', isDropDown );
						jQuery( '#field_columns #list_choice_dropdown_options_' + choice_index ).val( isDropDownChoices );
						jQuery( '#field_columns #list_choice_dropdown_enhanced_' + choice_index ).prop( 'checked', isDropDownEnhanced );
						if ( isDropDownEnhanced && jQuery( '#field_columns #list_choice_dropdown_enhanced_warning_' + choice_index ).length === 0 ) {
							jQuery( '#field_columns #list_choice_dropdown_enhanced_' + choice_index ).parent().append( '<div id=list_choice_dropdown_enhanced_warning_' + choice_index + ' class="gform-alert gform-alert--accessibility gform-alert--inline"><span class="gform-alert__icon gform-icon gform-icon--accessibility" aria-hidden="true"></span><div class="gform-alert__message-wrap"><p class="gform-alert__message">The Enhanced User Interface is not accessible for screen reader users and people who cannot use a mouse.</p><a class="gform-alert__cta gform-button gform-button--white gform-button--size-xs" href="https://docs.gravityforms.com/field-accessibility-warning" target="_blank">Learn more</a></div></div>');
						}
						if ( ! isDropDownEnhanced ) {
							jQuery( '#field_columns #list_choice_dropdown_enhanced_warning_' + choice_index ).remove();
						}
						//jQuery( '#field_columns #list_choice_dropdown_enhanced_other_' + choice_index ).prop( 'checked', isDropDownEnhancedOther );
						jQuery( '#field_columns #list_choice_values_enabled_' + choice_index ).prop( 'checked', isDropDownEnableChoiceValue );

						// set drop down options
						itsg_gf_list_drop_down_displayed_options( choice_index );
					} else {
						jQuery( '.list_choice_dropdown_options_' + choice_index ).hide();
					}
				}
			} else {
				// show single column options
				jQuery( '.list_drop_down_settings' ).show();

				var choice_index = 0;

				// set values
				var isDropDown = 'undefined' !== typeof field.itsg_list_field_drop_down ? field.itsg_list_field_drop_down : false;
				var isDropDownChoices = 'undefined' !== typeof field.itsg_list_field_drop_down_options ? field.itsg_list_field_drop_down_options : '';
				var isDropDownEnhanced = 'undefined' !== typeof field.itsg_list_field_drop_down_enhanced ? field.itsg_list_field_drop_down_enhanced : false;
				//var isDropDownEnhancedOther = 'undefined' !== typeof field.list_choice_drop_down_enhanced_other ? field.list_choice_drop_down_enhanced_other : false;
				var isDropDownEnableChoiceValue = 'undefined' !== typeof field.isDropDownEnableChoiceValue ? field.isDropDownEnableChoiceValue : false;

				jQuery( '.list_drop_down_settings #list_choice_dropdown_enable_single' ).prop( 'checked', isDropDown );
				jQuery( '.list_drop_down_settings #list_choice_dropdown_options_single' ).val( isDropDownChoices );
				jQuery( '.list_drop_down_settings #list_choice_dropdown_enhanced_single' ).prop( 'checked', isDropDownEnhanced );
				//jQuery( '.list_drop_down_settings #list_choice_dropdown_enhanced_other_single' ).prop( 'checked', isDropDownEnhancedOther );
				jQuery( '.list_drop_down_settings #list_choice_values_enabled_single' ).prop( 'checked', isDropDownEnableChoiceValue );

				// display options if isDropDown enabled
				if ( isDropDown ) {
					if ( ! jQuery( '#list_choice_dropdown_options .choices_setting_' + choice_index ).length ) {

						// add options
						SetFieldChoicesList( field, choice_index );

						// display
						jQuery( '.choices_setting_' + choice_index ).show();
					}

					jQuery( '#list_drop_down_options' ).show();

					// set drop down options
					itsg_gf_list_drop_down_displayed_options( 'single' );
				} else {
					jQuery( '#list_drop_down_options' ).hide();
				}
			}
		}
	}, 50 );
}

// trigger for when column titles are updated
jQuery( document ).on( 'change', '#gfield_settings_columns_container #field_columns li', function() {
	itsg_gf_list_drop_down_init();
} );

// trigger when 'Enable multiple columns' is ticked
jQuery( document ).on( 'change', '#field_settings input[id=field_columns_enabled]', function() {
	itsg_gf_list_drop_down_init();
} );

// trigger for when field is opened
jQuery( document ).bind( 'gform_load_field_settings', function( event, field, form ) {
	itsg_gf_list_drop_down_init();
} );

function ToggleListChoicesAccessibilityWarning( element ) {
	console.log( ' yes' );
}
