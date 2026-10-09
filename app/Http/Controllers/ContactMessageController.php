<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'website' => ['nullable', 'max:0'],
            'name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::query()->create([
            ...$validated,
            'ip_address' => $request->ip(),
        ]);

        Mail::raw('A new website contact message has been received.', fn ($mail) => $mail->to((string) config('mail.from.address'))->subject('New Velmora contact message'));

        return redirect()->route('contact', ['locale' => app()->getLocale()])->with('sent', true);
    }
}
