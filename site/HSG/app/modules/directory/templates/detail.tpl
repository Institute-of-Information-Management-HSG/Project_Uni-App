{include file="findInclude:common/templates/header.tpl"}


<h2 class="h2">{$name}</h2>

<div class="focal" style="font-size: small">
    <table>
        <tr>
            <td>Funktion:</td>
            <td>{$function}</td>    
        </tr>
        <tr>
            <td>Bereich:</td>
            <td>{$institute}</td>    
        </tr>
        <tr>
            <td>Telefon:</td>
            <td>{$phone}</a></td>   
        </tr>
        <tr>
            <td>Fax:</td>
            <td>{$fax}</td>    
        </tr>
        <tr>
            <td>E-Mail:</td>
            <td>{$email}</a></td>    
        </tr>
        <tr>
            <td>Homepage:</td>
            <td>{$homepage}</td>    
        </tr>
        <tr>
            <td>Adresse:</td>
            <td>{$address}</td>    
        </tr>
    </table>
</div>

{if $hasContacts}
    <h2 class="h2">Optionen</h2>
    {include file="findInclude:common/templates/results.tpl" results=$contact}
{/if}
{include file="findInclude:common/templates/footer.tpl"}