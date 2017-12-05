<style type="text/css">
body{
	font-family:Verdana, Geneva, sans-serif;
	font-size:10px;
	margin:0;
}
table{
	border:solid 1px #00815C;
	border-bottom:none;
}
table th{
	background:#00B481;
	color:#FFF;
}
table td,
table th{
	border-left:solid 2px #00815C;
	border-bottom:solid 2px #00815C;
	padding:3px;
	font-size:10px;
}
table td:first-child,
table th:first-child{
	border-left:none;
}
</style>
<table border="0" cellspacing="0" cellpadding="2" width="100%">
    <thead bgcolor="#00B481" align="center">
        <tr>
            <th bgcolor="#00B481"></th>
            <th bgcolor="#00B481"></th>
            <th colspan="5" align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">LOCATIONS</th>
            <th colspan="2" align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">HEALTHCARE WORKER</th>
            <th colspan="7" align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">HAND HYGIENE COMPLIANCE</th>
            <th rowspan="2" align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">Occupational<br>Exposure Risk</th>
            <th colspan="5" bgcolor="#00B481"></th>
        </tr>
        <tr>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">Date & Time</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">Auditor</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">Branch</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">Facility</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">Department</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">Ward</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">Service</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">Title</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">Name</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;" colspan="5">Indication/s</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">Action</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">Result</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">GLOVES</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">GOWN</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">MASK</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">Mask Type</th>
            <th align="center" bgcolor="#00B481" style="color:#FFF; font-weight:bold; font-size:9px;">Notes</th>
        </tr>
    </thead>
    <tbody>
        	<?php
            function clearstr($data){
				return (empty($data)) ? '&nbsp;' : $data;
			}
			?>
            
            <?php $x = 0; foreach($data as $list): $x++?>
            	<tr bgcolor="<?=$x % 2 == 0 ? '#ECECEC' : '#FFF'?>">
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->datetime)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->full_name)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->organization)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->location_level1)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->location_level2)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->location_level3)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->location_level4)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->hcw_title)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->hcw_name)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->moment1)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->moment2)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->moment3)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->moment4)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->moment5)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->hh_compliance)?></td>
                    <td style="font-size:8px; padding:2px;"><?=$list->hh_compliance == 'missed' ? 'Failed' : 'Passed'?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->hh_compliance_type)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->glove_compliance)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->gown_compliance)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->mask_compliance)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->mask_type)?></td>
                    <td style="font-size:8px; padding:2px;"><?=clearstr($list->note)?></td>
                </tr>
            <?php endforeach;?>
    </tbody>
</table>
