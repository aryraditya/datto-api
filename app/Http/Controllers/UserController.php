<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $models     = User::orderBy('created_at', 'desc')->paginate(50);

        return view('users.index', compact('models'));
    }

    public function create(Request $request)
    {
        $model      = new User();

        return view('users.form', compact('model'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'nullable|max:20',
            'name'      => 'required|max:125',
            'email'     => 'required|max:125|unique:users,email',
            'password'  => 'required',
        ]);

        DB::transaction(function() use ($request) {
            $model      = new User();
            $companies  = [];

            $model->fill($request->input());
            $model->password = \Hash::make($request->password);
            $model->save();

            $company_type   = $request->input('company_type', 'selected');

            if($company_type == 'selected')
                $companies  = $request->input('company_id', []);
            else if($company_type == 'all')
                $companies  = Company::all('id')->toArray();

            $model->companies()->sync($companies);
        });

        return redirect()->route('user.index');
    }

    public function edit(Request $request, $id)
    {
        $model      = User::findOrFail($id);

        return view('users.form', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model          = User::findOrFail($id);
        $request->validate([
            'title'     => 'nullable|max:20',
            'name'      => 'required|max:125',
            'email'     => 'required|max:125|unique:users,email,'.$model->id,
            'password'  => 'nullable',
        ]);

        DB::transaction(function() use ($request, $id, $model) {
            /** @var User $model */
            $companies      = [];
            $company_type   = $request->input('company_type', 'selected');

            $model->fill($request->except('password'));

            if($request->password)
                $model->password = \Hash::make($request->password);

            $model->save();

            if($company_type == 'selected')
                $companies  = $request->input('company_id', []);
            else if($company_type == 'all')
                $companies  = Company::all('id')->toArray();

            $model->companies()->sync($companies);
        });

        return redirect()->route('user.index');
    }

    public function destroy(Request $request, $id)
    {

    }
}
