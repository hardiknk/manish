<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CoupanRequest;
use App\Models\Coupan;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoupanController extends Controller
{
    public function index()
    {
        return view('admin.pages.coupan.index')->with(['custom_title' => 'Coupan']);
    }

    public function listing(Request $request)
    {
        try {
            extract($this->DTFilters($request->all()));
            $records = [];
            $coupans = Coupan::orderBy($sort_column, $sort_order);

            if ($search != '') {
                $coupans->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhereHas('state', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            }

            $count = $coupans->count();

            $records['recordsTotal'] = $count;
            $records['recordsFiltered'] = $count;
            $records['data'] = [];

            $cities = $coupans->offset($offset)->limit($limit)->orderBy($sort_column, $sort_order);

            $coupans = $coupans->get();
            foreach ($coupans as $coupan) {

                $params = [
                    'checked' => ($coupan->is_active == 'y' ? 'checked' : ''),
                    'getaction' => $coupan->is_active,
                    'class' => '',
                    'id' => $coupan->custom_id ?? "0",
                ];

                $records['data'][] = [
                    'code' => $coupan->code,
                    'percentage' => $coupan->percentage,
                    'active' => view('admin.layouts.includes.switch', compact('params'))->render(),
                    'action' => view('admin.layouts.includes.actions')->with(['custom_title' => 'Coupan', 'id' => $coupan->custom_id], $coupan)->render(),
                    'checkbox' => view('admin.layouts.includes.checkbox')->with('id', $coupan->custom_id)->render(),
                    'updated_at' => $coupan->updated_at,
                ];
            }
            return $records;
        } catch (\Exception $th) {
            dd($th->getMessage() . $th->getLine());
        }
    }

    public function create()
    {
        return view('admin.pages.coupan.create')->with(['custom_title' => 'Coupan']);
    }

    public function edit(Coupan $coupan)
    {
        return view('admin.pages.coupan.edit', compact('coupan'))->with(['custom_title' => 'Coupan']);
    }

    public function store(CoupanRequest $request)
    {
        $request['custom_id'] = getUniqueString('coupans');
        $country = Coupan::create($request->all());
        if ($country) {
            flash('Coupan code is created successfully!')->success();
        } else {
            flash('Unable to save coupan code. Please try again later.')->error();
        }
        return redirect(route('admin.coupans.index'));
    }

    public function update(CoupanRequest $request, Coupan $coupan)
    {
        try{
            DB::beginTransaction();
            if(!empty($request->action) && $request->action == 'change_status') {
                $content = ['status'=>204, 'message'=>"something went wrong"];
                if($coupan) {
                    $coupan->is_active = $request->value;
                    if($coupan->save()) {
                        DB::commit();
                        $content['status']= 200;
                        $content['message'] = "Status updated successfully.";
                    }
                }
                return response()->json($content);
            } else {
                $coupan->fill($request->all());
                if( $coupan->save() ) {
                    DB::commit();
                    flash('Coupan updated successfully!')->success();
                } else {
                    flash('Unable to update coupan. Try again later')->error();
                }
                return redirect(route('admin.coupans.index'));
            }
        }catch(QueryException $e){
            DB::rollback();
            return redirect()->back()->flash('error',$e->getMessage());
        }catch(Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        if (!empty($request->action) && $request->action == 'delete_all') {
            $content = ['status' => 204, 'message' => "something went wrong"];
            Coupan::whereIn('custom_id', explode(',', $request->ids))->delete();
            $content['status'] = 200;
            $content['message'] = "Coupan deleted successfully.";
            $content['count'] =  Coupan::all()->count();
            return response()->json($content);
        } else {
            $coupan = Coupan::where('custom_id', $id)->firstOrFail();
            $coupan->delete();
            if (request()->ajax()) {
                $content = array('status' => 200, 'message' => "Coupan deleted successfully.", 'count' => Coupan::all()->count());
                return response()->json($content);
            } else {
                flash('Coupan deleted successfully.')->success();
                return redirect()->route('admin.coupans.index');
            }
        }
    }
}
