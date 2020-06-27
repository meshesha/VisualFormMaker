<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use App\User;
use App\models\Forms;

class SubmittedForms extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'submitted_forms';

    /**
     * A Submission may belong to a User
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A Submission belongs to a Form
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function forms()
    {
        return $this->belongsTo(Forms::class,'form_id','id');
    }
    /**
     * The attributes that should be casted to another data type
     *
     * @var array
     */
    protected $casts = [
        'content' => 'array',
    ];

    /**
     * Turn the current value we are trying to display to string we can actually display
     *
     * @param string $key
     * @param string $type the type of the input type that this key belongs to on the form
     * @param boolean $limit_string
     * @return Illuminate\Support\HtmlString
     */
    public function renderEntryContent($key, $type = null, $limit_string = false) : HtmlString
    {
        $str = '';

        if(
            ! empty($this->content[$key]) &&
            is_array($this->content[$key])
        ) {
            $str = implode(', ', $this->content[$key]);
        } else {
            $str = $this->content[$key] ?? '';
        }

        if ($limit_string) {
            $str = Str::of($str)->limit(20);
        }

        // if the type is 'file' then we have to render this as a link
        if ($type == 'file') {		
		if(isset($this->content[$key])){
			$file_link = url('/') .Storage::url($this->content[$key]);
			$str = "<a href='{$file_link}' target='_blank'>{$str}</a>";
	    	} else {
			$str = "No file";
	    	}
        }

        return new HtmlString($str);
    }
}
