  
<?php
function smarty_function_json_to_array($params, &$smarty)
{
	$ret = array();
	if(!empty($params['string'])){
        $ret = json_decode(html_entity_decode($params['string']),true);
    }
    
	if (!empty($params['assign']))
	{
		$smarty->assign($params['assign'], $ret);
		return "";
	}
	
	return ($ret);
}
?>