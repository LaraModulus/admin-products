<?php

namespace LaraMod\Admin\Products\Controllers;

use App\Http\Controllers\Controller;
use \LaraMod\Admin\Products\Models\Collections;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class CollectionsController extends Controller
{

    private $data = [];

    public function __construct()
    {
        config()->set('admincore.menu.products.active', true);
    }

    public function index(Request $request)
    {
        $items = new Collections();
        if($request->has('q')){
            $items->where('title_'.config('app.fallback_locale', 'en'), 'like', '%'.$request->get('q').'%');
        }
        $this->data['items'] = $items->paginate(20);

        if ($request->wantsJson()) {
            return response()->json($this->data);
        }

        return view('adminproducts::collections.list', $this->data);
    }

    public function getForm(Request $request)
    {
        $this->data['item'] = ($request->has('id') ? Collections::find($request->get('id')) : new Collections());

        return view('adminproducts::collections.form', $this->data);
    }

    public function postForm(Request $request)
    {

        $item = Collections::firstOrNew(['id' => $request->get('id')]);
        try {
            if(!$request->has('slug')){
                $request->merge(['slug' => $item->createSlug(
                    $request->get('title_'.config('app.fallback_locale', 'en'))
                )]);
            }
            $item->autoFill($request);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['errors' => $e->getMessage()]);
        }
        $item->products()->sync($request->get('products', []));

        return redirect()->route('admin.products.collections')->with('message', [
            'type' => 'success',
            'text' => 'Item saved.',
        ]);
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) {
            return redirect()->route('admin.products.collections')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!',
            ]);
        }
        try {
            Collections::find($request->get('id'))->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.products.collections')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.products.collections')->with('message', [
            'type' => 'success',
            'text' => 'Item moved to trash.',
        ]);
    }

    public function dataTable()
    {
        $items = Collections::select(['id','title_'.config('app.fallback_locale', 'en'),'created_at', 'viewable']);

        return DataTables::of($items)
            ->addColumn('action', function ($item) {
                return '<a href="' . route('admin.products.collections.form',
                        ['id' => $item->id]) . '" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>'
                    . '<a href="' . route('admin.products.collections.delete',
                        ['id' => $item->id]) . '" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('d.m.Y H:i');
            })
            ->addColumn('status', function ($item) {
                return !$item->viewable ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>';
            })
            ->orderColumn('created_at $1', 'id $1')
            ->make('true');
    }


}