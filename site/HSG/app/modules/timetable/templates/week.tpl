{include file="findInclude:common/templates/header.tpl"}

<div class="sidenav2" >
    <a href="{$prevUrl}" class="sidenav-prev">Vorherige</a>
<a href="{$nextUrl}" class="sidenav-next">Nächste</a>
</div>
{foreach from=$results item=result}
    <p><h2>{$result@key}</h2></p>
    {include file="findInclude:common/templates/results.tpl" results=$result noResultsText="Keine Veranstaltungen"}
{/foreach}

<div class="sidenav2" >
<a href="{$prevUrl}" class="sidenav-prev">Vorherige</a>
<a href="{$nextUrl}" class="sidenav-next">Nächste</a>
</div>
{include file="findInclude:common/templates/navlist.tpl" navlistItems=$additionalLinks}
<p>
{include file="findInclude:common/templates/timetableFooter.tpl" isAuth=$isAuth}
</p>
{include file="findInclude:common/templates/footer.tpl"}