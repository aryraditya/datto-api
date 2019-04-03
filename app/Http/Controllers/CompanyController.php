<?php

namespace App\Http\Controllers;

use App\Exports\CompanyReport;
use App\Exports\CompanyReportAsset;
use App\Exports\CompanyReportRegional;
use App\Exports\CompanyReportStorage;
use App\Models\Company;
use App\Models\Device;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models     = Company::orderBy('name', 'asc')->paginate(50);

        return view('company.index', compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model      = new Company();
        $devices    = Device::all('sn', 'name')->makeHidden(['storage_capacity']);

        return view('company.form', compact('model', 'devices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model      = new Company();

        $request->validate([
            'name'  => 'required|max:125',
        ]);

        $model->name    = $request->input('name');
        $model->save();

        return redirect()->route('company.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model      = Company::findOrFail($id);
        $devices    = Device::all('sn', 'name')->makeHidden(['storage_capacity']);

        return view('company.form', compact('model', 'devices'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /** @var Company $model */
        $model      = Company::findOrFail($id);

        $request->validate([
            'name'  => 'required|max:125'
        ]);

        $model->name    = $request->input('name');
        $model->save();

        $model->devices()->sync($request->input('devices', []));

        return redirect()->route('company.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model      = Company::findOrFail($id);

        $model->delete();

        return back();
    }

    public function reportRegional(Request $requst, $id)
    {
        $model      = Company::findOrFail($id);
        $format     = \Maatwebsite\Excel\Excel::XLSX;
        $name       = $model->name . '_REGIONAL_REPORT_' . now()->format('Ymd') .'.' .strtolower($format);

        return Excel::download(new CompanyReport($model), $name, $format);
    }

    public function reportAsset(Request $request, $id)
    {
        $model      = Company::findOrFail($id);
        $format     = \Maatwebsite\Excel\Excel::XLSX;
        $name       = $model->name . '_ASSET_REPORT_' . now()->format('Ymd') .'.' .strtolower($format);

        return Excel::download(new CompanyReportAsset($model), $name, $format);
    }

    public function reportStorage(Request $request, $id)
    {
        $model      = Company::findOrFail($id);
        $format     = \Maatwebsite\Excel\Excel::XLSX;
        $name       = $model->name . '_STORAGE_REPORT_' . now()->format('Ymd') .'.' .strtolower($format);

        return Excel::download(new CompanyReportStorage($model), $name, $format);
    }
}
