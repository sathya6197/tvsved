<!DOCTYPE html>
<html>
<head>
    
    
<style>
        .table {
             
            border-collapse: collapse;
            padding:0px !important;
        }

            .table th {
                border: 1px solid black;
                border-collapse: collapse;
                padding: 0px 0px 0px 0px !important;
                font-size: 14px;
            }

                .table th td {
                     
                    border-collapse: collapse;
                    padding: 0px 0px 0px 0px !important;
                }

            .table tr td {
                border: 1px solid black;
                border-collapse: collapse;
                font-size: 14px;
				
                padding: 0px 0px 0px 0px !important;
				height:38px;
				text-align:center;
				
            }

        .pdf_container{
            width:100%;
            display:block;
            padding:0px;
            margin:0px;
        }

    </style>
</head>


<body>
    <div style="padding:10px 10px 10px 10px; width:852px; font-family:Arial, Helvetica, sans-serif;">
		<div align="center">
			   <p align="center" style="font-weight:bold; font-size:16px;">TAMILNADU VETERINARY AND ANIMAL SCIENCES UNIVERSITY<br />
			   <span align="center" style=" font-size:14px; padding-top:0px;">AGGREGATE RESULT MARK REPORT</span><br />
			   <span align="center" style=" font-size:14px; padding-top:0px;"><?php echo $aggregate_marks[0]['semester_name']; ?> (<?php echo $aggregate_marks[0]['degree_code']; ?>)</span><br /><br />			  
		</div>
        <div class="pdf_container">
		<?php foreach($aggregate_marks as $key=>$student_marks){ ?>
		<div align="center" style="border:1px solid;margin-bottom:75px;">
            <table style="font-size:14px;">
                <tr>
                  
					<td align="left" width="200px" style="vertical-align:top;font-weight:bold;"><?php echo $student_marks['user_unique_id']; ?></td>
                   
					<td align="left" style="vertical-align:top;font-weight:bold;"><?php echo $student_marks['first_name']; ?></td>                    
                </tr>				
            </table> 
			
            <table class="table" width="852" style="border:solid 1px black; font-size:14px; ">
                <tr>
                    <th  style="font-weight:bold;padding:2px;">Course</th>
                    <th  style="font-weight:bold;padding:2px;">Credit<br /> Hours</th>
					<th  style="font-weight:bold;padding:2px;">Internal <br />(20)</th>                    
                    <th  style="font-weight:bold;padding:2px;">Theory <br />(40)</th>
					<th  style="font-weight:bold;padding:2px;">Practical <br />(40)</th>
					<th  style="font-weight:bold;padding:2px;">Total <br />(100)</th>
					<!--<th  style="font-weight:bold;padding:2px;">Credit <br />Hours</th>-->
					<th  style="font-weight:bold;padding:2px;">G.P.</th>
                    <th  style="font-weight:bold;padding:2px;">C.P.</th>
                    <th  style="font-weight:bold;padding:2px;">Result</th>
					<!--<th  style="font-weight:bold;padding:2px;">Cradit Points</th>-->
                </tr>
				<?php 
			$total_cp='';
			$total_gp='';
			
			foreach($student_marks['subjectList'] as $subject_data){
				//p($subject_data); exit;
				     $total_cp = $total_cp+$subject_data['creditval'];
					 $total_gp = $total_gp+$subject_data['gradeval'];
					// p($total_cp); 
				?>
				<tr>	
					
					<td><?php if($subject_data['course_code']==''){ echo '';} else{ echo $subject_data['course_code'];}?></td>
			<td><?php echo $subject_data['theory_credit'].'+'.$subject_data['practicle_credit'];?></td>
			<td><?php if($subject_data['theory_internal']==''){echo '';}else{echo $subject_data['theory_internal'];}?></td>
			<td><?php if($subject_data['sum_theory']==''){echo '';}else{echo $subject_data['sum_theory'];}?></td>
			<td><?php if($subject_data['sum_practical']==''){echo '';}else{echo $subject_data['sum_practical'];}?></td>
			<td><?php if($subject_data['sum_total']==''){echo '';}else{echo $subject_data['sum_total'];}?></td>
			<!--<td><?php if($subject_data['percentval']==''){echo '';}else{echo $subject_data['percentval'];}?></td>-->
			<td><?php if($subject_data['gradeval']==''){echo '';}else{echo $subject_data['gradeval'];}?></td>
			<td><?php if($subject_data['creditval']==''){echo '';}else{echo $subject_data['creditval'];}?></td>
			<td><?php if($subject_data['passfail_status']==''){echo '';}else{echo $subject_data['passfail_status'];}?></td>			
				</tr>				
<?php } 	?>
			
            </table>
			</div>
<?php   if((($key+1)%3)==0) echo "<pagebreak>";  }  ?>

    </div>
</body>
</html>
