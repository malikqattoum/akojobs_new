<?php


namespace App\Http\Controllers\Account;

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\UserRequest;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\UserExperience;
use App\UserEducation;
use App\UserSkill;
use App\UserLanguage;
use App\UserTraining;
use App\UserReference;
use App\UserVideo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\Debugbar\Facade as Debugbar;
use Torann\LaravelMetaTags\Facades\MetaTag;


class ProfileController extends AccountBaseController
{
	use VerificationTrait;

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */

    public function editProfileHead()
	{
        $user = auth()->user();
        return view('account.forms.editProfileHead')->with('userPhoto', $user->photo);
	}

    // Upload Profile Image
	public function updateProfileHead(Request $request)
	{
        // Get current user
        $userId = Auth::id();
        $user = User::findOrFail($userId);
        request()->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $imageName = time().'.'.request()->photo->getClientOriginalExtension();
        $destination_path = 'avatars/' . strtolower($user->country_code) . '/' . $user->id;
        Storage::makeDirectory($destination_path);
        request()->photo->move(public_path().'/'.$destination_path, $imageName);
        //dd($user->photo);
        // Fill user model
        $user->fill([
            'photo' => '/'.$imageName
        ]);
        
        $user->save();
        return redirect(config('app.locale') . '/account')
            ->with('success','You have successfully upload image.')
            ->with('image','images/'.$imageName);
    }
    
    public function editPersonalInfo()
	{
        return view('account.forms.editPersonalInfo');
    }
    
    public function updatePersonalInfo(Request $request)
	{
        // Get current user
        $userId = Auth::id();
        $user = User::findOrFail($userId);
        request()->validate([
            'name' => 'required|max:100',
            'birthday' => 'required',
            'gender_id' => 'required',
            'nationality' => 'required',
            'residence_country'=>'required',
        ]);
        
        $user->fill([
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'birthday' => $request->birthday,
            'gender_id' => $request->gender_id,
            'nationality' => $request->nationality,
            'residence_country' => $request->residence_country,
            'martial_status' => $request->martial_status,
            'num_dependents' => $request->num_dependents,
        ]);
        
        $user->save();
        return redirect(config('app.locale') . '/account')
            ->with('success','Your data have updated successfully.');
    }
    
    public function editContactInfo()
	{
        return view('account.forms.editContactInfo');
    }
    
    public function updateContactInfo(Request $request)
	{
        // Get current user
        $userId = Auth::id();
        $user = User::findOrFail($userId);

        if(empty($request->phone))
            $request->phone = $user->phone;

        request()->validate([
            'email' => 'required|email|max:100',
            'phone' => 'max:20',
        ]);
        
        if(!empty($request->country_code))
            $fullPhone = '+'.$request->country_code.$request->phone;
        else
            $fullPhone = $request->phone;

        $user->fill([
            'email' => $request->email,
            'phone' => $fullPhone,
        ]);
        
        $user->save();
        return redirect(config('app.locale') . '/account')
            ->with('success','Your data have updated successfully.');
    }
    
    public function editPreferredJob()
	{
        $preferredJobTitles = explode(',', Auth()->user()->preferred_job_title);
        return view('account.forms.editPreferredJob')->with('preferredJobTitles', $preferredJobTitles);
    }
    
    public function updatePreferredJob(Request $request)
	{
        // Get current user
        $userId = Auth::id();
        $user = User::findOrFail($userId);

        if(empty($request->trgt_month_sal))
            $request->trgt_month_sal = $user->trgt_month_sal;

        request()->validate([
            'preferred_job_title' => 'required|max:250',
            'trgt_job_location' => 'required',
            'trgt_month_sal' => 'numeric',
        ]);

        if(!empty($request->currency))
            $targetSalary = $request->trgt_month_sal.' '.$request->currency;
        else
            $targetSalary = $request->trgt_month_sal.' USD';
        
        $request->preferred_job_title = implode(',',$request->preferred_job_title);
        $user->fill([
            'preferred_job_title' => $request->preferred_job_title,
            'trgt_job_location' => $request->trgt_job_location,
            'trgt_city' => $request->trgt_city,
            'trgt_month_sal' => $targetSalary,
        ]);
        
        $user->save();
        return redirect(config('app.locale') . '/account')
            ->with('success','Your data have updated successfully.');
    }

    public function createUserExperience()
	{
        return view('account.forms.createUserExperience');
    }
    
