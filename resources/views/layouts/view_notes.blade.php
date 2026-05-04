@foreach($notes as $key => $value)
    <tr>
        <td style="vertical-align:middle;">{{$key + 1}}</td>
        <td style="width:90%;vertical-align:middle;">{!! html_entity_decode($value->auto_save)  !!}</td>
        <td><input type="hidden" id="noteId" value="{{$value->id}}" /><button type="button" class="btn btn-danger deleteNote">Delete</button></td>
    </tr>
@endforeach