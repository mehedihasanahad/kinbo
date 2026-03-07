@extends('layouts.app')

@section('title', __('front.page_faq_title') . ' — ' . config('app.name'))
@section('meta_description', __('front.page_faq_subtitle'))

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 text-white py-16 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-16 -right-16 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
    </div>
    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block bg-accent-500/20 text-accent-300 text-xs font-bold px-4 py-1.5 rounded-full mb-5 tracking-widest uppercase">
            {{ __('front.page_faq_badge') }}
        </span>
        <h1 class="text-4xl sm:text-5xl font-extrabold mb-4">{{ __('front.page_faq_title') }}</h1>
        <p class="text-primary-200 text-lg">{{ __('front.page_faq_subtitle') }}</p>
    </div>
</section>

<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    @php
    $locale = app()->getLocale();
    $faqs = [
        'ordering' => [
            'title_en' => 'Ordering & Payment',
            'title_bn' => 'অর্ডার ও পেমেন্ট',
            'items' => [
                [
                    'q_en' => 'How do I place an order?',
                    'q_bn' => 'কীভাবে অর্ডার দেব?',
                    'a_en' => 'Browse our products, add items to your cart, then proceed to checkout. Fill in your delivery address, choose a shipping method and payment option, then place your order. You\'ll receive a confirmation immediately.',
                    'a_bn' => 'আমাদের পণ্য দেখুন, কার্টে যোগ করুন, তারপর চেকআউটে যান। ডেলিভারি ঠিকানা, শিপিং পদ্ধতি ও পেমেন্ট অপশন পূরণ করে অর্ডার দিন। আপনি সাথে সাথে নিশ্চিতকরণ পাবেন।',
                ],
                [
                    'q_en' => 'What payment methods are accepted?',
                    'q_bn' => 'কোন পেমেন্ট পদ্ধতি গ্রহণ করা হয়?',
                    'a_en' => 'We accept Cash on Delivery (COD), bKash, Nagad, and online card payments via SSLCommerz (Visa, Mastercard).',
                    'a_bn' => 'আমরা ক্যাশ অন ডেলিভারি (COD), বিকাশ, নগদ এবং SSLCommerz-এর মাধ্যমে অনলাইন কার্ড পেমেন্ট (Visa, Mastercard) গ্রহণ করি।',
                ],
                [
                    'q_en' => 'Can I modify or cancel my order after placing it?',
                    'q_bn' => 'অর্ডার দেওয়ার পর কি পরিবর্তন বা বাতিল করা যাবে?',
                    'a_en' => 'You can request a modification or cancellation within 1 hour of placing your order by contacting our support team. Once an order is shipped, it cannot be cancelled.',
                    'a_bn' => 'অর্ডার দেওয়ার ১ ঘণ্টার মধ্যে সাপোর্টে যোগাযোগ করে পরিবর্তন বা বাতিলের অনুরোধ করতে পারবেন। পণ্য পাঠানো হয়ে গেলে বাতিল করা সম্ভব নয়।',
                ],
                [
                    'q_en' => 'Is my payment information secure?',
                    'q_bn' => 'আমার পেমেন্ট তথ্য কি নিরাপদ?',
                    'a_en' => 'Yes. We use SSL encryption and process all card payments through SSLCommerz, a PCI-DSS compliant payment gateway. We never store your card details.',
                    'a_bn' => 'হ্যাঁ। আমরা SSL এনক্রিপশন ব্যবহার করি এবং সব কার্ড পেমেন্ট PCI-DSS সম্মত পেমেন্ট গেটওয়ে SSLCommerz-এর মাধ্যমে প্রক্রিয়া করি। আমরা আপনার কার্ডের তথ্য সংরক্ষণ করি না।',
                ],
            ],
        ],
        'shipping' => [
            'title_en' => 'Shipping & Delivery',
            'title_bn' => 'শিপিং ও ডেলিভারি',
            'items' => [
                [
                    'q_en' => 'How long does delivery take?',
                    'q_bn' => 'ডেলিভারি কত দিন লাগে?',
                    'a_en' => 'Delivery times vary by district. Dhaka city orders typically arrive within 1–3 business days. Outside Dhaka may take 3–7 business days.',
                    'a_bn' => 'জেলা অনুযায়ী ডেলিভারির সময় ভিন্ন। ঢাকা শহরের অর্ডার সাধারণত ১–৩ কার্যদিবসের মধ্যে পৌঁছায়। ঢাকার বাইরে ৩–৭ কার্যদিবস লাগতে পারে।',
                ],
                [
                    'q_en' => 'Is free delivery available?',
                    'q_bn' => 'বিনামূল্যে ডেলিভারি পাওয়া যায় কি?',
                    'a_en' => 'Yes! Orders above ৳999 qualify for free delivery. The exact threshold may vary during promotions.',
                    'a_bn' => 'হ্যাঁ! ৳৯৯৯-এর উপরে অর্ডারে বিনামূল্যে ডেলিভারি পাওয়া যায়। প্রমোশনের সময় সীমা ভিন্ন হতে পারে।',
                ],
                [
                    'q_en' => 'How can I track my order?',
                    'q_bn' => 'আমার অর্ডার কীভাবে ট্র্যাক করব?',
                    'a_en' => 'Log in to your account and visit "My Orders". You\'ll see the current status and tracking number (when available) for each order.',
                    'a_bn' => 'আপনার অ্যাকাউন্টে লগইন করে "আমার অর্ডার" ভিজিট করুন। প্রতিটি অর্ডারের বর্তমান স্ট্যাটাস ও ট্র্যাকিং নম্বর (পাওয়া গেলে) দেখতে পাবেন।',
                ],
            ],
        ],
        'returns' => [
            'title_en' => 'Returns & Refunds',
            'title_bn' => 'রিটার্ন ও রিফান্ড',
            'items' => [
                [
                    'q_en' => 'What is your return policy?',
                    'q_bn' => 'আপনাদের রিটার্ন পলিসি কী?',
                    'a_en' => 'You can request a return within 7 days of delivery. Items must be unused, in original packaging, and accompanied by proof of purchase. Certain categories (e.g. consumables, digital goods) are non-returnable.',
                    'a_bn' => 'ডেলিভারির ৭ দিনের মধ্যে রিটার্নের অনুরোধ করতে পারবেন। পণ্যটি অব্যবহৃত, মূল প্যাকেজিংসহ এবং ক্রয়ের প্রমাণসহ থাকতে হবে। কিছু পণ্যশ্রেণি (যেমন: ভোগ্যপণ্য, ডিজিটাল পণ্য) রিটার্নযোগ্য নয়।',
                ],
                [
                    'q_en' => 'How do I request a return?',
                    'q_bn' => 'রিটার্নের অনুরোধ কীভাবে করব?',
                    'a_en' => 'Go to "My Orders" in your account, select the relevant order, and click "Request Return". Fill in the reason and submit. Our team will review and respond within 24–48 hours.',
                    'a_bn' => 'আপনার অ্যাকাউন্টে "আমার অর্ডার"-এ যান, সংশ্লিষ্ট অর্ডার বেছে "রিটার্ন অনুরোধ"-এ ক্লিক করুন। কারণ লিখে জমা দিন। আমাদের টিম ২৪–৪৮ ঘণ্টার মধ্যে উত্তর দেবে।',
                ],
                [
                    'q_en' => 'How long does a refund take?',
                    'q_bn' => 'রিফান্ড পেতে কত দিন লাগে?',
                    'a_en' => 'Once your return is approved, refunds are processed within 3–5 business days to your original payment method.',
                    'a_bn' => 'রিটার্ন অনুমোদিত হওয়ার পর মূল পেমেন্ট পদ্ধতিতে ৩–৫ কার্যদিবসের মধ্যে রিফান্ড প্রক্রিয়া করা হয়।',
                ],
            ],
        ],
        'account' => [
            'title_en' => 'Account & Privacy',
            'title_bn' => 'অ্যাকাউন্ট ও গোপনীয়তা',
            'items' => [
                [
                    'q_en' => 'How do I create an account?',
                    'q_bn' => 'অ্যাকাউন্ট কীভাবে তৈরি করব?',
                    'a_en' => 'Click "Register" in the top navigation, enter your name, email, and password. You can also sign in with Google if enabled.',
                    'a_bn' => 'শীর্ষ নেভিগেশনে "নিবন্ধন করুন"-এ ক্লিক করুন, নাম, ইমেইল ও পাসওয়ার্ড দিন। Google দিয়ে সাইন ইনও করা যেতে পারে।',
                ],
                [
                    'q_en' => 'Is my personal data safe?',
                    'q_bn' => 'আমার ব্যক্তিগত তথ্য কি নিরাপদ?',
                    'a_en' => 'Absolutely. We never sell your data to third parties. Please read our Privacy Policy for full details on how we collect, use, and protect your information.',
                    'a_bn' => 'অবশ্যই। আমরা কখনো আপনার তথ্য তৃতীয় পক্ষের কাছে বিক্রি করি না। আমরা কীভাবে তথ্য সংগ্রহ, ব্যবহার ও সুরক্ষা করি তার পূর্ণ বিবরণের জন্য আমাদের গোপনীয়তা নীতি পড়ুন।',
                ],
            ],
        ],
    ];
    @endphp

    <div class="space-y-10">
        @foreach($faqs as $section)
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">
                {{ $locale === 'bn' ? $section['title_bn'] : $section['title_en'] }}
            </h2>
            <div class="space-y-3">
                @foreach($section['items'] as $i => $item)
                <details class="group bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                    <summary class="flex items-center justify-between px-5 py-4 cursor-pointer select-none list-none gap-3">
                        <span class="font-medium text-gray-800 text-sm leading-snug">
                            {{ $locale === 'bn' ? $item['q_bn'] : $item['q_en'] }}
                        </span>
                        <svg class="w-5 h-5 text-primary-500 shrink-0 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <div class="px-5 pb-4 text-sm text-gray-600 leading-relaxed border-t border-gray-50 pt-3">
                        {{ $locale === 'bn' ? $item['a_bn'] : $item['a_en'] }}
                    </div>
                </details>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    {{-- Still have questions? --}}
    <div class="mt-14 bg-primary-50 rounded-2xl p-8 text-center">
        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('front.page_faq_still_q') }}</h3>
        <p class="text-gray-500 text-sm mb-5">{{ __('front.page_faq_still_sub') }}</p>
        <a href="{{ route('page.contact') }}"
           class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-full transition-colors text-sm">
            {{ __('front.page_faq_contact_btn') }}
        </a>
    </div>

</section>

@endsection
