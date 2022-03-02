<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WelcomeControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
	    // This calls KernelTestCase::bootKernel(), and creates a
	    // "client" that is acting as the browser
        $client = static::createClient();
	
	    // Request a specific page
        $crawler = $client->request('GET', '/');
	
	    // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Welcome to Two Factor Authentication Demo');
		
		
    }
}
