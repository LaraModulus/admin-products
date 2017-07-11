@extends('admincore::layouts.dashboard')

@section('content')
    <div id="page-wrapper" data-ng-app="App" data-ng-controller="productController">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Products</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        @if(count($errors))
            <div class="row">
                <div class="col-xs-12">
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
    @endif
    <!-- /.row -->
        <form action="{{route('admin.products.items.form', ['id' => $item->id])}}" method="post" role="form">
            <div class="row">
                <div class="col-md-9">
                    <!-- TAB NAVIGATION -->
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach(config('app.locales', ['en']) as $key => $locale)
                            <li @if($key==0) class="active" @endif><a href="#{{$locale}}" role="tab"
                                                                      data-toggle="tab">{{$locale}}</a></li>
                        @endforeach
                    </ul>
                    <!-- TAB CONTENT -->
                    <div class="tab-content">
                        @foreach(config('app.locales', ['en']) as $key => $locale)
                            <div class="@if($key==0) active fade in @endif tab-pane panel panel-default"
                                 id="{{$locale}}">

                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="title_{{$locale}}" class="control-label">Title</label>

                                        <input type="text" class="form-control" name="title_{{$locale}}"
                                               id="title_{{$locale}}"
                                               placeholder=""
                                               value="{{old('title_'.$locale, $item->{'title_'.$locale})}}">

                                    </div>
                                    <div class="form-group">
                                        <label for="sub_title_{{$locale}}" class="control-label">Sub title</label>

                                        <input type="text" class="form-control" name="sub_title_{{$locale}}"
                                               id="sub_title_{{$locale}}"
                                               placeholder=""
                                               value="{{old('sub_title_'.$locale, $item->{'sub_title_'.$locale})}}">

                                    </div>
                                    <div class="form-group">
                                        <label for="description_{{$locale}}"
                                               class="control-label">Description </label>

                                        <textarea name="description_{{$locale}}" id="description_{{$locale}}" cols="30"
                                                  rows="10"
                                                  class="form-control editor">{{old('description_'.$locale, $item->{'description_'.$locale})}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="excerpt_{{$locale}}"
                                               class="control-label">Short description </label>

                                        <textarea name="short_description_{{$locale}}"
                                                  id="short_description_{{$locale}}" cols="30" rows="3"
                                                  class="form-control editor">{{old('short_description_'.$locale, $item->{'short_description_'.$locale})}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_title_{{$locale}}" class="control-label">Meta title</label>

                                        <input type="text" class="form-control" name="meta_title_{{$locale}}"
                                               id="meta_title_{{$locale}}"
                                               placeholder=""
                                               value="{{old('meta_title_'.$locale, $item->{'meta_title_'.$locale})}}">

                                    </div>
                                    <div class="form-group">
                                        <label for="meta_description_{{$locale}}"
                                               class="control-label">Meta Description </label>

                                        <textarea name="meta_description_{{$locale}}" id="meta_description_{{$locale}}"
                                                  cols="30" rows="3"
                                                  class="form-control ">{{old('meta_description_'.$locale, $item->{'meta_description_'.$locale})}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_keywords_{{$locale}}" class="control-label">Meta
                                            keywords</label>

                                        <input type="text" class="form-control" name="meta_keywords_{{$locale}}"
                                               id="meta_keywords_{{$locale}}"
                                               placeholder=""
                                               data-role="tagsinput"
                                               value="{{old('meta_keywords_'.$locale, $item->{'meta_keywords_'.$locale})}}">

                                    </div>

                                </div>

                            </div>
                        @endforeach
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="item_categories">Categories</label>
                                        <select class="form-control select2" name="item_categories[]" id="item_categories"
                                                multiple>
                                            @foreach(\LaraMod\Admin\Products\Models\Categories::all() as $category)
                                                <option value="{{$category->id}}"
                                                        @if(in_array($category->id, $item->categories->pluck('id')->toArray())) selected @endif
                                                >{{$category->{'title_'.config('app.fallback_locale', 'en')} }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="collections_ids">Collections</label>
                                        {{--<input type="hidden" id="products_ids" name="products">--}}
                                        <select multiple class="form-control" name="collections[]" id="collections_ids">
                                            @foreach($item->collections as $c)
                                                <option value="{{$c->id}}">{{$c->{'title_'.config('app.fallback_locale', 'en')} }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Product options
                        </div>
                        <div class="panel-body">
                            <button type="button" class="btn btn-primary" data-ng-click="newOption()">Add</button>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th colspan="2">Name</th>
                                    <th>Code</th>
                                    <th>Price</th>
                                    <th>Promo price</th>
                                    <th>Available quantity</th>
                                    <th><i class="fa fa-cogs"></i></th>
                                </tr>
                                </thead>
                                <tbody ui-sortable="{ 'ui-floating': false }" data-ng-model="product_options">
                                <tr data-ng-repeat="opt in product_options track by $index">
                                    <td class="text-center"><i class="fa fa-arrows fa-1x"></i></td>
                                    <td>@{{ opt.title_<?=config('app.fallback_locale', 'en')?> }}</td>
                                    <td>@{{ opt.pivot.code }}</td>
                                    <td>@{{ opt.pivot.price }}</td>
                                    <td>@{{ opt.pivot.promo_price }}</td>
                                    <td>@{{ opt.pivot.avlb_qty }}</td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-xs" data-ng-click="editOption($index)"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger btn-xs" data-ng-click="deleteOption($index)"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Product characteristics
                        </div>
                        <div class="panel-body">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#productCharacteristics">Edit characteristics</button>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <div class="input-group">
                                    <div class="input-group-addon">{{url('/shop/first-catagory-slug-cid')}}/</div>
                                    <input pattern="[A-z0-9\-]+" type="text" name="slug" id="slug" class="form-control" value="{{old('slug', $item->slug)}}" placeholder="Example: hello-world-123. Set automatically if empty.">
                                    <div class="input-group-addon">-{{($item->id ? $item->id : $item->max('id') + 1)}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(class_exists(\LaraMod\Admin\Files\AdminFilesServiceProvider::class))
                        <div class="panel panel-default" data-ng-controller="filesContainerController">
                            <div class="panel-body">
                                <div data-ng-class="{hidden: !files.item_files.length}">

                                    <ul class="list-inline files-list" data-ng-if="files.item_files.length" ui-sortable="{ 'ui-floating': true }" data-ng-model="files.item_files">
                                        <li class="item" data-ng-repeat="file in files.item_files track by $index">

                                            <div class="text-center image-block">
                                                <div class="editor-block text-right">
                                                    <button type="button" class="btn btn-xs btn-primary"
                                                            data-ng-click="editFile($index)"><i
                                                                class="fa fa-pencil"></i></button>
                                                    <button type="button" class="btn btn-xs btn-danger"
                                                            data-ng-click="removeFile($index)"><i
                                                                class="fa fa-times"></i></button>
                                                </div>
                                                <img src="@{{ file.thumb.encoded }}" alt="@{{ file.filename }}">
                                            </div>
                                            <div class="help-block small"
                                                 title="@{{ file.filename }} (@{{ file.file_size | bytes}})">
                                                @{{ file.filename }} (@{{ file.file_size | bytes}})
                                            </div>
                                        </li>

                                    </ul>
                                    <small class="help-block"><i class="fa fa-question-circle text-success"></i> Drag to arrange. First image is main image.</small>
                                    <hr>
                                </div>
                                <button class="btn btn-primary btn-md" data-target="#filesModal" data-toggle="modal"
                                        type="button">Add files
                                </button>
                            </div>
                            <div class="modal fade" id="filesModal">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                &times;
                                            </button>
                                            <h4 class="modal-title">Add file</h4>
                                        </div>
                                        <div class="modal-body">
                                            @include('adminfiles::_partials.manager')
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                            <button type="button" class="btn btn-primary"
                                                    data-ng-click="addSelectedFiles()">Add file
                                            </button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </div>

                    @endif


                </div>
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <div class="form-group">
                                <label for="visible">Visible?</label>
                                <div class="checkbox">
                                    <input type="checkbox" value="1" id="viewable" name="viewable"
                                           @if($item->viewable || !$item->id) checked @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" name="price" id="price" class="form-control"
                                       value="{{old('price', $item->price)}}">
                            </div>
                            <div class="form-group">
                                <label for="avlb_qty">Available quantity</label>
                                <input type="text" name="avlb_qty" id="avlb_qty" class="form-control"
                                       value="{{old('avlb_qty', $item->avlb_qty)}}">
                            </div>
                            <div class="form-group">
                                <label for="subtract_qty">Subtract quantity?</label>
                                <div class="checkbox">
                                    <input type="checkbox" value="1" id="subtract_qty" name="subtract_qty"
                                           @if($item->substract_qty) checked @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="promo_price">Promo price</label>
                                <input type="text" name="promo_price" id="promo_price" class="form-control"
                                       value="{{old('promo_price', $item->promo_price)}}">
                            </div>
                            <div class="form-group">
                                <label for="promo_from">Promo From</label>
                                <input type="text" name="promo_from" id="promo_from" class="form-control datetimepicker"
                                       value="{{old('promo_from', $item->promo_from)}}">
                            </div>
                            <div class="form-group">
                                <label for="promo_to">Promo to</label>
                                <input type="text" name="promo_to" id="promo_to" class="form-control datetimepicker"
                                       value="{{old('promo_to', $item->promo_to)}}">
                            </div>
                            <div class="form-group">
                                <label for="weight">Weight</label>
                                <input type="text" name="weight" id="weight" class="form-control"
                                       value="{{old('weight', $item->weight)}}">
                            </div>
                            <div class="form-group">
                                <label for="volume">Valume</label>
                                <input type="text" name="volume" id="volume" class="form-control"
                                       value="{{old('volume', $item->volume)}}">
                            </div>

                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" name="code" id="code" class="form-control"
                                       value="{{old('code', $item->code)}}">
                            </div>
                            <div class="form-group">
                                <label for="manufacturer_code">Manufacturer code</label>
                                <input type="text" name="manufacturer_code" id="manufacturer_code" class="form-control"
                                       value="{{old('manufacturer_code', $item->manufacturer_code)}}">
                            </div>
                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <input class="form-control" name="brand" id="brand" value="{{($item->brand ? $item->brand->{'title_'.config('app.fallback_locale', 'en')} : '')}}">
                            </div>

                            <textarea class="hidden" name="characteristics" id="characteristics">@{{ product_characteristics }}</textarea>
                            <textarea class="hidden" name="options" id="options">@{{ product_options }}</textarea>
                            <textarea class="hidden" name="files" id="files_input">@{{ files.item_files }}</textarea>
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="modal fade" id="optionModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add/Edit option</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Option title</label>
                                    <input type="text" class="form-control" data-ng-model="option.title_{{config('app.fallback_locale', 'en')}}" name="option_title" id="option_title" title="Option title" placeholder="Option name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Option price</label>
                                    <input type="text" class="form-control" data-ng-model="option.pivot.price" name="option_price" title="Option price" placeholder="Option price">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Promo price</label>
                                    <input type="text" class="form-control" data-ng-model="option.pivot.promo_price" name="promo_option_price" title="Promo price" placeholder="Promo price">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Code</label>
                                    <input type="text" class="form-control" data-ng-model="option.pivot.code" name="option_code" title="Option code" placeholder="Option code">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Manufacturer code</label>
                                    <input type="text" class="form-control" data-ng-model="option.pivot.manufacturer_code" name="option_manufacturer_code" title="Option manufacturer" placeholder="Manufacturer code">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Weight</label>
                                    <input type="text" class="form-control" data-ng-model="option.pivot.weight" name="option_weight" title="Option weight" placeholder="Weight">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Volume</label>
                                    <input type="text" class="form-control" data-ng-model="option.pivot.volume" name="option_volume" title="Option volume" placeholder="Volume">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Avail. quantity</label>
                                    <input type="text" class="form-control" data-ng-model="option.pivot.avlb_qty" name="option_qty" title="Available quantity" placeholder="Qty">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-ng-click="saveOption()">Save</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" id="productCharacteristics">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Product Characteristics</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th colspan="2">Name</th>
                                <th>Value</th>
                                <th><i class="fa fa-cogs"></i></th>
                            </tr>
                            </thead>
                            <tbody ui-sortable="{ 'ui-floating': false }" data-ng-model="product_characteristics">
                            <tr data-ng-repeat="item in product_characteristics track by $index">
                                <td class="text-center"><i class="fa fa-arrows fa-2x"></i></td>
                                <td><input data-ng-value="item.title_{{config('app.fallback_locale', 'en')}}" data-ng-model="item.title_{{config('app.fallback_locale', 'en')}}" data-characteristics-autocomplete title="Title" class="form-control"></td>
                                <td><input data-ng-value="item.pivot.filter_value" data-ng-model="item.pivot.filter_value" title="Value" class="form-control"></td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-xs"
                                            data-ng-click="product_characteristics.splice($index, 1)"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">
                                        <button type="button" class="btn btn-primary" data-ng-click="newCharacteristic()">Add</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Done</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    @if(class_exists(\LaraMod\Admin\Files\AdminFilesServiceProvider::class))
        <script>
            app.controller('productController', function ($scope, $http, SweetAlert, CSRF_TOKEN, $window, Files) {
                $scope.files = Files;
                $scope.files_loading = false;
                $scope.option = {
                    "pivot": {}
                };
                $scope.product_options = [];
                $scope.product_characteristics = [];

                $scope.$watch($scope.files, function (newVal, oldVal) {
                    Files = $scope.files;
                }, true);

                $http.get(window.location.href)
                    .then(function (response) {
                        if (response.data.item.files) {
                            $scope.files.item_files = response.data.item.files;
                            $scope.product_options = response.data.item.options;
                            $scope.product_characteristics = response.data.item.characteristics;
                        }
                    });
                $scope.removeFile = function (idx) {
                    $scope.files.item_files.splice(idx, 1);
                };

                $scope.newOption = function(){
                  $scope.option = {"pivot": {}};
                  $('#optionModal').modal('show');
                };
                $scope.saveOption = function(){
                    var done = false;
                    for(i=0;i<$scope.product_options.length; i++){
                        if($scope.option.title_{{config('app.fallback_locale', 'en')}} == $scope.product_options[i].title_{{config('app.fallback_locale', 'en')}}){
                            done = true;
                            break;
                        }
                    }
                    if(done===false){
                        $scope.product_options.push($scope.option);
                    }
                    $scope.option = {};
                    $('#optionModal').modal('hide');
                };
                $scope.editOption = function(idx){
                  $scope.option = $scope.product_options[idx];
                  $('#optionModal').modal('show');
                };
                $scope.deleteOption = function(idx){
                    $scope.product_options.splice(idx, 1);
                };

                $scope.newCharacteristic = function(){
                    $scope.product_characteristics.push({'title_{{config('app.fallback_locale', 'en')}}': '', 'pivot': {}});
                    setTimeout(function(){
                        $('[data-characteristics-autocomplete]').autocomplete({
                            appendTo: '#productCharacteristics',
                            source: {!! \LaraMod\Admin\Products\Models\Characteristics::all()->pluck('title_'.config('app.fallback_locale', 'en')) !!}
                        });
                    }, 500);
                };

            });
        </script>
    @endif
@stop
@section('js')
    <script type="text/javascript">

        function formatItems (item) {
            if (item.loading) return item.text;

            var markup = '<ul class="list-unstyled">' +
                '<li>['+item.id+'] ' + item.title_{{config('app.fallback_locale', 'en')}} + '</li>';

            markup += '</ul>';

            return markup;
        }

        function formatItemsSelection (item) {
            return item.title_{{config('app.fallback_locale', 'en')}};
        }

        $(document).ready(function(){
            $('#option_title').autocomplete({
                appendTo: '#optionModal',
                source: {!! \LaraMod\Admin\Products\Models\Options::all()->pluck('title_'.config('app.fallback_locale', 'en')) !!}
            });
            $('#brand').autocomplete({
                source: {!! \LaraMod\Admin\Products\Models\Brands::all()->pluck('title_'.config('app.fallback_locale', 'en')) !!}
            });



            $("#collections_ids").select2({
                theme: 'bootstrap',
                multiple: true,
                data: {!! $item->collections()->select(['id','title_'.config('app.fallback_locale', 'en')])->get()->map(function($item){
                        return [
                            "id" => $item->id,
                            "title_".config('app.fallback_locale', 'en') => $item->{'title_'.config('app.fallback_locale', 'en')}
                        ];
                    }) !!},
                ajax: {
                    url: "{{route('admin.products.collections')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.items.data,
                            pagination: {
                                more: (params.page * 20) < data.items.total
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; },
                minimumInputLength: 1,
                templateResult: formatItems,
                templateSelection: formatItemsSelection //

            })
                .val({!! $item->collections->pluck('id') !!})
                .trigger('change');
        });
    </script>
@stop