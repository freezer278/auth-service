<?php

namespace App\Http\Controllers;

use App\DataMappers\User\UserMapperInterface;
use App\Exceptions\ModelNotFoundException;
use App\Models\UserInterface;
use App\Utils\Jwt\UserTokenFactoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * @var UserTokenFactoryInterface
     */
    private $tokenFactory;
    /**
     * @var UserMapperInterface
     */
    private $userMapper;

    /**
     * Create a new controller instance.
     *
     * @param UserTokenFactoryInterface $tokenFactory
     * @param UserMapperInterface $userMapper
     */
    public function __construct(UserTokenFactoryInterface $tokenFactory, UserMapperInterface $userMapper)
    {
        $this->tokenFactory = $tokenFactory;
        $this->userMapper = $userMapper;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $data = $this->validate($request, [
            'nickname' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = $this->userMapper->findByNickname($data['nickname']);

       $this->performAdditionalValidation($user, $data);

        $token = $this->tokenFactory->create($user);

        return response()->json([
            'token' => $token->__toString(),
            'expires_in' => $this->tokenFactory->getExpiresIn(),
        ]);
    }

    /**
     * @param UserInterface $user
     * @param array $data
     */
    private function performAdditionalValidation(UserInterface $user, array $data): void
    {

        if (!$user) {
            ValidationException::withMessages([
                'nickname' => 'user with nickname ' . $data['nickname'] . ' was not found',
            ]);
        }

        if (!Hash::check($data['password'], $user->getPassword())) {
            ValidationException::withMessages([
                'password' => 'passwordInvalid',
            ]);
        }
    }
}
