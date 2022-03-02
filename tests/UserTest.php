<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserCreate(): void
    {
		
        $user = new User();
		   
	    $user->setEmail("email@email.com");
	    $user->setRoles([]);
	    $user->setFirstName("Name");
	    $user->setPassword("password");
	    $user->setPlainPassword("password");
	    $user->setEmailAuthCode("123456");
	    $user->setOtpValidUntil(new \DateTimeImmutable('2022-03-02'));
	    $user->setUseTwoFa(true);
		
	    $this->assertEquals("email@email.com", $user->getEmail());
	    $this->assertEquals(['ROLE_USER'], $user->getRoles());
	    $this->assertEquals("Name", $user->getFirstName());
	    $this->assertEquals("password", $user->getPassword());
	    $this->assertEquals("password", $user->getPlainPassword());
	    $this->assertEquals("123456", $user->getEmailAuthCode());
	    $this->assertEquals(new \DateTimeImmutable('2022-03-02'), $user->getOtpValidUntil());
	    $this->assertEquals(true , $user->getUseTwoFa());
	    $this->assertEquals(true , $user->isEmailAuthEnabled());
	    $this->assertEquals('email@email.com' , $user->getEmailAuthRecipient());
		
		
    
    }
}
