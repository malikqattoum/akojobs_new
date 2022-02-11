
            
<thead>
    <tr>
        <th style="width:2%" data-type="numeric" data-sort-initial="true"></th>
        <th style="width:60%" data-sort-ignore="true">{{ t('Conversations') }}</th>
        <th style="width:10%">{{ t('Option') }}</th>
        <th style="width:28%">{{t('Screening tools')}}</th>
    </tr>
</thead>
<tbody>
    <?php
    if (isset($people) && $people->count() > 0):
        foreach($people as $key => $applicant):
    ?>

    <?php 
    if(empty($applicant->message_id))
        $applicant->message_id = $applicant->id;
    ?>
    <tr>
        <td class="add-img-selector">
            <div class="checkbox">
                <label><input type="checkbox" name="entries[]" value="{{ $applicant->message_id }}"></label>
            </div>
        </td>
        <td>
            <div style="word-break:break-all;">
                <div style="font-size:15px">
                    @if (!empty($applicant->photo))
                        <img class="people-photo" src="{{ (substr($applicant->photo, 0, 1) != '/')?'/'.$applicant->photo:$applicant->photo }}" alt="user">&nbsp;
                    @elseif (!empty(Session::get('image')))
                        <img class="people-photo" src="{{ Session::get('image') }}" alt="user">
                    @else
                        <img class="people-photo" src="{{ url('images/user.jpg') }}" alt="user">
                    @endif
                    <a href="{{ lurl('profile/employer-view/'.$applicant->from_user_id) }}" class="people-name"><strong>{{ \Illuminate\Support\Str::limit($applicant->from_name, 50) }}</strong></a>
                    <form id="addCandidateRatingForm{{ $applicant->from_user_id }}">
                        <div class="rating-div">
                            @if (isset($userRatings[$applicant->from_user_id]) && !empty($userRatings[$applicant->from_user_id]))
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $userRatings[$applicant->from_user_id])
                                        <input type="hidden" id="rating_{{ $applicant->from_user_id }}_{{$i}}_hidden" value="{{$i}}">
                                        <img src="{{ url('images/site/star2.png')}}" onmouseover="changeRating(this.id,{{ $applicant->from_user_id }});" id="rating_{{ $applicant->from_user_id }}_{{$i}}" class="rating-star">
                                    @else
                                        <input type="hidden" id="rating_{{ $applicant->from_user_id }}_{{$i}}_hidden" value="{{$i}}">
                                        <img src="{{ url('images/site/star1.png')}}" onmouseover="changeRating(this.id,{{ $applicant->from_user_id }});" id="rating_{{ $applicant->from_user_id }}_{{$i}}" class="rating-star">
                                    @endif
                                @endfor
                            @else
                                <input type="hidden" id="rating_{{ $applicant->from_user_id }}_1_hidden" value="1">
                                <img src="{{ url('images/site/star1.png')}}" onmouseover="changeRating(this.id,{{ $applicant->from_user_id }});" id="rating_{{ $applicant->from_user_id }}_1" class="rating-star">
                                <input type="hidden" id="rating_{{ $applicant->from_user_id }}_2_hidden" value="2">
                                <img src="{{ url('images/site/star1.png')}}" onmouseover="changeRating(this.id,{{ $applicant->from_user_id }});" id="rating_{{ $applicant->from_user_id }}_2" class="rating-star">
                                <input type="hidden" id="rating_{{ $applicant->from_user_id }}_3_hidden" value="3">
                                <img src="{{ url('images/site/star1.png')}}" onmouseover="changeRating(this.id,{{ $applicant->from_user_id }});" id="rating_{{ $applicant->from_user_id }}_3" class="rating-star">
                                <input type="hidden" id="rating_{{ $applicant->from_user_id }}_4_hidden" value="4">
                                <img src="{{ url('images/site/star1.png')}}" onmouseover="changeRating(this.id,{{ $applicant->from_user_id }});" id="rating_{{ $applicant->from_user_id }}_4" class="rating-star">
                                <input type="hidden" id="rating_{{ $applicant->from_user_id }}_5_hidden" value="5">
                                <img src="{{ url('images/site/star1.png')}}" onmouseover="changeRating(this.id,{{ $applicant->from_user_id }});" id="rating_{{ $applicant->from_user_id }}_5" class="rating-star">
                            @endif
                            <input type="submit" class="btn btn-warning btn-sm rating-button" value="Save rating" name="submit_rating">
                        </div>
                        <input type="hidden" name="rating_user" value="{{ $applicant->from_user_id }}">
                        <input type="hidden" name="{{ $applicant->from_user_id }}rating" id="{{ $applicant->from_user_id }}rating" value="0">
                    </form>
                </div>
                <div class="people-data">
                    <strong>{{ t('Received at') }}:</strong>
                    {{ date('d M Y H:i',strtotime($applicant->created_at)) }}
                    @if (\App\Models\Message::conversationHasNewMessages($applicant))
                        <i class="icon-flag text-primary"></i>
                    @endif
                    <br>
                    @if (!empty($applicant->subject))
                        <strong>{{ t('Subject') }}:</strong>&nbsp;{{ $applicant->subject }}
                    @endif
                    @if(!empty($applicant->job_role))
                        <br><strong>{{ t('Job Role') }}:</strong>&nbsp;{{ t($applicant->job_role, [], 'roles') }}<br>
                    @endif
					<p>
						<a class="text-primary" data-toggle="collapse" data-target="#collapseExample{{$applicant->message_id}}" aria-expanded="false" aria-controls="collapseExample">
							{{t('view all candidate data')}}
							<i class="fa fa-angle-down"></i>
						</a>
					</p>
                    <div class="collapse" id="collapseExample{{$applicant->message_id}}">
                        <div class="card card-body">
                            @if(!empty($applicant->email))
                                <strong>{{ t('Email Address') }}:&nbsp;{{ $applicant->email }}<br>
                            @endif
                            @if(!empty($applicant->nationality))
                                <strong>{{ t('Nationality') }}:</strong>&nbsp;{{ t($user->nationality, [], 'residenceCountry') }}<br>
                            @endif
                            @if(!empty($applicant->residence_country))
                                <strong>{{ t('Resident Country') }}:</strong>&nbsp;{{ t($user->residence_country, [], 'residenceCountry') }}<br>
                            @endif
                            @if(!empty($applicant->gender))
                                <strong>{{ t('Gender') }}:</strong>&nbsp;{{ $applicant->gender }}<br>
                            @endif
                            @if(!empty($applicant->phone))
                                <strong>{{ t('Mobile Number') }}:</strong>&nbsp;{{ $applicant->phone }}<br>
                            @endif
                            @if(!empty($applicant->industry))
                                <strong>{{ t('Industry') }}:</strong>&nbsp;{{ t($applicant->industry, [], 'industry') }}<br>
                            @endif
                            @if(!empty($applicant->user_experience))
                                <strong>{{ t('Experience Years') }}:</strong>&nbsp;{{ t($applicant->user_experience, [], 'experience') }}<br>
                            @endif
                        </div>
                    </div>
                    <p class="mt-2 mb-1">
                        {!! (!empty($applicant->filename) and \Storage::exists($applicant->filename)) ? ' <i class="icon-attach-2"></i> ' : '' !!}&nbsp;|&nbsp;
                        <a href="{{ lurl('account/conversations/' . $applicant->message_id . '/messages') }}">
                            {{ t('Click here to read the messages') }}
                        </a>
                    </p>
                </div>
            </div>
        </td>
        <td class="action-td">
            <div>
                <p>
                    <a class="btn btn-default btn-sm" href="{{ lurl('account/conversations/' . $applicant->message_id . '/messages') }}">
                        <i class="icon-eye"></i> {{ t('View') }}
                    </a>
                </p>
                <p>
                    <a class="btn btn-danger btn-sm delete-action" href="{{ lurl('account/conversations/' . $applicant->message_id . '/delete') }}">
                        <i class="fa fa-trash"></i> {{ t('Delete') }}
                    </a>
                </p>
                <p>
                    <a class="btn btn-primary btn-sm" href="{{ lurl('profile/employer-view/'.$applicant->from_user_id) }}">
                        <i class="fa fa-user"></i> {{t('View user profile')}}
                    </a>
                </p>
				<p>
					@if (!empty($applicant->cvText))
						<button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#cvTextModal{{$applicant->message_id}}">
							{{t('View original CV')}}
						</button>

						<!-- Modal -->
						<div class="modal fade" id="cvTextModal{{$applicant->message_id}}" tabindex="-1" role="dialog" aria-labelledby="cvTextLabel{{$applicant->message_id}}" aria-hidden="true">
							<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
								<h5 class="modal-title" id="cvTextLabel{{$applicant->message_id}}">Original CV</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								</div>
								<div class="modal-body">
									{!! nl2br($applicant->cvText) !!}
								</div>
								<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">{{t('Close')}}</button>														</div>
							</div>
							</div>
						</div>
					@endif
				</p>
				<p>
					@if (!empty($applicant->filename) and \Storage::exists($applicant->filename))
						<a class="btn btn-info btn-sm" target="_blank" href="{{ \Storage::url($applicant->filename) }}">{{ t('Download') }}</a>
					@endif
				</p>
            </div>
        </td>
        <td>
            <!-- add note modal button -->
            @if(!empty($applicant->applicant_note))
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewNoteModal{{$applicant->message_id}}">
                    {{t('View applicant note')}}
                </button>
                
                <!-- Modal -->
                <div class="modal fade" id="viewNoteModal{{$applicant->message_id}}" tabindex="-1" role="dialog" aria-labelledby="viewNoteLabel{{$applicant->message_id}}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="viewNoteLabel{{$applicant->message_id}}">applicant note</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                {{ $applicant->applicant_note }}
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary my-2 btn-sm" data-toggle="modal" data-target="#addNoteModal{{$applicant->message_id}}">
                    {{t('Edit Note')}}
                </button>
            @else
                <button type="button" class="btn btn-primary mb-2 btn-sm" data-toggle="modal" data-target="#addNoteModal{{$applicant->message_id}}">
                    {{t('Add Note')}}
                </button>
            @endif
			<form method="POST" id="addNoteForm{{$applicant->message_id}}" action="{{ lurl('account/conversations/add-note') }}">
				{!! csrf_field() !!}
				<!-- Modal -->
				<div class="modal fade" id="addNoteModal{{$applicant->message_id}}" tabindex="-1" role="dialog" aria-labelledby="addNoteModal{{$applicant->message_id}}" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
							<h5 class="modal-title" id="addNoteLabel{{$applicant->message_id}}">{{ $applicant->applicant_note?'Edit Note':'Add Note' }}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label for="note">Add applicant note:</label>
									<input type="hidden" name="message_id" value="{{ $applicant->message_id }}">
									<textarea class="form-control" name="applicant_note" cols="30" rows="10" required>{{ (!empty($applicant->applicant_note))?$applicant->applicant_note:null }}</textarea>
								</div>
							</div>
							<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Save changes</button>
							</div>
						</div>
					</div>
				</div>
			</form>
            <br>
            <?php $applicantsStages = include(base_path() . '/resources/lang/en/applicantsStages.php');
                //asort($applicantsStages); 
                $applicantsStagesKeys = array_keys($applicantsStages);
            ?>
            <div class="form-group col-xs-12" id="applicantsStages{{ $applicant->message_id }}">
                <select class="form-control" onchange="applicantStage({{ $applicant->message_id }})" name="applicant_stage" id="applicant_stage_{{ $applicant->message_id }}" placeholder="{{t('Applicant stages')}}">
                    <option value="" selected="" disabled>{{t('Applicant stages')}}</option>
                    @foreach ($applicantsStagesKeys as $item)
                        <option value="{{$item}}" {{ (!empty($applicant->applicant_stage))?(($applicant->applicant_stage == $item)? "selected":""):'' }} >{{t($item,[],'applicantsStages')}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xs-12" id="assignToJob{{ $applicant->message_id }}">
                <select class="form-control" onchange="assignToJob({{ $applicant->message_id }})" name="assign_to_job" id="assign_to_job_{{ $applicant->message_id }}" placeholder="{{t('Assign to job')}}">
                    <option value="" selected="">{{ t('Assign to job') }}</option>
                    <?php $assignAddedJobs = []; ?>
                    @foreach ($posts as $job)
                        @if (!in_array($job->id,$assignAddedJobs))
                            <option value="{{$job->id}}" {{ (!empty($applicant->post_id))?(($applicant->post_id == $job->id)? "selected":""):'' }}>{{$job->title}}</option>
                        @endif
                        <?php 
                            $assignAddedJobs[] = $job->id;
                        ?>
                    @endforeach
                </select>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
</tbody>

<script>

$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
});
			
$("*[id*=addCandidateRatingForm]:visible").submit(function(e) {
	e.preventDefault();
	var formData = $(this).serializeArray().reduce(function(obj, item) {
		obj[item.name] = item.value;
		return obj;
	}, {});

	$.ajax({
		type:'POST',
		url: "{{ url('add-candidate-rating')}}",
		data: formData,
		dataType: "json",
		encode: true,
		success: (data) => {
			if(data.success !== undefined && data.success == 1)
			{
				if($("#addCandidateRatingMessage").length){
					$("#addCandidateRatingMessage").remove();
				}
				$(this).append("<span class=\"alert alert-success\" id=\"addCandidateRatingMessage\" role=\"alert\">\
						"+data.message+"</span>");
				
				setTimeout(function(){
					$("#addCandidateRatingMessage").remove();
				}, 3000);
			}
		},
		error: function(data){
			if($("#addCandidateRatingMessage").length){
					$("#addCandidateRatingMessage").remove();
			}
			$(this).append("<span class=\"alert alert-danger\" id=\"addCandidateRatingMessage\" role=\"alert\">\
					Error Occured</span>");

			setTimeout(function(){
				$("#addCandidateRatingMessage").remove();
			}, 3000);
		}
	});
	return false;
});

</script>