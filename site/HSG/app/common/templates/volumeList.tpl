{$defaultTemplateFile="findInclude:common/templates/listItem.tpl"}
{$listItemTemplateFile=$listItemTemplateFile|default:$defaultTemplateFile}
<ul class="nav{if $secondary} secondary{/if}{if $nested} nested{/if}{if $navlistClass} {$navlistClass}{/if}"{if $navlistID} id="{$navlistID}"{/if}>
  {foreach $navlistItems as $item}
    {if $hideImages}{$item['img']=null}{/if}
    {if !isset($item['separator'])}
      <li{if $item['img']||$item['listclass']} class="{$item['listclass']}{if $item['img']} icon{/if}"{/if}>
       <a class="noIcon" name="{$item['volumeID']}"></a>
       {include file="$listItemTemplateFile" subTitleNewline=$subTitleNewline|default:false}
       <div id="{$item['volumeID']}" style="display:none;" class="volumeList">
        {if $item['account'] == 'Loans'}
         {include file="findInclude:common/templates/loanList.tpl" allItems=$item['books']}
        {else if $item['account'] == 'HoldRequest'}
         {include file="findInclude:common/templates/holdList.tpl" allItems=$item['books']}
        {else if $item['account'] == 'PhotocopyRequest'}
         {include file="findInclude:common/templates/copyList.tpl" allItems=$item['books']}
        {else}
         {include file="findInclude:common/templates/bookList.tpl" allItems=$item['books']}
        {/if}
       </div>
      </li>
    {/if}
  {/foreach}
</ul>