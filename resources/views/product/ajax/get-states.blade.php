{{-- <option disabled selected>No State Selected</option> --}}
@foreach($states as $state)
    <option value="{{$state->id}}" @if($state->id == old('state') || session('shipping_state') == $state->state) selected @endif>{{ $state->state }}</option>
@endforeach
