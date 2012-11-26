{include file="findInclude:common/templates/header.tpl"}

<h2>{$h1}</h2>

{if $action == "copy"}
<div class="focal">
<form name="{$action}" method="post" action="proceed?a=copy">
 <input type="hidden" name="itemkey" value="{$seq}" />
 <label for="versand">
  <span class="mandatory" style="visibility:hidden;"></span>
  <input name="type" type="radio" value="HOME" /> Postversand (Fotokopie)<span class="notice">Kein Versand an Adressen in der Stadt St. Gallen</span><br />
  <input type="radio" name="type" value="EMAIL" checked="checked" /> E-Mailversand (PDF)<span class="notice">nur möglich, wenn im Benutzungskonto angegeben</span><br />
 </label>
 {foreach item=con from=$form}
 <label class="formular" for="{$con.name}">
  <span class="desc">{$con.desc}</span>
  {if $con.mandatory == 1}<span class="mandatory">*</span>{/if}
  <input type="{$con.type}" name="{$con.name}" id="{$con.name}"  value="{$con.value}" />
  <span class="error" style="display:none;">{$con.error}</span>
 </label>
 {/foreach}
 <label class="formular"  for="submit"><input type="submit" name="submit" value="OK" /></label>
 <div style="font-size:80%;clear:both;"><span class="mandatory">*</span> obligatorisch</div>
</form>
</div>
{else if $action == "order"}
 Abholbibliothek/Versandart wählen:
 {include file="findInclude:common/templates/navlist.tpl" navlistItems=$pickup}
 <strong>Bitte beachten Sie:</strong>
 <p>Nicht ausgeliehene Dokumente müssen selber im Regal geholt werden. Kein Postversand an Adressen in der Stadt St. Gallen.</p>
{/if}
 


{include file="findInclude:common/templates/navlist.tpl" navlistItems=$fees}

{include file="findInclude:common/templates/bibFooter.tpl" isAuth=$isAuth}

{include file="findInclude:common/templates/footer.tpl"}