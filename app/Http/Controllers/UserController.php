<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private Request $request;
    private array $rules = [
        'first_name' => "required|string|min:2|max:100", // probably should use regex for spaces and underscores
        'last_name' => "required|string|min:2|max:100",
        'email' => 'required|email|unique:users', // You can add DNS to email check, remove for faster testing
        'password' => 'required|string|min:5|max:40',
        'address' => 'string|nullable|max:255'
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = User::all();

        if ($users->count()) {
            $usersResponse = null;

            foreach ($users as $user)
                $usersResponse[] = [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'address' => $user->details->address ?? 'User has no address'
                ];
        }

        return response()->json($usersResponse ?? 'No users in database');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        $validator = Validator::make($this->request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        };

        $user = new User();
        $user->first_name = $this->request->get('first_name');
        $user->last_name = $this->request->get('last_name');
        $user->email = $this->request->get('email');
        $user->password = Hash::make($this->request->get('password'));
        $user->save();

        // Adding users address to different table (this shouldn't be here)
        if ($this->request->get('address')) {
            $userDetails = new UserDetails();
            $userDetails->user_id = $user->id;
            $userDetails->address = $this->request->get('address');
            $userDetails->timestamps = false;
            $userDetails->save();
        }

        return response()->json('User created successfully', 201);

    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);

        return response()->json(
            [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'address' => $user->details->address ?? 'User has no address'
            ],
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function update($id): JsonResponse
    {
        $validator = Validator::make($this->request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        };

        $user = User::findOrFail($id);
        $user->first_name = $this->request->get('first_name');
        $user->last_name = $this->request->get('last_name');
        $user->email = $this->request->get('email');
        $user->password = Hash::make($this->request->get('password'));
        $user->details->address = $this->request->get('address');
        $user->details->save();
        $user->save();

        return response()->json('User updated successfully', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        User::findOrFail($id)->delete();

        return response()->json('User deleted successfully');
    }
}
