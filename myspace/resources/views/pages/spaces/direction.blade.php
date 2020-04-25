@extends('layouts.app')

@section('title', 'Index')

@section('content')
<x-navigation></x-navigation>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="card-title text-center bg-primary text-white p-3"><h3>Edit Your Space</h3></div>
                <form id="formUpdate">
                    <div class="form-group visible">
                        <label for="">Id</label>
                        <input type="text" name="id" class="form-control" value="{{ $space['id'] }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $space['title'] }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <input type="text" name="description" class="form-control" value="{{ $space['description'] }}" disabled>
                    </div>
                    <div id="mapInit" style="height:300px;">
                    </div>
                    <div class="form-group">
                        <label for="">lat</label>
                        <input type="text" name="lat" class="form-control" value="{{ $space['lat'] }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">lng</label>
                        <input type="text" name="lng" class="form-control" value="{{ $space['lng'] }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">region</label>
                        <input type="text" name="region" class="form-control" value="{{ $space['region'] }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">district</label>
                        <input type="text" name="district" class="form-control" value="{{ $space['district'] }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">village</label>
                        <input type="text" name="village" class="form-control" value="{{ $space['village'] }}" disabled>
                    </div>
                    <button type="submit" id="btnUpdate" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var map, infoWindow, marker, directionService, directionRenderer, localPos, pos
    endTime = (new Date()).getTime();

$(document).ready(function () {

});

// callback map
function initMap() {
    // google.maps.event.addDomListener(window, 'load', initialize);
    $(document).ready(function () {
        setTimeout(function () {
            initialize();
        }, endTime - time);
    });
}

// initialize maps
function initialize() {
    uluru = {lat: -6.3851225, lng: 106.8489947};

    map = new google.maps.Map(document.getElementById('mapInit'), {
        center: uluru,
        zoom: 8
    });

    getCurrentPosition();

    $(document).ready(function () {
       setTimeout(function () {
            setPoint();
            calculateAndDisplayRoute();
        }, endTime - time);
    });
}

// change lat, lng in form input after marker change
function getPosDragMarker(markerObj) {
    google.maps.event.addListener(markerObj, 'dragend', function (e) {
        $('input[name="lat"]').val(markerObj.getPosition().lat().toFixed(7));
        $('input[name="lng"]').val(markerObj.getPosition().lng().toFixed(7));
    });
}
function getCurrentPosition() {
    infoWindow = new google.maps.InfoWindow;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position)  {
            // Get Coordinate
            localPos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            // Show Current Position
            // infoWindow.setPosition(localPos);
            // infoWindow.setContent('Your Location.');
            // infoWindow.open(map);
            markers = new google.maps.Marker({
                position: localPos,
                map: map,
            });
            map.setCenter(localPos);
        }, function () {
            // if gps blocked
            markers = new google.maps.Marker({
                position: uluru,
                map: map
            });
        });
    } else {
        console.warning("Your browser doesn't support GPS");

        markers = new google.maps.Marker({
            position: uluru,
            map: map
        });
    }
}
function setPoint() {
    pos = {
        lat: parseFloat("{{ $space['lat'] }}"),
        lng: parseFloat("{{ $space['lng'] }}"),
    }
    
    marker = new google.maps.Marker({
        position: pos,
        map: map
    });
}
function calculateAndDisplayRoute() {
    directionRenderer = new google.maps.DirectionsRenderer();
    directionService = new google.maps.DirectionsService();

    directionRenderer.setMap(map);
    directionService.route(
        {
            origin: localPos,
            destination: pos,
            travelMode: 'DRIVING',   
        },
        function (response,  status) {
            if (status === 'OK') {
                directionRenderer.setDirections(response);
            } else {
                alert('Direction request failed due to: '+ status);
            }
        }
    );
}

$('#btnUpdate').on('click', function (e) {
    e.preventDefault();
    return ;
    $.ajax({
        url: '/api/space/update',
        method: 'put',
        headers: {
            'Authorization': 'Bearer '+$.cookie('at')
        },
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: $('#formUpdate').serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqxhr) {
            if (data.hasOwnProperty('success')) {
                alert("Data edited");
                
                window.location.href = '/space';
                return ;
            }
            alert('Error when insert to database');
        },
        error: function (jqxhr, textStatus, error) {
            alert(error)
            console.error(error);
        },
    });
});

</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GMAPS_API') }}&callback=initMap" async defer></script>
@endpush