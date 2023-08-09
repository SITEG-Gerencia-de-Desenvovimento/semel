<?php
namespace GravityWP\List_Dropdown;

use GFForms;
use RGFormsModel;
use GFFormsModel;
use GFAddOn;
use GFAPI;
use GFCommon;
use GFFormDisplay;
use GravityWP\LicenseHandler;

class_exists( 'GFForms' ) || die();

GFForms::include_addon_framework();

class GravityWPListDropDown extends GFAddOn {
	private static $_instance            = null;
	protected $_version                  = GWP_LIST_DROPDOWN_VERSION;
	protected $_min_gravityforms_version = '2.5';
	protected $_slug                     = 'gravitywp-list-dropdown';
	protected $_path                     = 'gravitywp-list-dropdown/gravitywp-list-dropdown.php';
	protected $_full_path                = __FILE__;
	protected $_title                    = 'GravityWP - List Dropdown';
	protected $_short_title              = 'List Dropdown';

	/**
	 * Store the initialized gwp license handler
	 *
	 * @since  2.0
	 * @access private
	 * @var    Object $_gwp_license_handler License Handler instance.
	 */
	private $_gwp_license_handler = null;

	/**
	 * Defines if Add-On should use Gravity Forms servers for update data.
	 *
	 * @since  2.0
	 * @access protected
	 * @var    bool
	 */
	protected $_enable_rg_autoupgrade = false;

	/**
	 * Defines the capabilities needed for the Add-On
	 *
	 * @since  2.0
	 * @access protected
	 * @var    array $_capabilities The capabilities needed for the Add-On
	 */
	protected $_capabilities = array( 'gravitywplistdropdown_form_settings', 'gravitywplistdropdown_plugin_settings', 'gravitywplistdropdown_uninstall' );

	/**
	 * Defines the capability needed to access the Add-On settings page.
	 *
	 * @since  2.0
	 * @access protected
	 * @var    string $_capabilities_settings_page The capability needed to access the Add-On settings page.
	 */
	protected $_capabilities_settings_page = 'gravitywplistdropdown_plugin_settings';

	/**
	 * Defines the capability needed to access the Add-On form settings page.
	 *
	 * @since  2.0
	 * @access protected
	 * @var    string $_capabilities_form_settings The capability needed to access the Add-On form settings page.
	 */
	protected $_capabilities_form_settings = 'gravitywplistdropdown_form_settings';

	/**
	 * Defines the capability needed to uninstall the Add-On.
	 *
	 * @since  2.0
	 * @access protected
	 * @var    string $_capabilities_uninstall The capability needed to uninstall the Add-On.
	 */
	protected $_capabilities_uninstall = 'gravitywplistdropdown_uninstall';

	/**
	 * Defines the URL where this add-on can be found.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string The URL of the Add-On.
	 */
	public $gwp_site_slug = 'list-dropdown';

	/**
	 * Get an instance of this class.
	 */
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function init() {
		parent::init();

		// start the plugin.
		add_filter( 'gform_column_input', array( $this, 'change_column_input' ), 10, 5 );
		add_filter( 'gform_column_input_content', array( $this, 'change_column_content' ), 99, 6 );

		add_action( 'gform_field_standard_settings', array( $this, 'single_field_settings' ), 10, 2 );
		add_filter( 'gform_tooltips', array( $this, 'field_tooltips' ) );

		add_filter( 'gform_pre_render', array( $this, 'add_user_option' ), 10, 1 );
		add_filter( 'gform_pre_validation', array( $this, 'add_user_option' ), 10, 1 );
		add_filter( 'gform_admin_pre_render', array( $this, 'add_user_option' ), 10, 1 );
		add_filter( 'gform_pre_submission_filter', array( $this, 'add_user_option' ), 10, 1 );

		add_filter( 'gform_get_input_value', array( $this, 'display_dropdown_value' ), 10, 4 );
	}

	public function init_admin() {
		// Init license handler.
		if ( ! $this->_gwp_license_handler ) {
			$this->_gwp_license_handler = new GravityWP\LicenseHandler\LicenseHandler( __CLASS__, '0981c764-d33d-42b8-9d78-0ee2e8272a5e', plugin_dir_path( __FILE__ ) . 'gravitywp-list-dropdown.php' );
		}

		parent::init_admin();
	}

