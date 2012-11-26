{include file="findInclude:common/templates/header.tpl"}

<h2>Nächste Abfahrtszeiten</h2>
<div class="focal">
 {foreach item=bus from=$linien}
  <h3>{$bus.name}</h3>
  
  {foreach item=con from=$entries[$bus.title]}
  Nach: {$con['meta']['to']}
  <table width="50%" class="schedule">
   <tr>
    {foreach item=go from=$con['items']}
    <td width="50">
     {$go['departure']}
    </td>
    {/foreach}
    <td></td>
   </tr>
  </table>
  {/foreach}

 {/foreach}
</div>

<h2>Weitere Optionen</h2>
{include file="findInclude:common/templates/navlist.tpl" navlistItems=$categories}

{include file="findInclude:common/templates/footer.tpl"}