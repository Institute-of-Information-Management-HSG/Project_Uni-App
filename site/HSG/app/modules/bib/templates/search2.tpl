{include file="findInclude:common/templates/header.tpl"}
<h2 class="h2">Suche im Katalog</h2>
<div class="focal">
<ul>
  <li>
    000005584  Statistisches Jahrbuch der Schweiz
  </li>
  <li>000342978  Oesterreichisches Jahrbuch
  </li>
  <li>000010110  Arbido  (Zeitschrift)
  </li>
  <li>000154425  Banking Strategies  (Zeitschrift)
  </li>
  <li>000525885  Multivariate Analysemethoden  (Lehrbuch)</li>
  <li> 000498049  Managementlehre (mehrbändig, mehrere Ex.)</li>
  <li> 000006718  Theologische Realenzyklopädie (mehrbändig, 1 Ex.)
  </li>
  <li>000345678  Sokolow (normales Buch mit kyrillischer Umschrift)
  </li>
  <li>000500594  Burnout in Unternehmen (Masterarbeit mit Abstract)</li>
  <li>000530944  Essays on Trade,... (Dissertation)    </li>
  <li>000461064  Board Briefing (Volltext plus Papier)    </li>
  <li>000513487  Multikonferenz Wirtschaftsinformatik (Volltext)</li>
  <li>000224968  Spezialstandort FWR</li>
</ul>
<p>Dokumenten Nummer (9 Ziffern):</p>
<form action="{$url}" method="get">
 <input type="text" name="doc_nr" /> <input type="submit" value="Go" />
</form>
</div>
{$BIB_WELCOME}

{include file="findInclude:common/templates/bibFooter.tpl" isAuth=$isAuth}

{include file="findInclude:common/templates/footer.tpl"}