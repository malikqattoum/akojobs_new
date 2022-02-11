<thead>
    <tr>
        <th style="width:2%" data-type="numeric" data-sort-initial="true"></th>
        <th style="width:80%" data-sort-ignore="true">{{ t('Conversations') }}</th>
        <th style="width:18%">{{ t('Option') }}</th>
    </tr>
</thead>
<tbody>
    <?php
    if (isset($jobseekers) && $jobseekers->count() > 0):
        foreach($jobseekers as $key => $jobseeker):
    ?>
    <tr>
        <td class="add-img-selector">
            <div class="checkbox">
                <label><input type="checkbox" name="entries[]" value="{{ $jobseeker->id }}"></label>
            </div>
        </td>
        <td>
            <div style="word-break:break-all;">
                <div style="font-size:15px">
                    @if (!empty($jobseeker->photo))
                        <img class="people-photo" src="{{ (substr($jobseeker->photo, 0, 1) != '/')?'/'.$jobseeker->photo:$jobseeker->photo }}" alt="user">&nbsp;
                    @elseif (!empty(Session::get('image')))
                        <img class="people-photo" src="{{ Session::get('image') }}" alt="user">
                    @else
                        <img class="people-photo" src="{{ url('images/user.jpg') }}" alt="user">
                    @endif
                    <a href="{{ lurl('profile/employer-view/'.$jobseeker->id) }}" class="people-name"><strong>{{ \Illuminate\Support\Str::limit($jobseeker->name, 50) }}</strong></a>
                    <div class="people-data">
                        <strong>{{ t('registered at') }}:</strong>
                        {{ date('d M Y H:i',strtotime($jobseeker->created_at)) }}
                        @if(!empty($jobseeker->job_role))
                            <br><strong>{{ t('Job Role') }}:</strong>&nbsp;{{ t($jobseeker->job_role, [], 'roles') }}<br>
                        @endif
                        <p>
                            <button class="btn btn-primary mt-2" type="button" data-toggle="collapse" data-target="#collapseExample{{$jobseeker->id}}" aria-expanded="false" aria-controls="collapseExample">
                                {{t('view all candidate data')}}
                            </button>
                        </p>
                        <div class="collapse" id="collapseExample{{$jobseeker->id}}">
                            <div class="card card-body">
                                @if(!empty($jobseeker->email))
                                    <strong>{{ t('Email Address') }}:&nbsp;{{ $jobseeker->email }}<br>
                                @endif
                                @if(!empty($jobseeker->nationality))
                                    <strong>{{ t('Nationality') }}:</strong>&nbsp;{{ t($user->nationality, [], 'residenceCountry') }}<br>
                                @endif
                                @if(!empty($jobseeker->residence_country))
                                    <strong>{{ t('Resident Country') }}:</strong>&nbsp;{{ t($user->residence_country, [], 'residenceCountry') }}<br>
                                @endif
                                @if(!empty($jobseeker->gender))
                                    <strong>{{ t('Gender') }}:</strong>&nbsp;{{ $jobseeker->gender }}<br>
                                @endif
                                @if(!empty($jobseeker->phone))
                                    <strong>{{ t('Mobile Number') }}:</strong>&nbsp;{{ $jobseeker->phone }}<br>
                                @endif
                                @if(!empty($jobseeker->industry))
                                    <strong>{{ t('Industry') }}:</strong>&nbsp;{{ t($jobseeker->industry, [], 'industry') }}<br>
                                @endif
                                @if(!empty($jobseeker->user_experience))
                                    <strong>{{ t('Experience Years') }}:</strong>&nbsp;{{ t($jobseeker->user_experience, [], 'experience') }}<br>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </td>
        <td class="action-td">
            <div>
                <p>
                    <a class="btn btn-primary btn-sm" href="{{ lurl('profile/employer-view/'.$jobseeker->id) }}">
                        <i class="fa fa-user"></i> {{t('View user profile')}}
                    </a>
                </p>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
</tbody>