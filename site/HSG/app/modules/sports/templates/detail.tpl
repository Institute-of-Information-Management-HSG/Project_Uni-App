{include file="findInclude:common/templates/header.tpl"}

<h2 class="h2">{$name}</h2>
<div class="nonfocal" style="font-size: small;">
    {$goal}
</div>

<div class="focal" style="font-size: small">
    <table>
{foreach $attributes as $attribute}
        <tr>
            <td>{$attribute@key}:</td>
            <td><p style="width: 100%">{$attribute}</p></td>
        </tr>
{/foreach}
    </table>
</div>

<h2>{$times}</h2>

{include file="findInclude:common/templates/results.tpl" results=$results noResultsText="Aktuell keine Events"}


{include file="findInclude:common/templates/footer.tpl"}