<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function features()
    {
        return view('pages.features');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function sendContact(ContactRequest $request)
    {
        Mail::raw($request->message, function ($mail) use ($request) {
            $mail->to(config('mail.from.address'))
                ->subject('Contact Form: '.$request->subject)
                ->replyTo($request->email);
        });

        return redirect()->back()->with('status', 'Your message has been sent. We\'ll get back to you within 24 hours.');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }
}
