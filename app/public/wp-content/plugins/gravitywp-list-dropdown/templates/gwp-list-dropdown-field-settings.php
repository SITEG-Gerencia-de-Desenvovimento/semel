<li class="list_drop_down_settings gwp_list_drop_down_settings field_setting">
	<label class="section_label"><?php esc_html_e( 'Drop Down', 'gravitywp-list-dropdown' ); ?></label>
	<input type="checkbox" id="list_choice_dropdown_enable_single" onclick="SetFieldProperty( 'itsg_list_field_drop_down', this.checked);itsg_gf_list_drop_down_init();">
	<label class="inline" for="list_choice_dropdown_enable_single">
<?php esc_html_e( 'Enable Dropdown select', 'gravitywp-list-dropdown' ); ?>
<?php gform_tooltip( 'itsg_list_field_drop_down' ); ?>
	</label>
	<div id="list_drop_down_options" class="choices_setting_single" style="display:none; background: rgb(244, 244, 244) none repeat scroll 0px 0px; padding: 10px; border-bottom: 1px solid grey; margin-top: 10px;" >
		<div>
			<label class="gfield_choice_header_label"><?php esc_html_e( 'Label', 'gravitywp-list-dropdown' ); ?></label>
			<label class="gfield_choice_header_value"><?php esc_html_e( 'Value', 'gravitywp-list-dropdown' ); ?></label>
		</div>

		<div id="field_choices_single" >
			<!-- JAVASCRIPT WILL ADD CONTENT HERE -->
		</div>
		<br>
		<label for="choices" class="section_label"><b><?php esc_html_e( 'Options', 'gravitywp-list-dropdown' ); ?></b></label>
		<input type="checkbox" id="list_choice_values_enabled_single" onclick="SetFieldChoiceDropDown(0);  " onkeypress="SetFieldProperty('enableChoiceValue', this.checked); ToggleChoiceValue(); SetFieldChoices();">
				<label for="list_choice_values_enabled_single" class="inline gfield_value_label"><?php esc_html_e( 'Show values', 'gravitywp-list-dropdown' ); ?></label>
		<br><br>
		<input type="checkbox" onclick="SetFieldProperty( 'itsg_list_field_drop_down_enhanced', this.checked );itsg_gf_list_drop_down_init();" id="list_choice_dropdown_enhanced_single" >
		<label for="list_choice_dropdown_enhanced_single" style="display: inline; "><?php esc_html_e( 'Enable enhanced user interface', 'gravitywp-list-dropdown' ); ?></label>

		<!-- DROP SUPPORT FOR ADDING OPTIONS FRONTEND
		<br>		
		<input type="checkbox" onclick="SetFieldProperty( 'list_choice_drop_down_enhanced_other', this.checked );" id="list_choice_dropdown_enhanced_other_single" >
		<label for="list_choice_dropdown_enhanced_other_single" style="display: inline; "><?php esc_attr_e( 'Enable add options', 'gravitywp-list-dropdown' ); ?></label>
		-->
	</div>
</li>