{include file="findInclude:common/templates/header.tpl"}

<h2 class="h2">Öffnungszeiten</h2>

<div class="focal" style="font-size: small;"> 
  <h3 class="h3">{$openinghours.caption}</h3>
    <table>
    {foreach $openinghours.hours.times as $entry}
      <tr>
        <td width="50%">{$entry.day}</td>
        <td width="50%">{$entry.time}</td>
      </tr>
    {/foreach}
    </table>
</div>

<h2 class="h2">Bibliotheksschliessungen</h2>

<div class="focal" style="font-size: small;"> 
  <h3 class="h3">{$closingsTy.caption}</h3>
    <table>
    {foreach $closingsTy.hours.times as $entry}
      <tr>
        <td width="50%">{$entry.day}</td>
        <td width="50%">{$entry.description}</td>
      </tr>
    {/foreach}
    </table>
  <h3 class="h3">{$closingsNy.caption}</h3>
    <table>
    {foreach $closingsNy.hours.times as $entry}
      <tr>
        <td width="50%">{$entry.day}</td>
        <td width="50%">{$entry.description}</td>
      </tr>
    {/foreach}
    </table>
</div>

{include file="findInclude:common/templates/bibFooter.tpl" isAuth=$isAuth}

{include file="findInclude:common/templates/footer.tpl"}