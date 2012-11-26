{include file="findInclude:common/templates/header.tpl"}

{if $notComplete != "1"}

<h2>Bibliothekskatalog</h2>
<div class="focal" style="font-size: small">
    <table width="90%">
        <tr>
            <td nowrap="nowrap" width="20%">Autor:</td>
            <td width="80%">{$author}</td>    
        </tr>
        <tr>
            <td nowrap="nowrap" width="20%">Titel:</td>
            <td width="80%">{$title}</td>    
        </tr>
        <tr>
            <td nowrap="nowrap" width="20%">Impressum</td>
            <td width="80%">{$impressum}</a></td>   
        </tr>
        <tr>
            <td nowrap="nowrap" width="20%">Umfang:</td>
            <td width="80%">{$amount}</td>    
        </tr>
    </table>
</div>

{include file="findInclude:common/templates/navlist.tpl" navlistItems=$bibLinks}

{if $abstract}
 <ul class="nav abstract">
  <li><a href="javascript:show('showAbstract')">Abstract</a><div id="showAbstract" style="display:none;">{$abstract}</div></li>
 </ul>
{/if}

{if $showAllItems != 'no'}
    {if $multiples == "1"}
     {include file="findInclude:common/templates/volumeList.tpl" navlistItems=$bandLinks}
    {else}
     {include file="findInclude:common/templates/bookList.tpl" allItems=$allItems}
    {/if}
    <p>
    Legende:<br />
    <img src="common/images/order.png" width="16" height="16" /> bestellen/reservieren<br />
    <img src="common/images/copy.png" width="16" height="16" /> Kopierauftrag
    </p>
{/if}



<div class="focal" style="font-size: small">
    <table width="90%">
        <tr>
            <td nowrap="nowrap" width="20%">{$labelISBN}:</td>
            <td width="80%">{$isbn}</td>    
        </tr>
        <tr>
            <td nowrap="nowrap" width="20%">Schlagwort:</td>
            <td width="80%">{$tags}</td>    
        </tr>
        <tr>
            <td nowrap="nowrap" width="20%">Systemnummer:</td>
            <td width="80%">{$docNr}</td>   
        </tr>
    </table>
</div>

{else}
<div class="focal" style="font-size: small">
 Kein vollständiges Katalogisat. Bitte wählen Sie einen anderen Treffer<br />
 <a href="javascript:history.back()">zurück</a>
</div>
{/if}

{include file="findInclude:common/templates/bibFooter.tpl" isAuth=$isAuth}
{include file="findInclude:common/templates/footer.tpl"}