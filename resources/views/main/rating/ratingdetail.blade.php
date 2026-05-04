@include('partials.mainsite_pages.return_function')
<div class="modal-body pb-0">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center">OrderId#</th>
                <th class="text-center">Pickup</th>
                <th class="text-center">Delivery</th>
                <th class="text-center">Vehicle Name</th>
                <th class="text-center">Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td id="id" class="text-center">
                    <a href="{{url('/searchData')}}?search={{$order->id}}" target="_blank">{{$order->id}}</a>
                </td>
                <td id="pickup" class="text-center">
                    <a href="https://www.google.com/maps/dir/{{$order->originzip}},+USA/"
                       target="_blank" class="table1ancher">
                        <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                        <span>  {{$order->origincity . "-".$order->originstate ."-" .$order->originzip  }}</span>
                    </a>
                </td>
                <td id="delivery" class="text-center">
                    <a href="https://www.google.com/maps/dir/{{$order->destinationzip }},+USA/"
                       target="_blank" class="table1ancher">
                        <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                       <span> {{$order->destinationcity . "-".$order->destinationstate ."-" .$order->destinationzip  }}</span>
                    </a>
                </td>
                <td id="vehicle_name" class="text-center">{{$order->ymk}}</td>
                <td id="date" class="text-center">
                    Created At: {{\Carbon\Carbon::parse($order->created_at)->format('M, d Y h:i A')}} <br>
                    Updated At: {{\Carbon\Carbon::parse($order->updated_at)->format('M, d Y h:i A')}}
                </td>
            </tr>
        </tbody>
    </table>
</div>
@if(!isset($rating->rater_id))
    <div class="modal-body">
        <div class="form-group">
            <label class="form-label">Subject</label>
            <input type="text" id="subject" name="subject" class="form-control mb-2" placeholder="Subject..." />
        </div>
        <div class="form-group">
            <label class="form-label">Review</label>
            <textarea placeholder="Review..." class="form-control mb-2" name="review" id="reviewDetail"></textarea>
        </div>
        <div class="form-group">
            <div class="d-flex justify-content-center mb-2">
                <!--<div class="form-group mx-2 mb-0">-->
                <!--    <label class="form-label px-2 py-1 checkRate selected" style="cursor:pointer;" for="positive">-->
                <!--        <i class="fa fa-smile-o m-0" aria-hidden="true"></i>-->
                <!--        Positive-->
                <!--    </label>-->
                <!--    <input type="radio" name="rate1" id="positive" value="3" checked style="display:none;" />-->
                <!--</div>-->
                <div class="form-group mx-2 mb-0">
                    <label class="form-label px-2 py-1 checkRate" style="cursor:pointer;" for="neutral">
                        <i class="fa fa-meh-o m-0" aria-hidden="true"></i>
                        Neutral
                    </label>
                    <input type="radio" name="rate1" id="neutral" value="2" style="display:none;" />
                </div>
                <div class="form-group mx-2 mb-0">
                    <label class="form-label px-2 py-1 checkRate selected" style="cursor:pointer;" for="negative">
                        <i class="fa fa-frown-o m-0" aria-hidden="true"></i>
                        Negative
                    </label>
                    <input type="radio" name="rate1" id="negative" value="1" checked style="display:none;" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="submit3">Save</button>
    </div>
    <script>
        $('.checkRate').on('click',function(){
            $('.checkRate').removeClass('selected');
            $(this).addClass('selected');
            $("input[name='rate1']").attr('checked',false);
            $(this).siblings('input').attr('checked',true);
        })
        
        $("#submit3").on('click', function(e){
            e.preventDefault();
            var ord_id = $("#ord_id").val();
            var subject = $("#subject");
            var review = $("#reviewDetail");
            var rating = $("input[name='rate1']");
            subject.parent('.form-group').children('.alert').remove();
            review.parent('.form-group').children('.alert').remove();
            rating.parent('.form-group').parent('.d-flex').parent('.form-group').children('.alert').remove();
            
             $.ajax({
                 url:"{{ url('/ratingdetail/create') }}",
                 type:"POST",
                 data:{subject:subject.val(),review:review.val(),rating:$("input[name='rate1']:checked").val(),order_id:ord_id},
                 dataType:"json",
                 success:function(res)
                 {
                     if(res.status_code === 400)
                     {
                        if(res.error.review)
                        {
                            review.parent('.form-group').append(`
                                <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                  <strong>${res.error.review[0]}</strong>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div> 
                            `);
                        }
                        if(res.error.rating)
                        {
                            rating.parent('.form-group').parent('.d-flex').parent('.form-group').append(`
                                <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                  <strong>${res.error.rating[0]}</strong>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div> 
                            `);
                        }
                        if(res.error.subject)
                        {
                            subject.parent('.form-group').append(`
                                <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                  <strong>${res.error.subject[0]}</strong>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div> 
                            `);
                        }
                     }
                     else
                     {
                        location.reload(true);
                        $('#ratingPopup').modal('hide');
                        $(".modal-backdrop").eq(0).remove();
                     }
                 }
             });
        })
    </script>
