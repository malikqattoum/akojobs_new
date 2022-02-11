<?php


namespace App\Http\Controllers\Auth\Traits;


use App\Notifications\EmailVerification;
use Illuminate\Support\Facades\Request;
use Larapen\LaravelLocalization\Facades\LaravelLocalization;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Message;
use App\Mail\completeProfileMail;
use App\Mail\uploadResumeMail;
use App\Mail\CustomMail;
use App\Mail\CompanyInviteMail;
use App\Models\PackageRequest;
use App\trackedEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request AS VerficationRequest;

trait EmailVerificationTrait
{
	/**
	 * Send verification message
	 *
	 * @param $entity
	 * @param bool $displayFlashMessage
	 * @return bool
	 */
	public function sendVerificationEmail($entity, $displayFlashMessage = true)
	{
		// Get Entity
		$entityRef = $this->getEntityRef();
		if (empty($entity) || empty($entityRef)) {
            $message = t("Entity ID not found.");
            
            if (isFromAdminPanel()) {
                Alert::error($message)->flash();
            } else {
                flash($message)->error();
            }
            
			return false;
		}
		
		// Send Confirmation Email
		try {
			if (request()->filled('locale')) {
				$locale = (array_key_exists(request()->get('locale'), LaravelLocalization::getSupportedLocales()))
					? request()->get('locale')
					: null;
				
				if (!empty($locale)) {
					$entity->notify((new EmailVerification($entity, $entityRef))->locale($locale));
				} else {
					$entity->notify(new EmailVerification($entity, $entityRef));
				}
			} else {
				$entity->notify(new EmailVerification($entity, $entityRef));
			}
			
			if ($displayFlashMessage) {
				$message = t("An activation link has been sent to you to verify your email address.");
				flash($message)->success();
			}
			
			session(['verificationEmailSent' => true]);
			
			return true;
		} catch (\Exception $e) {
			$message = changeWhiteSpace($e->getMessage());
            if (isFromAdminPanel()) {
                Alert::error($message)->flash();
            } else {
                flash($message)->error();
            }
		}
		
		return false;
	}
	
	/**
	 * Show the ReSend Verification Message Link
	 *
	 * @param $entity
	 * @param $entityRefId
	 * @return bool
	 */
	public function showReSendVerificationEmailLink($entity, $entityRefId)
	{
		// Get Entity
		$entityRef = $this->getEntityRef($entityRefId);
		if (empty($entity) || empty($entityRef)) {
			return false;
		}
		
		// Show ReSend Verification Email Link
		if (session()->has('verificationEmailSent')) {
			$message = t("Resend the verification message to verify your email address.");
			$message .= ' <a href="' . lurl('verify/' . $entityRef['slug'] . '/' . $entity->id . '/resend/email') . '" class="btn btn-warning">' . t("Re-send") . '</a>';
			flash($message)->warning();
		}
		
		return true;
	}
	
	/**
	 * URL: Re-Send the verification message
	 *
	 * @param $entityId
	 * @return \Illuminate\Http\RedirectResponse
	 */
    public function reSendVerificationEmail($entityId)
    {
        // Non-admin data resources
        $entityRefId = getSegment(2);
        
        // Admin data resources
        if (isFromAdminPanel()) {
            $entityRefId = Request::segment(3);
        }
        
        // Keep Success Message If exists
        if (session()->has('message')) {
            session()->keep(['message']);
        }
        
        // Get Entity
        $entityRef = $this->getEntityRef($entityRefId);
        if (empty($entityRef)) {
            $message = t("Entity ID not found.");
            
            if (isFromAdminPanel()) {
                Alert::error($message)->flash();
            } else {
                flash($message)->error();
            }
            
            return back();
        }
        
        // Get Entity by Id
        $model = $entityRef['namespace'];
        $entity = $model::withoutGlobalScopes($entityRef['scopes'])->where('id', $entityId)->first();
        if (empty($entity)) {
            $message = t("Entity ID not found.");
            
            if (isFromAdminPanel()) {
                Alert::error($message)->flash();
            } else {
                flash($message)->error();
            }
            
            return back();
        }
        
        // Check if the Email is already verified
        if ($entity->verified_email == 1 || isDemo()) {
            if (isDemo()) {
                $message = t("This feature has been turned off in demo mode.");
				if (isFromAdminPanel()) {
					Alert::info($message)->flash();
				} else {
					flash($message)->info();
				}
            } else {
                $message = t("Your :field is already verified.", ['field' => t('Email Address')]);
				if (isFromAdminPanel()) {
					Alert::error($message)->flash();
				} else {
					flash($message)->error();
				}
            }
            
            // Remove Notification Trigger
            $this->clearEmailSession();
            
            return back();
        }
        
        // Re-Send the confirmation
        if ($this->sendVerificationEmail($entity, false)) {
            if (isFromAdminPanel()) {
                $message = 'The activation link has been sent to the user to verify his email address.';
                Alert::success($message)->flash();
            } else {
                $message = t("The activation link has been sent to you to verify your email address.");
                flash($message)->success();
            }
            
            // Remove Notification Trigger
            $this->clearEmailSession();
        }
        
        return back();
    }

