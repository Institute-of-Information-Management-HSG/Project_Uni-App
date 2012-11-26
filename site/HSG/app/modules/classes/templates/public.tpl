{include file="findInclude:common/templates/header.tpl"}

<h2>Heute an der HSG</h2>
    

{include file="findInclude:common/templates/results.tpl" results=$all noResultsText="{$error|default:"Aktuell keine Veranstaltungen"}"}

{include file="findInclude:common/templates/footer.tpl"}