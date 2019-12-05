<?php

require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');
require_once 'include/UploadStream.php';
class SugarFieldMultiFile extends SugarFieldBase {
    public function save(&$bean, $params, $field, $vardef, $prefix = '') {
        parent::save($bean, $params, $field, $vardef, $prefix = '');
        $upload_dir = 'upload://';
        $fieldJSON = $bean->$field;
        if(empty($fieldJSON)){
            $fieldJSON = '[]';
        }
        $removedKeys = [];
        $fieldValue = json_decode(html_entity_decode($fieldJSON),true);
        if( isset( $_REQUEST['removed_'.$field] ) ){
            $targets = $_REQUEST['removed_'.$field];
            foreach( $targets as $target ){
                foreach( $fieldValue as $key => $val ){
                    if( $target == $val['uploadname'] ){
                        $removedKeys[] = $key;
                        UploadStream::unlink($upload_dir.$target);
                    }
                }
            }
        }
        foreach($removedKeys as $removedKey){
            unset($fieldValue[$removedKey]);
        }
        $filesDefination = [];
        
        $total = count($_FILES[$field]['name']);
        for( $i=0 ; $i < $total ; $i++ ) {
            $tempDefination = [];
            $tempDefination['tmpFilePath'] = $_FILES[$field]['tmp_name'][$i];
            if ($tempDefination['tmpFilePath'] != ""){
                $tempDefination['filename'] = $_FILES[$field]['name'][$i];
                $tempDefination['ext'] = pathinfo($tempDefination['filename'], PATHINFO_EXTENSION);
                $tempDefination['uploadFileName'] = $bean->module_dir.'_'.$field. '_' .time().'_'.$i.'.'.$tempDefination['ext'];
                $tempDefination['newFilePath'] = $upload_dir. $tempDefination['uploadFileName'];
                if (
                    UploadStream::writable() 
                    && UploadStream::move_uploaded_file($tempDefination['tmpFilePath'], $tempDefination['newFilePath'])
                    ) {
                        $filesDefination[] = $tempDefination;
                }else{
                    $GLOBALS['log']->fatal("ERROR: cannot write to upload directory");
                }
            }
        }

        foreach($filesDefination as $fileKey => $fileVal){
            $fieldValue[] = array(
                'filename' => $fileVal['filename'],
                'uploadname' => $fileVal['uploadFileName'],
            );
        }

        $bean->$field = json_encode($fieldValue);
    }
}
?>