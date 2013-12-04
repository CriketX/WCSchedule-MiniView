<?php
global $wpdb;

$dayz=date('l'); 				
global $dayznum;
global $dayznum2;
$start_hour = '05:00:00';
$end_hour = '23:00:00';

if($dayz=='Sunday' || $dayz=='Monday'){ $dayznum=1; }
if($dayz=='Tuesday'){ $dayznum=2; }
if($dayz=='Wednesday'){ $dayznum=3; }
if($dayz=='Thursday'){ $dayznum=4; }
if($dayz=='Friday'){ $dayznum=5; }
if($dayz=='Saturday'){ $dayznum=6; }

if($dayznum != 6){ $dayznum2=$dayznum+1;}else{$dayznum2=1;}
$weekarray = array(
	1 => "Monday",
	2 => "Tuesday",
	3 => "Wednesday",
	4 => "Thursday",
	5 => "Friday",
	6 => "Saturday"
);
?>
	
	<table class="wcs-schedule">
		<tbody>
			<tr>
				<th></th>
				<th><?php print $weekarray[$dayznum]; ?></th>					
				<th><?php print $weekarray[$dayznum2]; ?></th>					
			</tr>
			<?php 
				$s = $start_hour;
				while($s != $end_hour){
				$endofquery = "start_hour='$s'";
				$result = $wpdb->get_var($wpdb->prepare("SELECT class_id FROM wp_wcs2_schedule WHERE ".$endofquery."", ARRAY_A));
					if($result){
					print '<tr><th class="wcs-hour-title">'; 
					$starttime = explode(':', $s);
					$start = $starttime[0].':'.$starttime[1];
					if(intval($starttime[0])<12){
						$suffix='am';
						$start= intval($starttime[0]);
						$start .= ':'.$starttime[1];
					}else{
						$suffix='pm';
						if($starttime[0] > 12)
						{
							$start= (intval($starttime[0])-12);
							$start .= ':'.$starttime[1];
						}
						else
						{
							$start = $starttime[0].':'.$starttime[1];
						}
					} print $start.' '.$suffix;
					print '</th>';
					$d = 1;
						while($d <= 2){
							if($d == 2){
								$dayznum3 = $dayznum2;
							}else{
								$dayznum3 = $dayznum;
							}
							print '<td';
							
							$result = $wpdb->get_var($wpdb->prepare("SELECT class_id FROM wp_wcs2_schedule WHERE weekday=$dayznum3 AND ".$endofquery."", ARRAY_A));
							if($result)
							{
								print ' class="wcs-schedule-cell active col-1">';
								print '<div class="wcs-active-class-container"><div class="wcs-active-div-0 wcs-active-div odd">';
								$classnumber = $result; 
								
								$datarow = $wpdb->get_results("SELECT * FROM wp_wcs2_class WHERE id=$classnumber",ARRAY_A);
								$nameresult = $datarow[0]['class_name'];
								$description = $datarow[0]['class_description'];
								
								$datarow = $wpdb->get_results("SELECT * FROM wp_wcs2_schedule WHERE weekday=$dayznum3 AND ".$endofquery."", ARRAY_A);								
								$instructorid = $datarow[0]['instructor_id'];
								$end = $datarow[0]['end_hour'];

								$datarow = $wpdb->get_results("SELECT * FROM wp_wcs2_instructor WHERE id=$instructorid",ARRAY_A);
								$instructor = $datarow[0]['instructor_name']; 
								$instructordescription = $datarow[0]['instructor_description'];
								
								$endtime = explode(':', $end);
								$end = $endtime[0].':'.$endtime[1];
								if(intval($endtime[0])<12)
								{$suffix='am';}else{$suffix='pm';}
								

								print '<p>'.$nameresult.'</p>';
								print '<div class="wcs-class-details" style="display: none;"><p><a class="wcs-qtip" name="'.$description.'">'.$nameresult.'</a> with <a class="wcs-qtip" name="'.$instructordescription.'">'.$instructor.'</a><br>'.$start.' '.$suffix.' am to '.$end.' '.$suffix.'<br></p></div>';
								print '</div></div>';
							}else{
								print '>';
							}
							print '</td>';
							$d++;
						}
					}
					
				print '</tr>';
				if($starttime[1] == '45'){ //if time is xx:45:00				
					$starttime[0] = intval($starttime[0] + 1);
					$starttime[1] = '00';
					if(intval($starttime[0]) < 10 ) {
						$starttime[0] = '0'.$starttime[0];						
					}
				} else {
					$starttime[1] = intval($starttime[1]) + 15;
				}
				$s = $starttime[0].':'.$starttime[1].':'.'00';
			} ?>
			
			</tbody>
	</table>		
