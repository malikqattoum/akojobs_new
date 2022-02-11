<?php


namespace App\Models;

use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\LocalizedScope;
use App\Observer\ResumeObserver;
use Illuminate\Support\Facades\Storage;
use Larapen\Admin\app\Models\Crud;

class Resume extends BaseModel
{
	use Crud;
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resumes';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['country_code', 'user_id', 'name', 'filename', 'active'];
    
    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    // protected $hidden = [];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
	protected $dates = ['created_at', 'updated_at'];
    
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();
	
		Resume::observe(ResumeObserver::class);
        
        static::addGlobalScope(new ActiveScope());
		static::addGlobalScope(new LocalizedScope());
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function post()
    {
        return $this->hasMany(Post::class);
    }
    public function user()
    {
        return $this->belongsToMany(User::class, 'user_id');
    }
    
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
	public function getNameAttribute()
	{
		$value = null;
		
		if (isset($this->attributes) && isset($this->attributes['name'])) {
			$value = $this->attributes['name'];
		}
		
		if (empty($value)) {
			$value = last(explode('/', $this->attributes['filename']));
		}
		
		return $value;
	}
	
    public function getFilenameAttribute()
    {
        if (!isset($this->attributes) || !isset($this->attributes['filename'])) {
            return null;
        }

        $value = $this->attributes['filename'];

        // Fix path
        $value = str_replace('uploads/resumes/', '', $value);
        $value = str_replace('resumes/', '', $value);
        $value = 'resumes/' . $value;

        if (!Storage::exists($value)) {
            return null;
        }

        // $value = 'uploads/' . $value;

        return $value;
    }
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setFilenameAttribute($value)
    {
		$field_name = 'resume.filename';
		$attribute_name = 'filename';
		$disk = config('filesystems.default');
	
		// Set the right field name
		$request = \Request::instance();
		if (!$request->hasFile($field_name)) {
			$field_name = $attribute_name;
			if (!$request->hasFile($field_name)) {
			    $this->attributes[$attribute_name] = $value;
			    return;
			}
		}
		//$this->attributes[$attribute_name] = $field_name;
		//return;
        if (!isset($this->country_code) || !isset($this->user_id)) {
            $this->attributes[$attribute_name] = null;
            return false;
        }

        // Path
        $destination_path = 'resumes/' . strtolower($this->country_code) . '/' . $this->user_id;

        // Upload
        $this->uploadFileToDiskCustom($value, $field_name, $attribute_name, $disk, $destination_path);
    }
    
    public static function getCvText($filename)
    {
        $cvText = '';
        $error = '';
        if(!empty($filename) && strpos($filename, '.pdf') !== false)
        {
            $parser = new \Smalot\PdfParser\Parser();
            try {
                $responseCode = self::get_http_response_code(\Storage::url($filename));
            } catch (\Exception $e) {
                $responseCode = 0;
                flash($e->getMessage())->error();
            }
            if($responseCode != "200"){
                $error = "error";
            }else{
                try {
                    $pdf    = $parser->parseFile(\Storage::url($filename));
                    $cvText = $pdf->getText();
                } catch (\Exception $e) {
                    flash($e->getMessage())->error();
                    $pdf = '';
                    $cvText = '';
                }
            }
        }
        elseif (!empty($filename))
        {
            $filePath = public_path()."/storage/".$filename;
            $docObj = new \App\Helpers\DocxConversion($filePath);
            $cvText= $docObj->convertToText();
        }
        
        return $cvText;
    }
    
    private static function get_http_response_code($url) {
        // Remove this stream_context_set_default on production
        stream_context_set_default( [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        try {
            $headers = get_headers($url);
            return substr($headers[0], 9, 3);
        } catch (\Exception $e) {
            $headers = [];
            return $headers;
        }
    }
}
