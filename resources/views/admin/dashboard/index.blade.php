<x-app-layout>
 @push('start-styles')
 @endpush
 @push('end-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/dashboard-ecommerce.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/charts/chart-apex.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/extensions/ext-component-toastr.css')}}">
    <style>
        #map {
            height: 100%;
            width:100%;
        }
    </style>
@endpush
 @push('start-script')

 @endpush
 @push('end-script')
 <script>
     $(window).on('load', function() {
               if (feather) {
                   setTimeout(()=>{
                       feather.replace({
                           width: 14,
                           height: 14
                       })
                   },500)
               }
           });
 </script>

    <script src="{{asset('app-assets/js/scripts/pages/dashboard-ecommerce.js')}}"></script>
    <script>
        var radius = 1000;
        let map;
        var circle;
        var marker;
        // window.initMap =
        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: new google.maps.LatLng(61.9241, 25.7482),
                mapTypeId: "terrain",
            });

            marker = new google.maps.Marker({
                position:new google.maps.LatLng(61.9241, 25.7482),
                map:map,
                animation:google.maps.Animation.BOUNCE,
                draggable:true,
                title:'drag the marker'
            });

            google.maps.event.addListener(marker, 'dragend', function(evt){
                document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(3) + ' Current Lng: ' + evt.latLng.lng().toFixed(3) + '</p>';
                $('#lat').val(marker.getPosition().lat());
                $('#lng').val(marker.getPosition().lng());

            });
            // var circle = new google.maps.Circle({
            // map: map,
            // radius: radius,    // 10 miles in metres
            // fillColor: '#AA0000'
            // });
            // circle.bindTo('center', marker, 'position');
            map.setCenter(marker.position);
            marker.setMap(map);
        }
        $("#radius").change(function() {
                // radius=parseInt(this.value);
                // alert(radius);
                var new_rad = $(this).val();
                radius = new_rad * 1600;
                if (!circle || !circle.setRadius) {
                circle = new google.maps.Circle({
                    map: map,
                    radius: radius,
                    fillColor: '#555',
                    strokeColor: '#ffffff',
                    strokeOpacity: 0.1,
                    strokeWeight: 3
                });
                circle.bindTo('center', marker, 'position');
                } else circle.setRadius(radius);
        });


    </script>
    <script type="text/javascript" defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA9F674J1WMb0DLor5yCz5rPEqEKBuvxec&libraries=places&callback=initMap"></script>
    <script>

        $(document).ready(function () {
            var input = document.getElementById('pac-input');
            var options = {
            };
            var infowindow = new google.maps.InfoWindow();
            marker.addListener("click", function () {
                infowindow.open(map, marker);
            });
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();// Retrieve details about the place

                if (!place.geometry) {
                    alert("No predictions were found")
                    return;
                }
                infowindow.close();
                marker.setVisible(false);
                infowindow.setContent(place.formatted_address);

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(25);
                }

                marker.addListener("click", function () {
                    infowindow.open(map, marker);
                });

                $('#lat').val(place.geometry.location.lat());
                $('#lng').val(place.geometry.location.lng());
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);
            });
        });



        function calculate()
        {
            var lat = $('#lat').val(), lng = $('#lng').val();
            radius = parseInt($('#radius').val());

            if(parseInt(lat) && parseInt(lng) && parseInt(radius)){
                $.ajax({
                    method:'post',
                    url:"{{route('interests.calculate')}}",
                    data:{
                        lat:lat,
                        lng:lng,
                        radius:radius,
                        _token:'{{csrf_token()}}',
                    },
                    success:function (response) {
                        console.log(response.interests.length);
                        var html = "";
                        if(response.interests.length > 0){
                            response.interests.forEach(element => {
                                html+='<div class="col-6">'+
                                '<div class="row">'+
                                `<div class="col ml-1 mr-2 text-center" onclick="interested_users(`+element.id+`,'`+element.name+`','`+lat+`','`+lng+`','`+radius+`')" style="color:white; background-color:`+element.color+`; cursor:pointer; border-radius:20px; padding:5px 10px; margin-bottom: 3px;">`+element.name+`</div>`+
                                '<div class="col-auto text-center" style="color:white; background-color:'+element.color+'; border-radius:20px; padding:5px 10px; margin-bottom: 3px;">'+element.count+'</div>'+
                                '</div></div>';

                            });
                        }else{
                            html+='<div class="col-12">'+
                                '<div class="row">'+
                                '<div class="col ml-1 mr-2 text-center">No Interesrt Found</div>'+
                                '</div></div>';
                        }
                        $('#top_interest').html(html);
                        $('#interest-row').css('display','block');


                    }
                })
            }
        }
        function interested_users(id,name,lat,lng,radius){
            $.ajax({
                    method:'post',
                    url:"{{route('intrested.users')}}",
                    data:{
                        id:id,
                        lat:lat,
                        lng:lng,
                        radius:radius,
                        _token:'{{csrf_token()}}',
                    },
                    success:function (response) {
                        console.log(response);
                        $('#exampleModalLong').modal('show');
                        $('#exampleModalLongTitle').text('Users interested in '+ name +' event');
                        var html = "";
                        if(response.users.length > 0){
                            response.users.forEach(user => {
                                html+='<div class="row">'+
                                    `<div class="col" style="margin-bottom: .25rem;">`+user.email+`</div>`+
                                '</div>';
                            });
                        }else{
                            html+='<div class="col-12">'+
                                '<div class="row">'+
                                '<div class="col ml-1 mr-2 text-center">No Interesrt Found</div>'+
                                '</div></div>';
                        }
                        $('#exampleModalLongBody').html(html);
                    }
                });

            // $('#exampleModalLongBody')
        }
    </script>
 @endpush
 @php
     function number_format_short( $n, $precision = 2 ) {
	if ($n < 999) {
		// 0 - 900
		$n_format = number_format($n, $precision);
		$suffix = '';
	} else if ($n < 999999) {
		// 0.9k-850k
		$n_format = number_format($n / 1000, $precision);
		$suffix = 'K';
	} else /* if ($n < 999999999)*/ {
		// 0.9m-850m
		$n_format = number_format($n / 1000000, $precision);
		$suffix = 'M';
	}
    // else if ($n < 999999999999) {
	// 	// 0.9b-850b
	// 	$n_format = number_format($n / 1000000000, $precision);
	// 	$suffix = 'B';
	// } else {
	// 	// 0.9t+
	// 	$n_format = number_format($n / 1000000000000, $precision);
	// 	$suffix = 'T';
	// }
	if ( $precision > 0 ) {
		$dotzero = '.' . str_repeat( '0', $precision );
		$n_format = str_replace( $dotzero, '', $n_format );
	}

	return $n_format . $suffix;
}

 @endphp


