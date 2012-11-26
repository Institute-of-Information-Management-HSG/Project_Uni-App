 {foreach item=con from=$allItems}
 <div class="focal bib">
   <a class="bibCon" href="{$con.link}">
    {$con.standort}<br />
    {$con.signatur}
   </a>
   <div class="clear"></div>
   {if $showInfo == "1"}
    {$con.info}<br />
   {/if}

   <div class="stati s{$con.status}">
    {if $con.status == '00' AND $con.datum != ""}
     {"BIB_BOOKED"|getLocalizedString} {$con.datum}
    {else}
     {$con.order['desc']} 
    {/if}
   </div>
    
   <div class="iconbox">
   {if $con.order['copy']=="Y"} 
   <a href="{$url}doc_nr={$docNr}&amp;seq={$con.sequence}&amp;a=copy" class="icon copy" title="Kopierauftrag"></a>
   {/if}
   {if $con.order['hold']=="Y"}
    <a href="{$url}doc_nr={$docNr}&amp;seq={$con.sequence}&amp;a=order" class="icon order" title="bestellen/reservieren"></a>
   {else}
    <div style="visibility:hidden;">&nbsp;</div>
   {/if}
    <div class="clear"></div>
   </div>
   
   <div class="clear"></div>
  </div>
 {/foreach}
