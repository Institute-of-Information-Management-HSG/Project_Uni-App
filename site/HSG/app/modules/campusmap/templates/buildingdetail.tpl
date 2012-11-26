{include file="findInclude:header-top.tpl"}

{$map->getHeaderJS()}
{$map->getMapJS()}

{include file="findInclude:header-bottom.tpl"}
<h2>{$buildingid}
</h2>
<!--<iframe height=200 width=200 src="http://maps.google.com/staticmap?center=$,9.374641&format=png32&zoom=17&size=200x200&key=ABQIAAAAVEA7a1qj2AdTcsdt3MeJUhT2SZcQc123BixydlR6sfXbZ4x0SRSSnZ3VrrEppPsUvpmXJhOlMD5vuw"></iframe>
-->
<div class="focal" height="400px" width="350px">


{$map->printOnLoad()}
{$map->printMap()}
{$map->printSidebar()}
</div>
{if $hasFloors}
<h2>Raumpläne</h2>
{include file="findInclude:common/templates/navlist.tpl" navlistItems=$floors}
{/if}
{include file="findInclude:common/templates/footer.tpl"}