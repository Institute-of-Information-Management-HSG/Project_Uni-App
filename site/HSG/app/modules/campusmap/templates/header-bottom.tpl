
</head>

{capture name="breadcrumbHTML" assign="breadcrumbHTML"}
  {block name="breadcrumbs"}
    {if !$isModuleHome}
      {if $moduleID != 'home'}
        <a href="./" class="module">
          <img src="/common/images/title-{$navImageID|default:$moduleID}.png" width="28" height="28" alt="" />
        </a>
      {/if}
      {foreach $breadcrumbs as $breadcrumb}
        {if count($breadcrumbs) == 1}
          {$crumbClass = 'crumb1'}
        {elseif count($breadcrumbs) == 2}
          {if !$breadcrumb@last}
            {$crumbClass = 'crumb2a'}
          {else}
            {assign var=crumbClass value='crumb2b'}
          {/if}
        {elseif count($breadcrumbs) > 2}
          {if $breadcrumb@last}
            {$crumbClass = 'crumb3c'}
          {elseif $breadcrumb@index == ($breadcrumb@total-2)}
            {assign var=crumbClass value='crumb3b'}
          {else}
            {assign var=crumbClass value='crumb3a'}
          {/if}
          
        {/if}
        <a href="{$breadcrumb['url']}" {if isset($crumbClass)}class="{$crumbClass}{/if}">
          <span>{$breadcrumb['title']}</span>
        </a>
      {/foreach}
    {/if}
  {/block}
{/capture}

<body{block name="onLoad"}{if count($onLoadBlocks)} onload="onLoad();"{/if}{/block}>
  <a name="top"></a>
  {if isset($customHeader)}
    {$customHeader|default:''}
  {else}
    {block name="navbar"}
      <div id="navbar"{if $hasHelp} class="helpon"{/if}>
        <div class="breadcrumbs{if $isModuleHome} homepage{/if}">
          <a name="top" href="/home/" class="homelink">
            <img src="/common/images/home-transp-p2.png" width="57" height="45" alt="Home" />
          </a>
          
          {$breadcrumbHTML}
          <span class="pagetitle">
            {if $isModuleHome}
              <img src="/common/images/title-{$navImageID|default:$moduleID}.png" width="28" height="28" alt="" class="moduleicon" />
            {/if}
            {$pageTitle}
          </span>
        </div>
        {if $hasHelp}
          <div class="help">
            <a href="help.php"><img src="/common/images/help-hsg.png" width="46" height="45" alt="Help" /></a>
          </div>
        {/if}
      </div>
    {/block}
  {/if}
  <div id="container">
