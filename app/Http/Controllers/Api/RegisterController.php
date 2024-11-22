<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Validator;

/**
 * @OA\Tag(
 *  name="Authentication",
 *  description="Allows you to register and login. [public]."
 * )
 */
class RegisterController extends BaseController
{
    /**
     * Register api.
     *
     * @OA\Post(
     *      path="/register",
     *      tags={"Authentication"},
     *      summary="Create an order",
     *      description="Allows creating a user account.",
     *      @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email"
     *                 ),
     *                  @OA\Property(
     *                     property="password",
     *                     type="string",
     *                 ),
     *                 example={"name": "John Doe", "email": "johndoe@email.fr", "password": "kanta"}
     *             )
     *         )
     *      ),
     *      @OA\Response(response="201", description="Created")
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $existingUser = User::where('email', '=', $request->get('email'))->first();

        if ($existingUser) {
            throw new BadRequestException();
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.', 201);
    }

    /**
     * Login api
     *
     * * @OA\Post(
     *      path="/login",
     *      tags={"Authentication"},
     *      summary="Login",
     *      description="Allows you to authenticate.",
     *      @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email"
     *                 ),
     *                  @OA\Property(
     *                     property="password",
     *                     type="string",
     *                 ),
     *                 example={"email": "johndoe@email.fr", "password": "kanta"}
     *             )
     *         )
     *      ),
     *      @OA\Response(response="200", description="Success")
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }
}
