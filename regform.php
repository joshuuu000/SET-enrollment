 <!-- `IDNO`, `FNAME`, `LNAME`, `MNAME`, `SEX`, `BDAY`, `BPLACE`, `STATUS`, `AGE`, `NATIONALITY`,
 `RELIGION`, `CONTACT_NO`, `HOME_ADD`, `EMAIL`, `ACC_PASSWORD`, `student_status`, `schedID`, `course_year` -->
 <?php
 if (isset($_POST['regsubmit'])) {

$_SESSION['STUDID'] 	  =  $_POST['IDNO'];
$_SESSION['FNAME'] 	      =  $_POST['FNAME'];
$_SESSION['LNAME']  	  =  $_POST['LNAME'];
$_SESSION['MI']           =  $_POST['MI'];
$_SESSION['PADDRESS']     =  $_POST['PADDRESS'];
$_SESSION['SEX']          =  $_POST['optionsRadios'];
$_SESSION['BIRTHDATE']    = date_format(date_create($_POST['BIRTHDATE']),'Y-m-d'); 
$_SESSION['NATIONALITY']  =  $_POST['NATIONALITY'];
$_SESSION['BIRTHPLACE']   =  $_POST['BIRTHPLACE'];
$_SESSION['RELIGION']     =  $_POST['RELIGION'];
$_SESSION['CONTACT']      =  $_POST['CONTACT'];
$_SESSION['CIVILSTATUS']  =  $_POST['CIVILSTATUS'];
$_SESSION['GUARDIAN']     =  $_POST['GUARDIAN'];
$_SESSION['GCONTACT']     =  $_POST['GCONTACT'];
$_SESSION['COURSEID'] 	  =  $_POST['COURSE'];
// $_SESSION['SEMESTER']     =  $_POST['SEMESTER'];  
$_SESSION['USER_NAME']    =  $_POST['USER_NAME']; 
$_SESSION['PASS']    	  =  $_POST['PASS']; 


 	$student = New Student();
	$res = $student->find_all_student($_POST['LNAME'],$_POST['FNAME'],$_POST['MI']);

if ($res) {
	# code...
	message("Student already exist.", "error");
    redirect(web_root."index.php?q=enrol");

 }else{

$sql="SELECT * FROM tblstudent WHERE ACC_USERNAME='" . $_SESSION['USER_NAME'] . "'";
$userresult = mysqli_query($mydb->conn,$sql) or die(mysqli_error($mydb->conn));
$userStud  = mysqli_fetch_assoc($userresult);

if($userStud){
	message("Username is already taken.", "error");
    redirect(web_root."index.php?q=enrol");
}else{
	if($_SESSION['COURSEID']=='Select' || $_SESSION['SEMESTER']=='Select' ){
		message("Select course and semester exactly"."error");
		redirect("index.php?q=enrol");

	}else{

	$age = date_diff(date_create($_SESSION['BIRTHDATE']),date_create('today'))->y;

    if ($age < 15){
       message("Cannot Proceed. Must be 15 years old and above to enroll.", "error");
       redirect("index.php?q=enrol");

    }else{
		$student = New Student();
		$student->IDNO 			= $_SESSION['STUDID'];
		$student->FNAME 		= $_SESSION['FNAME'];
		$student->LNAME 		= $_SESSION['LNAME'];
		$student->MNAME 		= $_SESSION['MI'];
		$student->SEX 			= $_SESSION['SEX'];
		$student->BDAY 			= $_SESSION['BIRTHDATE'];
		$student->BPLACE 		= $_SESSION['BIRTHPLACE'];
		$student->STATUS 		= $_SESSION['CIVILSTATUS'];
		$student->NATIONALITY 	= $_SESSION['NATIONALITY'];
		$student->RELIGION 		= $_SESSION['RELIGION'];
		$student->CONTACT_NO	= $_SESSION['CONTACT'];
		$student->HOME_ADD 		= $_SESSION['PADDRESS'];
		$student->ACC_USERNAME	= $_SESSION['USER_NAME'];
		$student->ACC_PASSWORD 	= sha1($_SESSION['PASS']);
		$student->COURSE_ID   	= $_SESSION['COURSEID'];
		$student->SEMESTER   	= $_SESSION['SEMESTER']; 
		$student->student_status ='New';
		$student->YEARLEVEL   	= 1; 
		$student->NewEnrollees  = 1; 
		$student->create();

		$studentdetails = New StudentDetails();
		$studentdetails->IDNO = $_SESSION['STUDID'];
		$studentdetails->GUARDIAN = $_SESSION['GUARDIAN'];
		$studentdetails->GCONTACT = $_SESSION['GCONTACT']; 
		$studentdetails->create(); 

		$studAuto = New Autonumber();
		$studAuto->studauto_update();

		@$_SESSION['IDNO'] = $_SESSION['STUDID'];
		redirect("index.php?q=profile");

    }

		
	}
}


 	# code...
// unset($_SESSION['STUDID']);
// unset($_SESSION['FNAME']);
// unset($_SESSION['LNAME']);
// unset($_SESSION['MI']);
// unset($_SESSION['PADDRESS']);
// unset($_SESSION['SEX']);
// unset($_SESSION['BIRTHDATE']); 
// unset($_SESSION['BIRTHPLACE']);
// unset($_SESSION['RELIGION']);
// unset($_SESSION['CONTACT']);
// unset($_SESSION['CIVILSTATUS']);
// unset($_SESSION['GUARDIAN']);
// unset($_SESSION['GCONTACT']);
// unset($_SESSION['COURSEID']);
// unset($_SESSION['SEMESTER']); 
// unset($_SESSION['USER_NAME']);
// unset($_SESSION['PASS']); 


  

	
 }
}


	$currentyear = date('Y');
	$nextyear =  date('Y') + 1;
	$sy = $currentyear .'-'.$nextyear;
	$_SESSION['SY'] = $sy; 


	$studAuto = New Autonumber();
	$autonum = $studAuto->stud_autonumber();
