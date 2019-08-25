<?php


namespace App\Utils\Jwt;

use App\DataMappers\Token\TokenMapperInterface;
use App\Models\Token as TokenModel;
use App\Models\UserInterface;
use Illuminate\Support\Str;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;

class UserTokenFactory implements UserTokenFactoryInterface
{
    /**
     * @var Sha256
     */
    private $signer;
    /**
     * @var TokenMapperInterface
     */
    private $tokenMapper;
    /**
     * @var Builder
     */
    private $builder;

    /**
     * UserTokenFactory constructor.
     * @param Sha256 $signer
     * @param TokenMapperInterface $tokenMapper
     */
    public function __construct(Sha256 $signer, TokenMapperInterface $tokenMapper, Builder $builder)
    {
        $this->signer = $signer;
        $this->tokenMapper = $tokenMapper;
        $this->builder = $builder;
    }

    /**
     * @param UserInterface $user
     * @return Token
     */
    public function create(UserInterface $user)
    {
        $time = time();
        $expiresAt = $time + $this->getExpiresIn();
        $id = $this->getNewTokenId();

        $token = $this->builder
            ->issuedBy(url('/'))
            ->permittedFor(url('/'))
            ->identifiedBy($id, true)
            ->issuedAt($time)
            ->canOnlyBeUsedAfter($time + 60)
            ->expiresAt($expiresAt)
            ->withClaim('sub', $user->getId())
            ->getToken($this->signer, new Key($this->getSignKey()));

        $this->tokenMapper->create(new TokenModel([
            'id' => $id,
            'expires_at' => $expiresAt,
        ]));

        return $token;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return 3600;
    }

    /**
     * @return string
     */
    private function getNewTokenId(): string
    {
        do {
            $id = Str::random(32);
        } while ($this->tokenMapper->findById($id));

        return $id;
    }

    /**
     * @return string
     */
    public function getSignKey(): string
    {
        return env('JWT_KEY', '23uu9ujfdiosu98fu4390923');
    }
}