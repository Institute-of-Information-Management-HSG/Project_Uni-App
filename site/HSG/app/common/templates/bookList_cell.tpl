<table border="0" cellpadding="0" cellspacing="0" class="bibTable bibCell">
 <thead>
 <tr>
  <td width="30%">Status</td>
  <td>Standort</td>
  {if $showInfo == "1"}
  <td>Info</td>
  {/if}
 </tr>
 </thead>

 {foreach item=con from=$allItems}
 <tr>
  <td>
   {if $con.datum and $con.status=="ausleihbar"} 
    <div class="stati booked">ausgeliehen bis {$con.datum}</div> 
   {elseif $con.status!="ausleihbar"}
    <div class="stati presentbook">Pr&auml;senzex.</div> 
   {else}
    <div class="stati free">ausleihbar</div> 
   {/if}
  </td>
  <td>
   <a class="bibCon" href="{$con.mediascout}">
    {$con.standort}<br />
    {$con.signatur}
   </a>
  </td>
  {if $showInfo == "1"}
  <td>{$con.info}</td>
  {/if}
 </tr>
 <tr class="cell">
 <td></td>
  <td>
 <a href="{$url}doc_nr={$docNr}&amp;seq={$con.sequence}&amp;a=copy" class="icon copy"></a>
  {if $con.status=="ausleihbar"} 
    <a href="{$url}doc_nr={$docNr}&amp;seq={$con.sequence}&amp;a=order" class="icon order"></a>
   {/if}
  </td>
 </tr>
 {/foreach}
</table>
