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
            function select_all() {
                if($('#customCheck1').is(':checked')){
                    $('#large-select-multi').select2('destroy').find('option').prop('selected', 'selected').end().select2();

                }else{

                    // Unselect all
                    $('#large-select-multi').select2('destroy').find('option').prop('selected', false).end().select2();
                }

            };
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
                                <h4 class="card-title">Create Notification for Users</h4>
                            </div>
                            <div class="card-body">
                                {{ Form::open(['method' => 'post', 'class'=>'form', 'enctype'=>'multipart/form-data', 'route' => 'notifications.store']) }}
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Select Users</label>
                                                <select class="select2 form-control" multiple="multiple" id="large-select-multi" name="user_ids[]">
                                                    @foreach($users as $user)
                                                        <option value="{{$user->id}}">{{$user->email}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                {{ Form::label('title', 'Title') }}
                                                {{ Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => 'Enter title', 'required']) }}
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 mb-1">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1" onclick="select_all()"/>
                                                    <label class="custom-control-label" style="font-size:unset" for="customCheck1">Select All</label>
                                                </div>
                                        </div>

                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                {{ Form::label('message', 'Message') }}
                                                {{ Form::textarea('message', old('message'), ['class' => 'form-control', 'placeholder' => 'Description', 'required']) }}
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" style="float:right" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                {{ Form::close() }}
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
