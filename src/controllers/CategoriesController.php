<?php
namespace LaraMod\Admin\Products\Controllers;

use App\Http\Controllers\Controller;
use LaraMod\Admin\Products\Models\Categories;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class CategoriesController extends Controller
{

    private $data = [];
    public function __construct()
    {
        config()->set('admincore.menu.products.active', true);
    }

    public function index()
    {
        $this->data['items'] = Categories::paginate(20);
        return view('adminproducts::categories.list', $this->data);
    }

    public function getForm(Request $request)
    {
        $this->data['item'] = ($request->has('id') ? Categories::find($request->get('id')) : new Categories());
        return view('adminproducts::categories.form', $this->data);
    }

    public function postForm(Request $request)
    {

        $category = $request->has('id') ? Categories::find($request->get('id')) : new Categories();
        try{
            foreach(config('app.locales', [config('app.fallback_locale', 'en')]) as $locale){
                $category->{'title_'.$locale} = $request->get('title_'.$locale);
                $category->{'sub_title_'.$locale} = $request->get('sub_title_'.$locale);
                $category->{'description_'.$locale} = $request->get('description_'.$locale);
                $category->{'meta_title_'.$locale} = $request->get('meta_title_'.$locale);
                $category->{'meta_description_'.$locale} = $request->get('meta_description_'.$locale);
                $category->{'meta_keywords_'.$locale} = $request->get('meta_keywords_'.$locale);
            }
            $category->viewable = $request->get('visible', 0);
            $category->save();
        }catch (\Exception $e){
            return redirect()->back()->withInput()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->route('admin.products.categories')->with('message', [
            'type' => 'success',
            'text' => 'Category saved.'
        ]);
    }

    public function delete(Request $request){
        if(!$request->has('id')){
            return redirect()->route('admin.products.categories')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!'
            ]);
        }
        try {
            Categories::find($request->get('id'))->delete();
        }catch (\Exception $e){
            return redirect()->route('admin.products.categories')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage()
            ]);
        }

        return redirect()->route('admin.products.categories')->with('message', [
            'type' => 'success',
            'text' => 'Category moved to trash.'
        ]);
    }

    public function dataTable(){
        $items = Categories::select(['id','title_en', 'viewable', 'created_at']);
        return DataTables::of($items)
            ->addColumn('action', function($item){
                return '<a href="'.route('admin.products.categories.form', ['id' => $item->id]).'" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>'
                        .'<a href="'.route('admin.products.categories.delete', ['id' => $item->id]).'" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>';
            })
            ->editColumn('created_at', function($item){
                return $item->created_at->format('d.m.Y H:i');
            })
            ->addColumn('status', function($item){
                return !$item->viewable ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>';
            })
            ->orderColumn('status $1','created_at $1')
            ->make('true');
    }


}