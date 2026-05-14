<div class="row">
    <div class="col-md-12">
        <h4 class="card-title">
            <span class="btn btn-info btn-sm"><i class="{{$iconClass}}"></i></span>
            {{$fileName}}</h4>
    </div>
    <div class="col-md-12">
        <div>
            <div class="d-sm-inline-block d-block pe-1 ps-1 border-end"><label>{{__('Type')}}</label>: {{$type}}</div>
            <div class="d-sm-inline-block d-block pe-1 ps-1 border-end"><label>{{__('Size')}}</label>: {{$filesizebyteformat}}</div>
            @foreach ($fileData as $fd)
            <div class="d-sm-inline-block d-block pe-1 ps-1 border-end"><label>{{$fd['label']}}</label>: {{$fd['value']}}</div>
            @endforeach
                
        </div>
    </div>
</div>