    public function storeUserExperience(Request $request)
	{
        // Get current user
        $userId = Auth::id();

        request()->validate([
            'exp_title' => 'required|max:100',
            'company_name' => 'required|max:100',
            'exp_country' => 'required',
            'exp_tasks' => 'required|min:10',
        ]);
        $request->from_date = date('Y-m-d', strtotime($request->from_date));
        if(empty($request->present))
            $request->to_date = date('Y-m-d', strtotime($request->to_date));
        else
            $request->to_date = null;

        $userExperience = new UserExperience();
        $userExperience->exp_title = $request->exp_title;
        $userExperience->exp_country = $request->exp_country;
        $userExperience->from_date = $request->from_date;
        $userExperience->to_date = $request->to_date;
        $userExperience->exp_tasks = $request->exp_tasks;
        $userExperience->user_id = $userId;
        $userExperience->company_name = $request->company_name;
        $userExperience->present = $request->present;
        $userExperience->save();

        return redirect(config('app.locale') . '/account')
            ->with('success','New experience has been added successfully.');
    }

    public function editUserExperience($id)
	{
        $userExperience = UserExperience::findOrFail($id);
        Debugbar::info($userExperience);
        return view('account.forms.editUserExperience')->with('userExperience', $userExperience);
    }

    public function updateUserExperience(Request $request, $id)
    {
         request()->validate([
            'exp_title' => 'required|max:100',
            'company_name' => 'required|max:100',
            'exp_country' => 'required',
            'exp_tasks' => 'required|min:10',
        ]);
        $request->from_date = date('Y-m-d', strtotime($request->from_date));
        if(empty($request->present))
            $request->to_date = date('Y-m-d', strtotime($request->to_date));
        else
            $request->to_date = null;

         $userExperience = UserExperience::findOrFail($id);
         $userExperience->exp_title = $request->exp_title;
         $userExperience->exp_country = $request->exp_country;
         $userExperience->from_date = $request->from_date;
         $userExperience->to_date = $request->to_date;
         $userExperience->exp_tasks = $request->exp_tasks;
         $userExperience->company_name = $request->company_name;
         $userExperience->present = $request->present;
         $userExperience->save();

         return redirect(config('app.locale') . '/account')
             ->with('success','Your Experience has been updated successfully.');
    }

    public function deleteUserExperience($id)
    {
        $userExperience = UserExperience::find($id);
        $userExperience->delete();

        return redirect(config('app.locale') . '/account')
        ->with('success','Experience has deleted successfully.');
    }

    public function editTotalExperience()
	{
        return view('account.forms.editTotalExperience');
    }

    public function updateTotalExperience(Request $request)
	{
        $userId = Auth::id();
        $user = User::findOrFail($userId);
        request()->validate([
            'total_exp' => 'required|max:100',
        ]);

        $user->fill([
            'total_exp' => $request->total_exp,
        ]);
        $user->save();
        return redirect(config('app.locale') . '/account')
        ->with('success','Your total experience has been updated successfully.');
    }


    public function createUserEducation()
	{
        return view('account.forms.createUserEducation');
    }
    
    public function storeUserEducation(Request $request)
	{
        // Get current user
        $userId = Auth::id();

        request()->validate([
            'edu_degree' => 'required',
            'edu_institution' => 'required|max:200',
        ]);
        if(empty($request->on_going))
            $request->edu_grad_date = date('Y-m-d', strtotime($request->edu_grad_date));
        else
            $request->edu_grad_date = null;
        

        $UserEducation = new UserEducation();
        $UserEducation->edu_degree = $request->edu_degree;
        $UserEducation->edu_institution = $request->edu_institution;
        $UserEducation->edu_grad_date = $request->edu_grad_date;
        $UserEducation->user_id = $userId;
        $UserEducation->on_going = $request->on_going;
        $UserEducation->save();

        return redirect(config('app.locale') . '/account')
            ->with('success','New education has been added successfully.');
    }

    public function editUserEducation($id)
	{
        $userEducation = UserEducation::findOrFail($id);
        return view('account.forms.editUserEducation')->with('userEducation', $userEducation);
    }

    public function updateUserEducation(Request $request, $id)
    {
         request()->validate([
            'edu_degree' => 'required',
            'edu_institution' => 'required|max:200',
        ]);
        if(empty($request->on_going))
            $request->edu_grad_date = date('Y-m-d', strtotime($request->edu_grad_date));
        else
            $request->edu_grad_date = null;

        $UserEducation = UserEducation::findOrFail($id);
        $UserEducation->edu_degree = $request->edu_degree;
        $UserEducation->edu_institution = $request->edu_institution;
        $UserEducation->edu_grad_date = $request->edu_grad_date;
        $UserEducation->on_going = $request->on_going;
        $UserEducation->save();

         return redirect(config('app.locale') . '/account')
             ->with('success','Your education has been updated successfully.');
    }

    public function deleteUserEducation($id)
    {
        $userEducation = UserEducation::find($id);
        $userEducation->delete();

        return redirect('account')
        ->with('success','Education has deleted successfully.');
    }


    public function createUserSkill()
	{
        return view('account.forms.createUserSkill');
    }
    
