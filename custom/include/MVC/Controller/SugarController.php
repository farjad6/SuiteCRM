<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('include/MVC/Controller/SugarController.php');

class CustomSugarController extends SugarController
{
	protected function action_subpannelmassupdate(){
	    global $beanList;
        if (!empty($_REQUEST['massupdate']) && $_REQUEST['massupdate'] == 'true' && !empty($_REQUEST['mass'])) {
            set_time_limit(0);//I'm wondering if we will set it never goes timeout here.
            // until we have more efficient way of handling MU, we have to disable the limit
            DBManagerFactory::getInstance()->setQueryLimit(0);
            require_once("include/MassUpdate.php");
            require_once('modules/MySettings/StoreQuery.php');
            if( in_array($_REQUEST['module'],$beanList) ){
                $_REQUEST['module'] = array_search ($_REQUEST['module'], $beanList);
            }
            $seed = BeanFactory::newBean($_REQUEST['module']);
            $mass = new MassUpdate();
            $mass->setSugarBean($seed);
            if (isset($_REQUEST['entire']) && empty($_POST['mass'])) {
                $mass->generateSearchWhere($_REQUEST['module'], $_REQUEST['current_query_by_page']);
            }
            $mass->handleMassUpdate();
            $storeQuery = new StoreQuery();//restore the current search. to solve bug 24722 for multi tabs massupdate.
            $temp_req = array(
                'current_query_by_page' => $_REQUEST['current_query_by_page'],
                'return_module' => $_REQUEST['return_module'],
                'return_action' => $_REQUEST['return_action'],
                'return_record' => $_REQUEST['return_record'],
            );
            if ($_REQUEST['return_module'] == 'Emails') {
                if (!empty($_REQUEST['type']) && !empty($_REQUEST['ie_assigned_user_id'])) {
                    $this->req_for_email = array(
                        'type' => $_REQUEST['type'],
                        'ie_assigned_user_id' => $_REQUEST['ie_assigned_user_id']
                    ); // Specifically for My Achieves
                }
            }
            $_REQUEST = array();
            $_REQUEST = json_decode(html_entity_decode($temp_req['current_query_by_page']), true);
            unset($_REQUEST[$seed->module_dir . '2_' . strtoupper($seed->object_name) . '_offset']);//after massupdate, the page should redirect to no offset page
            $storeQuery->saveFromRequest($_REQUEST['module']);
            $_REQUEST = array(
                'return_module' => $temp_req['return_module'],
                'return_action' => $temp_req['return_action'],
                'return_record' => $temp_req['return_record'],
            );//for post_massupdate, to go back to original page.
        } else {
            sugar_die("You must massupdate at least one record");
        }
    }
	
	protected function post_subpannelmassupdate(){
        $return_module = isset($_REQUEST['return_module']) ?
            $_REQUEST['return_module'] :
            $GLOBALS['sugar_config']['default_module'];
        $return_action = isset($_REQUEST['return_action']) ?
            $_REQUEST['return_action'] :
            $GLOBALS['sugar_config']['default_action'];
		$return_record = isset($_REQUEST['return_record']) ?
            $_REQUEST['return_record'] :
            $GLOBALS['sugar_config']['default_record'];
        $url = "index.php?module=" . $return_module . "&action=" . $return_action. "&record=" . $return_record;
        if ($return_module == 'Emails') {//specificly for My Achieves
            if (!empty($this->req_for_email['type']) && !empty($this->req_for_email['ie_assigned_user_id'])) {
                $url = $url . "&type=" . $this->req_for_email['type'] . "&assigned_user_id=" . $this->req_for_email['ie_assigned_user_id'];
            }
        }
        $this->set_redirect($url);
    }
}
