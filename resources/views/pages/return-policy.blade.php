@extends('layouts.app')

@section('title', 'Return & Exchange Policy — ' . config('app.name'))

@section('content')

{{-- Page Header --}}
<div class="bg-gray-50 border-b border-gray-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-extrabold text-gray-900 uppercase tracking-wide">Return &amp; Exchange Policy</h1>
    </div>
</div>

{{-- Content --}}
<div class="bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="privacy-content">

            <h2>Discount Sales</h2>
            <p><strong>Our return &amp; exchange policy shall not be applicable for any discount sales product(s). Any discount item once purchased cannot be exchanged or returned. No return or exchange is applicable for purchases made during the campaign period.</strong></p>

            <p>We offer on spot return facility. If for any reason you are unsatisfied with your purchase, simply return the goods in their original packaging and condition to the delivery person by paying delivery charge. Also We do not offer any partial delivery.</p>

            <p>We give our best effort to ensure that the product(s) you ordered online meets your expectations, but occasionally orders may reach you in a manner that is not anticipated. We will rectify any such claims or discrepancies related to your purchase. Any product would qualify as an Exchange if it meets any of the following condition(s):</p>

            <ul>
                <li>Products with major quality defects.</li>
                <li>Products damaged during shipment.</li>
                <li>Wrong product, size or color.</li>
                <li>Product lost in shipment.</li>
                <li>You are requested to inform Customer Service for any change within 7 days of receiving the product.</li>
                <li>Items that you want to exchange or return must be unworn, unwashed and unused with all original tags attached. Items that are opened or damaged or do not have a receipt may be denied an exchange.</li>
            </ul>

            <p><strong>Please note that the value of the exchanged product should be of similar or higher value than the original product price. If exchanging for a higher value item, the purchase value difference must be paid.</strong></p>
        </div>
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