    public function ajaxReSendVerificationEmail(Request $request)
    {
        $message = [];
        if(request()->ajax())
        {
            if(isset($_POST['entryIds']) && !empty($_POST['entryIds']))
            {
                foreach($_POST['entryIds'] as $entityId)
                {
                    // Non-admin data resources
                    $entityRefId = getSegment(2);
                    
                    // Admin data resources
                    if (isFromAdminPanel()) {
                        $entityRefId = Request::segment(3);
                    }
                    
                    // Keep Success Message If exists
                    if (session()->has('message')) {
                        session()->keep(['message']);
                    }
                    
                    // Get Entity
                    $entityRef = $this->getEntityRef($entityRefId);
                    if (empty($entityRef)) {
                        $message[] = t("Entity ID not found.");
                        
                        continue;
                    }
                    
                    // Get Entity by Id
                    $model = $entityRef['namespace'];
                    $entity = $model::withoutGlobalScopes($entityRef['scopes'])->where('id', $entityId)->first();
                    if (empty($entity)) {
                        $message[] = t("Entity ID not found.");
                        
                        continue;
                    }
                    
                    // Check if the Email is already verified
                    if ($entity->verified_email == 1 || isDemo()) {
                        if (isDemo()) {
                            $message[] = t("This feature has been turned off in demo mode.");
                        } else {
                            $message[] = t("Your :field is already verified.", ['field' => t('Email Address')]);
                        }
                        
                        // Remove Notification Trigger
                        $this->clearEmailSession();
                        
                        continue;
                    }
                    
                    // Re-Send the confirmation
                    if ($this->sendVerificationEmail($entity, false)) {
                        if (isFromAdminPanel()) {
                            $message[] = 'The activation link has been sent to the user to verify his email address.';
                        } else {
                            $message[] = t("The activation link has been sent to you to verify your email address.");
                        }
                        
                        // Remove Notification Trigger
                        $this->clearEmailSession();
                    }
                }
            }
        }
        
        return response()->json($message);
    }

    public function ajaxSendCompleteProfileEmail(Request $request)
    {
        $message = [];
        if(request()->ajax())
        {
            if(isset($_POST['entryIds']) && !empty($_POST['entryIds']))
            {
                foreach($_POST['entryIds'] as $entityId)
                {
                    $trackedEmail = new trackedEmail();
                    $trackedEmail->from_user_id = auth()->user()->id;
                    $trackedEmail->to_user_id = $entityId;
                    $trackedEmail->subject = "Complete Profile Mail";
                    $trackedEmail->message = "Welcome to akoJobs
                    Inorder to let the employers to see your profile, you should complete your profile";
                    $trackedEmail->save();

                    $user = new User();
                    $user = User::findOrFail($entityId);
                    Mail::to($user->email)->send(new completeProfileMail($user->pref_lang));
                }
                $message[] = "Complete profile emails have sent successfuly";
            }
        }
        
        return response()->json($message);
    }

