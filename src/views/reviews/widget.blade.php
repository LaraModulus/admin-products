<div class="col-lg-3 col-md-6">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-3">
                    <i class="fa fa-commenting fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">
                    <div class="huge">{{$reviews_count}}</div>
                    <div>since yesterday</div>
                </div>
            </div>
        </div>
        <a href="{{route('admin.products.reviews')}}">
            <div class="panel-footer">
                <span class="pull-left">View Reviews</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
            </div>
        </a>
    </div>
</div>