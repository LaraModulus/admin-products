@extends('admincore::layouts.dashboard')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Reviews</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-striped" id="items_table"
                           data-page-length="10"
                    >
                        <thead>
                        <tr>
                            <td colspan="4">
                                <a href="{{route('admin.products.reviews.form')}}"
                                   class="btn btn-md btn-primary">Create</a>
                            </td>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Rating</th>
                            <th>Created date</th>
                            <th>Product</th>
                            <th><i class="fa fa-cogs"></i></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
@stop
@section('js')
    <script type="text/javascript">
        $(function () {
            $('#items_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.products.reviews.datatable') !!}',
                order: [
                    [3, 'desc']
                ],
                columns: [
                    {data: 'id', name: 'ID'},
                    {data: 'title', name: 'title'},
                    {data: 'rating', searchable: false},
                    {data: 'created_at', searchable: false},
                    {data: 'product_title', name: 'product_title', searchable: false, orderable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@stop