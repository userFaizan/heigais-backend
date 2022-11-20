<x-app-layout>
    @push('start-styles')
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
    <!-- END: Vendor CSS-->
    @endpush
    @push('end-styles')
       <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
    @endpush
    @push('start-script')
        <!-- BEGIN: Page Vendor JS-->
        <script src="{{asset('app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js')}}"></script>
        <script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
        <script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.buttons.min.js')}}"></script>
        <script src="{{asset('app-assets/vendors/js/tables/datatable/jszip.min.js')}}"></script>
        <script src="{{asset('app-assets/vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
        <script src="{{asset('app-assets/vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
        <script src="{{asset('app-assets/vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
        <script src="{{asset('app-assets/vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
        <script src="{{asset('app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js')}}"></script>
        <script src="{{asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
        <!-- END: Page Vendor JS-->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    @endpush
    @push('end-script')
    <!-- BEGIN: Page JS-->
    <script src="{{asset('app-assets/js/scripts/tables/table-datatables-basic.js')}} "></script>
    <!-- END: Page JS-->
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
            var tablet;
            $(document).ready(function(){
                tablet = $('#user-table').DataTable({
                    ajax:{
                            url: "{{ route('get_all_events') }}"
                    },
                    "columns": [
                        { 'data': 'DT_RowIndex', 'name': 'DT_RowIndex', 'orderable': true, 'searchable': false },
                        { "name": "title", "data": "title" },
                        { "name": "description", "data": "description" },
                        { "name": "date_from", "data": "date_from" },
                        { "name": "date_to", "data": "date_to" },
                        { "name": "created_at", "data": "created_at" },
                        { "name": "category", "data": "category" },
                        { "name": "type", "data": "type" },
                        { "name": "address", "data": "address" },
                        { "name": "owner", "data": "owner" },
                        { "name": "link", "data": "link" },
                        { "name": "action", "data": "action" },
                    ],
                    "initComplete": function(settings, json) {
                        $('.dataTables_wrapper>.row:eq(1)').addClass('table-responsive p-0 m-0');
                        $('.table-responsive>.col-sm-12').addClass('p-0');
                        if (feather) {
                            feather.replace({
                                width: 14,
                                height: 14
                            });
                        }
                    },
                    "fnDrawCallback": function( oSettings ) {
                    if (feather) {
                            feather.replace({
                                width: 14,
                                height: 14
                            });
                        }
                    },
                    columnDefs: [{
                        "defaultContent": "-",
                        "targets": "_all"
                    }]
                });
            })
       </script>

       <script>
        //   sweet alert starts
        $(document).ready(function() {
            'use strict';
        });
        function delete_event_alert(id){
            Swal.fire({
                title: 'Deletion of event',
                text: "Do you wish to permanently delete this event?",
                type: 'warning',
                iconColor: '#808080',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-danger ml-1'
                },
                buttonsStyling: false
            }).then(function (result) {
                if (result.value) {
                    delete_event(id);
                    Swal.fire({
                        type: 'success',
                        title: 'Deletion successful ',
                        text: 'This event has successfully been deleted',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: 'Cancelled request',
                        text: 'Your request has successfully been cancelled',
                        type: 'error',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                }
            });
        }
        //   sweet alert ends
   </script>
       <script>
           function delete_event(id){
            $.ajax({
                method:'post',
                url:"{{route('delete_event')}}",
                data:{

                    id:id,
                    _token:'{{csrf_token()}}',
                },
                success:function () {
                    console.log('event deleted successfully');
                    tablet.ajax.reload();
                    setTimeout(()=>{
                        feather.replace({
                            width: 14,
                            height: 14
                        })
                    },500)
                }
            })
        }
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
               <div class="row">
                   <div class="col-auto ml-auto mb-2">
                        <a href="{{route('events.create')}}" type="button" class="btn btn-primary"> Create </a>
                   </div>
               </div>
               <!-- Row grouping -->
               <section id="row-grouping-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">All Events</h4>
                            </div>
                            <div class="card-datatable">
                                <table class="table" id="user-table">{{--dt-row-grouping--}}
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th style="min-width: 100px;">Start time</th>
                                            <th style="min-width: 100px;">End time</th>
                                            <th style="min-width: 100px;">Created on</th>
                                            <th>Category</th>
                                            <th>Type</th>
                                            <th>Address</th>
                                            <th>Owner</th>
                                            <th>link</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>id</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th style="min-width: 100px;">Start time</th>
                                            <th style="min-width: 100px;">End time</th>
                                            <th style="min-width: 100px;">Created on</th>
                                            <th>Category</th>
                                            <th>Type</th>
                                            <th>Address</th>
                                            <th>Owner</th>
                                            <th>link</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Row grouping -->
           </div>
       </div>
   </div>
   <!-- END: Content-->
</x-app-layout>
