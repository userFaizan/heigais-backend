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
                                <h4 class="card-title">{{isset($edit_category)?'Update':'Create'}} Category</h4>
                            </div>
                            <div class="card-body">
                                @if (isset($edit_category))
                                    {{ Form::model($edit_category, ['method' => 'put', 'class'=>'form', 'enctype'=>'multipart/form-data' , 'route' => ['categories.update', $edit_category->id]]) }}
                                @else
                                    {{ Form::open(['method' => 'post', 'class'=>'form', 'enctype'=>'multipart/form-data', 'route' => 'categories.store']) }}
                                @endif
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                {{ Form::label('color', 'Color') }}
                                                {{ Form::color('color', old('color'), ['class' => 'form-control', 'required']) }}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Parent</label>
                                                <select class="form-control mb-1" name="parent_id">
                                                    <option value="0">select parent if</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{$category->id}}" {{isset($edit_category)?($edit_category->parent?(($edit_category->parent->id == $category->id)? 'selected':''):''):''}}>{{$category->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Title in English</label>
                                                <input type="text" name="english" class="form-control" value="{{isset($edit_category)?$english->text:''}}" placeholder="Title in English" {{isset($edit_category)?'':'required'}}>
                                            </div>
                                        </div>
                                        @if (isset($edit_category))
                                            <input type="text" name="category_title" value="{{$english->text_key}}" hidden>
                                        @endif
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Title in Finish</label>
                                                <input type="text" name="finish" class="form-control" value="{{isset($edit_category)?$finish->text:''}}" placeholder="Title in Finish" {{isset($edit_category)?'':'required'}}>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Title in Swidish</label>
                                                <input type="text" name="swidish" class="form-control" value="{{isset($edit_category)?$swidish->text:''}}" placeholder="Title in Swidish" {{isset($edit_category)?'':'required'}}>
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
