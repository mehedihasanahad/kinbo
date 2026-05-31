@extends('layouts.app')

@section('title', __('front.page_privacy_title') . ' — ' . config('app.name'))

@section('content')

{{-- Page Header --}}
<div class="bg-gray-50 border-b border-gray-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-extrabold text-gray-900 uppercase tracking-wide">{{ __('front.page_privacy_title') }}</h1>
        {{-- <p class="text-sm text-gray-500 mt-1">{{ __('front.page_privacy_updated') }}</p> --}}
    </div>
</div>

{{-- Content --}}
<div class="bg-white min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @php $locale = app()->getLocale(); @endphp

        @if($locale === 'bn')

        <div class="privacy-content">

            <p>ইউথ কালেকশনস-এ আপনার গোপনীয়তা আমাদের সর্বোচ্চ অগ্রাধিকারগুলোর একটি।</p>

            <h2>১. আমরা কোন তথ্য সংগ্রহ করি</h2>
            <p>আমরা নিম্নলিখিত ধরনের তথ্য সংগ্রহ করতে পারি:</p>
            <ul>
                <li><strong>ব্যক্তিগত পরিচয় তথ্য:</strong> নাম, ইমেইল ঠিকানা, ফোন নম্বর, ডেলিভারি ঠিকানা।</li>
                <li><strong>লেনদেন তথ্য:</strong> অর্ডার ইতিহাস, পেমেন্ট পদ্ধতি।</li>
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
            <p>আমরা আপনার ব্যক্তিগত তথ্য তৃতীয় পক্ষের কাছে বিক্রি করি না।</p>
            <ul>
                <li>ডেলিভারি কুরিয়ার পার্টনারদের সাথে (ডেলিভারি সম্পন্ন করতে)।</li>
                <li>পেমেন্ট গেটওয়ে প্রদানকারীদের সাথে (পেমেন্ট প্রক্রিয়ার জন্য)।</li>
                <li>আইনগত বাধ্যবাধকতা পূরণে।</li>
            </ul>

            <h2>৪. কুকিজ</h2>
            <p>আমরা সাইটের কার্যকারিতা উন্নত করতে এবং আপনার পছন্দ মনে রাখতে কুকিজ ব্যবহার করি।</p>

            <h2>৫. ডেটা সুরক্ষা</h2>
            <p>আমরা আপনার তথ্য সুরক্ষায় শিল্পমানের নিরাপত্তা ব্যবস্থা ব্যবহার করি, যার মধ্যে SSL এনক্রিপশন অন্তর্ভুক্ত।</p>

            <h2>৬. আপনার অধিকার</h2>
            <ul>
                <li>আপনার সংরক্ষিত তথ্য অ্যাক্সেস করার অধিকার।</li>
                <li>ভুল তথ্য সংশোধনের অধিকার।</li>
                <li>নির্দিষ্ট পরিস্থিতিতে তথ্য মুছে ফেলার অনুরোধ।</li>
                <li>মার্কেটিং যোগাযোগ থেকে অপ্ট-আউট।</li>
            </ul>
            <p>এই অধিকারগুলো প্রয়োগ করতে <a href="{{ route('page.contact') }}">আমাদের সাথে যোগাযোগ করুন</a>।</p>

            <h2>৭. শিশুদের গোপনীয়তা</h2>
            <p>আমাদের পরিষেবা ১৩ বছরের কম বয়সীদের জন্য নয়। আমরা জেনেশুনে শিশুদের ব্যক্তিগত তথ্য সংগ্রহ করি না।</p>

            <h2>৮. নীতি পরিবর্তন</h2>
            <p>আমরা এই নীতি সময়ে সময়ে আপডেট করতে পারি। গুরুত্বপূর্ণ পরিবর্তনের ক্ষেত্রে ইমেইলের মাধ্যমে বা সাইটে নোটিশ দিয়ে আপনাকে জানানো হবে।</p>

            <h2>৯. যোগাযোগ</h2>
            <p>এই গোপনীয়তা নীতি সম্পর্কে প্রশ্ন থাকলে <a href="{{ route('page.contact') }}">আমাদের যোগাযোগ পাতায়</a> যান।</p>

        </div>

        @else

        <div class="privacy-content">

            <p>At {{ config('app.name') }}, accessible from <strong>{{ config('app.url') }}</strong>, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by {{ config('app.name') }} and how we use it.</p>
            <p>If you have additional questions or require more information about our Privacy Policy, do not hesitate to <a href="{{ route('page.contact') }}">contact us</a>.</p>
            <p>This Privacy Policy applies only to our online activities and is valid for visitors to our website with regards to the information that they shared and/or collect in {{ config('app.name') }}. This policy is not applicable to any information collected offline or via channels other than this website.</p>

            <h2>Consent</h2>
            <p>By using our website, you hereby consent to our Privacy Policy and agree to its terms.</p>

            <h2>Information we collect</h2>
            <p>The personal information that you are asked to provide, and the reasons why you are asked to provide it, will be made clear to you at the point we ask you to provide your personal information.</p>
            <p>If you contact us directly, we may receive additional information about you such as your name, email address, phone number, the contents of the message and/or attachments you may send us, and any other information you may choose to provide.</p>
            <p>When you register for an Account, we may ask for your contact information, including items such as name, company name, address, email address, and telephone number.</p>

            <h2>How we use your information</h2>
            <p>We use the information we collect in various ways, including to:</p>
            <ul>
                <li>Provide, operate, and maintain our website</li>
                <li>Improve, personalize, and expand our website</li>
                <li>Understand and analyze how you use our website</li>
                <li>Develop new products, services, features, and functionality</li>
                <li>Communicate with you, either directly or through one of our partners, including for customer service, to provide you with updates and other information relating to the website, and for marketing and promotional purposes</li>
                <li>Send you emails</li>
                <li>Find and prevent fraud</li>
            </ul>

            <h2>Log Files</h2>
            <p>{{ config('app.name') }} follows a standard procedure of using log files. These files log visitors when they visit websites. All hosting companies do this and a part of hosting services' analytics. The information collected by log files include internet protocol (IP) addresses, browser type, Internet Service Provider (ISP), date and time stamp, referring/exit pages, and possibly the number of clicks. These are not linked to any information that is personally identifiable. The purpose of the information is for analyzing trends, administering the site, tracking users' movement on the website, and gathering demographic information.</p>

            <h2>Advertising Partners Privacy Policies</h2>
            <p>You may consult this list to find the Privacy Policy for each of the advertising partners of {{ config('app.name') }}.</p>
            <p>Third-party ad servers or ad networks uses technologies like cookies, JavaScript, or Web Beacons that are used in their respective advertisements and links that appear on {{ config('app.name') }}, which are sent directly to users' browser. They automatically receive your IP address when this occurs. These technologies are used to measure the effectiveness of their advertising campaigns and/or to personalize the advertising content that you see on websites that you visit.</p>
            <p>Note that {{ config('app.name') }} has no access to or control over these cookies that are used by third-party advertisers.</p>

            <h2>Third Party Privacy Policies</h2>
            <p>{{ config('app.name') }}'s Privacy Policy does not apply to other advertisers or websites. Thus, we are advising you to consult the respective Privacy Policies of these third-party ad servers for more detailed information. It may include their practices and instructions about how to opt-out of certain options.</p>
            <p>You can choose to disable cookies through your individual browser options. To know more detailed information about cookie management with specific web browsers, it can be found at the browsers' respective websites.</p>

            <h2>CCPA Privacy Rights (Do Not Sell My Personal Information)</h2>
            <p>Under the CCPA, among other rights, California consumers have the right to:</p>
            <ul>
                <li>Request that a business that collects a consumer's personal data disclose the categories and specific pieces of personal data that a business has collected about consumers.</li>
                <li>Request that a business delete any personal data about the consumer that a business has collected.</li>
                <li>Request that a business that sells a consumer's personal data, not sell the consumer's personal data.</li>
            </ul>
            <p>If you make a request, we have one month to respond to you. If you would like to exercise any of these rights, please <a href="{{ route('page.contact') }}">contact us</a>.</p>

            <h2>GDPR Data Protection Rights</h2>
            <p>We would like to make sure you are fully aware of all of your data protection rights. Every user is entitled to the following:</p>
            <ul>
                <li><strong>The right to access</strong> – You have the right to request copies of your personal data. We may charge you a small fee for this service.</li>
                <li><strong>The right to rectification</strong> – You have the right to request that we correct any information you believe is inaccurate. You also have the right to request that we complete the information you believe is incomplete.</li>
                <li><strong>The right to erasure</strong> – You have the right to request that we erase your personal data, under certain conditions.</li>
                <li><strong>The right to restrict processing</strong> – You have the right to request that we restrict the processing of your personal data, under certain conditions.</li>
                <li><strong>The right to object to processing</strong> – You have the right to object to our processing of your personal data, under certain conditions.</li>
                <li><strong>The right to data portability</strong> – You have the right to request that we transfer the data that we have collected to another organization, or directly to you, under certain conditions.</li>
            </ul>
            <p>If you make a request, we have one month to respond to you. If you would like to exercise any of these rights, please <a href="{{ route('page.contact') }}">contact us</a>.</p>

            <h2>Children's Information</h2>
            <p>Another part of our priority is adding protection for children while using the internet. We encourage parents and guardians to observe, participate in, and/or monitor and guide their online activity.</p>
            <p>{{ config('app.name') }} does not knowingly collect any Personal Identifiable Information from children under the age of 13. If you think that your child provided this kind of information on our website, we strongly encourage you to <a href="{{ route('page.contact') }}">contact us</a> immediately and we will do our best efforts to promptly remove such information from our records.</p>

        </div>

        @endif
    </div>
</div>

@push('styles')
<style>
    .privacy-content p {
        color: #4b5563;
        font-size: 14px;
        line-height: 1.75;
        margin-bottom: 14px;
    }
    .privacy-content h2 {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin-top: 36px;
        margin-bottom: 12px;
    }
    .privacy-content ul {
        list-style: disc;
        padding-left: 20px;
        margin-bottom: 14px;
    }
    .privacy-content ul li {
        color: #4b5563;
        font-size: 14px;
        line-height: 1.75;
        margin-bottom: 6px;
    }
    .privacy-content strong {
        color: #1f2937;
        font-weight: 600;
    }
    .privacy-content a {
        color: #c4155c;
        font-weight: 600;
        text-decoration: none;
    }
    .privacy-content a:hover {
        text-decoration: underline;
    }
</style>
@endpush

@endsection
