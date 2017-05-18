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
                            <div class="form-group">
                                <label for="collections_ids">Collections</label>
                                {{--<input type="hidden" id="products_ids" name="products">--}}
                                <select multiple class="form-control" name="collections[]" id="collections_ids">
                                    @foreach($item->collections as $c)
                                        <option value="{{$c->id}}">{{$c->title_en}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @if(class_exists(\LaraMod\Admin\Files\AdminFilesServiceProvider::class))
                        <div class="panel panel-default" data-ng-controller="filesContainerController">
                            <div class="panel-body">
                                <div data-ng-class="{hidden: !files.item_files.length}">
                                    <ul class="list-inline files-list" data-ng-if="files.item_files.length">
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
                                <select class="form-control" name="brand" id="brand" multiple tabindex="-1" aria-hidden="true">
                                    @foreach(\LaraMod\Admin\Products\Models\Brands::all() as $brand)
                                    <option id="{{$brand->id}}" @if($item->brand_id==$brand->id) selected @endif >{{$brand->{'title_'.config('app.fallback_locale', 'en')} }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <textarea class="hidden" name="files" id="files_input">@{{ files.item_files }}</textarea>
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


    </div>
    @if(class_exists(\LaraMod\Admin\Files\AdminFilesServiceProvider::class))
        <script>
            app.controller('productController', function ($scope, $http, SweetAlert, CSRF_TOKEN, $window, Files) {
                $scope.files = Files;
                $scope.files_loading = false;

                $scope.$watch($scope.files, function (newVal, oldVal) {
                    Files = $scope.files;
                }, true);

                $http.get(window.location.href)
                    .then(function (response) {
                        if (response.data.item.files) {
                            $scope.files.item_files = response.data.item.files;
                        }
                    });
                $scope.removeFile = function (idx) {
                    $scope.files.item_files.splice(idx, 1);
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
                '<li>['+item.id+'] ' + item.title_en + '</li>';

            markup += '</ul>';

            return markup;
        }

        function formatItemsSelection (item) {
            return item.title_en;
        }

        $(document).ready(function(){
            $('#brand').select2({
                theme: 'bootstrap',
                tags: true,
                placeholder: 'Type brand name',
                maximumSelectionLength: 1
            });

            $("#collections_ids").select2({
                theme: 'bootstrap',
                multiple: true,
                data: {!! $item->collections()->select(['id','title_en'])->get()->map(function($item){
                        return [
                            "id" => $item->id,
                            "title_en" => $item->title_en
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