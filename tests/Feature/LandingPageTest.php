<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingPageTest extends TestCase
{
    public function test_public_landing_page_loads_successfully_with_sjut_branding()
    {
        $response = $this->get(route('landing'));

        $response->assertStatus(200);
        $response->assertSee('St. John', false);
        $response->assertSee('University of Tanzania', false);
        $response->assertSee('How can we help you today?', false);
        $response->assertSee('I Lost Something', false);
        $response->assertSee('I Found Something', false);
        $response->assertSee('To Learn To Serve', false);
        $response->assertSee('Dodoma, Tanzania', false);

        // 1. FAQ removed completely
        $response->assertDontSee('Frequently Asked Questions', false);

        // 2. "Do you already have an account?" body prompt removed completely
        $response->assertDontSee('Do you already have an account?', false);

        // 3. "How the System Works" section removed completely from Landing Page
        $response->assertDontSee('How The Recovery System Works', false);
        $response->assertDontSee('How the Recovery System Works', false);

        // 4. "I Lost Something" and "I Found Something" appear ONLY ONCE on the landing page
        $content = $response->getContent();
        $this->assertEquals(1, substr_count($content, 'I Lost Something'), 'I Lost Something should appear exactly once on the landing page');
        $this->assertEquals(1, substr_count($content, 'I Found Something'), 'I Found Something should appear exactly once on the landing page');

        // 5. Header clearly displays Login, Register / Create Account, and User Guide tab
        $response->assertSee('Login', false);
        $response->assertSee('Register / Create Account', false);
        $response->assertSee('User Guide', false);

        // 6. Chatbot widget integrated with safe pointer events and responsive classes
        $response->assertSee('sjut-chatbot-container', false);
        $response->assertSee('pointer-events-none', false);
        $response->assertSee('chatbot-backdrop', false);
    }

    public function test_system_user_guide_pdf_downloads_successfully()
    {
        $response = $this->get(route('guide.download'));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment; filename="SJUT_Lost_and_Found_System_User_Guide.pdf"', $response->headers->get('Content-Disposition'));
        $this->assertStringStartsWith('%PDF-1.4', $response->getContent());
    }

    public function test_chatbot_query_returns_intelligent_response_and_links()
    {
        $response = $this->postJson(route('chatbot.query'), [
            'message' => 'How do I report a lost item?',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'reply',
            'actions',
            'chips',
        ]);
        $response->assertJsonFragment([
            'success' => true,
        ]);
        $this->assertStringContainsString('Report Lost Item', $response->json('reply'));
    }

    public function test_user_guide_page_renders_with_how_the_system_works_and_download_options()
    {
        $response = $this->get(route('guide.index'));

        $response->assertStatus(200);
        $response->assertSee('How the Recovery System Works', false);
        $response->assertSee('Standard 5-Step Recovery Workflow', false);
        $response->assertSee('Ownership Claims, QR Release Passes & Security Desk Protocol', false);
        $response->assertSee('Download User Guide (PDF)', false);

        // Ensure Download User Guide (PDF) appears EXACTLY ONCE on the page (no duplicate button)
        $content = $response->getContent();
        $this->assertEquals(1, substr_count($content, 'Download User Guide (PDF)'), 'Download User Guide (PDF) button should appear only once on the User Guide page');

        // Verify footer does not have redundant Download User Guide (PDF) link
        $landingResponse = $this->get(route('landing'));
        $landingContent = $landingResponse->getContent();
        $this->assertStringNotContainsString('Download User Guide (PDF)', $landingContent);
    }
}
