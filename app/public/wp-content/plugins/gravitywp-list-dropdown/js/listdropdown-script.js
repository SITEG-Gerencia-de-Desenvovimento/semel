/* eslint-disable camelcase */
const text_no_results = itsg_gf_listdropdown_js_settings.text_no_results;
const text_placeholder = itsg_gf_listdropdown_js_settings.text_placeholder;
//const text_no_results_other = itsg_gf_listdropdown_js_settings.text_no_results_other;
//const text_placeholder_other = itsg_gf_listdropdown_js_settings.text_placeholder_other;

function itsg_gf_listdropdown_make_chosen( select, placeholder_text, no_results_text ) {
	const options = window.gform.applyFilters( 'gform_chosen_options', {
		width: '100%',
		placeholder_text,
		no_results_text,
	}, jQuery( '.ginput_list' ).find( 'select.chosen' ) );

	// set first value as selected if none are
	if ( ! select.find( 'option:selected' ).length ) {
		select.val( select.find( 'option:first' ).val() );
	}

	select.chosen( options );

	itsg_gf_listdropdown_set_width_function( select );
}

function itsg_gf_listdropdown_newrow_function( self ) {
	const new_row = jQuery( self ).parents( '.gfield_list_group' ).next( '.gfield_list_group' );
	new_row.find( '.gfield_list_cell > select option:first-of-type' ).prop( 'selected', true ); // select the first item in the select list
	new_row.find( '.chosen-container' ).remove(); // remove existing chosen container - ready for it to be recreated
	new_row.find( 'select.chosen' ).show(); // make sure the select is displayed - ready for it to be hidden again when chosen runs

	new_row.find( 'select.chosen:not(.other)' ).each( function() {
		const select = jQuery( this );
		itsg_gf_listdropdown_make_chosen( select, text_placeholder, text_no_results );
	} );

	/* new_row.find( 'select.chosen.other' ).each( function() {
		const select = jQuery( this );
		itsg_gf_listdropdown_make_chosen( select, text_placeholder_other, text_no_results_other );
	} ); */

	new_row.find( 'select' ).on( 'change', function() {
		jQuery( this ).keyup();
	} );

	itsg_gf_listdropdown_set_height_function();
	//itsg_gf_listdropdown_set_width_function();
}

function itsg_gf_listdropdown_set_height_function() {
	var height = jQuery( 'select.chosen' ).parents( 'tr' ).find( 'input' ).first().innerHeight();
	if ( height < 20 ) {
		height = jQuery( '.gfield input' ).first().innerHeight();
		if ( height < 20 ) {
			setTimeout( function() {
				height = jQuery( '.gfield input' ).first().innerHeight();
				if ( height < 20 ) {
					height = 34;
				}
				jQuery( '.ginput_list .chosen-container-single .chosen-single' ).height( height + 'px' );
			}, 500 );
		}
	}
	jQuery( '.ginput_list .chosen-container-single .chosen-single' ).height( height + 'px' );
}

function itsg_gf_listdropdown_set_width_function( select ) {
	const column = select.closest( 'td' );
	const chosen_container = select.next( '.chosen-container' );
	const chosen_container_width = chosen_container.width();
	if ( chosen_container_width > 0 ) {
		column.css( {
			width: chosen_container_width + 'px',
		} );
		chosen_container.css( {
			width: chosen_container_width + 'px',
			display: 'block',
		} );
	}
}

if ( '1' == itsg_gf_listdropdown_js_settings.is_entry_detail ) {
	// runs the main function when the page loads -- entry editor -- configures any existing upload fields
	jQuery( document ).ready( function( $ ) {
		jQuery( '.ginput_list' ).find( 'select.chosen:not(.other)' ).each( function( index, value ) {
			const select = jQuery( this );

			itsg_gf_listdropdown_make_chosen( select, text_placeholder, text_no_results );
		} );

/* 		jQuery( '.ginput_list' ).find( 'select.chosen.other' ).each( function( index, value ) {
			const select = jQuery( this );

			itsg_gf_listdropdown_make_chosen( select, text_placeholder_other, text_no_results_other );
		} ); */

		jQuery( '.ginput_list' ).find( 'select' ).on( 'change', function() {
			jQuery( this ).keyup();
		} );

		itsg_gf_listdropdown_set_height_function();
		//itsg_gf_listdropdown_set_width_function();

		// when field is added to repeater, runs the main function passing the current row
		jQuery( '.gfield_list' ).on( 'click', '.add_list_item', function() {
			itsg_gf_listdropdown_newrow_function( jQuery( this ) );
		} );
	} );
} else {
	// runs the main function when the page loads -- front end forms -- configures any existing upload fields
	jQuery( document ).bind( 'gform_post_render', function( $ ) {
		jQuery( '.ginput_list' ).find( 'select.chosen:not(.other)' ).each( function( index, value ) {
			const select = jQuery( this );

			itsg_gf_listdropdown_make_chosen( select, text_placeholder, text_no_results );
		} );

/* 		jQuery( '.ginput_list' ).find( 'select.chosen.other' ).each( function( index, value ) {
			const select = jQuery( this );

			itsg_gf_listdropdown_make_chosen( select, text_placeholder_other, text_no_results_other );
			itsg_gf_listdropdown_set_height_function( select );
		} ); */

		jQuery( '.ginput_list' ).find( 'select' ).on( 'change', function() {
			jQuery( this ).keyup();
		} );

		// when field is added to repeater, runs the main function passing the current row
		jQuery( '.gfield_list' ).on( 'click', '.add_list_item', function() {
			itsg_gf_listdropdown_newrow_function( jQuery( this ) );
		} );
	} );
}
