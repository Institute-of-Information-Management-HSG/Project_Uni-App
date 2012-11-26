{include file="findInclude:common/templates/header.tpl"}

<h2 class="h2">Beratung</h2>

<p>Unser Informationsteam setzt sich aus InformationsspezialistInnen und Fachreferenten zusammen und unterstützt Sie bei der Arbeit mit den Datenbanken und dem Bibliothekskatalog.</p>

<div class="focal" style="font-size: small;"> 
  <h3 class="h3">{$guidance.caption}</h3>
    <table>
    {foreach $guidance.hours.times as $entry}
      <tr>
        <td>{$entry.description}</td>
        <td>{$entry.day}</td>
        <td>{$entry.time}</td>
      </tr>
    {/foreach}
    </table>
</div>

<h2 class="h2">Kursangebot</h2>

<p>Mehr über die Schulungsziele, Termin und Ort erfahren Sie auf den jeweiligen Seiten (nicht für mobile Browser optimiert).</p>

{include file="findInclude:common/templates/navlist.tpl" navlistItems=$guidancelinks}

{include file="findInclude:common/templates/footer.tpl"}