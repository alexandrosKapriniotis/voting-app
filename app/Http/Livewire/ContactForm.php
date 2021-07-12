<?php

namespace App\Http\Livewire;

use App\Mail\ContactFormMailable;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactForm extends Component
{
    public $name;
    public $email;
    public $phone;
    public $message;
    public $successMessage;

    protected $rules = [
        'name'  => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'message'   => 'required|min:5'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm()
    {
        $contact = $this->validate();

        $contact['name'] = $this->name;
        $contact['email'] = $this->email;
        $contact['phone'] = $this->phone;
        $contact['message'] = $this->message;

        Mail::to('sim2ates@hotmail.com')->send(new ContactFormMailable($contact));

        $this->reset();

        $this->successMessage = 'We received your message successfully and will get back to you shortly!';

//        return back()->with('successs_message', 'We received your message successfully and will get back to you shortly!');
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
