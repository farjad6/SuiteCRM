{assign var="field_name" value={{sugarvar key='name' string='true'}} }
{json_to_array string={{sugarvar key='value' string=true}} assign="vals"}

{foreach key=key item=item from=$vals}
    <a target="_blank" href="index.php?entryPoint=multi_download&file={$item.uploadname}&filename={$item.filename}">{$item.filename}</a></br>
{/foreach}
