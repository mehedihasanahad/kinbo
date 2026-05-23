@extends('emails.layout')

@section('content')
<div style="padding: 8px 0;">
    <p class="greeting">New Contact Form Submission</p>

    <div class="card" style="margin-top: 16px;">
        <div class="card-row">
            <span class="label">Name</span>
            <span class="value">{{ $submission->name }}</span>
        </div>
        <div class="card-row">
            <span class="label">Email</span>
            <span class="value">{{ $submission->email }}</span>
        </div>
        <div class="card-row">
            <span class="label">Subject</span>
            <span class="value">{{ $submission->subject }}</span>
        </div>
    </div>

    <p class="section-title" style="margin-top: 24px;">Message</p>
    <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:16px 20px;font-size:14px;color:#374151;line-height:1.7;white-space:pre-wrap;">{{ $submission->message }}</div>

    <hr class="divider">
    <p class="help-text">
        You can reply directly to this email to respond to {{ $submission->name }}.
    </p>
</div>
@endsection