	/**
	 * Return the plugin's icon for the plugin/form settings menu.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_menu_icon(): string {
		return '<svg id="svg" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400"><g id="svgg"><path id="path1" d="M74.664 105.854 L 74.664 133.973 197.025 133.973 L 319.386 133.973 319.386 105.854 L 319.386 77.735 197.025 77.735 L 74.664 77.735 74.664 105.854 M314.779 105.950 L 314.779 129.559 197.409 129.559 L 80.038 129.559 80.038 105.950 L 80.038 82.342 197.409 82.342 L 314.779 82.342 314.779 105.950 M290.861 100.661 C 290.756 100.934,296.900 112.886,297.425 113.429 C 297.664 113.676,297.730 113.676,297.972 113.429 C 298.498 112.893,304.641 100.942,304.534 100.664 C 304.374 100.246,291.021 100.243,290.861 100.661 M74.472 195.298 L 74.472 223.417 196.833 223.417 L 319.194 223.417 319.194 195.298 L 319.194 167.179 196.833 167.179 L 74.472 167.179 74.472 195.298 M314.587 195.393 L 314.587 219.002 197.217 219.002 L 79.846 219.002 79.846 195.393 L 79.846 171.785 197.217 171.785 L 314.587 171.785 314.587 195.393 M290.669 190.104 C 290.564 190.378,296.708 202.330,297.233 202.872 C 297.472 203.119,297.538 203.119,297.780 202.872 C 298.306 202.336,304.449 190.386,304.342 190.107 C 304.182 189.690,290.829 189.687,290.669 190.104 M75.048 285.317 L 75.048 313.436 197.409 313.436 L 319.770 313.436 319.770 285.317 L 319.770 257.198 197.409 257.198 L 75.048 257.198 75.048 285.317 M315.163 285.413 L 315.163 309.021 197.793 309.021 L 80.422 309.021 80.422 285.413 L 80.422 261.804 197.793 261.804 L 315.163 261.804 315.163 285.413 M291.245 280.123 C 291.139 280.397,297.284 292.349,297.809 292.891 C 298.047 293.138,298.114 293.138,298.356 292.891 C 298.882 292.355,305.025 280.405,304.918 280.126 C 304.758 279.709,291.405 279.706,291.245 280.123 " stroke="none" fill="#000" fill-rule="evenodd"></path></g></svg>';
	}

	public function scripts() {
		$min     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';
		$version = $this->_version;

		$scripts = array(
			array(
				'handle'    => 'itsg_listdropdown_js',
				'src'       => $this->get_base_url() . "/js/listdropdown-script{$min}.js",
				'version'   => $version,
				'deps'      => array( 'jquery', 'gform_chosen' ),
				'enqueue'   => array( array( $this, 'requires_scripts' ) ),
				'in_footer' => true,
				'callback'  => array( $this, 'localize_scripts' ),
			),
			array(
				'handle'    => 'itsg_listdropdown_admin_js',
				'src'       => $this->get_base_url() . "/js/listdropdown-script-admin{$min}.js",
				'version'   => $version,
				'deps'      => array( 'jquery' ),
				'enqueue'   => array( array( $this, 'requires_admin_js' ) ),
				'in_footer' => true,
				'callback'  => array( $this, 'localize_scripts_admin' ),
			),
		);

			return array_merge( parent::scripts(), $scripts );
	}

	function requires_admin_js() {
		return GFCommon::is_form_editor();
	}

	public function localize_scripts_admin( $form, $is_ajax ) {
		$settings_array = array(
			'text_drop_down_columns'              => esc_js( __( 'Drop Down Columns', 'gravitywp-list-dropdown' ) ),
			'text_drop_down_columns_instructions' => esc_js( __( 'Place a tick next to the field to make it a drop down field. Enter the drop down options into the box as comma-separated-values, e.g. Mr,Mrs,Miss,Ms', 'gravitywp-list-dropdown' ) ),
			'text_make_drop_down'                 => esc_js( __( 'Enable Dropdown select', 'gravitywp-list-dropdown' ) ),
			'text_drop_down_options'              => esc_js( __( 'Drop Down Options', 'gravitywp-list-dropdown' ) ),
			'text_enable_enhanced'                => esc_js( __( 'Enable enhanced user interface', 'gravitywp-list-dropdown' ) ),
		//	'text_enable_add_options'             => esc_js( __( 'Enable add options', 'gravitywp-list-dropdown' ) ),
		);

		wp_localize_script( 'itsg_listdropdown_admin_js', 'itsg_listdropdown_admin_js_settings', $settings_array );
	}

	public function styles() {
		$min     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';
		$version = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? mt_rand() : $this->_version;

		$styles = array(
			array(
				'handle'  => 'listdropdown-style',
				'src'     => $this->get_base_url() . "/css/listdropdown-style{$min}.css",
				'version' => $version,
				'media'   => 'screen',
				'enqueue' => array( array( $this, 'requires_scripts' ) ),
			),
			array(
				'handle'  => 'itsg_listdropdown_admin_style',
				'src'     => $this->get_base_url() . '/css/listdropdown-admin-style.css',
				'version' => $version,
				'media'   => 'screen',
				'enqueue' => array( array( $this, 'requires_admin_js' ) ),
			),
		);

		return array_merge( parent::styles(), $styles );
	}

	public function localize_scripts( $form, $is_ajax ) {
		$is_entry_detail = GFCommon::is_entry_detail();

		$settings_array = array(
			'is_entry_detail'        => $is_entry_detail ? $is_entry_detail : 0,
			'text_no_results'        => esc_js( __( 'No results match', 'gravitywp-list-dropdown' ) ),
			'text_placeholder'       => esc_js( __( 'Select an option', 'gravitywp-list-dropdown' ) ),
			'text_no_results_other'  => esc_js( __( 'No results match, press enter to add: ', 'gravitywp-list-dropdown' ) ),
			'text_placeholder_other' => esc_js( __( 'Select or enter an option', 'gravitywp-list-dropdown' ) ),
		);

		wp_localize_script( 'itsg_listdropdown_js', 'itsg_gf_listdropdown_js_settings', $settings_array );
	}

	public function requires_scripts( $form, $is_ajax ) {
		if ( ! $this->is_form_editor() && is_array( $form ) ) {
			foreach ( $form['fields'] as $field ) {
				if ( 'list' == $field->get_input_type() ) {
					$has_columns = is_array( $field->choices );
					if ( $has_columns ) {
						foreach ( $field->choices as $choice ) {
							if ( rgar( $choice, 'isDropDown' ) ) {
								return true;
							}
						}
					} elseif ( $field->itsg_list_field_dropdown || $field->itsg_list_field_drop_down ) {
						return true;
					}
				}
			}
		}

		return false;
	}

			// ensures that the drop down LABEL is displayed - the VALUE is passed in the POST and stored by GF so we do a lookup to get the LABEL from the VALUE
	function display_dropdown_value( $value, $entry, $field, $input_id ) {
		if ( $value ) {
			$is_entry_detail = GFCommon::is_entry_detail();
			if ( ! ( $is_entry_detail && 'edit' == rgpost( 'screen_mode' ) ) && is_object( $field ) && 'list' == $field->get_input_type() ) {
				$has_columns = is_array( $field->choices );
				$list_values = unserialize( $value );
				if ( ! empty( $list_values ) ) {
					$form_id      = $entry['form_id'];
					$field_id     = $field->id;
					$submit_value = array(); // we'll be storing the row in here for passing later
					if ( $has_columns ) {
						foreach ( $list_values as $row ) { // get each row
							foreach ( $field->choices as $key => $choice ) { // for each column
								$column                      = rgars( $field->choices, "{$key}/text" ); // we'll be using the column label as the key
								$isDropDown                  = rgar( $choice, 'isDropDown' );
								$isDropDownEnableChoiceValue = rgar( $choice, 'isDropDownEnableChoiceValue' );

								if ( $isDropDown && $isDropDownEnableChoiceValue ) {
									$choices = rgar( $choice, 'isDropDownChoices' );
									foreach ( $choices as $ch ) { // get each row
										if ( rgar( $row, $column ) == $ch['value'] ) {
											$row[ $column ] = $ch['text'];
										}
									}
								}
							}
							array_push( $submit_value, $row ); // add row to submit value array
						}
					} else {
						foreach ( $list_values as $key => $value ) { // get each row
							$isDropDown                  = $field->itsg_list_field_drop_down;
							$isDropDownEnableChoiceValue = $field->isDropDownEnableChoiceValue;
							$choices                     = $field->isDropDownChoices;
							if ( $isDropDown && $isDropDownEnableChoiceValue && $choices ) {
								foreach ( $choices as $ch ) { // get each row
									if ( $value == $ch['value'] ) {
										$list_values[ $key ] = $ch['text'];
									}
								}
							}
						}
						$submit_value = $list_values; // add row to submit value array
					}
					$value = serialize( $submit_value );
				}
			}
		}
		return $value;
	} // END display_dropdown_value

	function drop_down_maybe_string_to_array( $choices, $column ) {
		if ( is_string( $choices ) ) {
			// allows a backslash to be used in string to escape a comma - allows an option value to include a comma
			$isDropDownChoices = str_replace( '\,', 'ITSG_TEMP_DELIM', $choices ); // replace escaped comma with temp string
			$options           = explode( ',', $isDropDownChoices );
			foreach ( $options as &$option ) {
				$option = str_replace( 'ITSG_TEMP_DELIM', ',', $option ); // replace temp string with comma
			}

			$options = array_map( 'trim', $options ); // remove blank spaces at start or end of each option

			$new_array = array();

			foreach ( $options as $option ) {
				$new_array[] = array(
					'value' => $option,
					'text'  => $option,
				);
			}

			return $new_array;
		} elseif ( is_array( $choices ) ) {
			$isDropDownEnableChoiceValue = rgar( $column, 'isDropDownEnableChoiceValue' );

			if ( ! $isDropDownEnableChoiceValue ) {
				foreach ( $choices as &$option ) {
					$option['value'] = $option['text'];
				}
			}
		}
		return $choices;
	}

			/*
			 * When the 'Enable add options' option is enabled and submitted value does not exist add it to the list of options
			 */
	function add_user_option( $form ) {
		if ( GFCommon::is_form_editor() ) {
			return $form;
		}

		if ( is_array( $form ) || is_object( $form ) ) {
			if ( isset( $_GET['gf_token'] ) ) { // if resuming saved form
				$incomplete_submission_info = GFFormsModel::get_incomplete_submission_values( $_GET['gf_token'] );
				if ( $incomplete_submission_info['form_id'] == $form['id'] ) {
					$submission_details_json = $incomplete_submission_info['submission'];
					$submission_details      = json_decode( $submission_details_json, true );
					$submitted_values        = $submission_details['submitted_values'];
				}
			} elseif ( GFCommon::is_entry_detail() ) { // if viewing entry in entry editor
				$lead_id = absint( rgar( $_POST, 'lid' ) ? rgar( $_POST, 'lid' ) : rgar( $_GET, 'lid' ) );
				if ( empty( $lead_id ) ) {
					return $form;
				}
				$lead = GFAPI::get_entry( $lead_id );
				if ( is_wp_error( $lead ) || ! $lead ) {
					return $form;
				}
			}

			foreach ( $form['fields'] as &$field ) {  // for all form fields
				$field_id = $field->id;
				if ( 'list' == $field->get_input_type() ) {

					if ( isset( $_GET['gf_token'] ) ) {
						$list_values = maybe_unserialize( $submitted_values[ $field_id ] );
					} elseif ( GFCommon::is_entry_detail() ) {
						$list_values = maybe_unserialize( $lead[ $field_id ] );
					} else {
						$list_values = maybe_unserialize( RGFormsModel::get_field_value( $field ) );  // get the value of the field
					}

					if ( empty( $list_values ) ) {
						continue;
					}

					$has_columns = is_array( $field->choices );

					if ( $has_columns ) {
						foreach ( $list_values as $row ) { // get each row
							foreach ( $field->choices as $key => &$choice ) { // for each column
								$column     = rgars( $field->choices, "{$key}/text" ); // we'll be using the column label as the key
								$isDropDown = rgar( $choice, 'isDropDown' );
								if ( $isDropDown ) {
									$choices = rgar( $choice, 'isDropDownChoices' );

									$choices = $this->drop_down_maybe_string_to_array( $choices, $choice );

									$choice_value = (string) rgar( $row, $column );
									foreach ( $choices as $ch ) { // get each row
										$arr_values[] = $ch['value'];
									}
									if ( ! in_array( $choice_value, $arr_values ) ) {
										$new_value                   = array(
											'value' => $choice_value,
											'text'  => $choice_value,
										);
										$choices[]                   = $new_value;
										$choice['isDropDownChoices'] = $choices;
									}
								}
							}
						}
					} else {
						foreach ( $list_values as $row ) { // get each row
							$isDropDown              = $field->itsg_list_field_drop_down;
							$isDropDownEnhanced      = $field->itsg_list_field_drop_down_enhanced;
							$isDropDownEnhancedOther = $field->list_choice_drop_down_enhanced_other;
							if ( $isDropDown ) {
								$choices = $field->itsg_list_field_drop_down_options;

								$choices = $this->drop_down_maybe_string_to_array( $choices, $field );

								$choice_value = (string) $row;
								foreach ( $choices as $ch ) { // get each row
									$arr_values[] = $ch['value'];
								}
								if ( ! in_array( $choice_value, $arr_values ) ) {
									$new_value                                = array(
										'value' => $choice_value,
										'text'  => $choice_value,
									);
									$choices[]                                = $new_value;
									$field->itsg_list_field_drop_down_options = $choices;

								}
							}
						}
					}
				}
			}
		}
		return $form;
	} // END add_user_option