?>
<?php
	// $currentyear = date('Y');
	// $nextyear =  date('Y') + 1;
	// $sy = $currentyear .'-'.$nextyear;
	// $_SESSION['SY'] = $sy;
	// // $newDate    = Carbon::createFromFormat('Y-m-d',$_SESSION['SY'] )->addYear(1);


	// $studAuto = New Autonumber();
	// $autonum = $studAuto->stud_autonumber();
?>

<form action="" class="form-horizontal well" method="post" >
<!-- <form action="index.php?q=subject" class="form-horizontal well" method="post" > -->
	<div class="table-responsive">
	<div class="col-md-8"><h2>PreRegistration Form</h2></div>
	<div class="col-md-4"><label>Academic Year: <?php echo $_SESSION['SY'] ; ?></label></div>
		<table class="table">
			<tr>
				<td><label>Id</label></td>
				<td >
					<input class="form-control input-md" readonly id="IDNO" name="IDNO" placeholder="Student Id" type="text" value="<?php echo isset($_SESSION['STUDID']) ? $_SESSION['STUDID'] : $autonum->AUTO; ?>">
				</td>
				<td colspan="4"></td>

			</tr>
			<tr>
				<td><label>Firstname</label></td>
				<td>
					<input required="true"   class="form-control input-md" id="FNAME" name="FNAME" placeholder="First Name" type="text" value="<?php echo isset($_SESSION['FNAME']) ? $_SESSION['FNAME'] : ''; ?>">
 				</td>
				<td><label>Lastname</label></td>
				<td colspan="2">
					<input required="true"  class="form-control input-md" id="LNAME" name="LNAME" placeholder="Last Name" type="text" value="<?php echo isset($_SESSION['LNAME']) ? $_SESSION['LNAME'] : ''; ?>">
				</td> 
				<td>
					<input class="form-control input-md" id="MI" name="MI" placeholder="MI"  maxlength="2" type="text" value="<?php echo isset($_SESSION['MI']) ? $_SESSION['MI'] : ''; ?>">
				</td>
			</tr>
			<tr>
				<td ><label>Sex </label></td> 
				<td colspan="2">
					<label>
						<input checked id="optionsRadios1" name="optionsRadios" type="radio" value="Female">Female 
						 <input id="optionsRadios2" name="optionsRadios" type="radio" value="Male"> Male
					</label>
				</td>
				<td ><label>Date of birth</label></td>
				<td colspan="2"> 
				<div class="input-group" >
                  <div class="input-group-addon"> 
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input  required="true" name="BIRTHDATE"  id="BIRTHDATE"  type="text" class="form-control input-md"   data-inputmask="'alias': 'mm/dd/yyyy'" data-mask value="<?php echo isset($_SESSION['BIRTHDATE']) ? $_SESSION['BIRTHDATE'] : ''; ?>">
				   </div>             
				</td>
				 
			</tr>
			<tr><td><label>Place of Birth</label></td>
				<td colspan="5">
				<input required="true"  class="form-control input-md" id="BIRTHPLACE" name="BIRTHPLACE" placeholder="Place of Birth" type="text" value="<?php echo isset($_SESSION['BIRTHPLACE']) ? $_SESSION['BIRTHPLACE'] : ''; ?>">
			   </td>
			</tr>
			<tr>
				<td><label>Nationality</label></td>
				<td colspan="2"><input required="true"  class="form-control input-md" id="NATIONALITY" name="NATIONALITY" placeholder="Nationality" type="text" value="<?php echo isset($_SESSION['CONTACT']) ? $_SESSION['CONTACT'] : ''; ?>">
							</td>
				<td><label>Religion</label></td>
				<td colspan="2"><input  required="true" class="form-control input-md" id="RELIGION" name="RELIGION" placeholder="Religion" type="text" value="<?php echo isset($_SESSION['RELIGION']) ? $_SESSION['RELIGION'] : ''; ?>">
				</td>
				
			</tr>
			<tr>
			<td><label>Contact No.</label></td>
				<td colspan="6"><input required="true"  class="form-control input-md" id="CONTACT" name="CONTACT" placeholder="Contact Number" type="number" maxlength="11" value="<?php echo isset($_SESSION['CONTACT']) ? $_SESSION['CONTACT'] : ''; ?>">
							</td>
				
			</tr>
			<tr>
			<td><label>Course/Year</label></td>
				<td colspan="2">
					
					<select class="form-control input-sm" name="COURSE">
								<?php
								if(isset($_SESSION['COURSEID'])){
									$course = New Course();
  								    $singlecourse = $course->single_course($_SESSION['COURSEID']);
  								    echo '<option value='.$singlecourse->COURSE_ID.' >'.$singlecourse->COURSE_NAME.'-'.$singlecourse->COURSE_LEVEL.' </option>';

								}else{
									echo '<option value="Select">Select</option>';
								}
								
								?>
								<?php 

								$mydb->setQuery("SELECT * FROM `course` WHERE COURSE_LEVEL=1");
								$cur = $mydb->loadResultList();

								foreach ($cur as $result) {
								  echo '<option value='.$result->COURSE_ID.' >'.$result->COURSE_NAME.'-'.$result->COURSE_LEVEL.' </option>';

								}
								?>
				    </select> 


				</td>
				
			 
				<td><label>Civil Status</label></td>
				<td colspan="2">
					<select class="form-control input-sm" name="CIVILSTATUS">
						<option value="<?php echo isset($_SESSION['CIVILSTATUS']) ? $_SESSION['CIVILSTATUS'] : 'Select'; ?>"><?php echo isset($_SESSION['CIVILSTATUS']) ? $_SESSION['CIVILSTATUS'] : 'Select'; ?></option>
						 <option value="Single">Single</option>
						 <option value="Married">Married</option> 
						 <option value="Widow">Widow</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label>Username</label></td>
				<td colspan="2">
				  <input required="true"  class="form-control input-md" id="USER_NAME" name="USER_NAME" placeholder="Username" type="text"value="<?php echo isset($_SESSION['USER_NAME']) ? $_SESSION['USER_NAME'] : ''; ?>">
				</td>
				<td><label>Password</label></td>
				<td colspan="2">
						<input required="true"  class="form-control input-md" id="PASS" name="PASS" placeholder="Password" type="password"value="<?php echo isset($_SESSION['PASS']) ? $_SESSION['PASS'] : ''; ?>">
				</td>
			</tr>
			<!-- ====================== NEWLY ADDED FIELDS ====================== -->
			<tr><td colspan="6"><h4><b>Address Information</b></h4></td></tr>
			<tr>
				<td><label>House No.</label></td>
				<td><input class="form-control input-md" name="HOUSE_NO" placeholder="House No." type="text"></td>
				<td><label>Street</label></td>
				<td><input class="form-control input-md" name="STREET" placeholder="Street" type="text"></td>
				<td><label>Barangay</label></td>
				<td><input class="form-control input-md" name="BARANGAY" placeholder="Barangay" type="text"></td>
			</tr>
			<tr>
				<td><label>City/Municipality</label></td>
				<td><input class="form-control input-md" name="CITY_MUNICIPALITY" placeholder="City or Municipality" type="text"></td>
				<td><label>Province</label></td>
				<td><input class="form-control input-md" name="PROVINCE" placeholder="Province" type="text"></td>
				<td><label>Zip Code</label></td>
				<td><input class="form-control input-md" name="ZIPCODE" placeholder="Zip Code" type="text"></td>
			</tr>

			<tr><td colspan="6"><h4><b>Guardian Information</b></h4></td></tr>
			<tr>
				<td><label>Guardian Name</label></td>
				<td colspan="2"><input class="form-control input-md" id="GUARDIAN" name="GUARDIAN" placeholder="Guardian Name" type="text"></td>
				<td><label>Guardian Contact</label></td>
				<td colspan="2"><input class="form-control input-md" id="GCONTACT" name="GCONTACT" placeholder="Guardian Contact" type="text"></td>
			</tr>
			<tr>
				<td><label>Guardian Occupation</label></td>
				<td colspan="2"><input class="form-control input-md" id="GUARDIAN" name="OCCUPATION" placeholder="Guardian Occupation" type="text"></td>
				<td><label>Relationship</label></td>
				<td colspan="2">
					<select class="form-control input-sm" name="RELATIONSHIP">
						<option value="<?php echo isset($_SESSION['RELATIONSHIP']) ? $_SESSION['RELATIONSHIP'] : 'Select'; ?>"><?php echo isset($_SESSION['RELATIONSHIP']) ? $_SESSION['RELATIONSHIP'] : 'Select'; ?></option>
						 <option value="Mother">Mother</option>
						 <option value="Father">Father</option> 
						 <option value="Guardian">Guardian</option>
					</select>
				</td>
			</tr>

			<tr><td colspan="6"><h4><b>Educational Attainment</b></h4></td></tr>
			<tr>
				<td><label>Level</label></td>
				<td colspan="2">
					<select class="form-control input-sm" name="LEVEL">
						<option value="<?php echo isset($_SESSION['LEVEL']) ? $_SESSION['LEVEL'] : 'Select'; ?>"><?php echo isset($_SESSION['LEVEL']) ? $_SESSION['LEVEL'] : 'Select'; ?></option>
						 <option value="Elementary">Elementary</option>
						 <option value="High School">High School</option> 
						 <option value="Senior High School">Senior High School</option>
					</select>
				</td>
				<td><label>School Name</label></td>
				<td colspan="2"><input class="form-control input-md" name="SCHOOL_NAME" placeholder="SCHOOL_NAME" type="text"></td>
			</tr>
			<tr>
				<td><label>School Address</label></td>
				<td colspan="2"><input class="form-control input-md" name="SCHOOL_ADDRESS" placeholder="SCHOOL_ADDRESS" type="text"></td>
				<td><label>Year Graduated</label></td>
				<td colspan="2"><input class="form-control input-md" name="HS_YEAR" placeholder="Year Graduated" type="text"></td>
			</tr>
			<!-- ====================== END OF NEWLY ADDED FIELDS ====================== -->
 
			
			<tr>
				<td></td>
				<td colspan="5"><button class="btn btn-success btn-lg" name="regsubmit" type="submit">Submit</button></td>
			</tr> 
		</table>
	</div>
</form>