<a  style="color: green" href="{{route('users.edit',[$user->id])}}"><i data-feather='edit-3'></i></a>
@if((int)$user->status == 1)
    <a style="color: orange" onclick="block_user_alert({{$user->id}})"><i data-feather='x-circle'></i></a>
@else
    <a style="color: blue" onclick="activate_user_alert({{$user->id}})"><i data-feather='check-circle'></i></a>
@endif
<a  style="color: red" onclick="delete_user_alert({{$user->id}})"><i data-feather='trash'></i></a>
