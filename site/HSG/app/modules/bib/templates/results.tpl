{include file="findInclude:common/templates/header.tpl"}
<h2 class="h2">Resultate</h2>

<div class="focal">
<form action="results" method="get">
 <div style="width:100%;">
     <input value="{$q}" type="text" name="q" /> 
     <input type="submit" value="Go" />
     <div class="clear"></div>
     <div class="selectcontainer">
     <div id="filteroutput">Suchen in: alles</div>
     <select name="scope" id="select-filter" class="filter">
      <option value="all">alles</option>
      <option value="books">nur Bücher</option>
      <option value="articles">nur Artikel</option>
     </select>
     </div>
     <div class="clear"></div>
 </div>
</form>
<p class="notice">{$total} Ergebnisse total, {$hsg} davon an der HSG Bibliothek</p>
</div>

<input type="hidden" name="page" id="page" value="2" />
<input type="hidden" name="query" id="query" value="{$query}" />
<div id="results">
 {include file="findInclude:common/templates/navlist.tpl" navlistItems=$results}
 
 {if $total > 0}
  <a id="loadNext" href="" class="bibCon">Weitere Resultate laden</a>
 {/if}
 <div id="darkcloud" style="display:none;">
  <img src="/common/images/loader.gif" width="16" height="16" alt="Loading" />
 </div>
</div>

{include file="findInclude:common/templates/bibFooter.tpl" isAuth=$isAuth}

{include file="findInclude:common/templates/footer.tpl"}