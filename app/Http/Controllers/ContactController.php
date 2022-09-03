<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contacts = Contact::where('customercode',$request->id)->first();
        $master_branch = DB::table('acme_customer_branches_master')->orderBy('branchname','asc')->get();
        if($contacts == null)
        {
            return view('master.customer.contact.create', compact('contacts', 'master_branch', 'request') );
        }
        $contact = Contact::where('customercode',$request->id)->simplePaginate(10);
        // return $contact;
        return view('master.customer.contact.index',compact('contacts', 'contact'))->with('i', 1);
    }

    public function create(Request $request)
    {
        $master_branch = DB::table('acme_customer_branches_master')->where('customercode', $request->id)->orderBy('branchname','asc')->get();
        return view('master.customer.contact.create', compact('master_branch', 'request'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'contactpersonname' => 'required|string',
            'phoneno' => 'required|numeric|digits:10',
            'mobileno' => 'required|numeric|digits:10',
            'emailid' => 'required|email',
            'branch' => 'required',
        ]);
        
        Contact::create($request->all());
        return redirect()->route('contact.index',['id' => $request->customercode])
                        ->with('success','Contact created successfully.');
    }

    public function show($id)
    {
        //
    }

    public function edit(Contact $contact)
    {
        $branch_master = Contact::join('acme_customer_branches_master','acme_customer_contact_master.branch', '=', 'acme_customer_branches_master.owncode')
                            ->where('acme_customer_contact_master.owncode',$contact->owncode)
                           ->get('acme_customer_branches_master.*');   
                            //   return $branch_master;
        $branch = DB::table('acme_customer_branches_master')->where('customercode', $contact->owncode)->orderBy('branchname','asc')->get();
        // return $branch;
        return view('master.customer.contact.edit',compact('contact', 'branch_master', 'branch'));
    }

    public function update(Request $request, Contact $contact)
    {
    //  return $request;
        $request->validate([
            'contactpersonname' => 'required|string',
            'phoneno' => 'required|numeric|digits:10',
            'mobileno' => 'required|numeric|digits:10',
            'emailid' => 'required|email',
            'branch' => '',
        ]);
        // return $request->customercode;
        $contact->update($request->all());
        // return  $contact->owncode;
        return redirect()->route('contact.index', ['id' => $request->customercode])
                        ->with('success','Contact updated successfully');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contact.index', ['id' => $contact->customercode])
                        ->with('success','Contact deleted successfully');
    }
}
