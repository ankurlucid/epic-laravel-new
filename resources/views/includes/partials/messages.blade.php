@if ($errors->any())
    <div class="alert alert-danger" style="color: #fff;">
        @foreach ($errors->all() as $error)
            {!! $error !!}<br/>
        @endforeach
    </div>
@elseif (Session::get('flash_success'))
    <div class="alert alert-success" style="color: #fff;">
        @if(is_array(json_decode(Session::get('flash_success'), true)))
            {!! implode('', Session::get('flash_success')->all(':message<br/>')) !!}
        @else
            {!! Session::get('flash_success') !!}
        @endif
    </div>
@elseif (Session::get('flash_warning'))
    <div class="alert alert-warning" style="color: #fff;">
        @if(is_array(json_decode(Session::get('flash_warning'), true)))
            {!! implode('', Session::get('flash_warning')->all(':message<br/>')) !!}
        @else
            {!! Session::get('flash_warning') !!}
        @endif
    </div>
@elseif (Session::get('flash_info'))
    <div class="alert alert-info" style="color: #fff;">
        @if(is_array(json_decode(Session::get('flash_info'), true)))
            {!! implode('', Session::get('flash_info')->all(':message<br/>')) !!}
        @else
            {!! Session::get('flash_info') !!}
        @endif
    </div>
@elseif (Session::get('flash_danger'))
    <div class="alert alert-danger" style="color: #fff;">
        @if(is_array(json_decode(Session::get('flash_danger'), true)))
            {!! implode('', Session::get('flash_danger')->all(':message<br/>')) !!}
        @else
            {!! Session::get('flash_danger') !!}
        @endif
    </div>
@elseif (Session::get('flash_message'))
    <div class="alert alert-info" style="color: #fff;">
        @if(is_array(json_decode(Session::get('flash_message'), true)))
            {!! implode('', Session::get('flash_message')->all(':message<br/>')) !!}
        @else
            {!! Session::get('flash_message') !!}
        @endif
    </div>
@endif