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
                                <h4 class="card-title">{{isset($edit_user)?'Update':'Create'}} User</h4>
                            </div>
                            <div class="card-body">
                                @if (isset($edit_user))
                                    {{ Form::model($edit_user, ['method' => 'put', 'class'=>'form', 'enctype'=>'multipart/form-data' , 'route' => ['users.update', $edit_user->id]]) }}
                                @else
                                    {{ Form::open(['method' => 'post', 'class'=>'form', 'enctype'=>'multipart/form-data', 'route' => 'users.store']) }}
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
                                                {{ Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Email', 'required', isset($edit_user)?'disabled':'' ]) }}
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
                                        @if (!isset($edit_user))
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
                                        @if(isset($edit_user))
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>Credit</label>
                                                    <input type="number"
                                                    value=@foreach ($edit_user->walets as $walet)
                                                        {{$walet->credit}}
                                                    @endforeach
                                                    class="form-control" name="credit">
                                                </div>
                                            </div>
                                        @endif

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
