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

        $item = Reviews::firstOrNew(['id' => $request->get('id')]);
        try {
            $item->autoFill($request);
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
        $items = Reviews::select(['id', 'title', 'language', 'created_at', 'products_items_id', 'rating']);

        return DataTables::of($items)
            ->addColumn('action', function ($item) {
                return '<a href="' . route('admin.products.reviews.form',
                        ['id' => $item->id]) . '" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>'
                    . '<a href="' . route('admin.products.reviews.delete',
                        ['id' => $item->id]) . '" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>';
            })
            ->addColumn('rating', function($item){
                $stars = '';
                $stars.=str_repeat('<i class="fa fa-star text-primary"></i>', $item->rating);
                $stars.=str_repeat('<i class="fa fa-star-o"></i>', 5-$item->rating);
                return $stars;
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

    public function reviewsWidget(){
        config()->set('admincore.menu.products.active', false);
        return view('adminproducts::reviews.widget', [
            'reviews_count' => Reviews::where('created_at', '>', new \Carbon\Carbon('yesterday'))->count()
        ])->render();
    }


}