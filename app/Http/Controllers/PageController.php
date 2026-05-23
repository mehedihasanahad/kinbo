<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormSubmission;
use App\Models\BlogPost;
use App\Models\ContactSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSend(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|min:10|max:2000',
        ]);

        $submission = ContactSubmission::create($validated);

        $adminEmail = User::role('super_admin')->value('email');

        if ($adminEmail) {
            Mail::to($adminEmail)->queue(new ContactFormSubmission($submission));
        }

        return back()->with('contact_sent', true);
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function termsConditions(): \Illuminate\View\View
    {
        return view('pages.terms-conditions');
    }

    public function blog()
    {
        $locale = app()->getLocale();
        $posts = BlogPost::published()
            ->forLocale($locale)
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('pages.blog', compact('posts'));
    }

    public function blogPost(string $slug)
    {
        $locale = app()->getLocale();
        $post = BlogPost::published()
            ->forLocale($locale)
            ->where('slug', $slug)
            ->firstOrFail();

        $related = BlogPost::published()
            ->forLocale($locale)
            ->where('id', '!=', $post->id)
            ->when($post->category, fn ($q) => $q->where('category', $post->category))
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('pages.blog-post', compact('post', 'related'));
    }
}
