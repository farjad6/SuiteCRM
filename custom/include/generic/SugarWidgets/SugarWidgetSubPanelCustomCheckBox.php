<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*
 * Class: SugarWidgetSubPanelCustomCheckBox
 * Description: Custom Check Box Widget for subpannel massupdate
 * */
include_once('include/generic/SugarWidgets/SugarWidgetField.php');

class SugarWidgetSubPanelCustomCheckBox extends SugarWidgetField{
    function displayListPlain($layout_def) {
        //Modification to allow checkboxes to be displayed correctly in subpanel
        if ($layout_def['checkbox_value']=='true' ) {
            return "&nbsp;<input name='mass[]' class='checkbox' type='checkbox' id='".$layout_def['module']."checkbox_display_id[]' value=\"".$layout_def['fields']['ID']."\" onclick=''>";
        }
        return "&nbsp;<input name='checkbox_display' class='checkbox' type='checkbox' disabled='true'>";
    }
}