@if (\Session::has('success'))
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-success" role="alert">
                {!! \Session::get('success') !!}
            </div>
        </div>
    </div>
@elseif(\Session::has('danger'))
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-danger" role="alert">
                {!! \Session::get('danger') !!}
            </div>
        </div>
    </div>
@endif
