
{include file="findInclude:common/templates/header.tpl"}

{$title}

{include file="findInclude:common/templates/results.tpl" results=$transport noResultsText="{$error|default:'Bitte Eingaben überprüfen'}"}

<div style="font-size: 8pt;" class="focal"><small>Disclaimer: Alle Informationen von fahrplan.search.ch. Für die Detailbetrachtung werden Sie auf fahrplan.search.ch weitergeleitet. Die Daten werden von der SBB bezogen.</small>
</div>
{include file="findInclude:common/templates/footer.tpl"}