@else
    <div class="p-3">
        <p class="text-center"><?php echo get_pstatus2($rating->pstatus) ?></p>
        <div class="row pb-3">
            <div class="@if(empty($rating->comments)) col-md-6 @else col-md-4 @endif">
                <div class="card mb-3" style="height:100%;">
                    <div class="card-header bg-light d-flex justify-content-between">
                        <div>
                            <b>Reviewer Name: </b><span class="text-capitalize ml-2">{{get_user_name($rating->rater_id)}}</span>
                        </div>
                        <a href="#" class="card-link">
                            @if($rating->rating == 3)
                            <span class="badge badge-success text-light">
                                <i class="fa fa-smile-o mr-1" aria-hidden="true"></i> Positive
                            </span>
                            @elseif($rating->rating == 2)
                            <span class="badge badge-primary text-light">
                                <i class="fa fa-meh-o mr-1" aria-hidden="true"></i> Neutral
                            </span>
                            @else
                            <span class="badge badge-danger text-light">
                                <i class="fa fa-frown-o mr-1" aria-hidden="true"></i> Negative
                            </span>
                            @endif 
                        </a>
                    </div>
                    <div class="card-body pt-3">
                        <h4 class="card-title mb-0 mt-2">Subject:</h4>
                        <p class="card-text">{{$rating->subject}}</p>
                        <h4 class="card-title m-0">Review:</h4>
                        <p class="card-text">{{$rating->review}}</p>
                    </div>
                </div>
            </div>
            <div class="@if(empty($rating->comments)) col-md-6 @else col-md-4 @endif">
                <div class="card mb-3" style="height:100%;">
                    <div class="card-header bg-light"><b>Replyer Name: </b> <span class="text-capitalize ml-2">{{get_user_name($rating->replyer_id)}}</span></div>
                    <div class="card-body pt-3">
                        @if(!empty($rating->reply))
                            <h4 class="card-title m-0">Reply:</h4>
                            <p class="card-text">{{$rating->reply}}</p>
                        @endif
                    </div>
                </div>
            </div>
            @if(!empty($rating->comments)) 
            <div class="col-md-4">
                <div class="card mb-3" style="height:100%;">
                    <div class="card-header bg-light"><b>Mistaker: </b> 
                        <span class="text-capitalize ml-2">
                            @if($rating->mistake_user_id > 0)
                                {{get_user_name($rating->mistake_user_id)}}
                            @else
                                No Mistaker
                            @endif
                        </span>
                    </div>
                    <div class="card-body pt-3">
                        <h4 class="card-title m-0">Admin Comments:</h4>
                        <p class="card-text">{{$rating->comments}}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @if(empty($rating->reply))
        @if(Auth::user()->id == $rating->replyer_id)
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Reply</label>
                    <textarea placeholder="Reply..." class="form-control mb-2" name="reply" id="replyDetail"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="submit3">Save</button>
            </div>
            <script>
                $("#submit3").on('click', function(e){
                    e.preventDefault();
                    var ord_id = $("#ord_id").val();
                    var reply = $("#replyDetail");
                    reply.parent('.form-group').children('.alert').remove();
                    
                     $.ajax({
                         url:"{{ url('/ratingdetail/create') }}",
                         type:"POST",
                         data:{reply:reply.val(),order_id:ord_id},
                         dataType:"json",
                         success:function(res)
                         {
                             if(res.status_code === 400)
                             {
                                if(res.error.reply)
                                {
                                    reply.parent('.form-group').append(`
                                        <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                          <strong>${res.error.reply[0]}</strong>
                                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div> 
                                    `);
                                }
                             }
                             else
                             {
                                location.reload(true);
                                $('#ratingPopup').modal('hide');
                                $(".modal-backdrop").eq(0).remove();
                             }
                         }
                     });
                })
            </script>
        @endif
    @else
        @if(empty($rating->comments))
            @if(Auth::user()->userRole->name == 'Admin')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Mistaker</label>
                        <select name="mistake_user_id" id="mistake_user_id" class="form-control mb-2">
                            <option value="0">No Mistaker</option>
                            <option value="{{$rating->rater_id}}">{{get_user_name($rating->rater_id)}}</option>
                            <option value="{{$rating->replyer_id}}">{{get_user_name($rating->replyer_id)}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Comments</label>
                        <textarea placeholder="Comments..." class="form-control mb-2" name="comments" id="comments"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submit3">Save</button>
                </div>
                <script>
                    $("#submit3").on('click', function(e){
                        e.preventDefault();
                        var ord_id = $("#ord_id").val();
                        var comments = $("#comments");
                        var mistake_user_id = $("#mistake_user_id");
                        comments.parent('.form-group').children('.alert').remove();
                        mistake_user_id.parent('.form-group').children('.alert').remove();
                        
                         $.ajax({
                             url:"{{ url('/ratingdetail/create') }}",
                             type:"POST",
                             data:{comments:comments.val(),mistake_user_id:mistake_user_id.val(),order_id:ord_id},
                             dataType:"json",
                             success:function(res)
                             {
                                 if(res.status_code === 400)
                                 {
                                    if(res.error.mistake_user_id)
                                    {
                                        mistake_user_id.parent('.form-group').append(`
                                            <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                              <strong>${res.error.mistake_user_id[0]}</strong>
                                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div> 
                                        `);
                                    }
                                    if(res.error.comments)
                                    {
                                        comments.parent('.form-group').append(`
                                            <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                              <strong>${res.error.comments[0]}</strong>
                                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div> 
                                        `);
                                    }
                                 }
                                 else
                                 {
                                    location.reload(true);
                                    $('#ratingPopup').modal('hide');
                                    $(".modal-backdrop").eq(0).remove();
                                 }
                             }
                         });
                    })
                </script>
            @endif
        @endif
    @endif
@endif