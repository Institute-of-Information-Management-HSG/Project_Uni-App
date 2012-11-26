<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml" />
  <title>{$moduleName}{if !$isModuleHome}: {$pageTitle}{/if}</title>
  <link href="{$minify['css']}" rel="stylesheet" media="all" type="text/css"/>
  {foreach $inlineCSSBlocks as $css}
    <style type="text/css" media="screen">
      {$css}
    </style>
  {/foreach}
  
  {block name="javascript"}
    {if strlen($GOOGLE_ANALYTICS_ID)}
      <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '{$GOOGLE_ANALYTICS_ID}']);
        _gaq.push(['_trackPageview']);
        
        (function() {ldelim}
          var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
          ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        {rdelim})();
      </script>
    {/if}
    
    {foreach $inlineJavascriptBlocks as $script}
      <script type="text/javascript">
        {$script} 
      </script>
    {/foreach}  
    
    {foreach $externalJavascriptURLs as $url}
      <script src="{$url}" type="text/javascript"></script>
    {/foreach}

    <script src="{$minify['js']}" type="text/javascript"></script>

    {if count($onOrientationChangeBlocks)}
      <script type="text/javascript">
        function onOrientationChange() {ldelim}
          {foreach $onOrientationChangeBlocks as $script}
            {$script}
          {/foreach}
        {rdelim}
        window.addEventListener("orientationchange", onOrientationChange, false);
        window.addEventListener("resize", onOrientationChange, false);
      </script>
    {/if}

    {if count($onLoadBlocks)}
      <script type="text/javascript">
        function onLoad() {ldelim}
          {foreach $onLoadBlocks as $script}
            {$script}
          {/foreach}
        {rdelim}
      </script>
    {/if}
  {/block}
  
  {if !$autoPhoneNumberDetection}
  <meta name="format-detection" content="telephone=no">
  {/if}
  <meta name="HandheldFriendly" content="true" />
  <meta name="viewport" id="viewport" 
    content="width=device-width, {if $scalable|default:true}user-scalable=yes{else}user-scalable=no, initial-scale=1.0, maximum-scale=1.0{/if}" />
  <link rel="apple-touch-icon" href="/common/images/icon-{$moduleID}.png" />
  {block name="additionalHeadTags"}{/block}
