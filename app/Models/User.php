<?php
// --------------------------------------------------------------------------
namespace App\Models;
// --------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
// --------------------------------------------------------------------------
use Illuminate\Foundation\Auth\Access\Authorizable;
// --------------------------------------------------------------------------
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
// --------------------------------------------------------------------------
use Illuminate\Notifications\Notifiable;
use App\Notifications\AdminResetPassword;
// --------------------------------------------------------------------------
use Illuminate\Database\Eloquent\SoftDeletes;
// --------------------------------------------------------------------------
use App\Models\UserRole as Role;
use App\Models\Company as Company;
use App\Models\MasMemo;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract {
    // ----------------------------------------------------------------------
    // These traits are used for admin login authentication
    // ----------------------------------------------------------------------
    use Authenticatable, Authorizable, CanResetPassword, Notifiable, SoftDeletes;
    // ----------------------------------------------------------------------
    protected $appends = [ 'full_name', 'full_kana_name' ];
    protected $casts = [ 'email_verified_at' => 'datetime' ];
    // ----------------------------------------------------------------------
    protected $hidden = [
        'password',
        'remember_token'
    ];
    // ----------------------------------------------------------------------
    protected $fillable = [
        'company_id',
        'user_role_id',
        'first_name',
        'last_name',
        'first_name_kana',
        'last_name_kana',
        'nickname',
        'real_estate_notary_registration',
        'real_estate_notary_office_id',
        'real_estate_notary_prefecture_id',
        'real_estate_notary_number',
        'cooperation_registration',
        'real_estate_information',
        'real_estate_information_text',
        'registration',
        'registration_text',
        'surveying',
        'surveying_text',
        'clothes',
        'clothes_text',
        'other',
        'other_text',
        'email',
        'remember_token'
    ];
    // ----------------------------------------------------------------------
    
    // ----------------------------------------------------------------------
    /** Override the default function to send password reset notification */
    // ----------------------------------------------------------------------
    public function sendPasswordResetNotification($token){
        $this->notify(new AdminResetPassword($token));
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // User role
    // ----------------------------------------------------------------------
    public function user_role(){
        return $this->belongsTo( Role::class );
    }
    // ----------------------------------------------------------------------
    public function userRole(){ // Alias
        return $this->belongsTo( Role::class );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function isGlobalAdmin(){
        return $this->user_role_id === 6;
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    public function company(){
        return $this->belongsTo( Company::class );
    }
    // ----------------------------------------------------------------------
    public function memo(){
        return $this->hasMany( MasMemo::class );
    }
    // ----------------------------------------------------------------------



    // ----------------------------------------------------------------------
    // Full name accessors
    // ----------------------------------------------------------------------
    public function getFullKanaNameAttribute(){
        return "{$this->last_name_kana} {$this->first_name_kana}";
    }
    public function getFullNameAttribute(){
        return "{$this->last_name} {$this->first_name}";
    }
    // ----------------------------------------------------------------------

}
