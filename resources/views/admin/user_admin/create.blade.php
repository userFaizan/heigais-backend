<x-app-layout>
    @push('start-styles')
    @endpush
    @push('end-styles')
       <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
       {{-- <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/dashboard-ecommerce.css')}}">
       <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/charts/chart-apex.css')}}">
       <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/extensions/ext-component-toastr.css')}}"> --}}
    @endpush
    @push('start-script')
    @endpush
    @push('end-script')
       {{-- <script src="{{asset('app-assets/js/scripts/pages/dashboard-ecommerce.js')}}"></script> --}}
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
                                <h4 class="card-title">{{isset($edit_admin)?'Update':'Create'}} Sub Admin</h4>
                            </div>
                            <div class="card-body">
                                @if (isset($edit_admin))
                                    {{ Form::model($edit_admin, ['method' => 'put', 'class'=>'form', 'enctype'=>'multipart/form-data' , 'route' => ['admins.update', $edit_admin->id]]) }}
                                @else
                                    {{ Form::open(['method' => 'post', 'class'=>'form', 'enctype'=>'multipart/form-data', 'route' => 'admins.store']) }}
                                @endif
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                {{ Form::label('name', 'Name') }}
                                                {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'First Name', 'required']) }}
                                                @error('name')
                                                    <span class="input-error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                {{ Form::label('email', 'Email') }}
                                                {{ Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Email', 'required', isset($edit_admin)?'disabled':'' ]) }}
                                                @error('email')
                                                    <span class="input-error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                {{ Form::label('location', 'Location') }}
                                                {{ Form::text('location', old('location'), ['class' => 'form-control', 'placeholder' => 'Enter location', 'required']) }}
                                                @error('location')
                                                    <span class="input-error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        @if (!isset($edit_admin))
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Password</label>
                                                @error('password')
                                                    <span class="input-error">{{ $message }}</span>
                                                @enderror
                                                <input type="text" name="password" class = "form-control" placeholder = "Enter password" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Confirm Password</label>
                                                @error('password_confirmation')
                                                    <span class="input-error">{{ $message }}</span>
                                                @enderror
                                                <input type="text" name="password_confirmation" class = "form-control" placeholder = "Enter confirm password" required>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12 mb-1">
                                                    What Permissions you want to allow?
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="dashboard" class="custom-control-input" id="customCheck1" {{isset($edit_admin)?($edit_admin->hasPermission('manage-dashboard')?'checked':''):''}}/>
                                                        <label class="custom-control-label" for="customCheck1">Dashboard</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="user" class="custom-control-input" id="customCheck2" {{isset($edit_admin)?($edit_admin->hasPermission('manage-users')?'checked':''):''}}/>
                                                        <label class="custom-control-label" for="customCheck2">User</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="event" class="custom-control-input" id="customCheck3" {{isset($edit_admin)?($edit_admin->hasPermission('manage-events')?'checked':''):''}}/>
                                                        <label class="custom-control-label" for="customCheck3">Event</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="category" class="custom-control-input" id="customCheck4" {{isset($edit_admin)?($edit_admin->hasPermission('manage-categories')?'checked':''):''}}/>
                                                        <label class="custom-control-label" for="customCheck4">Category</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- @if(isset($edit_admin))
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>Credit</label>
                                                    <input type="number"
                                                    value=@foreach ($edit_admin->walets as $walet)
                                                        {{$walet->credit}}
                                                    @endforeach
                                                    class="form-control" name="credit">
                                                </div>
                                            </div>
                                        @endif --}}

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
