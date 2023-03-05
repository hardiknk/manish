<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pages.faqs.index')->with(['custom_title' => 'Faqs']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.faqs.create')->with(['custom_title' => 'Faq']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['custom_id'] = getUniqueString('faqs');
        $faq = Faq::create($request->all());
        if ($faq) {
            flash('faq created successfully!')->success();
        } else {
            flash('Unable to save faq. Please try again later.')->error();
        }
        return redirect(route('admin.faqs.index'));
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
    public function edit(Faq $faq)
    {
        return view('admin.pages.faqs.edit', compact('faq'))->with(['custom_title' => 'Faq']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Faq $faq, Request $request)
    {
        if (!empty($request->action) && $request->action == 'change_status') {
            $content = ['status' => 204, 'message' => "something went wrong"];
            if ($faq) {
                $faq->is_active = $request->value;
                if ($faq->save()) {
                    $content['status'] = 200;
                    $content['message'] = "Faq updated successfully.";
                }
            }
            return response()->json($content);
        } else {
            $faq->fill($request->all());
            if ($faq->save()) {
                flash('Faq details updated successfully!')->success();
            } else {
                flash('Unable to update faq. Try again later')->error();
            }
            return redirect(route('admin.faqs.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (!empty($request->action) && $request->action == 'delete_all') {
            $content = ['status' => 204, 'message' => "something went wrong"];
            Faq::whereIn('custom_id', explode(',', $request->ids))->delete();
            $content['status'] = 200;
            $content['message'] = "faq deleted successfully.";
            $content['count'] = Faq::all()->count();
            return response()->json($content);
        } else {
            $faq = Faq::where('custom_id', $id)->firstOrFail();
            $faq->delete();
            if (request()->ajax()) {
                $content = array('status' => 200, 'message' => "faq deleted successfully.", 'count' => Faq::all()->count());
                return response()->json($content);
            } else {
                flash('faq deleted successfully.')->success();
                return redirect()->route('admin.faqs.index');
            }
        }
    }

    /* Listing Details */
    public function listing(Request $request)
    {
        extract($this->DTFilters($request->all()));
        $records = [];
        $faqs = Faq::orderBy($sort_column, $sort_order);

        if ($search != '') {
            $faqs->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $count = $faqs->count();

        $records['recordsTotal'] = $count;
        $records['recordsFiltered'] = $count;
        $records['data'] = [];

        $faqs = $faqs->offset($offset)->limit($limit)->orderBy($sort_column, $sort_order);

        $faqs = $faqs->latest()->get();
        foreach ($faqs as $faq) {
            $params = [
                'checked' => ($faq->is_active == 'y' ? 'checked' : ''),
                'getaction' => $faq->display_upload,
                'class' => '',
                'id' => $faq->custom_id,
            ];

            $records['data'][] = [
                'id' => $faq->id,
                'title' => $faq->title,
                'description' => $faq->description,
                'active' => view('admin.layouts.includes.switch', compact('params'))->render(),
                'action' => view('admin.layouts.includes.actions')->with(['custom_title' => 'Faqs', 'id' => $faq->custom_id], $faq)->render(),
                'checkbox' => view('admin.layouts.includes.checkbox')->with('id', $faq->custom_id)->render(),
                'updated_at' => $faq->updated_at,
            ];
        }
        // dd($records);
        return $records;
    }
}
