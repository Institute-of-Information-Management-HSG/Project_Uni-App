{include file="findInclude:common/templates/header.tpl"}
<h2>SBB Widget
</h2>
<div class = "focal" style="width:175px">
<div style="width: 175px; margin: 0px; padding: 0px; text-align: right; background-color:#FFFFFF;">
<img src="http://fahrplan.sbb.ch/img/igm-sbblogo.gif" width="110" height="18" alt="SBB|CFF|FFS" />
<h1 style="width:175px; background-color: #DDDDDD; color: #000000; font-family: Arial, Helvetica, sans-serif; font-size:12px; font-weight: bold; padding: 2px 0px; margin: 0; height: 15px; text-align: left;clear:both;"> Fahrplan</h1>
<div style="width: 100%; background-color: #F8F8F8; margin: 0; padding: 0px;" summary="Layout">
<form action="http://fahrplan.sbb.ch/bin/query.exe/dn?externalCall=yes&DCSext.wt_fp_request=partner_mini" name="formular" method="post" style="display:inline" target="_blank">
<input type="hidden" name="queryPageDisplayed" value="yes">
<table cellspacing="0" cellpadding="4" style="width: 170px; margin: 2px;" class="ig">
<tr>
<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; text-align:left; vertical-align:middle; padding:2px 3px 2px 0px;">
<select name="REQ0JourneyStopsSA" style="background-color:#fff; border: 1px solid #7F9DB9; color: #000; width: 60px; font-size:11px; margin:0px 0px;">
<option selected="selected" value="7">Von:</option>
<option  value="1">Bhf./Haltest.</option>
<option  value="2">Ort, Strasse Nr.</option>
<option  value="4">Sehenswürdigkeit</option>
</select>
</td>
<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; text-align:left; vertical-align:middle; padding:2px 3px 2px 0px;" colspan="2">
<input type="text" name="REQ0JourneyStopsSG" value="St. Gallen, Universität" size="16" style="background-color:#fff; border: 1px solid #7F9DB9; color: #000; width: 100px; height: 18px; font-size: 11px" accesskey="f" tabindex="1">
<input type="hidden" name="REQ0JourneyStopsSID">
</td>
</tr>
<tr>
<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; text-align:left; vertical-align:middle; padding:2px 3px 2px 0px;">
<select name="REQ0JourneyStopsZA" style="background-color:#fff; border: 1px solid #7F9DB9; color: #000; width: 60px; font-size:11px; margin:0px 0px;">
<option selected="selected" value="7">Nach:</option>
<option  value="1">Bhf./Haltest.</option>
<option  value="2">Ort, Strasse Nr.</option>
<option  value="4">Sehenswürdigkeit</option>
</select>
</td>
<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; text-align:left; vertical-align:middle; padding:2px 3px 2px 0px;" colspan="2">
<input type="text" name="REQ0JourneyStopsZG" value="St. Gallen, Universität" size="16" style="background-color:#fff; border: 1px solid #7F9DB9; color: #000; width: 100px; height: 18px; font-size: 11px" accesskey="t" tabindex="2">
<input type="hidden" name="REQ0JourneyStopsZID">
</td>
</tr>
<tr>
<th nowrap="nowrap" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; text-align:left; vertical-align:middle; font-weight:bold; width: 55px;">
 Datum: 
</th>
<td nowrap="nowrap" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; text-align:left; vertical-align:middle; padding:2px 3px 2px 0px;">
<input style="background-color:#fff; border: 1px solid #7F9DB9; color: #000; width: 100px; height: 18px; font-size: 11px" type="text" name="REQ0JourneyDate" maxlength="14" value="05.12.11" accesskey="d" tabindex="3">
</td>
</tr>
<tr>
<th nowrap="nowrap" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; text-align:left; vertical-align:middle; font-weight:bold; width: 55px;">
 Zeit: 
</th>
<td nowrap="nowrap" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; text-align:left; vertical-align:middle; padding:2px 3px 2px 0px;">
<input type="text" name="REQ0JourneyTime" value="11:22" size="5" maxlength="5" style="background-color:#fff; border: 1px solid #7F9DB9; color: #000; width: 100px; height: 18px; font-size: 11px" accesskey="c" tabindex="4">
</td>
</tr>
<tr>
<th> </th>
<td nowrap="nowrap" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; text-align:left; vertical-align:middle; padding:2px 3px 2px 0px;">
<input class="radio" type="radio" name="REQ0HafasSearchForw" value="1"  checked style="margin-right:3px;">Abfahrt 
<br /><input class="radio" type="radio" name="REQ0HafasSearchForw" value="0"   style="margin-right:3px;">Ankunft
</td>
</tr>
<tr>
<td colspan="2" style="text-align:left;">
<input type="hidden" name="start" value="Suchen">
<input type="submit" name="start" value="Verbindung suchen" tabindex="5" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; text-align:center; width:130px; vertical-align: middle; cursor:pointer; -moz-border-radius: 3px 3px 3px 3px; background-color:#EE0000; border:1px solid #B20000; color:#FFFFFF; font-weight:bold; height:auto; line-height:20px; padding:0px 10px; text-decoration:none; white-space:nowrap;">
</td>
</tr>
<tr>
<td colspan="2" style="text-align:left;">
<a style="font-family:Arial,Helvetica,sans-serif; font-size:12px; text-align:left; margin-top:4px; color: #6B7786; text-decoration:none; display:block;" href="http://www.sbb.ch/166" target="_blank" title="Aktuelle Informationen zu Streiks und grösseren Unterbrüchen im Schienenverkehr."><img src="http://fahrplan.sbb.ch/img/one/icon_arrow_right.png" alt="" style="vertical-align:top; padding-right:2px; border:none;" />Bahnverkehrsinformation</a>
</td>
</tr>
</table>
</form>
</div>
</div>
<script language="JavaScript1.2" type="text/javascript">
/* <![CDATA[ */
var time=new Date();
var hour = time.getHours(); hour=(hour<10)? '0'+hour:hour;
var minute = time.getMinutes();minute=(minute<10)? '0'+minute:minute;
var travelTime = hour+':'+minute;
document.formular.REQ0JourneyTime.value=travelTime;
var yy = time.getFullYear();
var mm = time.getMonth()+1; mm=(mm<10)?'0'+mm:mm;
var dd = time.getDate(); dd=(dd<10)?'0'+dd:dd;
var travelDate=dd+"."+mm+"."+yy;
document.formular.REQ0JourneyDate.value=travelDate;
// /* ]]> */
</script>

</div>
{include file="findInclude:common/templates/footer.tpl"}