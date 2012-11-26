{include file="findInclude:common/templates/header.tpl"}

<h2 class="h2">{"BIB_LOGIN_TITLE"|getLocalizedString}</h2>
<div class="focal">
<p>{"BIB_LOGIN_TEXT"|getLocalizedString}</p>
<form method="post" name="login" id="login">
 <label for="username">{"BIB_USER"|getLocalizedString}:</label>
 <input type="text" name="username" /><br /><br />
 <div class="clear"></div>
 <label for="password">{"BIB_PW"|getLocalizedString}:</label>
 <input type="password" name="passwort" /><br />
 <div class="clear"></div>
 <label for="error"> <div class="error"></div></label>
<br />
 <input type="hidden" name="a" value="{$action}" />
 <input type="hidden" name="docNr" value="{$docNr}" />
 <input type="hidden" name="ref" value="{$ref}" />
 <input type="hidden" name="seq" value="{$seq}" />
 <div class="clear"></div>
 <input type="submit" class="login" value="Login" />
 <div class="clear"></div>
</form>
</div>

<div id="darkcloud" style="display:none;">
  <div id="black"></div>
  <div class="loading"><img src="/common/images/loading.gif" width="32" height="32" alt="Loading" /></div>
 </div>



{include file="findInclude:common/templates/footer.tpl"}