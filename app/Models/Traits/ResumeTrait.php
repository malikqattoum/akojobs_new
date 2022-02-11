<?php


namespace App\Models\Traits;

use App\Models\Resume;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Models\Company;
use App\UserExperience;
use App\UserEducation;
use App\UserSkill;
use App\UserLanguage;
use App\UserTraining;
use App\UserReference;
use Debugbar;
use App\Models\User;
use Illuminate\Support\Facades\DB;
// use App\Models\TimeZone;

trait ResumeTrait
{
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getResumeHtml()
	{
		$out = '';

		if (isset($this->id)) {
			$resumeData = DB::table('resumes')->select('filename')
			->where('user_id',$this->id)
			->first();
			$this->setAttribute('resume',$resumeData->filename??null);
			$resume = $resumeData->filename??null;
			if (file_exists(public_path('storage/'.$resume)) && $resume != null) {
				$out = '';
				$out .= '<a class="btn btn-default"  href="' . \Storage::url($resume). '" target="_blank">';
				$out .= '<i class="icon-attach-2"></i> '.t('Download');
				$out .= '</a>';
				
				return $out;
			} else {
				return "No CV";
			}
		}
		
		return $out;
	}
	
	public function getUserSource()
	{
	    $out = '';
	    
	    if (!empty($this->user_source)) {
	        switch ($this->user_source)
	        {
	            case null:
	            case 1:
	                $out = "Registered";
	                break;
	            case 2:
	                $out = "Referral";
	                break;
	            case 3:
	                $out = "Easy Apply";
	                break;
	            case 4:
	                $out = "Invited";
	                break;
	        }
	        return $out;
	    } else {
	        return "No User Source";
	    }
	    
	    return $out;
	}

	public function getJobRole()
	{
		$out = '';

		if (!empty($this->job_role)) {
			$out = t($this->job_role,[],'roles');
			return $out;
		} else {
			return "No Job Role";
		}
		
		return $out;
	}

	public function getSecondaryJobRole()
	{
		$out = '';

		if (!empty($this->sec_job_role)) {
			$out = t($this->sec_job_role,[],'roles');
			return $out;
		} else {
			return "No Job Role";
		}
		
		return $out;
	}

	public function getIndustry()
	{
		$out = '';

		if (!empty($this->industry)) {
			$out = t($this->industry,[],'industry');
			return $out;
		} else {
			return "No Industry";
		}
		
		return $out;
	}

	public function getResidenceCountry()
	{
		$out = '';

		if (!empty($this->residence_country)) {
			$out = t($this->residence_country,[],'residenceCountry');
			return $out;
		} else {
			return "No Residence Country";
		}
		
		return $out;
	}

	
	public function getPhoneNumber()
	{
		$out = '';
		$countryCode = CountryLocalization::getCountryCodeFromIso($this->country_code);
		if (!empty($this->phone) && strlen($this->phone) <= 10 && !empty($countryCode)) {
			$out = $countryCode.' '.$this->phone;
			return $out;
		} elseif (!empty($this->phone)) {
			$out = $this->phone;
			return $out;
		} else {
			return "No Phone Number";
		}
		
		return $out;
	}

	public function getUserExperience()
	{
		$out = '';

		if (!empty($this->user_experience)) {
			$out = t($this->user_experience,[],'experience');
			return $out;
		} else {
			return "No Experience";
		}
		
		return $out;
	}

	
	public function getUserNote()
	{
		$out = '';

		if (!empty($this->note)) {
			$out = $this->note;
			return $out;
		} else {
			return "No Note";
		}
		
		return $out;
	}

	public function getCurrentJobTitle()
	{
		$out = '';

		if (!empty($this->current_job_title)) {
			$out = $this->current_job_title;
			return $out;
		} else {
			return "No Current job title";
		}
		
		return $out;
	}
	
