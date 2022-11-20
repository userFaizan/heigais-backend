<x-app-layout>
    @push('start-styles')
        <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/select/select2.min.css')}}">
    @endpush
    @push('end-styles')
       <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
       {{-- <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/dashboard-ecommerce.css')}}">
       <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/charts/chart-apex.css')}}">
       <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/extensions/ext-component-toastr.css')}}"> --}}
    @endpush
    @push('start-script')
        <script src="{{asset('app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
    @endpush
    @push('end-script')
       {{-- <script src="{{asset('app-assets/js/scripts/pages/dashboard-ecommerce.js')}}"></script> --}}
       <script src="{{asset('app-assets/js/scripts/forms/form-select2.js')}}"></script>
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
           })
       </script>
       <script>
            $('#parent').change(function() {
                $.ajax({
                    method:'post',
                    url:"{{route('get_child_categories')}}",
                    data:{
                        id:this.value,
                        _token:'{{csrf_token()}}',
                    },
                    success:function (response) {

                        $('#large-select-multi').empty();
                        response.categories.forEach(category => {
                            var newOption = new Option(category.title, category.id, false, false);
                            $('#large-select-multi').append(newOption);
                        });
                    }
                })
            });

            $('#type_id').change(function() {
                if(this.value == 3){
                    $('#end_date').removeAttr('disabled');

                }else{
                    $('#end_date').attr('disabled', 'disabled');
                }
            });
            $('#type_id').change(function() {
                if(this.value == 3){
                    $('#end_date').removeAttr('disabled');
                }else{
                    $('#end_date').attr('disabled', 'disabled');
                }
            });
            $('#start_time').change(function() {
                var start_time=this.value.split(":");
                if($('#type_id').val()==1){
                    if(parseInt(start_time[0]) >= 18 ){
                        $('#start_time_error').text(' The start time of event cannot be later than 6:00 PM');
                        $('#start_time').val('');
                        $('#start_time_error').show();
                    }else{
                        $('#start_time_error').hide();
                    }
                }
            });
            $('#end_time').change(function() {
                var start_time = $('#end_time').val();
                start_time = start_time.split(":");
                var end_time=this.value.split(":");
                if($('#type_id').val()==1){
                    if(parseInt(end_time[0]) >= 18 ){
                        $('#end_time_error').text('The end time of event cannot be later than 6:00 PM');
                        $('#end_time_error').show();
                        $('#end_time').val('');
                    }else{
                        $('#end_time_error').hide();
                            if((parseInt(end_time[0]) - parseInt(start_time[0]))>6){
                            $('#end_time_error').text('Free event will be maximum 6 hrs length' );
                            $('#end_time_error').show();
                            $('#end_time').val('');
                            $('#start_time_error').text('Free event will be maximum 6 hrs length' );
                            $('#start_time_error').show();
                            $('#start_time').val('');
                        }else{
                            $('#end_time_error').hide();
                            $('#start_time_error').hide();
                        }
                    }

                }
                if($('#type_id').val()==2){
                    if((parseInt(end_time[0]) - parseInt(start_time[0]))>10){
                        $('#end_time_error').text('Standard event will be maximum 10 hrs length' );
                        $('#end_time_error').show();
                        $('#end_time').val('');
                        $('#start_time_error').text('Standard event will be maximum 10 hrs length' );
                        $('#start_time_error').show();
                        $('#start_time').val('');
                    }else{
                        $('#end_time_error').hide();
                        $('#start_time_error').hide();
                    }
                }
            });
            $('#end_date').change(function() {
                var start_date = new Date($('#start_date').val());
                var end_date = new Date(this.value);
                var Difference_In_Time = end_date.getTime() - start_date.getTime();
                var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
                if(Difference_In_Days > 4)
                {
                    $('#start_date_error').text('Premium event will be maximun 96 hrs length' );
                    $('#start_date_error').show();
                    $('#start_date').val('');
                    $('#end_date_error').text('Premium event will be maximun 96 hrs length' );
                    $('#end_date_error').show();
                    $('#end_date').val('');
                }else{
                    $('#start_date_error').hide();
                    $('#end_date_error').hide();
                }
            });

       </script>
       <script>
        var radius = 1000;
        let map;
        var circle;
        var marker;
        var geocoder;
        // window.initMap =
        function initMap() {
            geocoder = new google.maps.Geocoder();
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
                // document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(3) + ' Current Lng: ' + evt.latLng.lng().toFixed(3) + '</p>';
                $('#lat').val(marker.getPosition().lat());
                $('#lng').val(marker.getPosition().lng());
                geocodePosition(marker.getPosition());

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
        function geocodePosition(pos) {
            geocoder.geocode({
                latLng: pos
            }, function(responses) {
                // console.log(responses[0].formatted_address);
                if (responses && responses.length > 0) {
                    $('#pac-input').val(responses[0].formatted_address);
                }
            });
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
    </script>
    @endpush


   <!-- BEGIN: Content-->
   <div class="app-content content ">
       <div class="content-overlay"></div>
       <div class="header-navbar-shadow"></div>
       <div class="content-wrapper">
           <div class="content-header row">
           </div>
           <div class="content-body">
               <!-- Dashboard Ecommerce Starts -->
               {{-- <section id="dashboard-ecommerce">
                   <div class="row match-height">

                   </div>

               </section> --}}
               <!-- Dashboard Ecommerce ends -->
               <section id="multiple-column-form">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{isset($edit_event)?'Update':'Create'}} Event</h4>
                            </div>
                            <div class="card-body">
                                @if (isset($edit_event))
                                    {{ Form::model($edit_event, ['method' => 'put', 'class'=>'form', 'enctype'=>'multipart/form-data' , 'route' => ['events.update', $edit_event->id]]) }}
                                @else
                                    {{ Form::open(['method' => 'post', 'class'=>'form', 'enctype'=>'multipart/form-data', 'route' => 'events.store']) }}
                                @endif
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                {{ Form::label('title', 'Title') }}
                                                {{ Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => 'Title', 'required']) }}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Select User</label>
                                                <select class="form-control mb-1" name="user_id" id="user_id">
                                                    @foreach($users as $user)
                                                        <option value="{{$user->id}}" {{isset($edit_event)?(($edit_event->user_id == $user->id)? 'selected':''):''}}>{{$user->email}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                {{ Form::label('sub_title', 'Subtitle') }}
                                                {{ Form::text('sub_title', old('sub_title'), ['class' => 'form-control', 'placeholder' => 'Subtitle', 'required']) }}
                                            </div>
                                        </div> --}}
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Main Category</label>
                                                <select class="form-control mb-1" name="category_id" id="parent">
                                                    @foreach($pcategories as $category)
                                                        <option value="{{$category->id}}" {{isset($edit_event)?(($edit_event->category_id == $category->id)? 'selected':''):''}}>{{$category->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Sub Category</label>
                                                <select class="select2 form-control" name="category_ids[]" multiple="multiple" id="large-select-multi">
                                                    @foreach($ccategories as $category)
                                                        <option value="{{$category->id}}" {{isset($edit_event)?(($edit_event->category_id == $category->id)? 'selected':''):''}}>{{$category->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <div class="input-with-icon">
                                                    <label>Search Location</label>
                                                    <input type="text" name="address" value="{{isset($edit_event)?$edit_event->address:''}}" class="form-control" id="pac-input" placeholder="Search for a location">
                                                    {{-- <img src="assets/img/pin.svg" width="20"> --}}
                                                    <input type="text" id="lat" name="lat" value="{{isset($edit_event)?$edit_event->lat:'61.9241'}}" hidden/>
                                                    <input type="text" id="lng" name="lng" value="{{isset($edit_event)?$edit_event->lng:'25.7482'}}" hidden/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Type</label>
                                                <select class="form-control mb-1" name="type_id" id="type_id">
                                                    @foreach($types as $type)
                                                        <option value="{{$type->id}}" {{isset($edit_event)?(($edit_event->type_id == $type->id)? 'selected':''):''}}>{{$type->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div id="map" style="width:100%; height:400px;"></div>
                                        </div>
                                        {{-- <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                {{ Form::label('lat', 'Latitude') }}
                                                {{ Form::number('lat', old('lat'), ['class' => 'form-control', 'step'=>'0.000001', 'placeholder' => 'Enter Latitude', 'required']) }}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                {{ Form::label('lng', 'Longitude') }}
                                                {{ Form::number('lng', old('lng'), ['class' => 'form-control', 'step'=>'0.000001', 'placeholder' => 'Enter longitude', 'required']) }}
                                            </div>
                                        </div> --}}
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Start Date <span style="color:red;" id="start_date_error"></span></label>
                                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{isset($edit_event)?date('Y-m-d', strtotime($edit_event->date_from)):''}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>End Date <span style="color:red;" id="end_date_error"></span></label>
                                                <input type="date" name="end_date" id="end_date" class="form-control"  value="{{isset($edit_event)?date('Y-m-d', strtotime($edit_event->date_to)):''}}"  required disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Start Time <span style="color:red;" id="start_time_error"></span></label>
                                                <input type="time" name="start_time" id="start_time" class="form-control" value="{{isset($edit_event)?date('h:i', strtotime($edit_event->date_from)):''}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>End time <span style="color:red;" id="end_time_error"></span></label>
                                                <input type="time" id="end_time" name="end_time" class="form-control" value="{{isset($edit_event)?date('h:i', strtotime($edit_event->date_from)):''}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                {{ Form::label('link', 'Link') }}
                                                {{ Form::text('link', old('link'), ['class' => 'form-control', 'placeholder' => 'Enter link']) }}
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                {{ Form::label('description', 'Description') }}
                                                {{ Form::textarea('description', old('description'), ['class' => 'form-control', 'placeholder' => 'Description', 'required']) }}
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" style="float:right" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

           </div>
       </div>
   </div>
   <!-- END: Content-->
   </x-app-layout>
