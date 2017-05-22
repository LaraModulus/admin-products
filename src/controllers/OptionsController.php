<?php

namespace LaraMod\Admin\Products\Controllers;

use App\Http\Controllers\Controller;
use \LaraMod\Admin\Products\Models\Options;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class OptionsController extends Controller
{

    private $data = [];

    public function __construct()
    {
        config()->set('admincore.menu.products.active', true);
    }

    public function index()
    {
        $this->data['items'] = Options::paginate(20);

        return view('adminproducts::options.list', $this->data);
    }

    public function getForm(Request $request)
    {
        $this->data['item'] = ($request->has('id') ? Options::find($request->get('id')) : new Options());

        return view('adminproducts::options.form', $this->data);
    }

    public function postForm(Request $request)
    {

        $item = Options::firstOrCreate(['id' => $request->get('id')]);
        try {
            $item->update(array_filter($request->only($item->getFillable()), function ($key) use ($request, $item) {
                return in_array($key, array_keys($request->all())) || @$item->getCasts()[$key] == 'boolean';
            }, ARRAY_FILTER_USE_KEY));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->route('admin.products.options')->with('message', [
            'type' => 'success',
            'text' => 'Item saved.',
        ]);
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) {
            return redirect()->route('admin.products.options')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!',
            ]);
        }
        try {
            Options::find($request->get('id'))->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.products.options')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.products.options')->with('message', [
            'type' => 'success',
            'text' => 'Item moved to trash.',
        ]);
    }

    public function dataTable()
    {
        $items = Options::select(['id','created_at','title_en']);

        return DataTables::of($items)
            ->addColumn('action', function ($item) {
                return '<a href="' . route('admin.products.options.form',
                        ['id' => $item->id]) . '" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>'
                    . '<a href="' . route('admin.products.options.delete',
                        ['id' => $item->id]) . '" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('d.m.Y H:i');
            })
            ->orderColumn('created_at $1', 'id $1')
            ->make('true');
    }


}