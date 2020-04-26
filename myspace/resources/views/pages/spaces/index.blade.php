@extends('layouts.app')

@section('title', 'Index')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between mb-3">
            <div id="left">
                <a href="{{ route('space.create') }}" class="btn btn-primary">Pin!</a>
            </div>
            <div id="right">
                <a href="{{ route('space.index') }}" class="btn btn-secondary"><i class="fas fa-list"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div id="indexSpace" class="col-md-8">
        @foreach ($spaces as $space)
        <div class="card mb-2">
            <div class="card-header bg-primary text-white">{{ $space->title }}</div>
            <div class="card-body">
                <div class="card-title">{{ $space->description }} </div>
                <div class="card-subtitle mb-2 text-muted">lat: {{ $space->lat }}, lng: {{ $space->lng }} </div>
                <div class="card-text">
                    {{ $space->region }}, {{ $space->district }}, {{ $space->village }}
                </div>
                <a href="{{ route('space.edit', ['id'=>$space->id]) }}" class="btn btn-primary">Edit</a>
                <button type="button" data-id="{{ $space->id }}" class="btn btn-danger btnDelete">Delete</button> <br>
                <a href="{{ route('space.direction', ['id'=>$space->id]) }}" class="card-link">Direction</a>
            </div>
        </div>
        @endforeach
        {{ $spaces->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#indexSpace').on('click', '.btnDelete', function(e) {
    var id = $(e.target).attr('data-id');

    $.ajax({
        url: '/api/space/delete',
        method: 'delete',
        headers: {
            'Authorization': 'Bearer '+$.cookie('at')
        },
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: {id: id},
        dataType: 'json',
        success: function (data, textStatus, jqxhr) {
            if (data.hasOwnProperty('success')) {
                alert('Space success deleted');
                window.location.href = '/space';
                return ;
            }
            alert('Cannot delete space');
        },
        error: function (jqxhr, textStatus, error) {
            alert(error);
            console.error(error);
        },
    });
});
</script>
@endpush