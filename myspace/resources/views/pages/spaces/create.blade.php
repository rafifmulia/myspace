@extends('layouts.app')

@section('title', 'Index')

@section('content')
<x-navigation></x-navigation>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="card-title text-center bg-primary text-white p-3"><h3>Submit Your Space</h3></div>
                <form id="formCreate">
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" name="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <input type="text" name="description" class="form-control">
                    </div>
                    <div id="mapInit" style="height:300px;">
                    </div>
                    <div class="form-group">
                        <label for="">lat</label>
                        <input type="text" name="lat" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">lng</label>
                        <input type="text" name="lng" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">region</label>
                        <input type="text" name="region" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">district</label>
                        <input type="text" name="district" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">village</label>
                        <input type="text" name="village" class="form-control">
                    </div>
                    <button type="submit" id="btnSubmit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var map, infoWindow, marker;
function initMap() {
    // initialize maps
    uluru = {lat: -34.397, lng: 150.644};
    map = new google.maps.Map(document.getElementById('mapInit'), {
        center: uluru,
        zoom: 15
    });

    // show current position by gps(geolocation)
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
            // infoWindow.setContent('Location Found.');
            // infoWindow.open(map);
            marker = new google.maps.Marker({
                position: localPos,
                map: map,
                draggable: true,
                title: "Drag me!"
            });
            map.setCenter(localPos);
            getPosDragMarker(marker);

            // set lat, lng to form input
            $('input[name="lat"]').val(localPos.lat.toFixed(7));
            $('input[name="lng"]').val(localPos.lng.toFixed(7));
        }, function () {
            // if gps blocked
            marker = new google.maps.Marker({
                position: uluru,
                map: map,
                draggable: true,
                title: "Drag me!"
            });
            getPosDragMarker(marker);

            // set lat, lng to form input
            $('input[name="lat"]').val(uluru.lat.toFixed(7));
            $('input[name="lng"]').val(uluru.lng.toFixed(7));
        });
    } else {
        console.warning("Your browser doesn't support GPS");

        marker = new google.maps.Marker({
            position: uluru,
            map: map,
            draggable: true,
            title: "Drag me!"
        });
        getPosDragMarker(marker);

        // set lat, lng to form input
        $('input[name="lat"]').val(uluru.lat.toFixed(7));
        $('input[name="lng"]').val(uluru.lng.toFixed(7));
    }

    
}

// change lat, lng in form input after marker change
function getPosDragMarker(markerObj) {
    google.maps.event.addListener(markerObj, 'dragend', function (e) {
        $('input[name="lat"]').val(markerObj.getPosition().lat().toFixed(7));
        $('input[name="lng"]').val(markerObj.getPosition().lng().toFixed(7));
    });
}

$('#btnSubmit').on('click', function (e) {
    e.preventDefault();
    $.ajax({
        url: '/api/space/create',
        method: 'post',
        headers: {
            'Authorization': 'Bearer '+$.cookie('at')
        },
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: $('#formCreate').serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqxhr) {
            if (data.hasOwnProperty('success')) {
                alert("Data created")
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

// example ajax
function ajax() {
    $.ajax({
        url: '/api/example',
        method: 'get',
        headers: {
            'Authorization': 'Bearer '+$.cookie('at')
        },
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: {},
        dataType: 'json',
        success: function (data, textStatus, jqxhr) {

        },
        error: function (jqxhr, textStatus, error) {

        },
    });
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GMAPS_API') }}&callback=initMap" async defer></script>
@endpush