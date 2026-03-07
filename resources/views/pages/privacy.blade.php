@extends('layouts.app')

@section('title', __('front.page_privacy_title') . ' — ' . config('app.name'))

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 text-white py-14 relative overflow-hidden">
    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block bg-accent-500/20 text-accent-300 text-xs font-bold px-4 py-1.5 rounded-full mb-5 tracking-widest uppercase">
            {{ __('front.page_privacy_badge') }}
        </span>
        <h1 class="text-4xl sm:text-5xl font-extrabold mb-3">{{ __('front.page_privacy_title') }}</h1>
        <p class="text-primary-400 text-sm">{{ __('front.page_privacy_updated') }}</p>
    </div>
</section>

<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    @php $locale = app()->getLocale(); @endphp

    <div class="prose prose-sm sm:prose max-w-none prose-headings:text-gray-900 prose-headings:font-bold prose-p:text-gray-600 prose-li:text-gray-600 prose-a:text-primary-600">

    @if($locale === 'bn')

<h2>১. আমরা কোন তথ্য সংগ্রহ করি</h2>
<p>আমরা নিম্নলিখিত ধরনের তথ্য সংগ্রহ করতে পারি:</p>
<ul>
    <li><strong>ব্যক্তিগত পরিচয় তথ্য:</strong> নাম, ইমেইল ঠিকানা, ফোন নম্বর, ডেলিভারি ঠিকানা।</li>
    <li><strong>লেনদেন তথ্য:</strong> অর্ডার ইতিহাস, পেমেন্ট পদ্ধতি (কার্ডের সম্পূর্ণ তথ্য আমরা সংরক্ষণ করি না)।</li>
    <li><strong>ডিভাইস ও ব্যবহার তথ্য:</strong> IP ঠিকানা, ব্রাউজার ধরন, পেজ ভিজিট, কুকিজ।</li>
</ul>

<h2>২. আমরা তথ্য কীভাবে ব্যবহার করি</h2>
<ul>
    <li>অর্ডার প্রক্রিয়া এবং ডেলিভারি সম্পন্ন করতে।</li>
    <li>গ্রাহক সেবা প্রদান ও সমস্যা সমাধান করতে।</li>
    <li>প্রাসঙ্গিক অফার ও আপডেট পাঠাতে (আনসাবস্ক্রাইব বিকল্পসহ)।</li>
    <li>সাইটের নিরাপত্তা ও কার্যকারিতা উন্নত করতে।</li>
</ul>

<h2>৩. তথ্য শেয়ারিং</h2>
<p>আমরা আপনার ব্যক্তিগত তথ্য তৃতীয় পক্ষের কাছে বিক্রি করি না। আমরা নিম্নলিখিত ক্ষেত্রে সীমিতভাবে তথ্য শেয়ার করতে পারি:</p>
<ul>
    <li>ডেলিভারি কুরিয়ার পার্টনারদের সাথে (ডেলিভারি সম্পন্ন করতে)।</li>
    <li>পেমেন্ট গেটওয়ে প্রদানকারীদের সাথে (পেমেন্ট প্রক্রিয়ার জন্য)।</li>
    <li>আইনগত বাধ্যবাধকতা পূরণে।</li>
</ul>

<h2>৪. কুকিজ</h2>
<p>আমরা সাইটের কার্যকারিতা উন্নত করতে এবং আপনার পছন্দ মনে রাখতে কুকিজ ব্যবহার করি। আপনি ব্রাউজার সেটিংস থেকে কুকিজ নিয়ন্ত্রণ করতে পারেন, তবে কিছু সুবিধা সীমিত হতে পারে।</p>

<h2>৫. ডেটা সুরক্ষা</h2>
<p>আমরা আপনার তথ্য সুরক্ষায় শিল্পমানের নিরাপত্তা ব্যবস্থা ব্যবহার করি, যার মধ্যে SSL এনক্রিপশন অন্তর্ভুক্ত। তবে ইন্টারনেটে কোনো পদ্ধতিই ১০০% নিরাপদ নয়।</p>

