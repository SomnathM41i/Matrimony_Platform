<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactFormController extends Controller
{
    public function index(Request $request): View
    {
        $contacts = ContactForm::when($request->status, fn($q) => $q->where('status', $request->status))
                               ->latest()->paginate(20);
        return view('admin.contacts.index', compact('contacts'));
    }
    public function show(ContactForm $contact): View { return view('admin.contacts.show', compact('contact')); }
    public function reply(Request $request, ContactForm $contact): RedirectResponse
    {
        $request->validate(['reply' => 'required|string']);
        // Send mail / update record
        $contact->update(['status' => 'replied', 'admin_notes' => $request->reply, 'replied_at' => now()]);
        return back()->with('success', 'Reply sent.');
    }
    public function destroy(ContactForm $contact): RedirectResponse { $contact->delete(); return redirect()->route('admin.contacts.index')->with('success', 'Deleted.'); }
}
