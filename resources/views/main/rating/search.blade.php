@include('partials.mainsite_pages.return_function')
<?php 
    function getRole($id)
    {
        $query = \App\User::find($id);
        if(!empty($query))
        {
            return get_role($query->role, 'name');
        }
        return '';
    }
?>
<div id="table_data">
    <div class="table-responsive">
        <table class="table table-bordered table-sm" style="width:100%" id="" role="grid">
            <thead>
                <tr>
                    <th style="width:5%;">Order Id#</th>
                    <th style="width:25%;">Review</th>
                    <th style="width:25%;">Reply</th>
                    <th style="width:25%;">Admin Comments</th>
                    <th style="width:10%;">Review Date</th>
                    <th style="width:10%;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rating as $key => $value)
                    <tr>
                        <td style="width:5%;">
                            <a href="{{url('/searchData')}}?search={{$value->order_id}}" target="_blank">{{$value->order_id}}</a> <br>
                            <p><?php echo get_pstatus2($value->pstatus) ?></p>
                        </td>
                        <td style="width:25%;">
                            <b>Name: </b> <span class="text-capitalize">{{get_user_name($value->rater_id)}} ({{getRole($value->rater_id)}})</span><br>
                            <b>Rating: </b> 
                            @if($value->rating == 3)
                            <span class="badge badge-success">
                                <i class="fa fa-smile-o mr-1" aria-hidden="true"></i> Positive
                            </span>
                            @elseif($value->rating == 2)
                            <span class="badge badge-primary">
                                <i class="fa fa-meh-o mr-1" aria-hidden="true"></i> Neutral
                            </span>
                            @else
                            <span class="badge badge-danger">
                                <i class="fa fa-frown-o mr-1" aria-hidden="true"></i> Negative
                            </span>
                            @endif <br>
                            <b>Subject: </b> {{$value->subject}} <br>
                            <b>Review: </b> {{$value->review}}
                        </td>
                        <td style="width:25%;">
                            <b>Name: </b> <span class="text-capitalize">{{get_user_name($value->replyer_id)}} ({{getRole($value->replyer_id)}})</span><br>
                            <b>Reply: </b> {{$value->reply}}
                        </td>
                        <td style="width:25%;">
                            <b>Mistaker: </b> <span class="text-capitalize">@if($value->mistake_user_id > 0 || !empty($value->mistake_user_id)) {{get_user_name($value->mistake_user_id)}} ({{getRole($value->mistake_user_id)}}) @else N/A @endif </span><br>
                            <b>Comments: </b> {{$value->comments ?? 'N/A'}}
                        </td>
                        <td style="width:10%;">
                            {{\Carbon\Carbon::parse($value->created_at)->format('M,d Y h:i A')}}
                        </td>
                        <td style="width:10%;">
                            @if(Auth::user()->userRole->name == 'Order Taker' || Auth::user()->userRole->name == 'CSR' || Auth::user()->userRole->name == 'Seller Agent' || Auth::user()->userRole->name == 'Manager')
                                <?php  
                                    $textRate = isset($value->id) ? (isset($value->reply) ? 'View Rating' : ($value->rater_id == Auth::user()->id ? 'View Rating' : 'Reply Dispatcher')) : 'Rate Dipatcher';
                                ?>
                            @elseif(Auth::user()->userRole->name == 'Dispatcher')
                                <?php  
                                    $textRate = isset($value->id) ? (isset($value->reply) ? 'View Rating' : ($value->rater_id == Auth::user()->id ? 'View Rating' : 'Reply OrderTaker')) : 'Rate OrderTaker';
                                ?>
                            @elseif(Auth::user()->userRole->name == 'Admin')
                                <?php 
                                    $textRate = 'View Rating';
                                ?>
                            @endif
                            <button type="button" title="{{$textRate}}!" data-toggle="modal" data-target="#ratingPopup" onclick="ratingDetail({{$value->order_id}})" 
                            class="btn btn-outline-info btn-sm w-100 position-relative">
                                {{$textRate}} <i class="fa fa-star" data-placement="bottom" title="{{$textRate}}!"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="d-flex justify-content-between">
    <div class="text-secondary my-auto">
        Showing {{ $rating->firstItem() ?? 0 }} to {{ $rating->lastItem() ?? 0 }} from total {{$rating->total()}} entries
    </div>
    <div>
        {{$rating->links()}}
    </div>
</div>