    public function storeUserSkill(Request $request)
	{
        // Get current user
        $userId = Auth::id();

        request()->validate([
            'skill_name' => 'required|max:200',
            'skill_level' => 'required',
        ]);

        $userSkill = new UserSkill();
        $userSkill->skill_name = $request->skill_name;
        $userSkill->skill_level = $request->skill_level;
        $userSkill->user_id = $userId;
        $userSkill->save();

        return redirect(config('app.locale') . '/account')
            ->with('success','New skill has been added successfully.');
    }

    public function editUserSkill($id)
	{
        $userSkill = UserSkill::findOrFail($id);
        return view('account.forms.editUserSkill')->with('userSkill', $userSkill);
    }

    public function updateUserSkill(Request $request, $id)
    {
         request()->validate([
            'skill_name' => 'required|max:200',
            'skill_level' => 'required',
        ]);

        $userSkill = UserSkill::findOrFail($id);
        $userSkill->skill_name = $request->skill_name;
        $userSkill->skill_level = $request->skill_level;
        $userSkill->save();

         return redirect(config('app.locale') . '/account')
             ->with('success','Your skill has been updated successfully.');
    }

    public function deleteUserSkill($id)
    {
        $userSkill = UserSkill::find($id);
        $userSkill->delete();

        return redirect(config('app.locale') . '/account')
        ->with('success','Skill has deleted successfully.');
    }

    public function createUserLang()
	{
        return view('account.forms.createUserLanguage');
    }
    
    public function storeUserLang(Request $request)
	{
        // Get current user
        $userId = Auth::id();

        request()->validate([
            'language' => 'required',
            'lang_level' => 'required',
        ]);

        $userLang = new UserLanguage();
        $userLang->language = $request->language;
        $userLang->lang_level = $request->lang_level;
        $userLang->user_id = $userId;
        $userLang->save();

        return redirect(config('app.locale') . '/account')
            ->with('success','New language has been added successfully.');
    }

    public function editUserLang($id)
	{
        $userLang = UserLanguage::findOrFail($id);
        return view('account.forms.editUserLanguage')->with('userLang', $userLang);
    }

    public function updateUserLang(Request $request, $id)
    {
         request()->validate([
            'language' => 'required',
            'lang_level' => 'required',
        ]);

        $userLang = UserLanguage::findOrFail($id);
        $userLang->language = $request->language;
        $userLang->lang_level = $request->lang_level;
        $userLang->save();

         return redirect(config('app.locale') . '/account')
             ->with('success','Your language has been updated successfully.');
    }

    public function deleteUserLang($id)
    {
        $userLang = UserLanguage::find($id);
        $userLang->delete();

        return redirect(config('app.locale') . '/account')
        ->with('success','Language has deleted successfully.');
    }

    public function createUserTraining()
	{
        return view('account.forms.createUserTraining');
    }
    
    public function storeUserTraining(Request $request)
	{
        // Get current user
        $userId = Auth::id();

        request()->validate([
            'training_name' => 'required|max:200',
            'training_institution' => 'required|max:200',
        ]);

        $userTraining = new UserTraining();
        $userTraining->training_name = $request->training_name;
        $userTraining->training_institution = $request->training_institution;
        $userTraining->training_completion = $request->training_completion;
        $userTraining->user_id = $userId;
        $userTraining->save();

        return redirect(config('app.locale') . '/account')
            ->with('success','New training has been added successfully.');
    }

    public function editUserTraining($id)
	{
        $userTraining = UserTraining::findOrFail($id);
        return view('account.forms.editUserTraining')->with('userTraining', $userTraining);
    }

    public function updateUserTraining(Request $request, $id)
    {
         request()->validate([
            'training_name' => 'required|max:200',
            'training_institution' => 'required|max:200',
        ]);

        $userTraining = UserTraining::findOrFail($id);
        $userTraining->training_name = $request->training_name;
        $userTraining->training_institution = $request->training_institution;
        $userTraining->training_completion = $request->training_completion;
        $userTraining->save();

         return redirect(config('app.locale') . '/account')
             ->with('success','Your training has been updated successfully.');
    }

    public function deleteUserTraining($id)
    {
        $userTraining = UserTraining::find($id);
        $userTraining->delete();

        return redirect(config('app.locale') . '/account')
        ->with('success','Training has deleted successfully.');
    }

    public function createUserReference()
	{
        return view('account.forms.createUserReference');
    }
    
