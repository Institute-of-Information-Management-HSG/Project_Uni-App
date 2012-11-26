<table border="0" cellpadding="0" cellspacing="0" class="bibTable">
 <thead>
 <tr>
  <td>Titel</td>
  <td></td>
 </tr>
 </thead>

 {foreach item=con from=$allItems}
 <tr id="t{$con.delete}">
  <td>
   {$con.titel}<br />{$con.signatur}
  </td>
  <td>
   {if $con.delete != ""}
    <a href="javascript:void(0);" title="photocopies" id="{$con.delete}" class="icon delete" title="löschen"></a>
   {/if}&nbsp;
  </td>
 </tr>
 {/foreach}
</table>