    public function ajaxSendUploadResumeEmail(Request $request)
    {
        $message = [];
        if(request()->ajax())
        {
            if(isset($_POST['entryIds']) && !empty($_POST['entryIds']))
            {
                foreach($_POST['entryIds'] as $entityId)
                {
                    $trackedEmail = new trackedEmail();
                    $trackedEmail->from_user_id = auth()->user()->id;
                    $trackedEmail->to_user_id = $entityId;
                    $trackedEmail->subject = "Upload Resume Mail";
                    $trackedEmail->message = "Welcome to akoJobs
                    Inorder to let the employers to reach you, Please upload your resume";
                    $trackedEmail->save();

                    $user = new User();
                    $user = User::findOrFail($entityId);
                    Mail::to($user->email)->send(new uploadResumeMail($user->pref_lang));
                }
                $message[] = "Upload resume emails have sent successfuly";
            }
        }
        
        return response()->json($message);
    }

    
    public function ajaxSendCustomEmail(Request $request)
    {
        $message = [];
        if(request()->ajax())
        {
            if(isset($_POST['entryIds']) && !empty($_POST['entryIds']))
            {
                if(!is_array($_POST['entryIds']))
                    $_POST['entryIds'] = explode(",", $_POST['entryIds']);
                    
                foreach($_POST['entryIds'] as $entityId)
                {
                    if(isset($_POST['applicants']) && $_POST['applicants'] == 1)
                    {
                        $conversation = Message::findOrFail($entityId);
                        $email = $conversation->from_email;
                    }
                    else
                    {
                        $user = new User();
                        $user = User::findOrFail(trim($entityId));
                        $email = $user->email;
                    }
                    //return response()->json(json_encode($user));
                    Mail::to($email)->send(new CustomMail($_POST));

                    $trackedEmail = new trackedEmail();
                    $trackedEmail->from_user_id = auth()->user()->id;
                    $trackedEmail->to_user_id = $entityId;
                    $trackedEmail->subject = $_POST["email_subject"];
                    $trackedEmail->message = $_POST["email_message"];
                    $trackedEmail->save();
                }
                $message[] = "Custom emails have sent successfuly";
            }
        }
        
        return response()->json($message);
    }

    public function ajaxSendInvoiceEmail(Request $request)
    {
        $message = [];
        if(request()->ajax())
        {
            if(isset($_POST['entryIds']) && !empty($_POST['entryIds']))
            {
                if(!is_array($_POST['entryIds']))
                    $_POST['entryIds'] = explode(",", $_POST['entryIds']);
                    
                foreach($_POST['entryIds'] as $entityId)
                {
                    $packageRequest = PackageRequest::findOrFail(trim($entityId));
                    $user = User::findOrFail($packageRequest->user_id);
                    Mail::to($user->email)->send(new CustomMail($_POST));

                    $trackedEmail = new trackedEmail();
                    $trackedEmail->from_user_id = auth()->user()->id;
                    $trackedEmail->to_user_id = $entityId;
                    $trackedEmail->subject = $_POST["email_subject"];
                    $trackedEmail->message = $_POST["email_message"];
                    $trackedEmail->save();
                }
                $message[] = "Invoice emails have sent successfuly";
            }
        }
        
        return response()->json($message);
    }
    
    
    /**
     * Remove Notification Trigger (by clearing the sessions)
     */
    private function clearEmailSession()
    {
        if (session()->has('verificationEmailSent')) {
            session()->forget('verificationEmailSent');
        }
    }

    public function sendInviteCompanyMemberEmail(VerficationRequest $request)
    {
        $validator = Validator::make($request->all(), [
			'emails' => 'required',
            'companyId' => 'required'
		 ]);
 
		 if ($validator->fails()) {
			 flash("Please add your company members emails")->error();
		 } else {
            $data = $request->all();
            if(isset($data['emails']) && !empty($data['emails']))
            {
                if(!is_array($data['emails']))
                    $data['emails'] = explode(",", $data['emails']);
                    
                foreach($data['emails'] as $email)
                {
                    $email = trim($email);
                    Mail::to($email)->send(new CompanyInviteMail($data['companyId']));
                }
                flash("The invitation has successfully sent")->success();
            }
		 }
        
        return redirect(config('app.locale') . '/account/company-invitation');
    }
}
