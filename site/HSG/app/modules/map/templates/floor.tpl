{include file="findInclude:common/templates/header.tpl"}

<h2>Etagenansicht </h2>

<div class="focal">
    <h3><p>
    Gebäude: {$buildingid}<br />
    Etage: {$floorid}<br />
    </p></h3>
<img src={$image} alt="Kein Plan vorhanden" height="relative" width="100%"/>
</div>

{include file="findInclude:common/templates/navlist.tpl" navlistItems=$places}

{include file="findInclude:common/templates/footer.tpl"}