{include file="findInclude:common/templates/header.tpl"}

<h2>Bestellungen/Reservierungen</h2>
<p>Leider ist bei der Bestellung ein Fehler aufgetreten:</p>
<div class="focal">
<h3>{$errorTitle}</h3>
{$errorMessage}
</div>
<p><a href="{$backLink}">zurück zur Übersicht</a></p>

{include file="findInclude:common/templates/bibFooter.tpl" isAuth=$isAuth}

{include file="findInclude:common/templates/footer.tpl"}