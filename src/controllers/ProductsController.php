<?php

namespace LaraMod\Admin\Products\Controllers;

use App\Http\Controllers\Controller;
use LaraMod\Admin\Products\Models\Brands;
use LaraMod\Admin\Products\Models\Options;
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
        $items = Products::with(['files']);
        if($request->has('q')){
            $items->where('title_en', 'like', '%'.$request->get('q').'%');
        }
        $this->data['items'] = $items->paginate(20);

        if ($request->wantsJson()) {
            return response()->json($this->data);
        }

        return view('adminproducts::products.list', $this->data);
    }

    public function getForm(Request $request)
    {
        $this->data['item'] = ($request->has('id') ? Products::with(['files', 'options'])->find($request->get('id')) : new Products());
        if ($request->wantsJson()) {
            return response()->json($this->data);
        }

        return view('adminproducts::products.form', $this->data);
    }

    public function postForm(Request $request)
    {

        $item = Products::firstOrCreate(['id' => $request->get('id')]);
        try {
            if($request->has('brand')){
                $brand = Brands::where('title_'.config('app.fallback_locale', 'en'),'LIKE',$request->get('brand'))->first();
                if(!$brand){
                    $brand = new Brands();
                    $brand->{'title_'.config('app.fallback_locale', 'en')} = $request->get('brand');
                    $brand->save();
                }
                $request->merge(['brand_id' => $brand->id]);
            }
            $item->update(array_filter($request->only($item->getFillable()), function($key) use ($request, $item){
                return in_array($key, array_keys($request->all())) || @$item->getCasts()[$key]=='boolean';
            }, ARRAY_FILTER_USE_KEY));

            $item->categories()->sync($request->get('item_categories', []));
            $item->collections()->sync($request->get('collections', []));
            $product_options = [];
            $options = collect(json_decode($request->get('options')));
            if($options){
                foreach($options as $opt){
                    $o = Options::firstOrCreate(['title_en' => $opt->title_en]);
                    $product_options[$o->id] = [
                        'price' => $opt->pivot->price,
                        'promo_price' => $opt->pivot->promo_price,
                        'code' => $opt->pivot->code,
                        'manufacturer_code' => $opt->pivot->manufacturer_code,
                        'weight' => $opt->pivot->weight,
                        'volume' => $opt->pivot->volume,
                        'avlb_qty' => $opt->pivot->avlb_qty
                    ];
                }
            }
            $item->options()->sync($product_options);
            $files = [];
            if ($request->get('files') && Schema::hasTable('files_relations')) {
                $files_data = json_decode($request->get('files'));

                foreach ($files_data as $f) {
                    $files[$f->id] = [];
                    foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
                        $files[$f->id]['title_' . $locale] = $f->{'title_' . $locale};
                        $files[$f->id]['description_' . $locale] = $f->{'description_' . $locale};
                    }
                }
                $item->files()->sync($files);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['message' => $e->getMessage()]);
        }

        return redirect()->route('admin.products.items')->with('message', [
            'type' => 'success',
            'text' => 'Post saved.',
        ]);
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) {
            return redirect()->route('admin.products.items')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!',
            ]);
        }
        try {
            Products::find($request->get('id'))->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.products.items')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.products.items')->with('message', [
            'type' => 'success',
            'text' => 'Item moved to trash.',
        ]);
    }

    public function dataTable()
    {
        $items = Products::select(['id', 'title_en', 'code', 'created_at', 'viewable']);

        return DataTables::of($items)
            ->addColumn('action', function ($item) {
                return '<a href="' . route('admin.products.items.form',
                        ['id' => $item->id]) . '" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>'
                    . '<a href="' . route('admin.products.items.delete',
                        ['id' => $item->id]) . '" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('d.m.Y H:i');
            })
            ->addColumn('status', function ($item) {
                return !$item->viewable ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>';
            })
            ->orderColumn('created_at $1', 'code $1')
            ->make('true');
    }


}