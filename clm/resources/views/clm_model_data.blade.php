<style>
    .multiselect-native-select .btn-group {
        width: 100%;
    }
.multiselect {
    display: block;
    width: 100%;
    padding: .375rem .75rem;
    font-size: .8rem;
    font-weight: 400;
    line-height: 1.5;
    color: var(--bs-body-color);
    background-color: var(--bs-body-bg);
    background-clip: padding-box;
    border: var(--bs-border-width) solid var(--bs-border-color);
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: var(--bs-border-radius);
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    text-align:left !important;
}
.modal-dialog-scrollable .modal-content {
    overflow: visible;
}
</style>






<div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$task_name}}</h5>
        <button type="button" class="btn-close btn_modal_close" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>

<div class="modal-body">
       <form class="g-3" id="clmModelForm" method="POST"  action="{{ route('task_save')}}">
        <input type="hidden" name="fortab" value="{{$tabPageValue}}">

       @csrf
                                    <div class="row mb-3">
                                        <label for="taskGenDate" class="col-sm-4 col-form-label col-form-label-sm">Task generated date :</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control form-control-sm" id="taskGenDate" placeholder="Default task generated date & time"  disabled value="{{$gen_task_id !=0 ? $task_gen_data->task_generate_date : date('Y-m-d')}}">
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="taskDueDate" class="col-sm-4 col-form-label col-form-label-sm">Task due date :</label>
                                        <div class="col-sm-8">
                                            <input type="date" name="task_due_date"  min="{{ $currentDate }}" class="form-control form-control-sm" id="taskDueDate" placeholder="Due date as per set matrix" value="{{$gen_task_id !=0 ? $task_gen_data->task_due_date : ''}}" {{$gen_task_id !=0 ? 'readonly' : ''}}>
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="taskOwner" class="col-sm-4 col-form-label col-form-label-sm">Task Owner :</label>
                                        <div class="col-sm-8">
                                            @if($gen_task_id ==0)
                                            <select  class="form-select" name="task_owner" required>
                                            <option value="">Select faculty</option>
                                        @foreach($faculty as $ft)
                                                <option value="{{$ft->id}}">{{$ft->name}}</option>
                                            @endforeach
                                            </select>
                                            @else
                                            <input type="text" class="form-control form-control-sm" id="taskOwner" value="{{$task_gen_data->task_owner}}" name="task_owner" disabled>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="taskSub" class="col-sm-4 col-form-label col-form-label-sm">Task Subject :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" id="taskSub" value="{{$task_name}}" disabled>
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Status :</label>
                                        <div class="col-sm-8">
                                            <select  class="form-select" id="status" name="status" onchange="statusChange(this.value,{{$task_id}})">
                                                @if($gen_task_id == 0)
                                                <option value="Not Started" selected>Not started</option>
                                                @else
                                                <option value="">Select option</option>
                                                <option {{$task_gen_data->task_status == 'Not Started' ? 'selected' : '' }} value="Not Started">Not started</option>
                                                <option {{$task_gen_data->task_status == 'In Progress' ? 'selected' : '' }} value="In Progress">In progress</option>
                                                <option {{$task_gen_data->task_status == 'Completed' ? 'selected' : '' }} value="Completed">Completed</option>
                                                <option {{$task_gen_data->task_status == 'Re-scheduled' ? 'selected' : '' }} value="Re-scheduled">Re-scheduled</option>
                                                <option {{$task_gen_data->task_status == 'Cancelled' ? 'selected' : '' }} value="Cancelled">Cancelled</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    
                                    @if($task_id == 9 && $gen_task_id != 0)
                                    <div id="completedCondDiv" style="{{$task_gen_data->task_status == 'Re-scheduled' ? 'display:none' : ''}}">
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Grade :</label>
                                        <div class="col-sm-8">
                                            <select  class="form-select" id="id39" name="name39[]" onchange="gradeListing({{$task_gen_data->id}})" multiple="multiple">
                                                @foreach($grade_id as $idg)
                                                <option {{ isset($answers[39]) && $answers->isNotEmpty() ? (@in_array((string)$idg->grade,$grade, true) ? 'selected' : '') : '' }} value="{{$idg->grade}}">Grade {{$idg->grade}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if( isset($answers[39]) && $answers->isNotEmpty())
                                    <div id = "notReqGrade" class="row ">
                                   
                                        <label for="id40" class="col-sm-4 col-form-label mb-3 col-form-label-sm">Not required tools :</label>
                                        <div class="col-sm-8 mb-3">
                                        <select  class="form-select" id="id40" name="name40[]" multiple="multiple" onchange="toolsCondition('40',{{$task_gen_data->id}})">

                                                @foreach($tools as $tool)
                                                <option {{ @in_array((string)$tool->id,$not_req, true) ? 'selected' : '' }} value="{{$tool->id}}">{{$tool->tool}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    
                                   
                                        <label for="id41" class="col-sm-4 col-form-label col-form-label-sm mb-3">Tools Covered :</label>
                                        <div class="col-sm-8 mb-3">
                                        <select  class="form-select" id="id41" name="name41[]"  multiple="multiple" onchange="toolsCondition('41',{{$task_gen_data->id}})">

                                                @foreach($toolsForCovered as $tool)
                                                <option {{ @in_array((string)$tool->id,$covered_tool, true) ? 'selected' : '' }} value="{{$tool->id}}">{{$tool->tool}}</option>
                                                @endforeach
                                            </select>
                                       
                                    </div>
                                </div>
                                @else
                                <div class="row mb-3">
                                        <label for="id40" class="col-sm-4 col-form-label col-form-label-sm">Not required tools :</label>
                                        <div class="col-sm-8">
                                        <select  class="form-select" id="id40" name="name40[]" multiple="multiple"  onchange="toolsCondition('40',{{$task_gen_data->id}})">


                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="id41" class="col-sm-4 col-form-label col-form-label-sm">Tools Covered :</label>
                                        <div class="col-sm-8">
                                        <select  class="form-select" id="id41" name="name41[]" multiple="multiple"  onchange="toolsCondition('41',{{$task_gen_data->id}})">


                                    </select>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Session feedback :</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="id42" name="name42"  class="form-control form-control-sm" value="{{isset($answers) && $answers->isNotEmpty() && isset($answers[42]) ? $answers[42] : '' }}">
                                            <!-- <select  class="form-select" id="id42" name="name42" >
                                                <option value="">Select option</option>
                                                <option {{$answers && $answers->isNotEmpty() && isset($answers['42']) ? ($answers['42']=='Fair' ? 'selected' : '') : ''}} value="Fair">Fair</option>
                                                <option {{$answers && $answers->isNotEmpty() && isset($answers['42']) ? ($answers['42']=='Average' ? 'selected' : '') : ''}} value="Average">Average</option>
                                                <option {{$answers && $answers->isNotEmpty() && isset($answers['42']) ? ($answers['42']=='Good' ? 'selected' : '') : ''}} value="Good">Good</option>
                                                <option {{$answers && $answers->isNotEmpty() && isset($answers['42']) ? ($answers['42']=='Excellent' ? 'selected' : '') : ''}} value="Excellent">Excellent</option>
                                            </select> -->
                                        </div>
                                    </div>	
                                    <div class="row mb-3">
                                        <label for="id43" class="col-sm-4 col-form-label col-form-label-sm">
                                            Number of attendees :
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="number" id="id43" name="name43"  class="form-control form-control-sm" value="{{isset($answers) && $answers->isNotEmpty() && isset($answers[43]) ? $answers[43] : '' }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="id44" class="col-sm-4 col-form-label col-form-label-sm">Next tool training scheduled ? :</label>
                                        <div class="col-sm-8">
                                        <select  class="form-select" id="id44" name="name44" onchange="gradeListingToBe(this.value,{{$task_gen_data->id}})" {{isset($answers[44]) && $answers->isNotEmpty() && $answers['44'] == 'Yes' ? 'disabled' : ''}}>
                                                <option  value="">Select option</option>
                                                <option {{$answers && $answers->isNotEmpty() && isset($answers['44']) ? ($answers['44']=='Yes' ? 'selected' : '') : ''}} value="Yes">Yes</option>
                                                <option {{$answers && $answers->isNotEmpty() && isset($answers['44']) ? ($answers['44']=='No' ? 'selected' : '') : ''}} value="No">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    @if(isset($answers[44]) && $answers->isNotEmpty() && $answers['44'] == 'Yes')
                                   
                                    <div class="row mb-3" id = "toolsToCover">
                                    <label for="id93" class="col-sm-4 col-form-label col-form-label-sm">Next tool training date :</label>
                                        <div class="col-sm-8 mb-3">
                                        <input type="date" class="form-control form-control-sm" id="id93" name="name93" placeholder="" value="{{isset($answers) && $answers->isNotEmpty() && isset($answers['93']) ? $answers[93] : ''}}" disabled>
                                    </div>
                                        <label for="id95" class="col-sm-4 col-form-label col-form-label-sm">Next tool training time :</label>
                                        <div class="col-sm-8 mb-3">
                                        <input type="time" class="form-control form-control-sm" id="id95" name="name95" placeholder="" value="{{isset($answers) && $answers->isNotEmpty() && isset($answers['95']) ? $answers[95] : ''}}" disabled>
                                    </div>
                                        <label for="id94" class="col-sm-4 col-form-label col-form-label-sm">Faculty :</label>
                                        <div class="col-sm-8 mb-3">
                                        <select  class="form-select" id="id94" name="name94" disabled>
                                            <option value="">Select faculty</option>
                                        @foreach($faculty as $ft)
                                                <option {{$answers && $answers->isNotEmpty() && isset($answers['94']) ? ($answers['94']==$ft->id ? 'selected' : '') : ''}} value="{{$ft->id}}">{{$ft->name}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <label for="id45" class="col-sm-4 col-form-label col-form-label-sm">Tools to be covered ? :</label>
                                        <div class="col-sm-8">
                                        <select class="form-select" id="id45" name="name45[]" multiple="multiple">                                            
                                                @foreach($toolsForToBeCovered as $tool)
                                                <option {{ @in_array((string)$tool->id,$to_be_covered_tool, true) ? 'selected' : '' }} value="{{$tool->id}}">{{$tool->tool}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                               
                                @else
                                <div class="row" id = "toolsToCover" style="{{$answers && $answers->isNotEmpty() && isset($answers['44']) ? ($answers['44']=='Yes' ? 'display:block' : 'display:none') : 'display:none'}}">
                                    <label for="id93" class="col-sm-4 col-form-label col-form-label-sm">Next tool training date :</label>
                                        <div class="col-sm-8 mb-3">
                                        <input type="date" class="form-control form-control-sm" id="id93" name="name93" placeholder="">
                                    </div>
                                        <label for="id95" class="col-sm-4 col-form-label col-form-label-sm">Next tool training time :</label>
                                        <div class="col-sm-8 mb-3">
                                        <input type="time" class="form-control form-control-sm" id="id95" name="name95" placeholder="">
                                    </div>
                                        <label for="id94" class="col-sm-4 col-form-label col-form-label-sm">Faculty :</label>
                                        <div class="col-sm-8 mb-3">
                                        <select  class="form-select" id="id94" name="name94">
                                            <option value="">Select faculty</option>
                                        @foreach($faculty as $ft)
                                                <option value="{{$ft->id}}">{{$ft->name}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <label for="id45" class="col-sm-4 col-form-label col-form-label-sm">Tools to be covered ? :</label>
                                        <div class="col-sm-8 mb-3">
                                        <select  class="form-select" id="id45" name="name45[]" multiple="multiple">
                                            </select>
                                    </div>
                                </div>
                                    @endif
                                </div>
                                @elseif($gen_task_id == 0 && $task_id == 9)
                                <div class="" id="completedCondDiv">
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Grade :</label>
                                        <div class="col-sm-8">
                                            <select  class="form-select" id="id39" name="name39[]" onchange="gradeListing({{$task_gen_data->id}})" multiple="multiple">
                                                @foreach($grade_id as $idg)
                                                <option value="{{$idg->grade}}">Grade {{$idg->grade}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="id40" class="col-sm-4 col-form-label col-form-label-sm">Not required tools :</label>
                                        <div class="col-sm-8">
                                        <select  class="form-select" id="id40" name="name40[]" multiple="multiple"  onchange="toolsCondition('40',{{$task_gen_data->id}})">


                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3"> 
                                        <label for="id41" class="col-sm-4 col-form-label col-form-label-sm">Tools Covered :</label>
                                        <div class="col-sm-8">
                                        <select  class="form-select" id="id41" name="name41[]" multiple="multiple"  onchange="toolsCondition('41',{{$task_gen_data->id}})">


                                        </select>
                                        </div>
                                    </div>

                                     <!-- <div class="row mb-3">
                                        <label for="id44" class="col-sm-4 col-form-label col-form-label-sm">Next tool training scheduled ? :</label>
                                        <div class="col-sm-8">
                                        <select  class="form-select" id="id44" name="name44" onchange="gradeListingToBe(this.value,{{$task_gen_data->id}})">
                                                <option value="">Select option</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div> 
                                <div class="row" id = "toolsToCover" style="display:none">
                                    <label for="id93" class="col-sm-4 col-form-label col-form-label-sm">Next tool training date :</label>
                                        <div class="col-sm-8 mb-3">
                                        <input type="date" class="form-control form-control-sm" id="id93" name="name93" placeholder="">
                                    </div>
                                        <label for="id95" class="col-sm-4 col-form-label col-form-label-sm">Next tool training time :</label>
                                        <div class="col-sm-8 mb-3">
                                        <input type="time" class="form-control form-control-sm" id="id95" name="name95" placeholder="">
                                    </div>
                                        <label for="id94" class="col-sm-4 col-form-label col-form-label-sm">Faculty :</label>
                                        <div class="col-sm-8 mb-3">
                                        <select  class="form-select" id="id94" name="name94">
                                            <option value="">Select faculty</option>
                                        @foreach($faculty as $ft)
                                                <option value="{{$ft->id}}">{{$ft->name}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <label for="id45" class="col-sm-4 col-form-label col-form-label-sm">Tools to be covered ? :</label>
                                        <div class="col-sm-8 mb-3">
                                        <select  class="form-select" id="id45" name="name45[]" multiple="multiple">
                                            </select>
                                    </div>
                                </div> -->
                                </div>
                                @else
                                <div id="completedCondDiv" style="{{$task_gen_data->task_status == 'Re-scheduled' ? 'display:none' : ($task_id==11 || $task_id==14 ? ($task_gen_data->task_status != 'Completed' ? 'display:none' : '') : '')}}">
                                    <div class="row" >
                                    @foreach($ques as $q)
                                        @if($q['dont_show'] == 0)
                                        @if($q['ques_type'] == 'select')
                                            <label for="{{$q['input_id']}}" class="col-sm-4 mb-3 col-form-label col-form-label-sm">{{$q['question']}} :</label>
                                            <div class="col-sm-8 mb-3">
                                            <select class="form-select" id="{{$q['input_id']}}" name="{{$q['input_name']}}" {{$q['event_function']}}>
                                            @php
                                                $options = explode(',', $q['select_option']);
                                            @endphp
                                            <option value="">Select option</option>
                                            @foreach($options as $dd)
                                                <option {{$q["answer"] == $dd ? 'selected' : '' }} value="{{$dd}}">{{$dd}}</option>
                                            @endforeach
                                            </select>
                                            </div>
                                        @elseif($q['ques_type'] == 'text')
                                            <label for="{{$q['input_id']}}" class="col-sm-4 mb-3 col-form-label col-form-label-sm">{{$q['question']}} :</label>
                                            <div class="col-sm-8 mb-3">
                                            <input type="text" id="{{$q['input_id']}}" name="{{$q['input_name']}}" class="form-control form-control-sm" value="{{$q['answer']}}">
                                            </div>
                                        @elseif($q['ques_type'] == 'number')
                                            <label for="{{$q['input_id']}}" class="col-sm-4 mb-3 col-form-label col-form-label-sm">{{$q['question']}} :</label>
                                            <div class="col-sm-8 mb-3">
                                            <input type="number" id="{{$q['input_id']}}" name="{{$q['input_name']}}"  class="form-control form-control-sm" value="{{$q['answer']}}">
                                            </div>
                                        @elseif($q['ques_type'] == 'date')
                                            <label for="{{$q['input_id']}}"  class="col-sm-4 mb-3 col-form-label col-form-label-sm">{{$q['question']}} :</label>
                                            <div class="col-sm-8 mb-3">
                                            <input type="date" id="{{$q['input_id']}}" name="{{$q['input_name']}}" class="form-control form-control-sm" value="{{$q['answer']}}">
                                            </div>
                                        @endif
                                        @endif
                                    @endforeach
                                    </div>
                                    </div>
                                    @if($task_id == 2)
                                    <div class="row">
                                    <label for="id8"  class="col-sm-4 mb-3 col-form-label col-form-label-sm">Program initiation date? :</label>
                                            <div class="col-sm-8 mb-3">
                                            <input type="date" id="id8" name="name8" class="form-control form-control-sm" value="{{$program_initiation_date}}" {{$program_initiation_date != '' ? 'readonly' : ''}}>
                                            </div>
                                            <label for="id92" class="col-sm-4 mb-3 col-form-label col-form-label-sm">School Spoc for upcoming CLM :</label>
                                            <div class="col-sm-8 mb-3">
                                            <select class="form-select" id="id92" name="name92">
                                            <option value="">Select option</option>
                                            @foreach($faculty as $ft)
                                                <option {{isset($facultySelected) ? ($facultySelected==$ft->id ? 'selected' : '') : ''}} value="{{$ft->id}}">{{$ft->name}}</option>
                                            @endforeach
                                            </select>
                                            </div>
                                            </div>
                                    @endif
                                @endif
                                    
                                    <div class="row mb-3" id="reschDateDiv" style="{{$display}}">
                                            <label for="rescheduleDate"  class="col-sm-4  col-form-label col-form-label-sm">Rescheduled on ? :</label>
                                            <div class="col-sm-8 ">
                                            <input type="date" id="rescheduleDate" name="rescheduleDate" class="form-control form-control-sm" value="{{$task_gen_data->reschedule_date}}">
                                            </div>
                                        </div>
                                        <div class="row mb-3" id="reschTimeDiv" style="{{$display}}">
                                            <label for="rescheduleTime"  class="col-sm-4 col-form-label col-form-label-sm">Time :</label>
                                            <div class="col-sm-8">
                                            <input type="time" id="rescheduleTime" name="rescheduleTime" class="form-control form-control-sm" value="{{$task_gen_data->reschedule_time}}">
                                            </div>
                                        </div>
                                <input type="hidden" name="task_id" id="task_id" value="{{$task_id}}">          
                                <input type="hidden" name="lead_id" id="lead_id" value="{{$lead_id}}">          
                                <input type="hidden" name="task_gen_id" id="task_gen_id" value="{{$task_gen_data->id}}">          

    <div class="col-12 text-center">
        <button type="submit" id="clm_submit" class="btn btn-primary">Submit</button>
    </div>
    </form>
      </div>

<script>
function remarksReq(e,id)
{
    if(e == 'Yes'){
        const inputField = document.getElementById(id); 
        inputField.required = true; 
    }else{
        const inputField = document.getElementById(id); 
        inputField.required = false; 
    }
}
</script>
 <script>
    function toolsCondition(fieldId,gen_task_id){
        gradeIds = $('#id39').val();
        notReqIds = $('#id40').val();
        if(fieldId == '40'){
            $.ajax({
                url: "{{ route('gradeTools') }}", 
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    fieldId:'40',
                    grade_id:gradeIds,
                    notReqIds:notReqIds,
                    gen_task_id:gen_task_id,
                },
                success: function (response) {
                    $('#id41').multiselect('destroy');
                    $('#id41').empty();
                    $.each(response, function(key, value){
                            $('#id41').append('<option value="' + value.id + '">' + value.tool + '</option>');
                    });
                    $('#id41').multiselect();
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }else if(fieldId == '41'){
            coveredIds = $('#id41').val();
            var nextTraining = $('#id44').val();
            $.ajax({
                url: "{{ route('gradeTools') }}", 
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    fieldId:'41',
                    grade_id:gradeIds,
                    notReqIds:notReqIds,
                    coveredIds:coveredIds,
                    gen_task_id:gen_task_id,
                },
                success: function (response) {
                    if(nextTraining == 'Yes'){
                        $('#id45').multiselect('destroy');
                        $('#id45').empty();
                        $.each(response, function(key, value){
                            $('#id45').append('<option value="' + value.id + '">' + value.tool + '</option>');
                        });
                        $('#id45').multiselect();
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    }

function gradeListing(gen_task_id)
    {
        var nextTraining = $('#id44').val();
        var ids = $('#id39').val();
        if(ids != ''){
        $.ajax({
                url: "{{ route('gradeTools') }}", 
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    grade_id:ids,
                    fieldId:'1',
                    gen_task_id:gen_task_id,
                },
                success: function (response) {
                    $('#id40').multiselect('destroy');
                    $('#id41').multiselect('destroy');
                    $('#id40').empty();
                    $('#id41').empty();
                    $.each(response, function(key, value){
                        $('#id40').append('<option value="' + value.id + '">' + value.tool + '</option>');
                    });
                    $('#id40').multiselect();
                    $.each(response, function(key, value){
                            $('#id41').append('<option value="' + value.id + '">' + value.tool + '</option>');
                    });
                    $('#id41').multiselect();
                    if(nextTraining == 'Yes'){
                        $('#id45').multiselect('destroy');
                        $('#id45').empty();
                        $.each(response, function(key, value){
                            $('#id45').append('<option value="' + value.id + '">' + value.tool + '</option>');
                        });
                        $('#id45').multiselect();
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }else{
            $('#id40').multiselect('destroy');
            $('#id41').multiselect('destroy');
            $('#id45').multiselect('destroy');
            $('#id40').empty();
            $('#id41').empty();
            $('#id45').empty();
            $('#id40').multiselect();
            $('#id41').multiselect();
            $('#id45').multiselect();
        }
    }

    function gradeListingToBe(e,gen_task_id)
    {
        var div = document.getElementById("toolsToCover");
        if(e == 'Yes'){
            id45 = document.getElementById('id45'); 
            id93 = document.getElementById('id93'); 
            id94 = document.getElementById('id94'); 
            id95 = document.getElementById('id95'); 
            id45.required = true; 
            id93.required = true; 
            id94.required = true; 
            id95.required = true; 
            
            div.style.display = "flex";
            gradeIds = $('#id39').val();
            notReqIds = $('#id40').val();
            coveredIds = $('#id41').val();
            $.ajax({
                url: "{{ route('gradeTools') }}", 
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    fieldId:'41',
                    grade_id:gradeIds,
                    notReqIds:notReqIds,
                    coveredIds:coveredIds,
                    gen_task_id:gen_task_id,
                },
                success: function (response) {
                        $('#id45').multiselect('destroy');
                        $('#id45').empty();
                        $.each(response, function(key, value){
                            $('#id45').append('<option value="' + value.id + '">' + value.tool + '</option>');
                        });
                        $('#id45').multiselect();
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }else{
            id45 = document.getElementById('id45'); 
            id93 = document.getElementById('id93'); 
            id94 = document.getElementById('id94'); 
            id95 = document.getElementById('id95'); 
            id45.required = false; 
            id93.required = false; 
            id94.required = false; 
            id95.required = false; 
            div.style.display = "none";
        }
    }
    
    function statusChange(e,task_id){
        var div1 = document.getElementById("reschDateDiv");
        var div2 = document.getElementById("reschTimeDiv");
        var div3 = document.getElementById("completedCondDiv");
        if(e == 'Re-scheduled'){
            div3.style.display = "none";
            if(task_id != 1 && task_id != 2){
                div1.style.display = "flex";
                div2.style.display = "flex";
            }else{
                div2.style.display = "none";
                div1.style.display = "none";
            }
            if(task_id == 2){
                var programIniDiv = document.getElementById("reschudleShowdiv");
                programIniDiv.style.display = "flex";
            }
        }else{
            div2.style.display = "none";
            div1.style.display = "none";
            div3.style.display = "block";
        }
        if(task_id == 14 || task_id == 11)
        {
            if(e == 'Completed'){
                div3.style.display = "block";
            }else{
                div3.style.display = "none";
            }
        }
    }

    window.onload = onloadCheck();
    function onloadCheck(){
        var e = document.getElementById("status").value;
        var task_id = document.getElementById("task_id").value;
        const form = document.getElementById('clmModelForm');
        const inputs = form.querySelectorAll('input, select, textarea');
        if(e == 'Completed'){
            inputs.forEach(input => {
                input.disabled = true;
                document.getElementById("clm_submit").disabled = true;
            });
        }
    }
   
 </script>

    <script type="text/javascript">
    $(document).ready(function() {
        $('#id39').multiselect();
       
    });
    $(document).ready(function() {
        $('#id40').multiselect();
    });
    $(document).ready(function() {
        $('#id41').multiselect();
    });
    $(document).ready(function() {
        $('#id45').multiselect();
    });
</script>