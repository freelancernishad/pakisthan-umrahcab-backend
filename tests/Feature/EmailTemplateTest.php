<?php

namespace Tests\Feature;

use App\Models\EmailTemplate;
use App\Mail\DynamicEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('an email template can be created', function () {
    $template = EmailTemplate::create([
        'name' => 'Test Template',
        'subject' => 'Test Subject',
        'content_html' => '<p>Hello {{name}}</p>',
    ]);

    $this->assertDatabaseHas('email_templates', [
        'name' => 'Test Template',
        'subject' => 'Test Subject',
    ]);
});

test('dynamic email renders with variables', function () {
    $html = '<p>Hello {{name}}</p>';
    $data = ['name' => 'Nishad'];
    $mailable = new DynamicEmail('Subject', $html, $data);

    $htmlContent = $mailable->build()->render();
    
    expect($htmlContent)->toContain('Hello Nishad');
});
