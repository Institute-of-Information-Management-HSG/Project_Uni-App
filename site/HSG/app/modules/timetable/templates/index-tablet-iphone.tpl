{extends file="findExtends:modules/timetable/templates/index.tpl"}

{block name="ical"}
<h2>Kalender-Integration des aktuellen Semesters</h2>
{include file="findInclude:common/templates/navlist.tpl" navlistItems=$ical}
{/block}