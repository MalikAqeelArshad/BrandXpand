<?php

namespace App\Http\Controllers\Admin;

use App\Contact;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('id','desc')->paginate(10);
        return view('admin.contacts.index',compact('contacts'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        flash('success', "Contact has been deleted successfully.");
        return back();
    }

}
