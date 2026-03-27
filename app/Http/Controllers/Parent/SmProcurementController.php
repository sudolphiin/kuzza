<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;

class SmProcurementController extends Controller
{
    public function procurementDashboard()
    {
        try {
            return view('backEnd.parentPanel.procurement.procurement_dashboard');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function bookList()
    {
        try {
            return view('backEnd.parentPanel.procurement.book_list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function myBidhaaLink()
    {
        try {
            // For now, let's just return a view. Later, this might redirect or display an embedded link.
            return view('backEnd.parentPanel.procurement.mybidhaa_link');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function productList()
    {
        try {
            return view('backEnd.parentPanel.procurement.product_list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function buyProduct(Request $request)
    {
        try {
            // Logic for buying a product will go here
            Toastr::success('Product purchase initiated.', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function uploadProductList(Request $request)
    {
        try {
            // Logic for uploading a list of products will go here
            Toastr::success('Product list upload initiated.', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
