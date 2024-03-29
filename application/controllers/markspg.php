<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Markspg extends CI_Controller {
	
	 private $user_name='';
	 private $user_fullname='';
	 private $user_role = 0;
	 private $user_email='';
	 private $user_id='';
	 
	 public function __construct()
     {
             parent::__construct();
			$this->load->database();
			$this->load->library('session');
			$this->load->library('encrypt');
			$this->load->helper('url');
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->load->library('email');
			$this->load->model('newsession_model');
			$this->load->helper('email');
			$this->load->model('newsession_model');
			$this->load->model('common_model');
			$this->load->model('user_model');
			$this->load->model('type_model');
			$this->load->model('Master_model');
			$this->load->model('Marks_model');
			$this->load->model('Discipline_model');
			$this->load->model('Generate_model');
			$this->load->library('permission_lib');
			
			$this->load->library('excel');
			$sessdata= $this->session->userdata('sms');
		    if(empty($sessdata)){
				$this->load->view('admin/session_time_out_view');
			    redirect('authenticate', 'refresh');
		    }
			
			/*if($this->session->userdata('sms'))
			{
				$session_data = $this->session->userdata('sms');
				//print_r($session_data); exit;
				if(isset($session_data[0]))
				{
					$session_data=$session_data[0];
					$this->user_name = $session_data->username;
					$this->user_fullname = $session_data->first_name.' '. $session_data->last_name;
					$this->user_role = $session_data->role_id;
					$this->user_email =$session_data->email;
					$this->user_id = $session_data->id;
				}
				if($this->user_role!=0)
				{
					$this->load->library('permission_lib');
					 $permit=$this->permission_lib->permit_new($this->user_id,$this->user_role);
					// p($permit); exit;
				}
				
			}
			else
			{
				redirect('authenticate', 'refresh');
			}*/
	}
	//======================================UG Marks Upload==================================================//
	
	function marksUpload()
	{
		
		
		$data['page_title']="Upload PG Marks";
		$data['campuses'] = $this->Discipline_model->get_campus(); 
		$data['batches'] = $this->Discipline_model->get_batches(); 
		$data['semesters'] = $this->Discipline_model->get_semester(); 
		$data['disciplines'] = $this->Discipline_model->get_discipline(); 
		$this->load->view('admin/marks/marks_upload_add_pg_view',$data);
		
	}
	function getStudentAssignedMarksPG()
	{
		//print_r($_POST); exit;
		$campus_id=$this->input->post('campus_id');
		$program_id=$this->input->post('program_id');
		$degree_id=$this->input->post('degree_id');
		$batch_id=$this->input->post('batch_id');
		$semester_id=$this->input->post('semester_id');
		$discipline_id=$this->input->post('discipline_id');
		$course_id=$this->input->post('course_id');
		$marks_type=$this->input->post('marks_type');
		
	    $send['campus_id']=$campus_id;
	    $send['program_id']=$program_id;
	    $send['degree_id']=$degree_id;
	    $send['batch_id']=$batch_id;
	    $send['semester_id']=$semester_id;
	    $send['discipline_id']=$discipline_id;
	    $send['course_id']=$course_id;
		
		$studentList= $this->Marks_model->get_student_assigned_marks($send);
		
	    //p($studentList); exit;
		
	    if(!empty($studentList[0]->course_id)){
		$trdata='';
			$i=0;
			foreach($studentList as $students)
			{
				if($marks_type=='1')
				{
					
				$i++;
				$checked = 'checked';
				$trdata.='<tr>
				            <td><input type="hidden"  value="'.$students->user_unique_id.'">'.$students->user_unique_id.'
							<input type="hidden" name="student_id[]" value="'.$students->id.'">
							</td>
							
							<td>'.$students->first_name.' '.$students->last_name.'</td>
							<td><input type="text" name="theory_internal[]" value="'.$students->theory_internal.'"></td>
							<td><input type="text" name="practical_internal[]" value="'.$students->practical_internal.'"></td>
							<td><input type="text" name="theory_external[]" value="'.$students->theory_external.'"></td>
					    </tr>';
				}
				if($marks_type=='2'){
				$i++;
				$checked = 'checked';
				$readonly='readonly';
				$trdata.='<tr>
				         <td><input type="hidden"  value="'.$students->user_unique_id.'">'.$students->user_unique_id.' 
						    <input type="hidden" name="student_id[]" value="'.$students->id.'"></td>
						 
						<td>'.$students->first_name.' '.$students->last_name.'</td>
						
						  <td>
							  <input type="hidden" name="theory_internal[]" value="'.$students->theory_internal.'">
							  <input type="hidden" name="practical_internal[]" value="'.$students->practical_internal.'">
							  <input type="hidden" name="theory_external[]" value="'.$students->theory_external.'">
							  <input type="text" name="practical_external[]" value="'.$students->practical_external.'">
						  </td>
						
						
						
					</tr>';
				}
			}
			echo $trdata; 
		}
		else
		{   $studentListNew= $this->Marks_model->get_student_assigned_marks_course_where_not_inserted($send);
	       //p($studentListNew); exit;
	       if(!empty($studentListNew))
		   {
			   
			$trdata='';
			$i=0;
			
			foreach($studentListNew as $students)
			{
				if($marks_type=='1')
				{
					
				$i++;
				$checked = 'checked';
				$trdata.='<tr>
						  <td><input type="hidden"  value="'.$students->user_unique_id.'">'.$students->user_unique_id.'
								<input type="hidden" name="student_id[]" value="'.$students->id.'">
						  </td>
				          <td>'.$students->first_name.' '.$students->last_name.'</td>
						  <td><input type="text" name="theory_internal[]" value=""></td>
						  <td><input type="text" name="practical_internal[]" value=""></td>
						  <td><input type="text" name="theory_external[]" value=""></td>
						
						
						
					</tr>';
				}
				if($marks_type=='2'){
					$i++;
				$checked = 'checked';
				$readonly='readonly';
				if($course_id==$students->course_id)
				{
				$trdata.='<tr>
				         <td><input type="hidden"  value="'.$students->user_unique_id.'">'.$students->user_unique_id.'
						     <input type="hidden" name="student_id[]" value="'.$students->id.'"> 
						 </td>
					
						  <td><input type="text" name="practical_external[]" value=""></td>
					     </tr>';
				}
				else
				{
				$trdata.='<tr>
				         <td><input type="hidden"  value="'.$students->user_unique_id.'">'.$students->user_unique_id.'
						     <input type="hidden" name="student_id[]" value="'.$students->id.'">
						 </td>
						  <td>'.$students->first_name.' '.$students->last_name.'</td>
						 
						  <td><input type="text" name="practical_external[]" value=""></td>
						
						
						
					</tr>';	
				}
				}
			}
			echo $trdata; 
		}
	}		
	}
	
	function savePGMULInternalMarks()
	{
		//print_r($_POST); exit;
		$register_date_time=date('Y-m-d H:i:s');
		$campus_id=$this->input->post('campus_id');
		$program_id=$this->input->post('program_id');
		$degree_id=$this->input->post('degree_id');
		$batch_id=$this->input->post('batch_id');
		$semester_id=$this->input->post('semester_id');
		$discipline_id=$this->input->post('discipline_id');
		$course_id=$this->input->post('course_id');
		$marks_type=$this->input->post('marks_type');
		$student_ids=$this->input->post('student_id');
		$theory_internal=$this->input->post('theory_internal');
		$practical_internal=$this->input->post('practical_internal');
		
		$theory_external=$this->input->post('theory_external');
		$practical_external=$this->input->post('practical_external');
		//print_r($practical_external); exit;
		$return='';
		if($marks_type=='1'){
				for($i=0;$i<count($theory_internal);$i++){
						$theory_marks=$theory_internal[$i];
						$practical_marks=$practical_internal[$i];
						$external_theory_marks=$theory_external[$i];
						$student_id=$student_ids[$i];
						$this->Marks_model->delete_ug_marks($student_id,$course_id); //delete old and save new
						$marks_sum=$theory_marks+$practical_marks+$external_theory_marks;//adding internal marks
						$data=array(
							'campus_id'=>$campus_id,
							'program_id'=>$program_id,
							'degree_id'=>$degree_id,
							'batch_id'=>$batch_id,
							'semester_id'=>$semester_id,
							'discipline_id'=>$discipline_id,
							'student_id'=>$student_id,
							'course_id'=>$course_id,
							'theory_internal'=>$theory_marks,
							'practical_internal'=>$practical_marks,
							'theory_external'=>$external_theory_marks,
							
							'marks_sum'=>$marks_sum,
							'created_on'=>$register_date_time
						
						);
						//p($data); 
						
						
						$save = $this->Marks_model->save_ug_marks($data); 
						//print_r($save);
						if(!empty($save))
						{
							$return =1;
						}
						else{
							$return =0;
						}
				}// exit; 
	}
	if($marks_type=='2'){
		        
				for($i=0;$i<count($theory_internal);$i++){
						$theory_marks=$theory_internal[$i];
						$practical_marks=$practical_internal[$i];
						$theory_external_marks=$theory_external[$i];
						$practical_external_marks=$practical_external[$i];
						$student_id=$student_ids[$i];
						if(!empty($theory_marks)){$theory_inter=$theory_marks;} else {$theory_inter=00;}
						if(!empty($practical_marks)){$practical_inter=$practical_marks;} else {$practical_inter=00;}
						if(!empty($theory_external_marks)){$theory_extern=$theory_external_marks;} else {$theory_extern=00;}
						if(!empty($practical_external_marks)){$practical_extern=$practical_external_marks;} else {$practical_extern=00;}
						$marks_sum = $theory_inter+$practical_marks+$theory_external_marks+$practical_external_marks; //adding internal and external marks
						
						$data2=array(
							'campus_id'=>$campus_id,
							'program_id'=>$program_id,
							'degree_id'=>$degree_id,
							'batch_id'=>$batch_id,
							'semester_id'=>$semester_id,
							'discipline_id'=>$discipline_id,
							'student_id'=>$student_id,
							'course_id'=>$course_id,
							'theory_internal'=>$theory_marks,
							'practical_internal'=>$practical_marks,
							
							'theory_external'=>$theory_external_marks,
							'practical_external'=>$practical_external_marks,
							'marks_sum'=>$marks_sum,
							'created_on'=>$register_date_time
						
						);
						//p($data1); 
						$save = $this->Marks_model->update_marks_pg_new($data2); 
						//print_r($save);
						
						if(!empty($save))
						{
							$return =1;
						}
						else{
							$return =0;
						}
				}  
	}
		echo $return;  
	}
	
	//ajax function 
	function getProgramByCampus()
	{
		$campus_id = $this->input->post('campus_id');
		$data['programs']=$this->Marks_model->get_program_by_campus($campus_id); 
		//print_r($data['programs']); exit;
		$str = '';
         foreach($data['programs'] as $k=>$v){ 
           $str .= "<option value=".$v->id.">".$v->program_name."</option>";
           }
		   echo $str;
	}
	function getCourseByIds()
	{
		//print_r($_POST); exit;
		$campus_id = $this->input->post('campus_id');
		$program_id = $this->input->post('program_id');
		$degree_id = $this->input->post('degree_id');
		$batch_id = $this->input->post('batch_id');
		$semester_id = $this->input->post('semester_id');
		$discipline_id = $this->input->post('discipline_id');
		
		$data['courses']=$this->Marks_model->get_course_by_ids($campus_id,$program_id,$degree_id,$batch_id,$semester_id,$discipline_id); 
		//print_r($data['programs']); exit;
		$str = '';
         foreach($data['courses'] as $k=>$v){ 
           $str .= "<option value=".$v->id.">".$v->course_code.'-'.$v->course_title."</option>";
           }
		   echo $str;
	}
	//======================================UG Marks Upload End==================================================//
    //======================================PG Marks Upload Start==================================================//
	  function marksUploadPG()
	  {
		$data['page_title']="Upload PG Marks";
		$data['campuses'] = $this->Discipline_model->get_campus(); 
		$data['batches'] = $this->Discipline_model->get_batches(); 
		$data['semesters'] = $this->Discipline_model->get_semester(); 
		$data['disciplines'] = $this->Discipline_model->get_discipline(); 
		$this->load->view('admin/marks/marks_upload_pg_add_view',$data);
		
	  }
	  function getStudentList()
	  {
		$campus_id = $this->input->post('campus_id');
		$program_id = $this->input->post('program_id');
		$degree_id = $this->input->post('degree_id');
		$batch_id = $this->input->post('batch_id');
		$semester_id = $this->input->post('semester_id');
		$data['students']=$this->Marks_model->get_ug_students_by_ids($campus_id,$program_id,$degree_id,$batch_id); 
		//print_r($data['students']); exit;
		$str = '';
        foreach($data['students'] as $k=>$v){ 
           $str .= "<option value=".$v->id.">".$v->first_name.'-'.$v->last_name."</option>";
        }
		   echo $str;
	  }
	  
	  
	  function getStudentAssignCourseList()
	  {
		 // p($_POST); exit;
		$campus_id = $this->input->post('campus_id');
		$program_id = $this->input->post('program_id');
		$degree_id = $this->input->post('degree_id');
		$batch_id = $this->input->post('batch_id');
		$semester_id = $this->input->post('semester_id');
		$student_id = $this->input->post('student_id');
		$marks_type = $this->input->post('marks_type');
		//$studentAssignCourses = $this->Marks_model->get_student_assigned_courses($campus_id,$program_id,$degree_id,$batch_id,$semester_id,$student_id);
		$studentAssignCourses = $this->Marks_model->get_student_assigned_pg_courses($campus_id,$program_id,$degree_id,$batch_id,$semester_id,$student_id);
		//p($studentAssignCourses); exit;
		
		$trdata='';
	    $i=0;
			foreach($studentAssignCourses as $courses)
			{
				if($marks_type=='1')
				{
				$i++;
				$checked = 'checked';
				$trdata.='<tr>
				              <td><input type="hidden" name="course_id[]" value="'.$courses->id.'">'.$courses->course_code.'</td>
							 
							  <td>'.$courses->course_title.'</td>
							  <td>'.$courses->theory_credit.'+'.$courses->practicle_credit.'</td>
							  <td><input type="text" name="internal_theory[]" value="'.$courses->internal_theory.'"></td>
							  <td><input type="text" name="term_paper[]" value="'.$courses->term_paper.'"></td>
							  <td><input type="text" name="internal_practical[]" value="'.$courses->internal_practical.'"></td>
					     </tr>';
				}
				if($marks_type=='2'){
					//echo "hello"; exit;
					$i++;
				$checked = 'checked';
				$readonly='readonly';
				$trdata.='<tr>
							   <td><input type="hidden" name="internal_theory[]" value="'.$courses->internal_theory.'">
							   <input type="hidden" name="term_paper[]" value="'.$courses->term_paper.'">
							   <input type="hidden" name="internal_practical[]" value="'.$courses->internal_practical.'">
							   <input type="hidden" name="course_id[]" value="'.$courses->id.'">'.$courses->course_code.'</td>
							  <td>'.$courses->course_title.'</td>
							  <td>'.$courses->theory_credit.'+'.$courses->practicle_credit.'</td>
							  <td><input type="text" name="external_theory[]" value="'.$courses->external_theory.'"></td>
					     </tr>';
				}
			}
			echo $trdata; 
	 }
	 function savePGInternalMarks11()
	 {
		//print_r($_POST); exit;
		$register_date_time=date('Y-m-d H:i:s');
		$campus_id = $this->input->post('campus_id');
		$program_id = $this->input->post('program_id');
		$degree_id = $this->input->post('degree_id');
		$batch_id = $this->input->post('batch_id');
		$semester_id = $this->input->post('semester_id');
		$student_id = $this->input->post('student_id');
		$marks_type = $this->input->post('marks_type');
		$course_ids = $this->input->post('course_id');
		$internal_theory = $this->input->post('internal_theory');
		$term_paper = $this->input->post('term_paper');
		$internal_practical = $this->input->post('internal_practical');
		$external_theory = $this->input->post('external_theory');
		
		
		
		if($marks_type=='1'){
			
	    $updateData = $this->Marks_model->get_student_alerady_uploaded_marks($student_id,$semester_id,$batch_id,$program_id); //delete old and save new
		//print_r($updateData); exit;
					if(!empty($updateData))
					{
						for($i=0;$i<count($internal_theory);$i++){
									$theory_marks=$internal_theory[$i];
									$term_marks=$term_paper[$i];
									$practical_marks=$internal_practical[$i];
									$course_id=$course_ids[$i];
									$data=array(
										'campus_id'=>$campus_id,
										'program_id'=>$program_id,
										'degree_id'=>$degree_id,
										'batch_id'=>$batch_id,
										'semester_id'=>$semester_id,
										'student_id'=>$student_id,
										'course_id'=>$course_id,
										'theory_internal'=>$theory_marks,
										'practical_internal'=>$term_marks,
										'theory_external'=>$practical_marks,
										'created_on'=>$register_date_time
									
									);
									//p($data); 
									$save = $this->Marks_model->update_pg_internal_marks($data); 
									//print_r($save);
									if(!empty($save))
									{
										$return =1;
									}
									else{
										$return =0;
									}
							} 
					}
					else{
						for($i=0;$i<count($internal_theory);$i++){
									$theory_marks=$internal_theory[$i];
									$term_marks=$term_paper[$i];
									$practical_marks=$internal_practical[$i];
									$course_id=$course_ids[$i];
									//$this->Marks_model->delete_pg_marks($student_id,$course_id); //delete old and save new
									
									$data=array(
										'campus_id'=>$campus_id,
										'program_id'=>$program_id,
										'degree_id'=>$degree_id,
										'batch_id'=>$batch_id,
										'semester_id'=>$semester_id,
										'student_id'=>$student_id,
										'course_id'=>$course_id,
										'theory_internal'=>$theory_marks,
										'practical_internal'=>$term_marks,
										'theory_external'=>$practical_marks,
										'created_on'=>$register_date_time
									
									);
									//p($data);  exit;
									
									
									$save = $this->Marks_model->save_pg_marks($data); 
									//print_r($save);
									if(!empty($save))
									{
										$return =1;
									}
									else{
										$return =0;
									}
							}
					}		
		
		 
		}
		if($marks_type=='2'){
			for($i=0;$i<count($internal_theory);$i++){
						$theory_marks=$internal_theory[$i];
						$term_marks=$term_paper[$i];
						$practical_marks=$internal_practical[$i];
						$external_marks=$external_theory[$i];
						$course_id=$course_ids[$i];
						//$this->Marks_model->delete_pg_marks($student_id,$course_id); //delete old and save new
						
						$data=array(
							'campus_id'=>$campus_id,
							'program_id'=>$program_id,
							'degree_id'=>$degree_id,
							'batch_id'=>$batch_id,
							'semester_id'=>$semester_id,
							'student_id'=>$student_id,
							'course_id'=>$course_id,
                            'theory_internal'=>$theory_marks,
							'practical_internal'=>$term_marks,
							'theory_external'=>$practical_marks,
							'practical_external'=>$external_marks,
							'created_on'=>$register_date_time
						
						);
						//p($data);  exit;
						
						
						$save = $this->Marks_model->update_pg_marks($data); 
						//print_r($save);
						if(!empty($save))
						{
							$return =1;
						}
						else{
							$return =0;
						}
				} 
		}
		echo $return;
	 }
	//======================================PG Marks Upload End==================================================//
	//======================================UG Marks Upload Start==================================================//
	function uploadUgMarksExcel()
	{
		 $data['page_title']='Upload Marks Excel';
		 $data['campuses'] = $this->Discipline_model->get_campus();
		 $data['degrees'] = $this->Discipline_model->get_degree(); 
		 $data['programs'] = $this->Discipline_model->get_program(); 
		 $this->load->view('admin/excel/add_ug_marks_excel_view',$data);
	}
	//======================================UG Marks Upload End==================================================//
	//----------------  Download Discipline Excel ----------------------------//
      function downloadMarksFormat()
	  {  
	 // echo "hello"; exit;
	  if($this->input->post('downloadExcel'))
	  {
		  // echo "hello"; exit;
		   $campus_id = $this->input->post('campus_id');
		   $program_id = $this->input->post('program_id');
		   $degree_id = $this->input->post('degree_id');
		   $record['campuses'] = $this->Discipline_model->get_campus_by_id($campus_id); 
		  // print_r($record['campuses']); exit;
		   $record['programs'] = $this->Discipline_model->get_program_by_id($program_id); 
		   $record['degrees'] = $this->Discipline_model->get_degree_by_id($degree_id);
           $record['batches'] = $this->Discipline_model->get_batch(); 		   
           $record['semesters'] = $this->Discipline_model->get_semester(); 	
          // p($record['semesters']); exit;		   
		   $record['disciplines'] = $this->Discipline_model->get_discipline(); 
		   $record['courses'] = $this->Discipline_model->get_assign_course(); 
		   //p($record['courses']); exit;
		 
		   $record['students'] = $this->Discipline_model->get_students_by_cpd($campus_id,$program_id,$degree_id);
		   // p($record['students']); exit;
       //............. Add Model............... //
	    //-----------Va Define-----------------------//
     $allArr               = array();
     $templevel            = 0;  
     $newkey               = 0;
     $grouparr[$templevel] = "";
  //-----------------------------------------------//

  
     //p($fuelProName); exit;
   //----------End Universal Products -----------//
     $allArr               = array();
     $templevel            = 0;  
     $newkey               = 0;
     $grouparr[$templevel] = "";
    // p($responce['segmentname']); exit;
	  //**************campuses dropdown********************************//
	 // print_r($record['campuses']); exit;
	   foreach ($record['campuses'] as $key => $value) {
          $campusid[]    = $value->id;
          $campusname[]  = $value->campus_name;
       }//foreach
	 
       $finalcampusid   = implode($campusid, ',');
       $finalcampusname = implode($campusname, ',');
	  // print_r($finalcampusname); exit;
      
       //**************campuses dropdown End********************************// 
      //**************programs dropdown********************************//
	   foreach ($record['programs'] as $key => $value) {
          $programid[]    = $value->id;
          $programname[]  = $value->program_name;
       }//foreach
       $finalprogramid   = implode($programid, ',');
       $finalprogramname = implode($programname, ',');
      //**************program dropdown End********************************//
	  
     //**************degree dropdown********************************//
	   foreach ($record['degrees'] as $key => $value) {
          $degreeid[]    = $value->id;
          $degreename[]  = $value->degree_name;
       }//foreach
       $finaldegreeid   = implode($degreeid, ',');
       $finaldegreename = implode($degreename, ',');
	   
      //**************degree dropdown End********************************//    
	   
	   //**************batch dropdown********************************//
	   foreach ($record['batches'] as $key => $value) {
          $batchid[]    = $value->id;
          $batchname[]  = $value->batch_name;
       }//foreach
       $finalbatchid   = implode($batchid, ',');
       $finalbatchname = implode($batchname, ',');
	 //**************batch dropdown End********************************// 
	  
       //**************semesters dropdown********************************//
	   foreach ($record['semesters'] as $key => $value) {
          $semesterid[]    = $value->id;
          $semestername[]  = $value->semester_name;
       }//foreach
	   //p($semestername); exit;
       $finalsemesterid   = implode($semesterid, ',');
       $finalsemestername = implode($semestername, ',');
	  // p($finalsemestername); exit;
      //**************semesters dropdown End********************************// 
	  
       //**************semesters dropdown********************************//
	   foreach ($record['courses'] as $key => $value) {
          $courseid[]    = $value->id;
          $coursename[]  = $value->course_code.'-'.$value->course_title;
       }//foreach
       $finalcourseid   = implode($courseid, ',');
       $finalcoursename = implode($coursename, ',');
      //**************semesters dropdown End*****************************// 
      
       //**************students dropdown********************************//
	   foreach ($record['students'] as $key => $value) {
          $studentid[]    = $value->id;
          $studentname[]  = $value->first_name.' '.$value->last_name;
       }//foreach
       $finalstudentid = implode($studentid, ',');
       $finalstudentname = implode($studentname, ',');
      //**************students dropdown End********************************//   	 	  

    //------------Define Index ---------//
       $featurevaluee1[]    = 'campusName';
	   $featurevaluee1[]    = 'programName'; 
       $featurevaluee1[]    = 'degreeName'; 
       $featurevaluee1[]    = 'batchName'; 
       $featurevaluee1[]    = 'semestersName'; 
       $featurevaluee1[]    = 'courseName'; 
       $featurevaluee1[]    = 'studentName'; 
     
     
    
        
      $excelHeadArrqq1 = array_merge($featurevaluee1);
      $excelHeadArr1 = array_unique($excelHeadArrqq1);
      
      $oldfinalAttrData['campusName']       = $finalcampusname;
      $oldfinalAttrData2['campusName']      = $campusname;
	  
	  $oldfinalAttrData['programName']       = $finalprogramname;
      $oldfinalAttrData2['programName']      = $programname;
	  
	  $oldfinalAttrData['degreeName']       = $finaldegreename;
      $oldfinalAttrData2['degreeName']      = $degreename;
	  
	  $oldfinalAttrData['batchName']       = $finalbatchname;
      $oldfinalAttrData2['batchName']      = $batchname;
	  
	  $oldfinalAttrData['semestersName']       = $finalsemestername;
      $oldfinalAttrData2['semestersName']      = $semestername;
	  
	  $oldfinalAttrData['courseName']       = $finalcoursename;
      $oldfinalAttrData2['courseName']      = $coursename;
	  
	  $oldfinalAttrData['studentName']       = $finalstudentname;
      $oldfinalAttrData2['studentName']      = $studentname;
	  
	  
	  
	  
      
       $finalAttrData  = array_merge($oldfinalAttrData);
       $finalAttrData2 = $oldfinalAttrData2;
       $attCount = array();
       foreach($finalAttrData as $k=>$v){
       $valueCount   = count(explode(",", $v));
       $attCount[$k] = $valueCount+1;

      } 
    // p($attCount['semesterName']); exit;

       $objPHPExcel   = new PHPExcel();
    //-------------------------------- First defult Sheet -------------------------------------------------//
    $objWorkSheet  = $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(0);

    $finalExcelArr1 = array_merge($excelHeadArr1);
//p($finalAttrData2);exit;
     $cols   = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
        $j      = 2;
       
        for($i=0;$i<count($finalExcelArr1);$i++){
         $objPHPExcel->getActiveSheet()->setCellValue($cols[$i].'1', $finalExcelArr1[$i]);
         foreach ($finalAttrData2 as $key => $value) {
          foreach ($value as $k => $v) {

            if($key == $finalExcelArr1[$i]){
            $newvar = $j+$k;
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($cols[$i].$newvar, $v, PHPExcel_Cell_DataType::TYPE_STRING);
            }
        }
      }  
    }
     $objPHPExcel->getSheetByName('Worksheet')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
        $arrPart2 = array('Campus','Program','Degree','Batch','Semester','Course','Student','Internal-Theory(100)','Internal-Practical(100)','External-Theory(100)','External Practical(100)');
          if(!empty($excelHeadArr)){
             $finalExcelArr = array_merge($arrPart2,$excelHeadArr);
         }else{
            $finalExcelArr = array_merge($arrPart2);
         }
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet()->setTitle('VendorProductWorksheet#'.$value->id.'');
      
    $cols  = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ');
 
 //Set border style for active worksheet
 $styleArray = array(
      'borders' => array(
          'allborders' => array(
            'style'  => PHPExcel_Style_Border::BORDER_THIN
          )
      )
);
$objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray($styleArray);

        for($i=0;$i<count($finalExcelArr);$i++){
            //Set width for column head.
            $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$i])->setAutoSize(true);

            //Set background color for heading column.
            $objPHPExcel->getActiveSheet()->getStyle($cols[$i].'1')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '71B8FF')
                    ),
                      'font'  => array(
                      'bold'  => false,
                      'size'  => 15,
                      )
                )
            );
            $objPHPExcel->getActiveSheet()->setCellValue($cols[$i].'1', $finalExcelArr[$i]);

          for($k=2;$k <1000;$k++){
    //Set height for every single row.
    $objPHPExcel->getActiveSheet()->getRowDimension($k)->setRowHeight(20);

    //Create select box for segment.
    $objValidation22 = $objPHPExcel->getActiveSheet()->getCell('A2')->getDataValidation();
    $objValidation22->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
    $objValidation22->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
    $objValidation22->setAllowBlank(false);
    $objValidation22->setShowInputMessage(true);
    $objValidation22->setShowErrorMessage(true);
    $objValidation22->setShowDropDown(true);
    $objValidation22->setErrorTitle('Input error');
    $objValidation22->setError('Value is not in list.');
    $objValidation22->setPromptTitle('Pick from list');
    $objValidation22->setPrompt('Please pick a value from the drop-down list.');
    $objValidation22->setFormula1('Worksheet!$'.'A$2:$'.'A$'.($attCount['campusName']));
    $var = $objPHPExcel->getActiveSheet()->getCell('A'.$k)->setDataValidation($objValidation22);
   
    $objValidation23 = $objPHPExcel->getActiveSheet()->getCell('B2')->getDataValidation();
    $objValidation23->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
    $objValidation23->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
    $objValidation23->setAllowBlank(false);
    $objValidation23->setShowInputMessage(true);
    $objValidation23->setShowErrorMessage(true);
    $objValidation23->setShowDropDown(true);
    $objValidation23->setErrorTitle('Input error');
    $objValidation23->setError('Value is not in list.');
    $objValidation23->setPromptTitle('Pick from list');
    $objValidation23->setPrompt('Please pick a value from the drop-down list.');
    $objValidation23->setFormula1('Worksheet!$'.'B$2:$'.'B$'.($attCount['programName']));
    $objPHPExcel->getActiveSheet()->getCell('B'.$k)->setDataValidation($objValidation23);
   
    $objValidation24 = $objPHPExcel->getActiveSheet()->getCell('C2')->getDataValidation();
    $objValidation24->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
    $objValidation24->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
    $objValidation24->setAllowBlank(false);
    $objValidation24->setShowInputMessage(true);
    $objValidation24->setShowErrorMessage(true);
    $objValidation24->setShowDropDown(true);
    $objValidation24->setErrorTitle('Input error');
    $objValidation24->setError('Value is not in list.');
    $objValidation24->setPromptTitle('Pick from list');
    $objValidation24->setPrompt('Please pick a value from the drop-down list.');
    $objValidation24->setFormula1('Worksheet!$'.'C$2:$'.'C$'.($attCount['degreeName']).'');
    $objPHPExcel->getActiveSheet()->getCell('C'.$k)->setDataValidation($objValidation24);
	
	$objValidation25 = $objPHPExcel->getActiveSheet()->getCell('D2')->getDataValidation();
    $objValidation25->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
    $objValidation25->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
    $objValidation25->setAllowBlank(false);
    $objValidation25->setShowInputMessage(true);
    $objValidation25->setShowErrorMessage(true);
    $objValidation25->setShowDropDown(true);
    $objValidation25->setErrorTitle('Input error');
    $objValidation25->setError('Value is not in list.');
    $objValidation25->setPromptTitle('Pick from list');
    $objValidation25->setPrompt('Please pick a value from the drop-down list.');
    $objValidation25->setFormula1('Worksheet!$'.'D$2:$'.'D$'.($attCount['batchName']).'');
    $objPHPExcel->getActiveSheet()->getCell('D'.$k)->setDataValidation($objValidation25);
	
	$objValidation26 = $objPHPExcel->getActiveSheet()->getCell('E2')->getDataValidation();
    $objValidation26->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
    $objValidation26->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
    $objValidation26->setAllowBlank(false);
    $objValidation26->setShowInputMessage(true);
    $objValidation26->setShowErrorMessage(true);
    $objValidation26->setShowDropDown(true);
    $objValidation26->setErrorTitle('Input error');
    $objValidation26->setError('Value is not in list.');
    $objValidation26->setPromptTitle('Pick from list');
    $objValidation26->setPrompt('Please pick a value from the drop-down list.');
    $objValidation26->setFormula1('Worksheet!$'.'E$2:$'.'E$'.($attCount['semestersName']).'');
    $objPHPExcel->getActiveSheet()->getCell('E'.$k)->setDataValidation($objValidation26);
	
	$objValidation27 = $objPHPExcel->getActiveSheet()->getCell('F2')->getDataValidation();
    $objValidation27->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
    $objValidation27->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
    $objValidation27->setAllowBlank(false);
    $objValidation27->setShowInputMessage(true);
    $objValidation27->setShowErrorMessage(true);
    $objValidation27->setShowDropDown(true);
    $objValidation27->setErrorTitle('Input error');
    $objValidation27->setError('Value is not in list.');
    $objValidation27->setPromptTitle('Pick from list');
    $objValidation27->setPrompt('Please pick a value from the drop-down list.');
    $objValidation27->setFormula1('Worksheet!$'.'F$2:$'.'F$'.($attCount['courseName']).'');
    $objPHPExcel->getActiveSheet()->getCell('F'.$k)->setDataValidation($objValidation27);
	
	$objValidation28 = $objPHPExcel->getActiveSheet()->getCell('G2')->getDataValidation();
    $objValidation28->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
    $objValidation28->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
    $objValidation28->setAllowBlank(false);
    $objValidation28->setShowInputMessage(true);
    $objValidation28->setShowErrorMessage(true);
    $objValidation28->setShowDropDown(true);
    $objValidation28->setErrorTitle('Input error');
    $objValidation28->setError('Value is not in list.');
    $objValidation28->setPromptTitle('Pick from list');
    $objValidation28->setPrompt('Please pick a value from the drop-down list.');
    $objValidation28->setFormula1('Worksheet!$'.'G$2:$'.'G$'.($attCount['studentName']).'');
    $objPHPExcel->getActiveSheet()->getCell('G'.$k)->setDataValidation($objValidation28);
	
	}//secfor
}

        $filename  = 'marks_upload.xls';
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
        ob_end_clean();
		//ob_start();
        $objWriter->save('php://output');
        exit;
 }
		
	   //----------Upload Marks Excel ---------------------//	
	if($this->input->post('Test'))
	{
		//echo "hello"; exit;
		
	   $fileName    = $_FILES['userfile']['tmp_name'];
	   $objPHPExcel = PHPExcel_IOFactory::load($fileName);
	   $maxCell = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();
	   $rowsold     = $objPHPExcel->getActiveSheet()->rangeToArray('A2:' . $maxCell['column'] . $maxCell['row']); 
	   $rowsoldHeader     = $objPHPExcel->getActiveSheet()->toArray(); 
	   $sheetName   = $objPHPExcel->getActiveSheet()->getTitle();
	  //p($rowsold); exit;
	   	$m =0;
	  //--------Insret Data Form Excel File --------//
	  foreach($rowsold as $firstrow){
		    //p($firstrow); 
		    if(!empty($firstrow[$m]))
			{
			// p($firstrow[6]); exit;	
			 $explodename=explode(' ',$firstrow[6]);
			// p($explodename); exit;
			 $firstname=$explodename[0];
			 $lastname=$explodename[1];
			 //p($firstrow[5]); exit;
			 $coursename=explode('-',$firstrow[5]);
			 $courseCode = $coursename[0];
			 $courseName = $coursename[1];
		    $campusLists=$this->type_model->get_campus_info($firstrow[0]);
		    $programLists=$this->type_model->get_program_info($firstrow[1]);
		    $degreeLists=$this->type_model->get_degree_info($firstrow[2]);
		    $batchLists=$this->type_model->get_batch_info($firstrow[3]);
		    $semesterLists=$this->type_model->get_semester_info($firstrow[4]);
			//print_r($semesterLists); exit;
		    $courseLists=$this->type_model->get_course_info($courseCode,$courseName);
		    $studentLists=$this->type_model->get_student_info($firstname,$lastname);
			
			$campusLists->id;
			$programLists->id;
			$degreeLists->id;
			$batchLists->id;
			$semesterLists->id;
			$courseLists->id;
			$studentLists->id;
			$register_date_time=date('Y-m-d H:i:s');
			$dataArr= array(
			                'campus_id'=>$campusLists->id,
			                'program_id'=>$programLists->id,
			                'degree_id'=>$degreeLists->id,
			                'batch_id'=>$batchLists->id,
			                'semester_id'=>$semesterLists->id,
			                'student_id'=>$studentLists->id,
			                'course_id'=>$courseLists->id,
							'theory_internal'=>$firstrow[7],
			                'practical_internal'=>$firstrow[8],
			                'theory_external'=>$firstrow[9],
			                'practical_external'=>$firstrow[10],
			                'created_on'=>$register_date_time
			);
			//p($dataArr1); exit;
		  $insertid=$this->type_model->save_ug_excel_marks($dataArr);
			
		 $m++;
	   } 
	  
	  } 
	  $this->session->set_flashdata('message', 'Marks  uploaded  successfully');
	  redirect('marks/uploadUgMarksExcel');
	  
	}
	
		
		
		
}

    //----------End Download Excel File ---------------------//
	
	

}
?>