	public function addNoteModal()
	{
		$length = 32;
			$token = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
			$out = "<p id='noteButton".$this->id."'><a class='btn btn-primary' data-toggle='modal' data-target='#noteModal".$this->id."'>Add Note</a></p>";
			$out .= '<div class="modal fade" id="noteModal'.$this->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
			  <div class="modal-content">
				<div class="modal-header">
				  <h5 class="modal-title" id="exampleModalLabel">Add Note</h5>
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				  </button>
				</div>
				<form id="add_note_form'.$this->id.'" method="post" action="javascript:void(0)">
					<div class="modal-body">

						<div class="alert alert-success" style="display:none" id="msg_div'.$this->id.'">
							<span id="res_message'.$this->id.'"></span>
						</div>
						<div class="form-group">
							<label for="note" style="margin-right:10px">Add Note</label>
							<textarea name="note" rows="5" cols="60" class="form-control" id="addNoteArea'.$this->id.'" placeholder="Please enter note">
							</textarea>
							<span class="text-danger"></span>
						</div>
						<input type="hidden" name="user_id" value="'.$this->id.'" />
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<input type="submit" id="send_form'.$this->id.'" class="btn btn-primary" value="Save changes">
					</div>
				</form>
			  </div>
			</div>
		  </div>
		  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>

		 <script>
		 tinyMCE.execCommand("mceAddEditor", false, "addNoteArea'.$this->id.'"); 
		$("#send_form'.$this->id.'").click(function(e){
				if ($("#add_note_form'.$this->id.'").length > 0) {
					$("#add_note_form'.$this->id.'").validate({
					
					rules: {
					note: {
						required: true,
						maxlength: 500
					},
				
					user_id: {
							required: true,
						},   
					},
					messages: {
						
					name: {
						required: "Please enter note",
						maxlength: "Your last note maxlength should be 50 characters long."
					},
					user_id: {
						required: "The User Id is Required",
					},  
					},
					submitHandler: function(form) {
					$.ajaxSetup({
						headers: {
							\'X-CSRF-TOKEN\': $(\'meta[name="csrf-token"]\').attr(\'content\')
						}
					});
					$(\'#send_form'.$this->id.'\').html(\'Sending..\');
					console.log($("#add_note_form'.$this->id.'").serialize());
					$.ajax({
						url: \''.env('APP_URL').'/admin/users/add-note\' ,
						type: "POST",
						data: $("#add_note_form'.$this->id.'").serialize(),
						success: function( response ) {
							$(\'#send_form'.$this->id.'\').html(\'Submit\');
							$(\'#res_message'.$this->id.'\').show();
							$(\'#res_message'.$this->id.'\').html(response.msg);
							$(\'#msg_div'.$this->id.'\').css(\'display\',\'block\');
							$("#noteButton"+response.user_id).html(response.note);
							$("#noteButton"+response.user_id).parent().append(`<a data-toggle=\'modal\' data-target=\'#editNoteModal`+response.user_id+`\' class=\'btn btn-xs btn-warning\'>
							<i class=\'fa fa-edit\'></i> Edit</a>
							<a data-toggle=\'modal\' data-target=\'#viewNoteModal`+response.user_id+`\' class=\'btn btn-xs btn-default\' style=\'margin-top:5px\'>
							<i class=\'fa fa-edit\'></i> View</a>

								<div class="modal fade" id="viewNoteModal`+response.user_id+`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
			  <div class="modal-content">
				<div class="modal-header">
				  <h2 class="modal-title" id="exampleModalLabel">View Note</h2>
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				  </button>
				</div>
				<div style="min-height:100px">
					<h4 style="margin-left:10px">Your Note is: </h4>
					<p id="viewNoteContent`+response.user_id+`" class="text-center" style="padding:10px">`+response.note+`</p>
				</div>
			  </div>
			</div>
		  </div>
		<div class="modal fade" id="editNoteModal`+response.user_id+`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
			  <div class="modal-content">
				<div class="modal-header">
				  <h5 class="modal-title" id="exampleModalLabel">Edit Note</h5>
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				  </button>
				</div>
				<form id="edit_note_form`+response.user_id+`" method="post" action="javascript:void(0)">
					<div class="modal-body">

						<div class="alert alert-success" style="display:none" id="edit_msg_div`+response.user_id+`">
							<span id="edit_res_message`+response.user_id+`"></span>
						</div>
						<div class="form-group">
							<label for="note" style="margin-right:10px">Note</label>
							<textarea name="note" rows="5" cols="60" class="form-control" id="editNoteArea`+response.user_id+`" placeholder="Please enter note">
								`+response.note+`
							</textarea>
							<span class="text-danger"></span>
						</div>
						<input type="hidden" name="user_id" value="`+response.user_id+`" />
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<input type="submit" id="edit_send_form`+response.user_id+`" class="btn btn-primary" value="Save changes">
					</div>
				</form>
			  </div>
			</div>
		  </div>`);
		tinyMCE.execCommand("mceAddEditor", false, "editNoteArea'.$this->id.'"); 
		$("#edit_send_form"+response.user_id).click(function(e){
				if ($("#edit_note_form"+response.user_id).length > 0) {
					$("#edit_note_form"+response.user_id).validate({
					
					rules: {
					note: {
						required: true,
						maxlength: 500
					},
				
					user_id: {
							required: true,
						},   
					},
					messages: {
						
					name: {
						required: "Please enter note",
						maxlength: "Your last note maxlength should be 50 characters long."
					},
					user_id: {
						required: "The User Id is Required",
					},  
					},
					submitHandler: function(form) {
					$.ajaxSetup({
						headers: {
							\'X-CSRF-TOKEN\': $(\'meta[name="csrf-token"]\').attr(\'content\')
						}
					});
					$("#edit_send_form"+response.user_id).html(\'Sending..\');
					$.ajax({
						url: \''.env('APP_URL').'/admin/users/add-note\' ,
						type: "POST",
						data: $("#edit_note_form"+response.user_id).serialize(),
						success: function( response ) {
							$("#edit_send_form"+response.user_id).html(\'Submit\');
							$("#edit_res_message"+response.user_id).show();
							$("#edit_res_message"+response.user_id).html(response.msg);
							$("#edit_msg_div"+response.user_id).css(\'display\',\'block\');
							if(response.note.length > 55)
								var subnote = response.note.substring(0,55)+"...";
							else
								var subnote = response.note;
							$("#noteButton"+response.user_id).html(subnote);
							$("#noteButton"+response.user_id).next().remove();
							$("#fullNote"+response.user_id).html(response.note);
							$("#editNoteArea"+response.user_id).html(response.note);
							$("#viewNoteContent"+response.user_id).html(response.note);
							document.getElementById("edit_note_form"+response.user_id).reset(); 
							setTimeout(function(){
							$("#edit_res_message"+response.user_id).hide();
							$("#edit_msg_div"+response.user_id).hide();
							},10000);
						}
					});
					}
				})
				}
			});
							$("#fullNote"+response.user_id).html(response.note);
							document.getElementById("add_note_form'.$this->id.'").reset(); 
							setTimeout(function(){
							$(\'#res_message'.$this->id.'\').hide();
							$(\'#msg_div'.$this->id.'\').hide();
							},10000);
						}
					});
					}
				})
				}
			});
		 </script>
		 
		  ';
		  return $out;
	}

	public function getNote()
	{
		$out = '';

		if (!empty($this->note)) {
			if(strlen($this->note) > 55)
				$out = "<p id='noteButton".$this->id."'>".mb_substr($this->note, 0, 55).'...</p>';
			else
				$out = "<p id='noteButton".$this->id."'>".$this->note."</p>";
			$out .= "<a data-toggle='modal' data-target='#editNoteModal".$this->id."' class='btn btn-xs btn-warning'>
			<i class='fa fa-edit'></i> Edit</a>";
			$out .="<a data-toggle='modal' data-target='#viewNoteModal".$this->id."' class='btn btn-xs btn-default' style='margin-top:5px'>
			<i class='fa fa-edit'></i> View</a>";
			$out .= '<div class="modal fade" id="viewNoteModal'.$this->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
			  <div class="modal-content">
				<div class="modal-header">
				  <h2 class="modal-title" id="exampleModalLabel">View Note</h2>
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				  </button>
				</div>
				<div style="min-height:100px">
					<h4 style="margin-left:10px">Your Note is: </h4>
					<p id="viewNoteContent'.$this->id.'" class="text-center" style="padding:10px">'.$this->note.'</p>
				</div>
			  </div>
			</div>
		  </div>';
			$out .= '<div class="modal fade" id="editNoteModal'.$this->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
			  <div class="modal-content">
				<div class="modal-header">
				  <h5 class="modal-title" id="exampleModalLabel">Edit Note</h5>
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				  </button>
				</div>
				<form id="edit_note_form'.$this->id.'" method="post" action="javascript:void(0)">
					<div class="modal-body">

						<div class="alert alert-success" style="display:none" id="edit_msg_div'.$this->id.'">
							<span id="edit_res_message'.$this->id.'"></span>
						</div>
						<div class="form-group">
							<label for="note" style="margin-right:10px">Note</label>
							<textarea name="note" rows="5" cols="60" class="form-control" id="editNoteArea'.$this->id.'" placeholder="Please enter note">
								'.$this->note.'
							</textarea>
							<span class="text-danger"></span>
						</div>
						<input type="hidden" name="user_id" value="'.$this->id.'" />
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<input type="submit" id="edit_send_form'.$this->id.'" class="btn btn-primary" value="Save changes">
					</div>
				</form>
			  </div>
			</div>
		  </div>
		  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>

		<script>
		tinyMCE.execCommand("mceAddEditor", false, "editNoteArea'.$this->id.'"); 
		$("#edit_send_form'.$this->id.'").click(function(e){
				if ($("#edit_note_form'.$this->id.'").length > 0) {
					$("#edit_note_form'.$this->id.'").validate({
					
					rules: {
					note: {
						required: true,
						maxlength: 500
					},
				
					user_id: {
							required: true,
						},   
					},
					messages: {
						
					name: {
						required: "Please enter note",
						maxlength: "Your last note maxlength should be 50 characters long."
					},
					user_id: {
						required: "The User Id is Required",
					},  
					},
					submitHandler: function(form) {
					$.ajaxSetup({
						headers: {
							\'X-CSRF-TOKEN\': $(\'meta[name="csrf-token"]\').attr(\'content\')
						}
					});
					$(\'#edit_send_form'.$this->id.'\').html(\'Sending..\');
					console.log($("#edit_note_form'.$this->id.'").serialize());
					$.ajax({
						url: \''.env('APP_URL').'/admin/users/add-note\' ,
						type: "POST",
						data: $("#edit_note_form'.$this->id.'").serialize(),
						success: function( response ) {
							$(\'#edit_send_form'.$this->id.'\').html(\'Submit\');
							$(\'#edit_res_message'.$this->id.'\').show();
							$(\'#edit_res_message'.$this->id.'\').html(response.msg);
							$(\'#edit_msg_div'.$this->id.'\').css(\'display\',\'block\');
							if(response.note.length > 55)
								var subnote = response.note.substring(0,55)+"...";
							else
								var subnote = response.note;
							$("#noteButton"+response.user_id).html(subnote);
							$("#noteButton"+response.user_id).next().remove();
							$("#fullNote"+response.user_id).html(response.note);
							$("#editNoteArea"+response.user_id).html(response.note);
							$("#viewNoteContent"+response.user_id).html(response.note);
							document.getElementById("edit_note_form'.$this->id.'").reset(); 
							setTimeout(function(){
							$(\'#edit_res_message'.$this->id.'\').hide();
							$(\'#edit_msg_div'.$this->id.'\').hide();
							},10000);
						}
					});
					}
				})
				}
			});
		 </script>
		 
		  ';
			return $out;
		} else {
			return $this->addNoteModal();
		}
		
		return $out;
	}

	public function getCompanyName()
	{
		$out = '';
		$company = Company::where('user_id', $this->id)->first();
		if(empty($company) && !empty($this->company_id))
		{
		    $company = Company::where('id', $this->company_id)->first();
		}
		if (!empty($company)) {
			$out = $company->name;
			return $out;
		} else {
			return "No Company name";
		}
		
		return $out;
	}

	public function getCompanyLocation()
	{
		$out = '';
		$company = Company::where('user_id', $this->id)->first();
		if(empty($company) && !empty($this->company_id))
		{
		    $company = Company::where('id', $this->company_id)->first();
		}
		if (!empty($company)) {
			$out = ($company->company_location)?t($company->company_location,[],'residenceCountry'):'No company location';
			return $out;
		} else {
			return "No company location";
		}
		
		return $out;
	}

	public function getProfileStatus()
	{
		$out = '';
		$userBirthday = $this->birthday;
		$userNationality = $this->nationality;
		$userResidenceCountry = $this->residence_country;
		$userEmail = $this->email;
		$userPhone = $this->phone;
		$userPreferedJobTitle = $this->preferred_job_title;
		$userTargetJobLocation = $this->trgt_job_location;
		$userExperience = UserExperience::where('user_id', $this->id)->first();
		$userSkill = UserSkill::where('user_id', $this->id)->first();
		$userLanguage = UserLanguage::where('user_id', $this->id)->first();
		$userTraining = UserTraining::where('user_id', $this->id)->first();
		$userReference = UserReference::where('user_id', $this->id)->first();

		if(empty($userBirthday) && empty($userResidenceCountry) && empty($userNationality))
			$out .= 'Personal information, ';
		if(empty($userEmail) && empty($userPhone))
			$out .= 'Contact information, ';
		if(empty($userPreferedJobTitle) && empty($userTargetJobLocation))
			$out .= 'Prefered job, ';
		if(empty($userExperience))
			$out .= 'Experience, ';
		if(empty($userSkill))
			$out .= 'Skill, ';
		if(empty($userLanguage))
			$out .= 'Language, ';
		if(empty($userTraining))
			$out .= 'Training, ';
		if(empty($userReference))
			$out .= 'Preference, ';
		
		if(!empty($out))
			return 'Missing sections ('.$out.')';
		else
			return 'Completed';
	}

	public function getUserProfileLink()
	{
		if($this->user_type_id == 2)
		{
			$out = '<a href="/profile/employer-view/'.$this->id.'">';
			$out .= 'View profile</a>';
		}
		else {
			$out = 'Employers don\'t have profiles';
		}
		return $out;
	}

	// public function getFullNote()
	// {
	// 	$out = '';

	// 	if (!empty($this->note)) {
	// 		$out = $this->note;
	// 		return $out;
	// 	} else {
	// 		$out = '<p id="fullNote'.$this->id.'">No Note</p>';
	// 	}
		
	// 	return $out;
	// }

}
