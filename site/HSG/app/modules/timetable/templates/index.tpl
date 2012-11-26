{include file="findInclude:common/templates/header.tpl"}

<h2>Persönlicher Stundenplan</h2>

{include file="findInclude:common/templates/navlist.tpl" navlistItems=$personals}
<h2>Nach Datum suchen</h2>
<div class="focal">
    <form method="post" action="today">
        <input class="forminput" type="date" name="time" value={$currentDate} style="width: 60%; margin-right: 10px;" />
        <input type="submit" name="Button" value="Suche" />
    </form>
    {if $isErrorDate == true}
        <span style="color: red; font-size: small;">Bitte Datumsformat überprüfen (DD.MM.YYYY)</span>
    {/if}
</div>

{block name="ical"}

{/block}

{include file="findInclude:common/templates/timetableFooter.tpl" isAuth=$isAuth}
{include file="findInclude:common/templates/footer.tpl"}