<?php


namespace App\Utils\Jwt;

use App\DataMappers\User\UserMapperInterface;
use App\Models\UserInterface;
use App\Utils\Jwt\Exceptions\TokenNotValidException;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;

class AuthUserResolver implements AuthUserResolverInterface
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Parser
     */
    private $tokenParser;
    /**
     * @var ValidationData
     */
    private $tokenValidationData;
    /**
     * @var UserMapperInterface
     */
    private $userMapper;

    /**
     * AuthUserResolver constructor.
     * @param Request $request
     * @param Parser $tokenParser
     * @param ValidationData $tokenValidationData
     * @param UserMapperInterface $userMapper
     */
    public function __construct(Request $request, Parser $tokenParser, ValidationData $tokenValidationData, UserMapperInterface $userMapper)
    {
        $this->request = $request;
        $this->tokenParser = $tokenParser;
        $this->tokenValidationData = $tokenValidationData;
        $this->userMapper = $userMapper;
    }

    /**
     * @return int
     * @throws TokenNotValidException
     */
    public function getId(): ?int
    {
        return $this->getUserIdFromRequest();
    }

    /**
     * @return UserInterface
     * @throws TokenNotValidException
     */
    public function getUser(): ?UserInterface
    {
        $id = $this->getId();
        if (!$id) {
            return null;
        }

        return $this->userMapper->findById($id);
    }

    /**
     * @return int|null
     * @throws TokenNotValidException
     */
    private function getUserIdFromRequest(): ?int
    {
        $token = $this->getTokenFromRequest();
        return $token->getClaim('sub');
    }

    /**
     * @return Token|null
     * @throws TokenNotValidException
     */
    private function getTokenFromRequest(): ?Token
    {
        $tokenString = $this->request->header('Authorization');

        if (!$tokenString) {
            return null;
        }

        $tokenString = (string)explode(' ', $tokenString)[1];

        try {
            $token = $this->tokenParser->parse($tokenString);
        } catch (\InvalidArgumentException $exception) {
            throw new TokenNotValidException($exception->getMessage(), $exception->getCode(), $exception);
        }

        $this->validateToken($token);

        return $token;
    }

    /**
     * @param Token $token
     * @throws TokenNotValidException
     */
    private function validateToken(Token $token)
    {
        $this->tokenValidationData->setIssuer(url('/'));
        $this->tokenValidationData->setAudience(url('/'));

        if (!$token->validate($this->tokenValidationData)) {
            throw new TokenNotValidException('token validation failed');
        }
    }
}
