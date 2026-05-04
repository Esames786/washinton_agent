@foreach ($ans  as $key => $value)
<li><input type="radio" name="question__name" value="fixedQandArightquestion{{$key}}"
        id="fixedQandArightquestion{{$key}}">
    <label for="fixedQandArightquestion{{$key}}">
        {!! html_entity_decode($value->anwser) !!} 
        <span
            class="fixedleftnohidden"></span></label>
    <input type="hidden" value="{{$value->id}}" class="a_ID">
</li>
@endforeach