<option value="">Select Option</option>
            @foreach($tools as $tool)
            <option value="{{$tool->id}}">{{$tool->tool}}</option>
            @endforeach
