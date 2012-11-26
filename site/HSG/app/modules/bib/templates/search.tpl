{include file="findInclude:common/templates/header.tpl"}
<h2 class="h2">{$titel}</h2>

<div class="focal">
<form action="" method="get">
 <div style="width:100%;">
     <input value="{$q}" type="text" name="q" /> 
     <button type="submit" value="Go">Go</button>
     <div class="clear trenner"></div>
     <div class="selectcontainer">
     <div id="filteroutput">Suchen in: alles</div>
     <select name="scope" id="select-filter" class="filter">
      <option {if $scope == "all"} selected="selected" {/if} value="all">alles</option>
      <option {if $scope == "books"} selected="selected" {/if} value="books">nur Bücher</option>
      <option {if $scope == "articles"} selected="selected" {/if} value="articles">nur Artikel</option>
     </select>
     </div>
     <div class="clear"></div>
 </div>
</form>
{if $showTotal}
<p class="notice">{$total} Ergebnisse total, {$hsg} davon an der HSG Bibliothek</p>
{/if}
</div>

<input type="hidden" name="page" id="page" value="2" />
<input type="hidden" name="query" id="query" value="{$query}" />
<div id="results">
 {include file="findInclude:common/templates/navlist.tpl" navlistItems=$results}
 
 {if $total > 9}
  <a id="loadNext" href="" class="bibCon">Weitere Resultate laden</a>
 {/if}
 <div id="darkcloud" style="display:none;">
  <div id="black"></div>
  <div class="loading"><img src="/common/images/loading.gif" width="32" height="32" alt="Loading" /></div>
 </div>
</div>

{include file="findInclude:common/templates/bibFooter.tpl" isAuth=$isAuth}

{include file="findInclude:common/templates/footer.tpl"}