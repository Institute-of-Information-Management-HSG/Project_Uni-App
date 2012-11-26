{capture name="categorySelect" assign="categorySelect"}
  <select class="newsinput" id="section" name="section" onchange="loadSection(this);">
    {foreach $sections as $section}
      {if $section['selected']}
        <option value="{$section['value']}" selected="true">{$section['title']}</option>
      {else}
        <option value="{$section['value']}">{$section['title']}</option>
      {/if}
    {/foreach}
  </select>
{/capture}

{block name="newsHeader"}
  <div class="header">
    <div id="category-switcher" class="category-mode">
      <form method="get" action="index.php" id="category-form">
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="formlabel">Richtung:</td>
            <td class="inputfield"><div id="news-category-select">{$categorySelect}</div></td>
            <td class="togglefield">
              {block name="categoryButton"}
                <input src="/common/images/search_button.png" type="image" class="toggle-search-button"  onclick="return toggleSearch();" />
              {/block}
            </td>
          </tr>
        </table>
        {foreach $hiddenArgs as $arg => $value}
          <input type="hidden" name="{$arg}" value="{$value}" />
        {/foreach}
        {foreach $breadcrumbSamePageArgs as $arg => $value}
          <input type="hidden" name="{$arg}" value="{$value}" />
        {/foreach}
      </form>

      <form method="get" action="search.php" id="search-form">
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="formlabel">Ort:</td>
            <td class="inputfield">
              <input class="newsinput search-field" type="text" id="search_terms" 
              name="filter" value="{$searchTerms|escape}" 
              onKeyPress="return submitenter(this, event);"/>
            </td>
            <td class="togglefield">
              <input type="button" class="toggle-search-button" onclick="return toggleSearch();" value="Cancel" />
            </td>
          </tr>
        </table>
        {foreach $hiddenArgs as $arg => $value}
          <input type="hidden" name="{$arg}" value="{$value}" />
        {/foreach}
        {foreach $breadcrumbArgs as $arg => $value}
          <input type="hidden" name="{$arg}" value="{$value}" />
        {/foreach}
      </form>
    </div>
  </div>
{/block}
<!--
<div class="p">
<iframe class="iframe" width="511px" scrolling="no" height="300" frameborder="0" marginheight="0" marginwidth="0" src="http://www.parlamentsg.ch/static/suche/fahrplan.html" title="" id="Par.0002" >
	<p>Ihr Browser kann leider keine eingebetteten Frames anzeigen. Sie k&ouml;nnen die eingebettete Seite &uuml;ber den folgenden Verweis aufrufen: <a href="http://www.parlamentsg.ch/static/suche/fahrplan.html" title="Externer Link: Es &ouml;ffnet sich ein neues Fenster: http://www.parlamentsg.ch/static/suche/fahrplan.html"  target="_blank" >http://www.parlamentsg.ch/static/suche/fahrplan.html<span class="hidden"> neues Fenster</span></a></p>
</iframe>
</div>
-->