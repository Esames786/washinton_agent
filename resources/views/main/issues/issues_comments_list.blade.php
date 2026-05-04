@extends('layouts.innerpages')

@section('template_title')
    Register
@endsection

@section('content')
    <!-- Row -->
    @include('partials.mainsite_pages.return_function')
    <div class="row">
        <div class="col-12">
            @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
        @endif
        <!--div-->
            <div class="page-header">
                <div class="text-secondary text-center text-uppercase w-100">
                    <h1 class="my-4"><b>Issues List for User Comments</b></h1>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><a type="button" href="{{url('issues_add')}}"
                                               class="btn btn-icon btn-primary"><i class="fe fe-plus"></i>Add Issue</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered text-nowrap key-buttons">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">ISSUE ID</th>
                                    <th class="border-bottom-0">CREATED DATE</th>
                                    <th class="border-bottom-0">SUBJECT</th>
                                    <th class="border-bottom-0">ISSUE WITH</th>
                                    <th class="border-bottom-0">ISSUE DETAIL</th>
                                    <th class="border-bottom-0">STATUS</th>
                                    <th class="border-bottom-0">ACTION</th>

                                </tr>
                                </thead>
                                <tbody>
                                @if (Auth::user()->role==1)
                                    @foreach($issues as $issue)
                                        <tr>
                                            <td>{{$issue->id}}</td>
                                            <td>{{\Carbon\Carbon::parse($issue->created_at)->format('M,d Y h:i A')}}</td>
                                            <td>{{$issue->subject}}</td>
                                            @php
                                                $issuewithid=explode(',',$issue->issuewithuserId);
                                            @endphp

                                            <td>
                                                @foreach($issuewithid as $issueuser)
                                                    @foreach($users as $user)
                                                        @if($user->id==$issueuser)
                                                            {{ $user->name }} ,
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </td>
                                            <td>{{$issue->detail}}</td>
                                            <td>{{$issue->status}}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-dark dropdown-toggle"
                                                            data-toggle="dropdown">
                                                        <i class="fe fe-arrow-down mr-2"></i>Comments
                                                    </button>
                                                    @forelse($issuescomments as $issuescomment)

                                                        @if ($issuescomment->issueId<>$issue->id)

                                                            @foreach($notifications as $notification)
                                                                @if ($notification->issueId==$issue->id && $notification->status==0)
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item"
                                                                           href="{{url('issue_comments_add'.'/'.$issue->id)}}">Add
                                                                            Comments</a>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @empty
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item"
                                                               href="{{url('issue_comments_add'.'/'.$issue->id)}}">Add
                                                                Comments</a>
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/div-->

        </div>
    </div>
    <!-- /Row -->



@endsection

@section('extraScript')


@endsection

