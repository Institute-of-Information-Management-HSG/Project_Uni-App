<table border="0" cellpadding="0" cellspacing="0" class="bibTable">
 <thead>
 <tr>
  <td>Titel</td>
  <td>Abholbereit</td>
  <td></td>
 </tr>
 </thead>

 {foreach item=con from=$allItems}
 <tr id="t{$con.delete}">
  <td>
   {$con.titel}<br />{$con.signatur}
  </td>
  <td>
  {$con.pickUp}<br />
  {if $con.loanDate == "S"}
  	<img src="common/images/compliant/bullet_green.png" width="16" height"16" alt="abholbereit" title="abholbereit" />
  {else}
   <img src="common/images/compliant/bullet_red.png" width="16" height"16" alt="in Bearbeitung" title="in Bearbeitung" />
  {/if}
  </td>
  <td>
  {if $con.delete != ""}
    <a href="javascript:void(0);" title="holds" id="{$con.delete}" class="icon delete"></a>
   {/if}
  </td>
 </tr>
 {/foreach}
</table>