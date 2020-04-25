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
                        <input type="text" name="id" class="form-control" value="{{ $space['id'] }}">
                    </div>
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $space['title'] }}">
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <input type="text" name="description" class="form-control" value="{{ $space['description'] }}">
                    </div>
                    <div id="mapInit" style="height:300px;">
                    </div>
                    <div class="form-group">
                        <label for="">lat</label>
                        <input type="text" name="lat" class="form-control" value="{{ $space['lat'] }}">
                    </div>
                    <div class="form-group">
                        <label for="">lng</label>
                        <input type="text" name="lng" class="form-control" value="{{ $space['lng'] }}">
                    </div>
                    <div class="form-group">
                        <label for="">region</label>
                        <input type="text" name="region" class="form-control" value="{{ $space['region'] }}">
                    </div>
                    <div class="form-group">
                        <label for="">district</label>
                        <input type="text" name="district" class="form-control" value="{{ $space['district'] }}">
                    </div>
                    <div class="form-group">
                        <label for="">village</label>
                        <input type="text" name="village" class="form-control" value="{{ $space['village'] }}">
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
var map, infoWindow, marker, 
    endTime = (new Date()).getTime(),
    markers = [],
    neighborhoods = [];

$(document).ready(function () {
    // get neighbor and show
    getNeighborhoods();
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
    uluru = {lat: parseFloat($('input[name="lat"]').val()), lng: parseFloat($('input[name="lng"]').val())};

    map = new google.maps.Map(document.getElementById('mapInit'), {
        center: uluru,
        zoom: 13
    });

    marker = new google.maps.Marker({
        position: uluru,
        map: map,
        draggable: true,
        title: "Drag me!"
    });
    map.setCenter(uluru);
    getPosDragMarker(marker);
}

// change lat, lng in form input after marker change
function getPosDragMarker(markerObj) {
    google.maps.event.addListener(markerObj, 'dragend', function (e) {
        $('input[name="lat"]').val(markerObj.getPosition().lat().toFixed(7));
        $('input[name="lng"]').val(markerObj.getPosition().lng().toFixed(7));
    });
}
function showNeighborhoods(data) {
    neighborhoods = "";
    neighborhoods = data;
    
    clearMarkers();
    for (var i=0; i<neighborhoods.length; i++) {
        addMarkerWithTimeout(neighborhoods[i], i * 200);
    }
}

function addMarkerWithTimeout(pos, timeout) {
    markerIcon = "http://127.0.0.1:8000/img/neighbord.png";
    setTimeout(function () {
        markers.push(new google.maps.Marker({
            position: pos,
            map: map,
            icon: markerIcon,
            animation: google.maps.Animation.DROP
        }))
    }, timeout);
}
function clearMarkers() {
    for (var i=0; i<markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
}

$('#btnUpdate').on('click', function (e) {
    e.preventDefault();
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

function getNeighborhoods() {
    $.ajax({
        url: '/api/space/neighbordhoods',
        method: 'get',
        headers: {
            'Authorization': 'Bearer '+$.cookie('at')
        },
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: {
            lat: parseFloat($('input[name="lat"]').val()),
            lng: parseFloat($('input[name="lng"]').val()),
            rad: 40
        },
        dataType: 'json',
        success: function (data, textStatus, jqxhr) {
            if (data.hasOwnProperty('errors')) {
                alert(data.message);
            }

            for (var i in data) {
                data[i].lat = parseFloat(data[i].lat);
                data[i].lng = parseFloat(data[i].lng);
            }

            showNeighborhoods(data);
        },
        error: function (jqxhr, textStatus, error) {
            alert(error)
            console.error(error);
        },
    });
}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GMAPS_API') }}&callback=initMap" async defer></script>
@endpush