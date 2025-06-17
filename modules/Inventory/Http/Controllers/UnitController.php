<?php

namespace Modules\Inventory\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Jobs\Common\CreateUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Inventory\Models\Unit;

class UnitController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:create-inventory-main')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-inventory-main')->only('index', 'show', 'export');
        $this->middleware('permission:udpate-inventory-main')->only('enable', 'disable');
        $this->middleware('permission:delete-inventory-main')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $units = Unit::all()->collect();
        return $this->response('inventory::units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('inventory::units.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $response = $this->ajaxDispatch(new CreateUnit($request));

        if ($response['success']) {
            $response['redirect'] = route('inventory.units.index');

            $message = trans('messages.success.created', ['type' => trans_choice('general.inventory.units', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('inventory.units.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('inventory::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('inventory::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
