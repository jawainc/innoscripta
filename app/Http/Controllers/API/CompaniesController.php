<?php

namespace App\Http\Controllers\API;

use App\Company;
use App\Http\Requests\StoreCompany;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyNameResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\InvoiceResource;
use App\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    /**
     * get all invoices
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list_all ()
    {
        $invoices = Invoice::orderBy('date', 'DESC')->get();
        return InvoiceResource::collection($invoices);
    }

    /**
     * search invoices with year and month
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search (Request $request) {
        $year = $request->year;
        $month = $request->month;

        $query = Invoice::query();
        $query->whereYear('date', '=', $year);
        if ($month != 'All Months') {
            $month = Carbon::parse("1 $month")->month;
            $query->whereMonth('date', '=', $month);
        }

        $query->orderBy('date', 'DESC');
        $results = $query->get();

        return InvoiceResource::collection($results);

    }

    /**
     * get single company and its invoices
     *
     * @param Request $request
     * @return CompanyResource
     */
    public function show (Request $request) {
        return new CompanyResource(Company::find($request->id));
    }

    /**
     * Create company
     *
     * @param StoreCompany $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store (StoreCompany $request) {

        $company = new Company();
        $company->name = $request->name;
        $company->customerNumber = $request->customerNumber;
        $company->cost = $request->cost;
        $company->street = $request->street;
        $company->save();

        // create company invoices
        $company->invoices()->createMany($request->bills);

        return response()->json($company, 201);

    }

    /**
     * update company
     *
     * @param StoreCompany $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (StoreCompany $request) {

        $company = Company::find($request->id);
        if ($company) {
            $company->name = $request->name;
            $company->customerNumber = $request->customerNumber;
            $company->cost = $request->cost;
            $company->street = $request->street;
            $company->save();

            // add new invoices to company
            $company->invoices()->createMany($request->bills);
        }

        return response()->json($company, 201);

    }

    /**
     * update single invoice
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_bill (Request $request) {

        $request->validate([
            'date' => 'required',
            'amount' => 'required',
        ]);

        $bill = Invoice::find($request->id);
        if ($bill) {
            $bill->billNumber = $request->billNumber;
            $bill->amount = $request->amount;
            $bill->purpose = $request->purpose;
            $bill->date = $request->date;
            $bill->save();
        }
        return response()->json(null, 204);
    }

    /**
     * search for company name by name keyword
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search_names (Request $request)
    {
        $names = Company::select('id','name')
            ->whereRaw("UPPER(name) LIKE '%". strtoupper($request->name)."%'")
            ->orderBy('name', 'ASC')->get();
        return CompanyNameResource::collection($names);
    }

    /**
     * delete invoice
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove_bill (Request $request)
    {
        Invoice::destroy($request->id);
        return response()->json(null, 204);
    }
}
