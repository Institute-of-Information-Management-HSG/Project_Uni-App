<table border="0" cellpadding="0" cellspacing="0" class="bibTable">
 <thead>
 <tr>
  <td>Titel</td>
  <td>Ausgeliehen seit</td>
  <td>Ausgeliehen bis</td>
 </tr>
 </thead>

 {foreach item=con from=$allItems}
 <tr>
  <td>
   {$con.titel}<br />{$con.signatur}
  </td>
  <td>
   {$con.loanDate}
  </td>
  <td>
  {$con.dueDate}
  </td>
 </tr>
 {/foreach}
</table>
Dokumente werden nach Ablauf der Leihfrist automatisch um 14 Kalendertage verlängert, sofern sie nicht reserviert sind und/oder die maximale Leihfrist von 6 Monaten (26 Wochen) nicht abgelaufen ist. Eine manuelle Verlängerung ist nicht möglich.