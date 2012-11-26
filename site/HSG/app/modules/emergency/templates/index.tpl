{include file="findInclude:common/templates/header.tpl"}

<h2>Sicherheitshinweise</h2>
{include file="findInclude:common/templates/navlist.tpl" navlistItems=$categories}

<h2>Wichtige Telefonnummern</h2>
{if $hasContacts}
  <div>
    {include file="findInclude:common/templates/navlist.tpl"
       navlistItems=$contactNavListItems
       accessKey=false
       nested=true
       subtitleNewLine=false}
  </div>
{/if}



{include file="findInclude:common/templates/footer.tpl"}
