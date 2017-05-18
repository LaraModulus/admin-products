@extends('admincore::layouts.dashboard')

@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Brands</h1>
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
        <form action="{{route('admin.products.brands.form', ['id' => $item->id])}}" method="post" role="form">
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
                                        <label for="description_{{$locale}}"
                                               class="control-label">Description </label>

                                        <textarea name="description_{{$locale}}" id="description_{{$locale}}" cols="30"
                                                  rows="10"
                                                  class="form-control editor">{{old('description_'.$locale, $item->{'description_'.$locale})}}</textarea>
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


                </div>
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="viewable">Visible?</label>
                                <div class="checkbox">
                                    <input type="checkbox" value="1" id="viewable" name="viewable"
                                           @if($item->viewable || !$item->id) checked @endif>
                                </div>
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