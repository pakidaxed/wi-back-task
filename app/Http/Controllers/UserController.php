<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Support\MessageBag
     */
    public function store(Request $request, \Illuminate\Http\JsonResponse $response)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => "required|string|min:2|max:100",
            'last_name' => "required|string|min:2|max:100",
            'email' => 'required|email|unique:users', // You can add DNS to email check, remove for faster testing
            'password' => 'required|string|min:5|max:40',
            'address' => 'string|nullable|max:255'
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        };

        $user = new User();
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('first_name'));
        $user->save();

        if ($request->get('address'))
            DB::table('user_details')->insert(['user_id' => $user->id, 'address' => $request->get('address')]);


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
