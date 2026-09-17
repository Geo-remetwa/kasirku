<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CompanyProfile;

use App\Http\Requests\CompanyProfileRequest;

class CompanyProfileController extends Controller
{
    public function index(){
        $title = "Company Profile";

        $item = CompanyProfile::find(1);

        return view('pages.companyProfile.index', [
            'title' => $title,
            'item' => $item
        ]);
    }

    public function save(CompanyProfileRequest $request){
        $data = $request->only(['name', 'address', 'contact']);
        $image = $request->file('image');

        if ($image){
            $data['image'] = $image->storeAs(
                'assets/company', 'company.jpg', 'public'
            );
        }

        $currentProfile = CompanyProfile::find(1);

        if (!$currentProfile){
            $data['image'] ??= '';
            CompanyProfile::create($data);
        }else{
            // Only overwrite the stored image when a new one was uploaded,
            // otherwise saving the form again would wipe out the existing logo.
            $currentProfile->update($data);
        }

        return redirect()->route('companyProfile.index')->with('success', 'Profil toko berhasil disimpan!');
    }
}
