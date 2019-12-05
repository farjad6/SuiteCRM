{assign var="field_name" value={{sugarvar key='name' string='true'}} }
{json_to_array string={{sugarvar key='value' string=true}} assign="vals"}
{foreach key=key item=item from=$vals}
    <span id="rem_{$field_name}_{$key}">
        <a target="_blank" href="index.php?entryPoint=multi_download&file={$item.uploadname}&filename={$item.filename}">{$item.filename}</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="#" onclick="removeSelectedRow(this);return false;" data-target="{$item.uploadname}" data-field="{$field_name}">x</a>
    </br></span>
{/foreach}
<input name="{$field_name}[]" type="file" multiple="multiple"/>



{literal}
    <script type="text/javascript">
        function removeSelectedRow(self){
            var field = $(self).attr("data-field");
            var target = $(self).attr("data-target");
            $(self).parent().before(`<input name="removed_${field}[]" value="${target}" type="hidden">`);
            $(self).parent().remove();
        }
    </script>
{/literal}