	/**
	 * Replaces field content for repeater lists - adds title to select drop down fields using the column title
	 */
	public function change_column_content( $input, $input_info, $field, $text, $value, $form_id ) {
		if ( 'list' == $field->get_input_type() ) {
			$input       = str_replace( '<select ', "<select title='" . $text . "'", $input );
			$has_columns = is_array( $field->choices );
			if ( $has_columns ) {
				foreach ( $field->choices as $choice ) {
					if ( $text == $choice['text'] && rgar( $choice, 'isDropDownEnhanced' ) && '' != rgar( $choice, 'isDropDownChoices' ) ) {
						// $classes = rgar( $choice, 'isDropDownEnhancedOther' ) ? 'chosen other' : 'chosen';
						$classes = 'chosen';
						$input   = str_replace( '<select ', "<select class='{$classes}' ", $input );
					}
				}
			} else {
				if ( $field->itsg_list_field_drop_down_enhanced && '' != $field->itsg_list_field_drop_down_options ) {
					// $classes = $field->list_choice_drop_down_enhanced_other ? 'chosen other' : 'chosen';
					$classes = 'chosen';
					$input   = str_replace( '<select ', "<select class='{$classes}' ", $input );
				}
			}
		}
		return $input;
	} // END change_column_content

	/*
		* Changes column field if 'drop down field' option is ticked. Creates array of options, changes input type to select and add options.
		*/
	public function change_column_input( $input_info, $field, $column, $value, $form_id ) {
		if ( 'list' == $field->get_input_type() ) {
			$has_columns = is_array( $field->choices );
			if ( $has_columns ) {
				foreach ( $field->choices as $choice ) {
					if ( $column == $choice['text'] && rgar( $choice, 'isDropDown' ) && '' != rgar( $choice, 'isDropDownChoices' ) ) {
						// if value is being pre-populated (array required) -- TO DO -- more work on this, likely need custom hook because pre-population currently only applies when form is loaded - not when navigated or resumed
						if ( is_array( $value ) ) {
							return array(
								'type'    => 'select',
								'choices' => $value,
							);
						}

						$isDropDownChoices = rgar( $choice, 'isDropDownChoices' );

						$isDropDownChoices = $this->drop_down_maybe_string_to_array( $isDropDownChoices, $choice );

						$isDropDownEnableChoiceValue = rgar( $choice, 'isDropDownEnableChoiceValue' );

						if ( ! $isDropDownEnableChoiceValue ) {
							foreach ( $isDropDownChoices as &$option ) {
								$option['value'] = $option['text'];
							}
						}

						// check if value is already in the list of options - if not, add it in. This is important if the isDropDownEnhancedOther option is enabled or for existing entries when the list of options has been changed.
						if ( ! empty( $value ) && ! $this->does_option_exist( $isDropDownChoices, 'value', $value ) ) {
							array_unshift(
								$isDropDownChoices,
								array(
									'value' => $value,
									'text'  => $value,
								)
							);  // push current value into select list if options list is empty
						}

						return array(
							'type'    => 'select',
							'choices' => $isDropDownChoices,
						);

					}
				}
			} else {
				if ( $field->itsg_list_field_drop_down && '' != $field->itsg_list_field_drop_down_options ) {
					$isDropDownChoices = $field->itsg_list_field_drop_down_options;

					$isDropDownChoices = $this->drop_down_maybe_string_to_array( $isDropDownChoices, $field );

					$isDropDownEnableChoiceValue = $field->isDropDownEnableChoiceValue;

					if ( ! $isDropDownEnableChoiceValue ) {
						foreach ( $isDropDownChoices as &$option ) {
							$option['value'] = $option['text'];
						}
					}

					// check if value is already in the list of options - if not, add it in. This is important if the isDropDownEnhancedOther option is enabled or for existing entries when the list of options has been changed.
					if ( ! empty( $value ) && ! $this->does_option_exist( $isDropDownChoices, 'value', $value ) ) {
						array_unshift(
							$isDropDownChoices,
							array(
								'value' => $value,
								'text'  => $value,
							)
						);  // push current value into select list if options list is empty
					}

					return array(
						'type'    => 'select',
						'choices' => $isDropDownChoices,
					);
				}
			}
			return $input_info;
		}
	} // END change_column_input

