{{--
    This partial renders the Success Messages from controllers or services.
--}}

@if (\Session::has('success') && !empty(\Session::get('success')))
    <div class="alert alert-success alert-dismissible margin-top-10" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>Success!!</strong><br>
        
        @if(is_array(session('success')))
        
            @foreach(\Session::get('success') as $success)
                <div>
                {{ $success }}
                </div>
            @endforeach
            
        @else
            <div>
                {{ session('success') }}
            </div>
        @endif  
        
    </div>
@endif