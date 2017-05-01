<?php

namespace LaraMod\Admin\Products\Controllers;

use App\Http\Controllers\Controller;
use LaraMod\Admin\Products\Models\Reviews;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ReviewsController extends Controller
{

    private $data = [];

    public function __construct()
    {
        config()->set('admincore.menu.products.active', true);
    }

    public function index()
    {
        $this->data['items'] = Reviews::paginate(20);

        return view('adminproducts::reviews.list', $this->data);
    }

    public function getForm(Request $request)
    {
        $this->data['item'] = ($request->has('id') ? Reviews::find($request->get('id')) : new Reviews());

        return view('adminproducts::reviews.form', $this->data);
    }

    public function postForm(Request $request)
    {

        $item = Reviews::firstOrCreate(['id' => $request->get('id')]);
        try {
            $item->update(array_filter($request->only($item->getFillable()), function($key) use ($request, $item){
                return in_array($key, array_keys($request->all())) || @$item->getCasts()[$key]=='boolean';
            }, ARRAY_FILTER_USE_KEY));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->route('admin.products.reviews')->with('message', [
            'type' => 'success',
            'text' => 'Review saved.',
        ]);
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) {
            return redirect()->route('admin.products.reviews')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!',
            ]);
        }
        try {
            Reviews::find($request->get('id'))->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.products.reviews')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.products.reviews')->with('message', [
            'type' => 'success',
            'text' => 'Review moved to trash.',
        ]);
    }

    public function dataTable()
    {
        $items = Reviews::select(['id', 'title', 'language', 'created_at', 'products_items_id']);

        return DataTables::of($items)
            ->addColumn('action', function ($item) {
                return '<a href="' . route('admin.products.reviews.form',
                        ['id' => $item->id]) . '" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>'
                    . '<a href="' . route('admin.products.reviews.delete',
                        ['id' => $item->id]) . '" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>';
            })
            ->addColumn('product_title', function ($item) {
                if (!$item->product) {
                    return null;
                }

                return $item->product->title;
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('d.m.Y H:i');
            })
            ->orderColumn('created_at $1', 'products_items_id $1')
            ->make('true');
    }


}