	public function does_option_exist( $array, $key, $val ) {
		foreach ( $array as $item ) {
			if ( isset( $item[ $key ] ) && $item[ $key ] == $val ) {
				return true;
			}
		}
		return false;
	}
			/*
			 * Tooltip for for datepicker option
			 */
	public static function field_tooltips( $tooltips ) {
		$tooltips['itsg_list_field_drop_down'] = '<h6>' . esc_html__( 'Drop Down', 'gravitywp-list-dropdown' ) . '</h6>' . esc_html__( 'Changes column to a drop down field. Only applies to single column list fields.', 'gravitywp-list-dropdown' );
		return $tooltips;
	} // END field_tooltips

	/**
	 * Adds custom settings for single column list field
	 */
	public function single_field_settings( $position, $form_id ) {
		if ( 1287 == $position ) {
			include $this->get_base_path() . '/templates/gwp-list-dropdown-field-settings.php';
		}
	} // END single_field_settings

	/*
	* Check if current form has a drop-down enabled list field
	*/
	public static function list_has_dropdown_field( $form ) {
		if ( is_array( $form['fields'] ) ) {
			foreach ( $form['fields'] as $field ) {
				if ( 'list' == $field->get_input_type() ) {
					$has_columns = is_array( $field->choices );
					if ( $has_columns ) {
						foreach ( $field->choices as $choice ) {
							if ( rgar( $choice, 'isDropDown' ) ) {
								return true;
							}
						}
					} elseif ( $field->itsg_list_field_dropdown || $field->itsg_list_field_drop_down ) {
						return true;
					}
				}
			}
		}
		return false;
	} // END list_has_datepicker_field

	// # PLUGIN SETTINGS -----------------------------------------------------------------------------------------------
	/**
	 * Define plugin settings fields.
	 *
	 * @since  1.0.2
	 *
	 * @return array
	 */
	public function plugin_settings_fields() {
		// Retrieve license fields.
		$license_fields = $this->_gwp_license_handler->plugin_settings_license_fields();
		$fields         = array( $license_fields );
		return $fields;
	}
}
