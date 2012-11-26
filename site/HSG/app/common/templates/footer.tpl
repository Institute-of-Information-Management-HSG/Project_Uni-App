{extends file="findExtends:common/templates/footer.tpl"}

  {block name="footerJavascript"}
    {foreach $inlineJavascriptFooterBlocks as $script}
      <script type="text/javascript">
        {$script} 
      </script>
    {/foreach}
    
    {if strlen($GOOGLE_ANALYTICS_ID)}
       <!--Disclaimer-->
      {include file="findInclude:common/templates/disclaimer.tpl"}
      <script type="text/javascript">
        (function() {ldelim}
          var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
          ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        {rdelim})();
      </script>
    {/if}
    {if strlen($PERCENT_MOBILE_ID)}
        <script>
           <!--
            percent_mobile_track('{$PERCENT_MOBILE_ID}', '{$pageTitle}');
            -->
        </script>
    {/if}
  {/block}