<a  style="color: green" href="{{route('events.edit',[$event->id])}}"><i data-feather='edit-3'></i></a>
<a  style="color: red"  onclick="delete_event_alert({{$event->id}})" ><i data-feather='trash'></i></a>
