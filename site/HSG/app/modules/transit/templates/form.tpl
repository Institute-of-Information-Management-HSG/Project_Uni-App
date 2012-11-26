{include file="findInclude:common/templates/header.tpl"}
<h2>{$fromto} {$university}
</h2>
<div class="focal">
    <form method="post" action="transport.php">
        <table>
            <tr>
                <td>{if $fromto == 'Nach'} 
                        Von 
                    {else} 
                        Nach                         
                    {/if}:
                </td>
                <td align="left">
                    <input class="forminput" type="text" name="place" style="width: 95%">
                    <input type="hidden" name="fromto" value={$fromto}>
                </td>
            </tr>
            <tr>
                <td align="left">Datum:
                </td>
                <td>
                    <input class="forminput" type="date" name="date" value={$currentDate} style="width: 95%">
                </td>
            </tr>
            <tr >
                <td valign="left">Zeit:
                </td>
                <td>
                    <input class="forminput" type="text" name="time" value={$currentTime} style="width: 95%">
                </td>
            </tr>
            <tr>
                <td>Ab/An:
                </td>
                <td>
                    <select  class= "selected" name="arrival" selected="{$arrivalDefault|default:'Dep'}">{html_options options=$arrival}
                </td>
            </tr>
            </table> 
                
                    <input type="submit" name="Button" value="Verbindungen suchen">
                
        
    </form>
</div>

<a class="nonfocal" style="color:red" href="sbb">Zum offiziellen SBB-Widget</a>
{include file="findInclude:common/templates/footer.tpl"}
