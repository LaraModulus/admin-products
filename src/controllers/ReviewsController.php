<?php
namespace LaraMod\Admin\Products\Controllers;

use App\Http\Controllers\Controller;
use LaraMod\Admin\Products\Models\Reviews;
use Illuminate\Http\Request;

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

        $item = $request->has('id') ? Reviews::find($request->get('id')) : new Reviews();
        try{
            $item->title = $request->get('title');
            $item->description = $request->get('description');
            $item->link = $request->get('link');
            $item->products_items_id = $request->get('products_items_id');
            $item->language = $request->get('language', config('app.fallback_locale'));
            $item->save();
        }catch (\Exception $e){
            return redirect()->back()->withInput()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->route('admin.products.reviews')->with('message', [
            'type' => 'success',
            'text' => 'Review saved.'
        ]);
    }

    public function delete(Request $request){
        if(!$request->has('id')){
            return redirect()->route('admin.products.reviews')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!'
            ]);
        }
        try {
            Reviews::find($request->get('id'))->delete();
        }catch (\Exception $e){
            return redirect()->route('admin.products.reviews')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage()
            ]);
        }

        return redirect()->route('admin.products.reviews')->with('message', [
            'type' => 'success',
            'text' => 'Review moved to trash.'
        ]);
    }


}