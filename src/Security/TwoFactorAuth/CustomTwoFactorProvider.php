<?php
	
	namespace App\Security\TwoFactorAuth;
	
	use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
	use Scheb\TwoFactorBundle\Security\TwoFactor\AuthenticationContextInterface;
	use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Email\Generator\CodeGeneratorInterface;
	use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorFormRendererInterface;
	use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorProviderInterface;
	
	class CustomTwoFactorProvider implements TwoFactorProviderInterface
	{
		
		/**
		 * @var CodeGeneratorInterface
		 */
		private $codeGenerator;
		
		/**
		 * @var TwoFactorFormRendererInterface
		 */
		private $formRenderer;
		
		public function __construct(CodeGeneratorInterface $codeGenerator, TwoFactorFormRendererInterface $formRenderer)
		{
			$this->codeGenerator = $codeGenerator;
			$this->formRenderer = $formRenderer;
		}
		
		public function beginAuthentication(AuthenticationContextInterface $context): bool
		{
			// Check if user can do email authentication
			/** @var TwoFactorInterface $user */
			$user = $context->getUser();
			
			return $user->isEmailAuthEnabled();
		}
		
		public function prepareAuthentication(object $user): void
		{
			
			/** @var TwoFactorInterface $user */
			$this->codeGenerator->generateAndSend($user);
		}
		
		public function validateAuthenticationCode(object $user, string $authenticationCode): bool
		{
			
			// Strip any user added spaces
			$authenticationCode = str_replace(' ', '', $authenticationCode);
			
			/** @var TwoFactorInterface $user */
			if($this->isValidUserCode($user, $authenticationCode))
			{
				return true;
			}
			
			return false;
			
		}
		
		public function getFormRenderer(): TwoFactorFormRendererInterface
		{
			return $this->formRenderer;
		}
		
		/**
		 * @param TwoFactorInterface $user
		 * @param array|string $authenticationCode
		 * @return bool
		 */
		private function isValidUserCode(TwoFactorInterface $user, array|string $authenticationCode): bool
		{
			return $user->getEmailAuthCode() === $authenticationCode && $user->getOtpValidUntil() > new \DateTimeImmutable('now');
		}
	}