<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserMgmtStoreRequest;
use App\Http\Requests\Admin\UserMgmtUpdateRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetails;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    protected $path = 'uploads/admin_profile/';

    public function __construct()
    {
        $this->middleware(['jwt.role:1']);
    }

    public function dashboard()
    {
        return response()->json(auth()->user());
    }

    public function index()
    {
        $user = User::whereNotIn('role_id', [2, 3])->orderBy('id', 'DESC')->get();
        return response()->json($user);
    }

    public function create()
    {
        $roles = Role::get();
        return view('admin.user_mgmt.create', compact('roles'));
    }
/**
     * @OA\Post(
     *     path="/api/admin/user_management",
     *     description="",
     *     summary="uploads an image",
     *     operationId="uploadFilePost",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="email"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="gender",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="file to upload",
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="successful operation",
     *     ),
     *     security={{ "apiAuth": {} }},
     *     tags={"pet"}
     * )
     * */
    public function store(UserMgmtStoreRequest $request)
    {
        // return response()->json(["user"=> $request->all()]);
        return $request->file('image')->getClientOriginalName();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role_id' => $request->role
        ]);

        $id = $user->id;

        if ($request->gender != NULL || $request->image != NULL) {
            $filename = '';
            if ($request->hasFile('image')) {
                $filename = $this->getAdminProfileImageName($request->image);
            }

            UserDetails::create([
                'user_id' => $id,
                'profile_img' => $filename,
                'gender' => $request->gender
            ]);
        }

        return response()->json([
            'user' => User::find($id),
            'message' => 'successfully inserted'
        ]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::get();
        return view('admin.user_mgmt.edit', compact('user', 'roles'));
    }

    /**
     * @OA\POST(
     *     path="/api/admin/user_management/{id}",
     *     description="",
     *     summary="uploads an image",
     *     operationId="uploadFile",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="email"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="gender",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="file to upload",
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="ID of pet to update",
     *         in="path",
     *         name="petId",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="successful operation",
     *     ),
     *     security={{ "apiAuth": {} }},
     *     tags={"pet"}
     * )
     * */
    public function updated(UserMgmtUpdateRequest $request, $id)
    {
        return $request->file('image')->getClientOriginalName();
        // $id = Crypt::decrypt($id);       
        User::where('id', $id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'role_id' => $request->role,
            'email' => $request->email,
        ]);

        $user = User::find($id);

        $user_details = $user->userDetails;

        if ($user_details == null) {
            if ($request->hasFile('image') || $request->gender) {
                $use = new UserDetails;
                $use->user_id = $id;
                $use->gender = $request->gender;

                if ($request->hasFile('image')) {
                    $filename = $this->getAdminProfileImageName($request->image);
                    $use->profile_img = $filename;
                }

                $use->save();
            }
        } else {
            $user_details->gender = $request->gender;

            if ($request->hasFile('image')) {
                if (Storage::exists($this->path . $user_details->profile_img)) {
                    Storage::delete($this->path . $user_details->profile_img);
                }

                $filename = $this->getAdminProfileImageName($request->image);
                $user_details->profile_img = $filename;
            }

            $user->userDetails()->save($user_details);
        }

        return response()->json([
            'user' => User::find($id),
            'message' => 'successfully updated'
        ]);
        // return back()->with(['success' => 'successfully updated']);
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::find($id);

        if ($user->delete()) {
            $user->status = 'delete';
            $user->save();
            return redirect()->back()->with('success', 'Successfully deleted');
        } else {
            return redirect()->back()->with('error', 'There is an error in deletion');
        }
    }

    public function statusUpdate(Request $request)
    {
        $userId = Crypt::decrypt($request->userId);
        user::where('id', $userId)
            ->update([
                'status' => $request->status_id
            ]);

        return "successfully updated";
    }

    protected function getAdminProfileImageName($image)
    {
        $file = $image;
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs($this->path, $filename);
        return $filename;
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function changePassword()
    {
        return view('admin.change_password');
    }

    public function updateCangePassword(Request $request)
    {
        $request->validate([
            'old_password' => ['required', new MatchOldPassword],
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->password),
            'pswd_change_date' => now()
        ]);

        return back()->with("success", "Password changed successfully!");
    }
}
