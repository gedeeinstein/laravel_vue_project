<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\MasSetting as Setting;
use App\Models\MasSection as Section;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class MasSectionPlan extends Model {
    // ----------------------------------------------------------------------
    protected $guarded = [ 'id' ];
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function setting(){
        return $this->belongsTo( Setting::class, 'mas_setting_id' );
    }
    // ----------------------------------------------------------------------
    public function project(){
        return $this->belongsTo( Project::class );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function sections(){
        return $this->hasMany( Section::class );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