<h2>৬. আপনার অধিকার</h2>
<ul>
    <li>আপনার সংরক্ষিত তথ্য অ্যাক্সেস করার অধিকার।</li>
    <li>ভুল তথ্য সংশোধনের অধিকার।</li>
    <li>নির্দিষ্ট পরিস্থিতিতে তথ্য মুছে ফেলার অনুরোধ।</li>
    <li>মার্কেটিং যোগাযোগ থেকে অপ্ট-আউট।</li>
</ul>
<p>এই অধিকারগুলো প্রয়োগ করতে <a href="{{ route('page.contact') }}">আমাদের সাথে যোগাযোগ করুন</a>।</p>

<h2>৭. শিশুদের গোপনীয়তা</h2>
<p>আমাদের পরিষেবা ১৮ বছরের কম বয়সীদের জন্য নয়। আমরা জেনেশুনে শিশুদের ব্যক্তিগত তথ্য সংগ্রহ করি না।</p>

<h2>৮. নীতি পরিবর্তন</h2>
<p>আমরা এই নীতি সময়ে সময়ে আপডেট করতে পারি। গুরুত্বপূর্ণ পরিবর্তনের ক্ষেত্রে ইমেইলের মাধ্যমে বা সাইটে নোটিশ দিয়ে আপনাকে জানানো হবে।</p>

<h2>৯. যোগাযোগ</h2>
<p>এই গোপনীয়তা নীতি সম্পর্কে প্রশ্ন থাকলে <a href="{{ route('page.contact') }}">আমাদের যোগাযোগ পাতায়</a> যান।</p>

    @else

<h2>1. Information We Collect</h2>
<p>We may collect the following types of information:</p>
<ul>
    <li><strong>Personal Identification:</strong> Name, email address, phone number, and delivery address.</li>
    <li><strong>Transaction Data:</strong> Order history and payment method (we do not store full card details).</li>
    <li><strong>Device &amp; Usage Data:</strong> IP address, browser type, pages visited, and cookies.</li>
</ul>

<h2>2. How We Use Your Information</h2>
<ul>
    <li>To process and fulfill your orders.</li>
    <li>To provide customer support and resolve issues.</li>
    <li>To send relevant offers and updates (with an unsubscribe option).</li>
    <li>To improve the security and performance of our website.</li>
</ul>

<h2>3. Information Sharing</h2>
<p>We do not sell your personal data to third parties. We may share data on a limited basis with:</p>
<ul>
    <li>Delivery courier partners (to complete your delivery).</li>
    <li>Payment gateway providers (to process payments).</li>
    <li>Authorities when required by law.</li>
</ul>

<h2>4. Cookies</h2>
<p>We use cookies to improve site functionality and remember your preferences. You can control cookies through your browser settings, but some features may be affected.</p>

<h2>5. Data Security</h2>
<p>We use industry-standard security measures including SSL encryption to protect your data. However, no method of transmission over the internet is 100% secure.</p>

<h2>6. Your Rights</h2>
<ul>
    <li>Right to access the personal data we hold about you.</li>
    <li>Right to correct inaccurate information.</li>
    <li>Right to request deletion of your data in certain circumstances.</li>
    <li>Right to opt out of marketing communications.</li>
</ul>
<p>To exercise these rights, <a href="{{ route('page.contact') }}">contact us</a>.</p>

<h2>7. Children's Privacy</h2>
<p>Our services are not directed to persons under 18 years of age. We do not knowingly collect personal data from children.</p>

<h2>8. Changes to This Policy</h2>
<p>We may update this policy from time to time. We will notify you of significant changes via email or a prominent notice on our website.</p>

<h2>9. Contact Us</h2>
<p>If you have questions about this Privacy Policy, visit our <a href="{{ route('page.contact') }}">Contact page</a>.</p>

    @endif

    </div>
</section>

@endsection
