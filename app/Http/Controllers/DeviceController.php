<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceAsset;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function all(Request $request)
    {
        $models     = Device::query()
                    ->when($request->q, function($query) use ($request) {
                        return $query->where(function($q) use ($request) {
                            return $q->where('name', 'LIKE', '%' . $request->q . '%')
                                ->orWhere('sn', '=', $request->q);
                        });
                    })
//                    ->with(['agents'])
                    ->paginate(15);

        return view('device.all', compact('models'));
    }

    public function company(Request $request)
    {

    }

    public function assets(Request $request, $sn)
    {
        /** @var Device $model */
        $model      = Device::findOrFail($sn);

        /** @var DeviceAsset $agent */
        foreach($model->agents as $agent) {
            $agent->backups = $agent->getLastDailyBackups();
        }

        return response()->json($model);
    }
}
