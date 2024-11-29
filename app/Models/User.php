<?php

namespace App\Models;

use App\Http\Controllers\API\Customer\CheckoutController;
use App\Mail\NewStaffAdded;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Sanctum\HasApiTokens;
use Validator;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Apply the validation rules to the request.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return Validator
     */
    public static function validateInput($request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|max:255',
        ]);

        return $validator;
    }

    public static function createNewUser($request, $siteUser)
    {
        Gate::inspect('create', self::class)->allowed();

        $validator = self::validateInput($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }

        $roles = explode(',', $request->roles);

        $decodedSites = json_decode($request->sites);

        $user = self::updateOrCreate(
            ['email' => $request->email],
            [
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => Hash::make(Str::random(12)),
            ]
        );

        foreach ($roles as $role) {
            $user->roles()->attach($role);
        }
        if ($request->sites) {
            foreach ($decodedSites as $site) {
                $user->sites()->attach($site->id);
            }
        }
        $token = Str::random(64);
        $appUrl = CheckoutController::getAdminAppUrl();
        DB::table('password_resets')->insert(['email' => $request->email, 'token' => bcrypt($token), 'created_at' =>  Carbon::now()->toDateTimeString()]);
        try {
            Mail::to($request->email)->send(new NewStaffAdded($request->firstname, $request->email, $token, $appUrl));
        } catch(\Exception $e) {
            return response()->json(['message' => 'Error: Could not send email',
                'error' => $e->getMessage(),
            ], 400);
        }

        return response()->json(['message' => 'User Saved Successfully'], 200);
    }

    public static function updateUserRoles($request, $id)
    {
        $canUpdate = Gate::inspect('update', self::class)->allowed();

        $user = self::findOrFail($id);
        $user->roles()->sync($request->roles);

        return response()->json(['message' => 'User Amended Successfully', 'canUpdate' => $canUpdate], 200);
    }

    public static function updateUserSites($request, $id)
    {
        $canUpdate = Gate::inspect('update', self::class)->allowed();

        $user = self::findOrFail($id);
        $user->sites()->sync($request->sites);

        return response()->json(['message' => 'User Amended Successfully', 'canUpdate' => $canUpdate], 200);
    }

    public static function updateUserDetails($request, $id)
    {
        $canUpdate = Gate::inspect('update', self::class)->allowed();

        $validator = self::validateInput($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }

        $user = self::findOrFail($id);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->save();

        return response()->json(['message' => 'User Amended Successfully', 'canUpdate' => $canUpdate], 200);
    }

    public static function deleteUser($id)
    {
        $user = self::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User Deleted Successfully'], 200);
    }

    public static function getAdmins()
    {
        $users = self::whereHas('roles', function ($query) {
            $query->where('role_id', '1')
                ->orWhere('role_id', '2');
        })->with('roles')->get();

        return $users;
    }

    public static function getSiteStaff()
    {
        $users = self::whereHas('roles', function ($query) {
            $query->where('role_id', '3')
                ->orWhere('role_id', '4');
        })->with('sites')->get();

        return $users;
    }

    public static function getSingleSiteStaff($id)
    {
        $users = self::where('id', $id)->with('sites')->get()->first();

        return $users;
    }

    /**
     * @param sites Must be an array of site IDs
     */
    public static function siteUsers($sites)
    {
        return self::with('roles')->whereHas('sites', function ($q) use ($sites) {
            $q->whereIn('site_id', $sites);
        })->get();
    }

    public static function isVivedia()
    {
        $roles = Auth::user()->roles->pluck('id')->toArray();

        if (in_array(Role::VIVEDIA_ADMIN_ID, $roles) || in_array(Role::VIVEDIA_STAFF_ID, $roles)) {
            return true;
        }

        return false;
    }

    public static function isVivediaAdmin()
    {
        $roles = Auth::user()->roles->pluck('id')->toArray();
        if (in_array(Role::VIVEDIA_ADMIN_ID, $roles)) {
            return true;
        }

        return false;
    }

    public static function isSiteAdmin()
    {
        $roles = Auth::user()->roles->pluck('id')->toArray();
        if (in_array(Role::SITE_ADMIN_ID, $roles)) {
            return true;
        }

        return false;
    }

    public static function authUserSites()
    {
        return Auth::user()->sites->pluck('id')->toArray();
    }

    public function hasRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();

        if (is_null($role)) {
            return false;
        }

        return $this->roles()->get()->contains($role);
    }

    public function noteorder()
    {
        return $this->hasMany(NoteOrder::class);
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function canImpersonate()
    {
        return $this->hasRole(Role::VIVEDIA_ADMIN);
    }

    public function canBeImpersonated()
    {
        return $this->hasRole(Role::VENUE_STAFF) || $this->hasRole(Role::VENUE_ADMIN);
    }

    public function note()
    {
        return $this->hasOne(ItemOrderNote::class);
    }
}
