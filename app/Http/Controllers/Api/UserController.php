<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Album;

class UserController extends Controller
{
    //Returns all users
    public function index()
    {
        if (auth()->user()->id == 1) {

            try {
                $users = User::all();
                return response()->json(['users' => $users], 200);
            } catch (\Exception $e) {

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json(["status" => "failure", "message" => "user is not Admin"], 400);
        }
    }

    //Creates a new user
    //Returns user id with status 201
    public function store(Request $request) //POST
    {
        try {
            //Validating the data
            try {
                $this->validate($request, [
                    'first_name' => ['required', 'string', 'alpha'],
                    'last_name' => ['required', 'string', 'alpha'],
                    'email' => ['required', 'string', 'email', 'unique:users'],
                    'username' => ['required', 'string', 'alpha_num', 'unique:users'],
                    'password' => ['required', 'string', 'min:6', 'confirmed'],
                    'gender' => ['required', 'integer', 'between:1,3'],
                    'mobile' => ['required', 'string', 'max:10'],
                    'profile_picture' => ['nullable', 'image', 'max:1999'],
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return \response($e->errors(), 400);
            }

            //Default Profile Picture
            $file_to_store = 'noimage.jpg';

            //Storing the profile picture
            if ($request->profile_picture !== null) {
                $filename = $request->file('profile_picture')->getClientOriginalName();
                $file_first = pathinfo($filename, PATHINFO_FILENAME);
                $extension = $request->file('profile_picture')->getClientOriginalExtension();

                $file_to_store = $file_first . '_' . time() . '.' . $extension;
                $path = $request->file('profile_picture')->storeAs('public/profile_pictures', $file_to_store);
            }


            //Creating user
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'profile_picture' => $file_to_store,
                'password' => Hash::make($request->password),
                'mobile' => $request->mobile,
                'address' => $request->address,
            ]);

            //Generating Passport token
            $token = $user->createToken('Token')->accessToken;

            return response()->json([
                'status' => 'Success',
                'message' => "User Created Successfully",
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failure',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateprofile(Request $request) //PUT
    {   //return response('here',200);
        try {
            //return dd($request);
            //Confirming correct user
            //return dd(auth()->user()->username =='');
            if (!auth()->user()->username)
                return response()->json([
                    'status' => 'failure',
                    'message' => 'Not Authorized'
                ], 401);
            $id = auth()->user()->username;
            //Validating data
            try {
                $validation = $this->validate($request, [
                    'first_name' => ['required', 'string', 'alpha'],
                    'last_name' => ['required', 'string', 'alpha'],
                    'email' => ['required', 'string', 'email', Rule::unique('users')->ignore($id, 'username')], //could be same as old
                    'password' => ['required', 'string', 'min:6', 'confirmed'],
                    'gender' => ['required', 'integer', 'between:1,3'],
                    'mobile' => ['required', 'digits:10'],
                    'profile_picture' => ['nullable', 'image', 'max:1999'],
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json($e->errors(), 400); //($e->errors(),400);
            }


            //default image
            $file_to_store = 'noimage.jpg';

            //storing profile pic
            if ($request->profile_picture != null) {
                $filename = $request->file('profile_picture')->getClientOriginalName();
                $file_first = pathinfo($filename, PATHINFO_FILENAME);
                $extension = $request->file('profile_picture')->getClientOriginalExtension();

                $file_to_store = $file_first . '_' . time() . '.' . $extension;
                $path = $request->file('profile_picture')->storeAs('public/profile_pictures', $file_to_store);
            }

            //Update the user
            $user_updated = User::where('username', $id)->update([
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'profile_picture' => $file_to_store,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'User Profile Updated'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Update User with username =$id if authenticated
    //Return status 200 for success

    // public function update(Request $request, $id) //PUT
    // {   //return response('here',200);
    //     try
    //     {
    //         //Confirming correct user
    //         if(auth()->user()->username !== $id)
    //             return response()->json(['success' => false,
    //                 'message' => 'Not Authorized'], 401);

    //         //Validating data
    //         try{
    //             $validation = $this->validate($request, [
    //                 'first_name' => ['required', 'string','alpha'],
    //                 'last_name' => ['required', 'string','alpha'],
    //                 'email' => ['required', 'string', 'email', Rule::unique('users')->ignore($id,'username')],//could be same as old
    //                 'password' => ['required', 'string', 'min:6', 'confirmed'],
    //                 'gender' => ['required','integer','between:1,3'],
    //                 'profile_picture' => ['nullable','image','max:1999'],
    //             ]);
    //         }
    //         catch (\Illuminate\Validation\ValidationException $e ) {
    //             return response()->json($e->errors(),400);//($e->errors(),400);
    //         }


    //         //default image
    //         $file_to_store = 'noimage.jpg';

    //         //storing profile pic
    //         if($request->profile_picture != null)
    //         {
    //             $filename = $request->file('profile_picture')->getClientOriginalName();
    //             $file_first = pathinfo($filename,PATHINFO_FILENAME);
    //             $extension = $request->file('profile_picture')->getClientOriginalExtension();

    //             $file_to_store = $file_first.'_'.time().'.'.$extension;
    //             $path=$request->file('profile_picture')->storeAs('public/profile_pictures',$file_to_store);
    //         }

    //         //Update the user
    //         $user_updated = User::where('username',$id)->update([
    //             'email' => $request->email,
    //             'first_name' => $request->first_name,
    //             'last_name' => $request->last_name,
    //             'gender' => $request->gender,
    //             'profile_picture' => $file_to_store,
    //             'password' => Hash::make($request->password),
    //         ]);

    //         return response()->json([], 200);

    //     }
    //     catch (\Exception $e)
    //     {
    //         return response()->json(['success' => false,
    //                                 'message' => $e->getMessage()], 500);
    //     }
    // }
    public function getprofile()
    {
        try {

            //Get the user
            $id = auth()->user()->username;
            $user = User::where('username', $id);
            // return dd($user->get());
            if (count($user->get()) === 0) //No such user
            {
                return response()->json([
                    'status' => "failure",
                    'message' => 'User not found'
                ], 404);
            }

            // $user_id = $user->pluck('id')->first();

            // if(auth()->check() and auth()->user()->username === $id)
            // {
            //     //Return User data + All Albums
            //     $albums = Album::where('user_id',$user_id);
            // }
            // else
            // {
            //     //Return only public albums
            //     $albums = Album::where('user_id',$user_id)
            //                     ->where('privacy','1');
            // }
            return response()->json([
                'status' => "Success",
                'data' => $user->get(),
                // 'albums' => $albums->get()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => "failure",
                'message' => $e->getMessage()
            ], 500);
        }
    }
    //Returns User details with username = $id
    //For authenticated user, returns all album details as well
    //For others, return a list of only public albums
    public function show($id) //GET
    {   //return response()->json(['message'=>'Not found'],412);
        try {
            //Get the user
            $user = User::where('username', $id);
            if (count($user->get()) === 0) //No such user
            {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $user_id = $user->pluck('id')->first();

            if (auth()->check() and auth()->user()->username === $id) {
                //Return User data + All Albums
                $albums = Album::where('user_id', $user_id);
            } else {
                //Return only public albums
                $albums = Album::where('user_id', $user_id)
                    ->where('privacy', '1');
            }
            return response()->json([
                'success' => true,
                'data' => $user->get(),
                'albums' => $albums->get()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Deletes user with $id, if same user
    //Returns status 200 for success
    public function destroy($id) //DELETE
    {
        if (auth()->user()->id == 1) {

            //return dd(auth()->user());
            if (!auth()->user()->username)
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            try {
                $user = User::where('id', $id);

                if (count($user->get()) == 0)
                    return response()->json([
                        'success' => false,
                        'message' => 'User not Found'
                    ], 404);

                //Get user details
                $profile = $user->pluck('profile_picture')->first();
                $user_id = $user->pluck('id')->first();
                //Get albums
                $albums = Album::where('user_id', $user_id);

                //Delete albums
                foreach ($albums->get() as $album) {
                    $request = Request::create('/api/albums/' . $album['id'], 'DELETE');
                    $response = app()->handle($request);
                }
                //Delete from Storage
                if ($profile !== 'noimage.jpg') {
                    Storage::delete('/public/profile_pictures/' . $profile);
                }
                //Revoke all tokens
                $userPass = new User;
                $userTokens = $userPass->find($id)->tokens;
                // $userTokens = $user->tokens;
                foreach ($userTokens as $token) {
                    $token->revoke();
                    $token->delete();
                }

                $user = $user->delete();
                return response()->json(['status' => 'Success', "message" => "user Deleted"], 200);
            } catch (\Exception $e) {

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json(["status" => "failure", "message" => "user is not Admin"], 400);
        }
    }
}