<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Dashboard Ecommerce Starts -->
            <section id="dashboard-ecommerce">
                <div class="row match-height">

                    <!-- Statistics Card -->

                    <div class="col-auto mb-2 ml-auto">
                        <a class="btn btn-primary" href="{{Route('notifications.create')}}">Push Notification</a>
                    </div>
                    <div class="col-xl-12 col-md-12 col-12">
                        <div class="card card-statistics">
                            <div class="card-header">
                                <h4 class="card-title">Statistics</h4>
                                {{-- <div class="d-flex align-items-center">
                                    <p class="card-text font-small-2 mr-25 mb-0">Updated 1 month ago</p>
                                </div> --}}
                            </div>
                            <div class="card-body statistics-body">
                                <div class="row mb-1">
                                    <div class="col-xl-4 col-sm-6 col-12 mb-2 mb-xl-1">
                                        <div class="media">
                                            <div class="avatar bg-light-primary mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="list" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{number_format_short($event_count)}}</h4>
                                                <p class="card-text font-small-3 mb-0">Events</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-sm-6 col-12 mb-2 mb-xl-1">
                                        <div class="media">
                                            <div class="avatar bg-light-danger mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="users" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{number_format_short($user_count)}}</h4>
                                                <p class="card-text font-small-3 mb-0">Customers</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-sm-6 col-12 mb-2 mb-xl-1">
                                        <div class="media">
                                            <div class="avatar bg-light-info mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="box" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{number_format_short($category_count)}}</h4>
                                                <p class="card-text font-small-3 mb-0">Categories</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="media">
                                            <div class="avatar bg-light-success mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="trending-up" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{number_format_short($visitors_today)}}</h4>
                                                <p class="card-text font-small-3 mb-0">Visitors Today</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="media">
                                            <div class="avatar bg-light-secondary mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="trending-up" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{number_format_short($visitors_week)}}</h4>
                                                <p class="card-text font-small-3 mb-0">Visitors Last 7 Days</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-sm-6 col-12 mb-2 mb-sm-0">
                                        <div class="media">
                                            <div class="avatar bg-light-warning mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="trending-up" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{number_format_short($visitors_month)}}</h4>
                                                <p class="card-text font-small-3 mb-0">Visitors Last 30 Days</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Statistics Card -->
                </div>
                <div class="row match-height">
                    <!-- Statistics Card -->
                    <div class="col-xl-12 col-md-12 col-12">
                        <div class="card card-statistics">
                            <div class="card-header">
                                <h4 class="card-title">User Interests</h4>
                                <div id="current"></div>
                            </div>
                            <div class="card-body statistics-body">
                                <div class="row">
                                    <div class="col-xl-12 col-sm-12 col-12 mb-2 mb-xl-0">
                                        <div id="map" style="width:100%;height:400px;"></div>
                                    </div>
                                    <div class="col-xl-12 col-sm-12 col-12 mb-2 mt-2 mb-xl-0">
                                            <form class="form row">
                                                    <input type="text" id="lat" value="61.9241" name="lat" hidden>
                                                    <input type="text" id="lng" value="25.7482" name="lng" hidden>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <div class="input-with-icon">
                                                                <label>Search Location</label>
                                                                <input type="text" class="form-control" id="pac-input" placeholder="Search for a location"  name="address">
                                                                {{-- <img src="assets/img/pin.svg" width="20"> --}}
                                                                <input type="text" id="latitude" name="latitude" hidden />
                                                                <input type="text" id="longitude" name="longitude" hidden />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>Radius (Miles)</label>
                                                            <input type="number" name="radius" id="radius" class = "form-control" placeholder = "Enter Radius" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div type="button" class="btn btn-primary" style="float:right;" onclick="calculate()"> Calculate </div>
                                                    </div>
                                            </form>
                                    </div>
                                    <div class="col-xl-12 col-sm-12 col-12 mt-2  mb-xl-0" style="display:none" id="interest-row">
                                        <div class="row">
                                            <div class="col">
                                                <h5>Top Interests</h5>
                                            </div>
                                        </div>
                                        <div class="row mx-2" id="top_interest">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Statistics Card -->
                </div>
            </section>
            <!-- Dashboard Ecommerce ends -->
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id = "exampleModalLongBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->
</x-app-layout>
