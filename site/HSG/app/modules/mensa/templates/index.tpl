{include file="findInclude:common/templates/header.tpl"}
    <h2>Menüplan</h2>
{include file="findInclude:common/templates/results.tpl" results=$menu noResultsText="Aktuell keine Mensapläne verfügbar"}

<!--{include file="findInclude:common/templates/results.tpl" results=$weeks noResultsText="Aktuell keine Mensapläne verfügbar"}-->



<h2>Standorte & Öffnungszeiten</h2>
<div class="focal" style="font-size: small;">
    <p>
    <h3>Während der Vorlesungszeit</h3>
    {foreach $semester as $entry}
        {$entry}<br/>
    {/foreach}
    </p>
    <p>
    <h3>Ausserhab der Vorlesungszeit</h3>
    {foreach $nonsemester as $nonentry}
        {$nonentry}<br/>
    {/foreach}
    </p>
</div>


{include file="findInclude:common/templates/footer.tpl"}