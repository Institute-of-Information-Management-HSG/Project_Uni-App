{include file="findInclude:common/templates/header.tpl"}

<h2 class="h2">Kontakt - {$address}</h2>

{include file="findInclude:common/templates/results.tpl" results=$contact}

<h2 class="h2">Öffnungszeiten</h2>

<div class="focal" style="font-size: small;"> 
    <h3 class="h3">{$semester.caption}</h3>
<table>
{foreach $semester.hours.times as $entry}
<tr>
    <td>{$entry.day}</td>
    <td>{$entry.am}</td>
    <td>{$entry.pm}</td>
</tr>
{/foreach}
</table>

<h3 class="h3">{$nosemester.caption}</h3>
<table>
{foreach $nosemester.hours.times as $entry}
<tr>
    <td>{$entry.day}</td>
    <td>{$entry.am}</td>
    <td>{$entry.pm}</td>
</tr>
{/foreach}
</table>
</div>



{include file="findInclude:common/templates/footer.tpl"}