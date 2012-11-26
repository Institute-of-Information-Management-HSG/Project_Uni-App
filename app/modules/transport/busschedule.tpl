{include file="findInclude:common/header.tpl"}
<h2>Nächste Abfahrtszeiten</h2>
<div class="focal">
<!-- TODO Hier ist etwas getrickst (nur ein Bus pro Haltestelle)-->
    <h2>{$bus_5[0]['transport']}</h2>
{foreach $bus_5 as $singleData}
    <p>
        Richtung {$singleData['direction']}:<br>
        {$singleData['schedule']}
    </p>
{/foreach}

    <h2>{$bus_9[0]['transport']}</h2>
   {foreach $bus_9 as $singleData}
    <p>Richtung {$singleData['direction']}:<br>
    {$singleData['schedule']}
    </p>
{/foreach}
</div>
<div style="font-size: 8pt;" class="focal"><small>Disclaimer: Alle Informationen von fahrplan.search.ch. Die Daten werden von der SBB bezogen.</small>
</div>


{include file="findInclude:common/footer.tpl"}