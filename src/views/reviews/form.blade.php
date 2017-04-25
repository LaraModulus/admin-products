@extends('admincore::layouts.dashboard')

@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Product reviews</h1>
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
        <form action="{{route('admin.products.reviews.form', ['id' => $item->id])}}" method="post" role="form">
            <div class="row">
                <div class="col-md-9">
                    <div class="panel panel-default" id="">

                        <div class="panel-body">
                            <div class="form-group">
                                <label for="title" class="control-label">Title</label>

                                <input type="text" class="form-control" name="title" id="title"
                                       placeholder="" value="{{old('title', $item->title)}}">

                            </div>
                            <div class="form-group">
                                <label for="description"
                                       class="control-label">Content </label>

                                <textarea name="description" id="description" cols="30" rows="10"
                                          class="form-control editor">{{old('description', $item->description)}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="link" class="control-label">Link</label>

                                <input type="text" class="form-control" name="link" id="link"
                                       placeholder="" value="{{old('link', $item->link)}}">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="language">Language</label>
                                <select class="form-control selector" name="language" id="language">
                                    @foreach(config('app.locales', [config('app.fallback_locale')]) as $language)
                                        <option value="{{$language}}" @if($item->language == $language) selected @endif>{{$language}}</option>
                                    @endforeach
                                </select>
                            </div>
                                <div class="form-group">
                                    <label for="products_items_id">Product</label>
                                    <select class="form-control selector" name="products_items_id" id="products_items_id">
                                        @foreach(\LaraMod\Admin\Products\Models\Products::all() as $p)
                                            <option value="{{$p->id}}" @if($item->product && $p->id==$item->product->id) selected @endif>{{$p->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


    </div>
@stop