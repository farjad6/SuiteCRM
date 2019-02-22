<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*
 * Class: SugarWidgetSubPanelCustomCheckBox
 * Description: massupdate Top button subpannel widget
 * */

class SugarWidgetSubPanelTopMassUpdateButton extends SugarWidgetSubPanelTopButton
{
	
	function display($defines, $additionalFormFields = null, $nonbutton = false)
	{
		$temp='';
		$inputID = $this->getWidgetId();
		if(!empty($this->acl) && ACLController::moduleSupportsACL($defines['module'])  &&  !ACLController::checkAccess($defines['module'], $this->acl, true)){
			return $temp;
		}

		global $app_strings;
		$button = "<input title='Mass Update' onclick='showMassUpdate()' class='button' type='button' name='$inputID' id='$inputID' value='Mass Update' />\n</form>";


        require_once ("custom/include/SubpannelMassUpdate.php");
        $pt = BeanFactory::newBean($defines['module']);
        $ma = new SubpannelMassUpdate();
        $ma->setSugarBean($pt);
        echo '<script>';
        echo 'var buttonID = ' . json_encode($inputID) . ';';
        echo '$( document ).ready(function() {
                    elem = $("#"+buttonID).closest(".panel-body");
                    $("#MassUpdate").prependTo(elem);
                    $("#"+buttonID).closest(".tab-content").prependTo("#MassUpdate");
            });';
        echo 'function showMassUpdate(){
            $("#massupdate_form").show();
        }';
        echo '</script>';
        return $button . $ma->getMassUpdateFormHeader() . $ma->getMassUpdateForm();
	}
	
	public function getWidgetId() {
	   return $this->widget_id;
	}
	
}
