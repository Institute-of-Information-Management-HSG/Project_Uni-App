{include file="findInclude:common/header.tpl"}
<h2>Universität St. Gallen
</h2>
<div class="focal">
    <form method="post" action="transport.php">
        <table>
            <tr>
                <td>Von/Nach:
                </td>
                <td align="left">
                    <select  class = "selected" name="fromto" selected="{$fromtoDefault|default:'From'}" >{html_options options=$fromto}
                </td>
            </tr>
            <tr>
                <td>Ort:
                </td>
                <td align="left">
                    <input class="forminput" type="text" name="place" style="width: 95%">
                </td>
            </tr>
            <tr>
                <td align="left">Datum:
                </td>
                <td>
                    <input class="forminput" type="text" name="date" value={$currentDate} style="width: 95%">
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

{include file="findInclude:common/footer.tpl"}