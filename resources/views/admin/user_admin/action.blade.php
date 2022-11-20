<a  style="color: green" href="{{route('admins.edit',[$admin->id])}}"><i data-feather='edit-3'></i></a>
@if((int)$admin->status == 1)
    <a style="color: orange" onclick="block_admin_alert({{$admin->id}})"><i data-feather='x-circle'></i></a>
@else
    <a style="color: blue" onclick="activate_admin_alert({{$admin->id}})"><i data-feather='check-circle'></i></a>
@endif
<a  style="color: red" onclick="delete_admin_alert({{$admin->id}})"><i data-feather='trash'></i></a>
