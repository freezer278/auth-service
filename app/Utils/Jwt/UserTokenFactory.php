<?php


namespace App\Utils\Jwt;

use App\Models\UserInterface;
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
     * UserTokenFactory constructor.
     * @param Sha256 $signer
     */
    public function __construct(Sha256 $signer)
    {
        $this->signer = $signer;
    }

    /**
     * @param UserInterface $user
     * @return Token
     */
    public function create(UserInterface $user)
    {
        $time = time();

        return (new Builder())
            ->issuedBy(url('/'))
            ->permittedFor(url('/'))
            ->identifiedBy($this->getNewTokenId(), true)
            ->issuedAt($time)
            ->canOnlyBeUsedAfter($time + 60)
            ->expiresAt($time + $this->getExpiresIn())
            ->withClaim('sub', $user->getId())
            ->getToken($this->signer, new Key($this->getSignKey()));
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
        //            Todo: add here unique identifier
        return '4f1g23a12aa';
    }

    /**
     * @return string
     */
    public function getSignKey(): string
    {
        return env('JWT_KEY', '23uu9ujfdiosu98fu4390923');
    }
}