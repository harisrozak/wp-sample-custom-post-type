<?php

function form_lib_print_select($arr_data, $name, $value)
{
	$print_select = "";
	$print_select.= "<select name='$name'>";

	foreach ($arr_data as $data) 
	{
		$selected = $data['value'] == $value ? 'selected' : '';

		$print_select.= "<option value='{$data['value']}' $selected >{$data['label']}</option>";
	}

	$print_select.= "</select>";

	echo $print_select;
}

function form_lib_print_radio($arr_data, $name, $value)
{
	$print_select = "";

	foreach ($arr_data as $data) 
	{
		$selected = $data['value'] == $value ? 'checked' : '';

		$print_select.= "<input type='radio' name='$name' value='{$data['value']}' $selected > {$data['label']}<br>";
	}

	echo $print_select;
}

function form_lib_print_checkbox($arr_data)
{
	$print_select = "";

	foreach ($arr_data as $data) 
	{
		$selected = $data['value'] == $data['saved_value'] ? 'checked' : '';

		$print_select.= "<input type='checkbox' name='{$data['name']}' value='{$data['value']}' $selected > {$data['label']}<br>";
	}

	echo $print_select;
}