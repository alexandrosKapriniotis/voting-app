<?php

namespace Tests\Feature;

use App\Http\Livewire\ContactForm;
use App\Mail\ContactFormMailable;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    /** @test */
    public function main_page_contains_contact_form_livewire_component()
    {
        //$this->get('/')->assertSeeLivewire('contact-form');
    }

    /** @test */
    public function contact_form_sends_outs_an_email()
    {
        Mail::fake();

        Livewire::test(ContactForm::class)
            ->set('name','Alex')
            ->set('email','someguy@test.com')
            ->set('phone','123233232')
            ->set('message','this is a test')
            ->call('submitForm')
            ->assertSee('We received your message successfully and will get back to you shortly!');

        Mail::assertSent(function(ContactFormMailable $mail) {
            $mail->build();

            return $mail->hasTo('sim2ates@hotmail.com') && $mail->hasFrom('someguy@test.com') && $mail->subject === 'Contact Form Submission';
        });
    }

    /** @test */
    public function contact_form_name_field_is_required()
    {
        Livewire::test(ContactForm::class)
            ->set('name','')
            ->set('email','someguy@test.com')
            ->set('phone','123233232')
            ->set('message','this is a test')
            ->call('submitForm')
            ->assertHasErrors(['name' => 'required']);
    }

    /** @test */
    public function contact_form_message_field_has_minimum_characters()
    {
        Livewire::test(ContactForm::class)
            ->set('name','test')
            ->set('email','someguy@test.com')
            ->set('phone','123233232')
            ->set('message','this')
            ->call('submitForm')
            ->assertHasErrors(['message' => 'min']);
    }
}
