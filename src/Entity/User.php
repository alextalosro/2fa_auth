<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\DateType;
use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, TwoFactorInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;
	
	private $plainPassword;
	
	#[ORM\Column(type: 'string', nullable: true)]
	private string $authCode;
	
	#[ORM\Column(type: 'datetime', nullable: true)]
    private $otpValidUntil;
	
	#[ORM\Column(type: 'boolean', nullable: true)]
	private $useTwoFa;
	
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
	
	/**
	 * @return mixed
	 */
	public function getPlainPassword(): ?string
	{
		return $this->plainPassword;
	}
	
	/**
	 * @param mixed $plainPassword
	 */
	public function setPlainPassword($plainPassword): void
	{
		$this->plainPassword = $plainPassword;
	}
	
	public function isEmailAuthEnabled(): bool
	{
		if ($this->getUseTwoFa() === null){
			return false;
		}
		return $this->getUseTwoFa();
	}
	
	public function getEmailAuthRecipient(): string
	{
		return $this->email;
	}
	
	public function getEmailAuthCode(): ?string
	{
		if (null === $this->authCode) {
			throw new \LogicException('The email authentication code was not set');
		}
		
		return $this->authCode;
	}
	
	public function setEmailAuthCode(string $authCode): void
	{
		$this->authCode = $authCode;
		
		$otpValidUnitilTime = new \DateTimeImmutable('now + 120 seconds');

		
		$this->otpValidUntil = $otpValidUnitilTime;
	}
	
	public function getOtpValidUntil()
	{
		return $this->otpValidUntil;
	}

	public function setOtpValidUntil($otpValidUntil): void
	{
		$this->otpValidUntil = $otpValidUntil;
	}
	
	public function getUseTwoFa()
	{
		return $this->useTwoFa;
	}
	
	public function setUseTwoFa($useTwoFa): void
	{
		$this->useTwoFa = $useTwoFa;
	}
	
}