    public function storeUserReference(Request $request)
	{
        // Get current user
        $userId = Auth::id();

        request()->validate([
            'ref_name' => 'required|max:200',
            'ref_position' => 'required|max:200',
            'ref_company' => 'required|max:200',
            'ref_email' => 'required|max:200',
            'ref_phone' => 'required|max:200',
        ]);

        if(!empty($request->country_code))
            $fullPhone = '+'.$request->country_code.$request->ref_phone;
        else
            $fullPhone = $request->ref_phone;

        $userReference = new UserReference();
        $userReference->ref_name = $request->ref_name;
        $userReference->ref_position = $request->ref_position;
        $userReference->ref_company = $request->ref_company;
        $userReference->ref_email = $request->ref_email;
        $userReference->ref_phone = $fullPhone;
        $userReference->user_id = $userId;
        $userReference->save();

        return redirect(config('app.locale') . '/account')
            ->with('success','New reference has been added successfully.');
    }

    public function editUserReference($id)
	{
        $userReference = UserReference::findOrFail($id);
        return view('account.forms.editUserReference')->with('userReference', $userReference);
    }

    public function updateUserReference(Request $request, $id)
    {
        request()->validate([
            'ref_name' => 'required|max:200',
            'ref_position' => 'required|max:200',
            'ref_company' => 'required|max:200',
            'ref_email' => 'required|max:200',
            'ref_phone' => 'max:20',
        ]);

        if(!empty($request->country_code))
            $fullPhone = '+'.$request->country_code.$request->ref_phone;
        else
            $fullPhone = $request->ref_phone;

        $userReference = UserReference::findOrFail($id);
        $userReference->ref_name = $request->ref_name;
        $userReference->ref_position = $request->ref_position;
        $userReference->ref_company = $request->ref_company;
        $userReference->ref_email = $request->ref_email;
        $userReference->ref_phone = $fullPhone;
        $userReference->save();

         return redirect(config('app.locale') . '/account')
             ->with('success','Your reference has been updated successfully.');
    }

    public function deleteUserReference($id)
    {
        $userReference = UserReference::find($id);
        $userReference->delete();

        return redirect(config('app.locale') . '/account')
        ->with('success','Reference has deleted successfully.');
    }

    public function userProfileEmployerView($id)
    {
        $user = DB::table('users')->where('id',$id)->first();
        $data['userPhoto'] = (!empty($user->email)) ? $user->photo : null;
        $data['userExperiences'] = UserExperience::where('user_id', $id)->get();
		$data['userEducations'] = UserEducation::where('user_id', $id)->get();
		$data['userSkills'] = UserSkill::where('user_id', $id)->get();
		$data['userLanguages'] = UserLanguage::where('user_id', $id)->get();
		$data['userTrainings'] = UserTraining::where('user_id', $id)->get();
        $data['userReferences'] = UserReference::where('user_id', $id)->get();
        $data['user'] = $user;
        $data['isEmployerView'] = 1;
        
		// Meta Tags
		MetaTag::set('title', t('Employer Profile View'));
        MetaTag::set('description', t('Employer profile view on :app_name', ['app_name' => config('settings.app.app_name')]));
        
        return view('account.userProfileEmployerView', $data);
    }

    public function createVideo()
	{
        return view('account.forms.createVideo');
    }
    
    public function storeVideo(Request $request)
	{
        $data = $request->all();
        $rules=[
            'video'          =>'mimes:mpeg,ogg,mp4,webm,3gp,mov,flv,avi,wmv,ts|max:100040|required'
        ];

        $validator = Validator($data,$rules);

        if ($validator->fails()){
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        } else {
            $video=$data['video'];
            $input = time().$video->getClientOriginalExtension();
            $destinationPath = 'uploads/videos';
            $video->move($destinationPath, $input);

            $userVideo = new UserVideo();
            $userVideo->user_id = auth()->user()->id;
            $userVideo->video = $input;
            $userVideo->save();
            return redirect(config('app.locale') . '/account')->with('success','Your video has been uploaded successfully');
        }
    }

    public function editVideo($id)
	{
        $userVideo = UserVideo::findOrFail($id);
        Debugbar::info($userVideo);
        return view('account.forms.editVideo')->with('userVideo', $userVideo);
    }

    public function updateVideo(Request $request, $id)
    {
        $data = $request->all();
        $rules=[
        'video'          =>'mimes:mpeg,ogg,mp4,webm,3gp,mov,flv,avi,wmv,ts|max:100040|required'];

        $validator = Validator($data,$rules);

        if ($validator->fails()){
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        } else {
            $video=$data['video'];
            $input = time().$video->getClientOriginalExtension();
            $destinationPath = 'uploads/videos';
            $video->move($destinationPath, $input);

            $userVideo = UserVideo::findOrFail($id);
            $userVideo->user_id = auth()->user()->id;
            $userVideo->video = $input;
            $userVideo->save();

            return redirect(config('app.locale') . '/account')->with('success','Your video has been uploaded successfully');
        }
    }

    public function deleteVideo($id)
    {
        $userVideo = UserVideo::find($id);
        $userVideo->delete();

        return redirect(config('app.locale') . '/account')
        ->with('success','Video has deleted successfully.');
    }
}