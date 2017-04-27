<?php
namespace LaraMod\Admin\Products\Controllers;

use App\Http\Controllers\Controller;
use LaraMod\Admin\Products\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Yajra\Datatables\Datatables;


class ProductsController extends Controller
{

    private $data = [];
    public function __construct()
    {
        config()->set('admincore.menu.products.active', true);
    }

    public function index(Request $request)
    {
        $this->data['items'] = Products::with(['files'])->paginate(20);

        if($request->wantsJson()){
            return response()->json($this->data);
        }
        return view('adminproducts::products.list', $this->data);
    }

    public function getForm(Request $request)
    {
        $this->data['item'] = ($request->has('id') ? Products::with(['files'])->find($request->get('id')) : new Products());
        if($request->wantsJson()){
            return response()->json($this->data);
        }
        return view('adminproducts::products.form', $this->data);
    }

    public function postForm(Request $request)
    {


        $item = $request->has('id') ? Products::find($request->get('id')) : new Products();
        try{
            foreach(config('app.locales', [config('app.fallback_locale', 'en')]) as $locale){
                $item->{'title_'.$locale} = $request->get('title_'.$locale);
                $item->{'sub_title_'.$locale} = $request->get('sub_title_'.$locale);
                $item->{'short_description_'.$locale} = $request->get('short_description_'.$locale);
                $item->{'description_'.$locale} = $request->get('description_'.$locale);
                $item->{'table_info_'.$locale} = $request->get('table_info_'.$locale);
                $item->{'meta_title_'.$locale} = $request->get('meta_title_'.$locale);
                $item->{'meta_description_'.$locale} = $request->get('meta_description_'.$locale);
                $item->{'meta_keywords_'.$locale} = $request->get('meta_keywords_'.$locale);
            }
            $item->viewable = $request->has('visible');
            $item->price = (double)$request->get('price', 0);
            $item->promo_price = (double)$request->get('promo_price', 0);
            $item->promo_from = $request->get('promo_from') ?: null;
            $item->promo_to = $request->get('promo_to') ?: null;
            $item->code = $request->get('code');
            $item->manufacturer_code = $request->get('manufacturer_code');
            $item->weight = (double) $request->get('weight', 0);
            $item->volume = (double) $request->get('volume',0);
            $item->avlb_qty = (double) $request->get('avlb_qty', 0);

            $item->save();

            $item->categories()->sync($request->get('item_categories', []));
            $files = [];
            if($request->get('files') && Schema::hasTable('files_relations')){
                $files_data = json_decode($request->get('files'));

                foreach($files_data as $f){
                    $files[$f->id] = [];
                    foreach(config('app.locales', [config('app.fallback_locale', 'en')]) as $locale){
                        $files[$f->id]['title_'.$locale] = $f->{'title_'.$locale};
                        $files[$f->id]['description_'.$locale] = $f->{'description_'.$locale};
                    }
                }
                $item->files()->sync($files);
            }
        }catch (\Exception $e){
            return redirect()->back()->withInput()->withErrors(['message' => $e->getMessage()]);
        }

        return redirect()->route('admin.products.items')->with('message', [
            'type' => 'success',
            'text' => 'Post saved.'
        ]);
    }

    public function delete(Request $request){
        if(!$request->has('id')){
            return redirect()->route('admin.products.items')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!'
            ]);
        }
        try {
            Products::find($request->get('id'))->delete();
        }catch (\Exception $e){
            return redirect()->route('admin.products.items')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage()
            ]);
        }

        return redirect()->route('admin.products.items')->with('message', [
            'type' => 'success',
            'text' => 'Item moved to trash.'
        ]);
    }

    public function dataTable(){
        $items = Products::select(['id','title_en', 'code', 'created_at','viewable']);
        return DataTables::of($items)
            ->addColumn('action', function($item){
                return '<a href="'.route('admin.products.items.form', ['id' => $item->id]).'" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>'
                    .'<a href="'.route('admin.products.items.delete', ['id' => $item->id]).'" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>';
            })
            ->editColumn('created_at', function($item){
                return $item->created_at->format('d.m.Y H:i');
            })
            ->addColumn('status', function($item){
                return !$item->viewable ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>';
            })
            ->orderColumn('created_at $1','code $1')
            ->make('true');
    }


}