{include file="findInclude:common/templates/header.tpl"}
<h2>{"BIB_ACCOUNT"|getLocalizedString} {$username}</h2>
{include file="findInclude:common/templates/volumeList.tpl" navlistItems=$userLinks}

{include file="findInclude:common/templates/bibFooter.tpl" isAuth=$isAuth}

{include file="findInclude:common/templates/footer.tpl"}