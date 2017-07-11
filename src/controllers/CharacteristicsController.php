<?php

namespace LaraMod\Admin\Products\Controllers;

use App\Http\Controllers\Controller;
use \LaraMod\Admin\Products\Models\Characteristics;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class CharacteristicsController extends Controller
{

    private $data = [];

    public function __construct()
    {
        config()->set('admincore.menu.products.active', true);
    }

    public function index(Request $request)
    {
        $items = new Characteristics();
        if($request->has('q')){
            $items->where('title_'.config('app.fallback_locale', 'en'), 'like', '%'.$request->get('q').'%');
        }
        $this->data['items'] = $items->paginate(20);

        if ($request->wantsJson()) {
            return response()->json($this->data);
        }

        return view('adminproducts::characteristics.list', $this->data);
    }

    public function getForm(Request $request)
    {
        $this->data['item'] = ($request->has('id') ? Characteristics::find($request->get('id')) : new Characteristics());

        return view('adminproducts::characteristics.form', $this->data);
    }

    public function postForm(Request $request)
    {

        $item = Characteristics::firstOrNew(['id' => $request->get('id')]);
        try {
            $item->autoFill($request);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->route('admin.products.characteristics')->with('message', [
            'type' => 'success',
            'text' => 'Item saved.',
        ]);
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) {
            return redirect()->route('admin.products.characteristics')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!',
            ]);
        }
        try {
            Characteristics::find($request->get('id'))->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.products.characteristics')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.products.characteristics')->with('message', [
            'type' => 'success',
            'text' => 'Item moved to trash.',
        ]);
    }

    public function dataTable()
    {
        $items = Characteristics::select(['id','created_at', 'title_'.config('app.fallback_locale', 'en')]);

        return DataTables::of($items)
            ->addColumn('action', function ($item) {
                return '<a href="' . route('admin.products.characteristics.form',
                        ['id' => $item->id]) . '" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>'
                    . '<a href="' . route('admin.products.characteristics.delete',
                        ['id' => $item->id]) . '" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('d.m.Y H:i');
            })
            ->orderColumn('created_at $1', 'id $1')
            ->make('true');
    }


}