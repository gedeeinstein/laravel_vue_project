<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\MasSectionPlan as Plan;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class MasSetting extends Model {
    // ----------------------------------------------------------------------
    protected $guarded = [ 'id', 'mas_finance_id' ];
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function project(){
        return $this->belongsTo( Project::class );
    }
    // ----------------------------------------------------------------------
    public function plans(){
        return $this->hasMany( Plan::class );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------