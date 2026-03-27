<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class LandingController extends Controller
{
    public function landing()
    {
        return view('frontEnd.landing.original');
    }

    public function index()
    {
        return view('frontEnd.landing.kuzza_landing');
    }

    public function submitGetStarted(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'organisation' => 'required|string|max:255',
            'phone' => 'required|string|max:60',
            'email' => 'required|email|max:255',
        ], [], [
            'organisation' => 'school or organisation',
        ]);

        $lines = [
            'New KUZZA Get Started inquiry',
            '',
            'Name: '.$validated['name'],
            'School / organisation: '.$validated['organisation'],
            'Phone: '.$validated['phone'],
            'Email: '.$validated['email'],
        ];

        $body = implode("\n", $lines);

        $fromAddress = config('mail.from.address') ?: 'hello@mybidhaa.com';
        $fromName = config('mail.from.name') ?: 'KUZZA';

        try {
            Mail::raw($body, function ($message) use ($validated, $fromAddress, $fromName): void {
                $message->from($fromAddress, $fromName)
                    ->to('hello@mybidhaa.com')
                    ->cc('kmuga@mybidhaa.com')
                    ->subject('KUZZA — Get Started inquiry from '.$validated['name'])
                    ->replyTo($validated['email'], $validated['name']);
            });
        } catch (Throwable $e) {
            report($e);

            $msg = 'We could not send your request right now. Please try again or email hello@mybidhaa.com.';
            if (config('app.debug')) {
                $msg .= ' ('.$e->getMessage().')';
            }

            return response()->json(['message' => $msg], 500);
        }

        return response()->json([
            'message' => 'Thank you, we will get in touch soon.',
        ]);
    }
}
