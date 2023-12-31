
@extends('Result.masters.app')

@section('required-styles')

{!! Html::style('result/css/fitness-core/jquery-ui-1.10.1.custom.css') !!}
{!! Html::style('result/css/fitness-core/jquery.ui.labeledslider.css') !!}
{!! Html::style('result/css/fitness-core/datetimepicker.min.css') !!}
<!--{!! Html::style('css/fitness-core/jquery.ui.1.10.1.ie.css') !!} -->
{!! Html::style('result/css/fitness-mapper/FitMapper.css') !!}
<style type="text/css">

    .tabb > li.active a, .tabb > li.active a:hover, .tabb > li.active a:focus{
        border-width: 1px;

    }
    .my-route{
        width: 100%;
    }
    .map-btn{
      position: absolute;
      top: 77px;
      color: black;
    }
    .map-btn .active{
        color: gray;
    }
    .map-btn .mapp{
    border-right: 1px solid #dbdbdb;
    border-radius: 4px 0px 0px 4px;
    font-weight: 600;
    }
    .map-btn .satellite{
    border-radius: 0px 4px 4px 0px;
    font-weight: 600;
}
    .reset{
        background: none;
        border: 0;
        color: #f64c1e;
        font-weight: 600;
        margin-top: 5px;
        margin-left:5px;
    }
    select{
        -webkit-appearance: auto;
        -moz-appearance: auto;
        appearance: auto;
    }
    .dataTables_paginate{
        float: left;
    }
    .d-color{
        color:#f64c1e; 
    }
    /* #data-table td{
        font-weight: 600;
    } */
    .data-table td{
        font-weight: 600;
    }
    .m-t-10{
        margin-top: 10px;
    }
    .action-btn{
        background: #f64c1e;
        color: white !important;
        padding: 4px 6px;
        border-radius: 4px;
        margin:3px;
        display: inline-block;
    }
    .div-tooltip {
        display: none;
        position: absolute;
        background-color: #000;
        padding: 10px;
        color: #fff;
        border-radius:3px;
    }
    @media(min-width: 1500px){
  #fitMap{
    max-height: 800px !important
  }
}
@media(max-width: 1499px){
  #fitMap{
    max-height: 500px !important
  }
}

.challenge-status-table.d-flex{
  display: flex;
}
.challenge-status-table.d-flex div{
  flex-shrink: 0;
  flex-grow: 1;
  padding: 7px 5px;
  border:1px solid #e0e0e0;
  border-top:0px;
  width: 33.3333%;
}
.challenge-status-table.d-flex:first-child div{
  border-top:1px solid #e0e0e0;
}
.challenge-status-table.d-flex div:first-child{
    border-right: 0px;
}
.challenge-status-table.d-flex div:last-child{
    border-left: 0px;
}
.fit-ui #ui-datepicker-div{
  z-index: 200022 !important
}

</style>
@stop

@section('page-title')
<span>Fitness mapper</span>
@stop

@section('content')
<style>

</style>
<div id="alertMsg">
    {!! displayAlert()!!}

</div>
<h2 class="clearfix"><span></span> 
</h2>
@php
    if(!empty($search) || !empty($page)){
        $my_route = 'active';
        $create_route = '';
        $my_route_div = 'in active';
        $create_route_div = '';
    }else{
        $my_route = '';
        $create_route = 'active';
        $my_route_div = '';
        $create_route_div = 'in active';
    }
@endphp

@php
    $page_name = session()->get( 'page_name' );
    $toaster_message = session()->get( 'message' );
    if($page_name == 'my_challenge'){
        $myChallenges = 'active';
        $create_route = '';
        $myChallenges_div = 'in active';
        $create_route_div = '';
    }else{
        $myChallenges = '';
        $create_route = 'active';
        $myChallenges_div = '';
        $create_route_div = 'in active';
    }
@endphp
    <a href="{{url('search/routes')}}" class="btn btn-primary filter">Search Route</a>
    <a href="{{ url('epic/train-gain/fitness-mapper') }}" class="btn btn-primary back_create" style="float:right">Create Route</a>
    <hr>
 
<ul class="nav nav-tabs tabb">
  <li class="{{ $create_route }} createRoute"><a data-toggle="tab" href="#create-route">Create route</a></li>
  <li class="routes {{ $my_route }}"><a data-toggle="tab" href="#my-route">My route</a></li>
  <li class="{{$myChallenges}} #"><a data-toggle="tab" href="#my-challenges">My Challenges</a></li>
</ul>
<input type="hidden" id="challenge_status" value="{{$page_name?$page_name:''}}">
<input type="hidden" id="toaster_message" value="{{$toaster_message?$toaster_message:''}}">
<div class="tab-content">   
    <div id="create-route" class="tab-pane fade {{ $create_route_div }}">
       <div id="fitMap" class="fitMap">
          <div id="fitMapMainContainer" class="fitMapMain">
             <div id="fitMapContentWrapper" class="contentWrapper">
                <div id="fitMapContentColumn" class="contentColumn">
                   <div class="fitMapInner">
                      <div id="fitMapCanvas" class="fitCanvas"></div>
                   </div>
                </div>
             </div>
             <div class="main-box">
                <div class="col-md-4 col-xs-12 col-sm-4">
                   <input type="text" id="fitSearchAddress" class="searchInput" size="15" placeholder="Enter a location"/>
                   <span id="fitMapBtnSearch" class="fa fa-search"></span>
                </div>
                <div class="col-md-4 col-xs-12 col-sm-4">
                   <div class="undo-div">
                      <ul class="list-group">
                         <li>
                            <a id="fitMapBtnUndo" class="fitMapBtn" href="#"><i class="fa fa-reply" aria-hidden="true"></i></a>
                            <span class="fitMapBtnText">undo</span>
                         </li>
                         <li>
                            <a id="fitMapBtnPlotBack" class="fitMapBtn" href="#"><i class="fa fa-share" aria-hidden="true"></i></a>
                            <span class="fitMapBtnText">redo</span>
                         </li>
                         <li><a id="fitMapBtnClear" class="fitMapBtn" href="#"><i class="fa fa-times" aria-hidden="true"></i></a><span class="fitMapBtnText">clear</span></li>
                         <li><a id="fitMapBtnOutBack" class="fitMapBtn" href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a><span class="fitMapBtnText">Out</span></li>
                         <li><a id="fitMapBtnSave" class="fitMapBtn" href="#"><i class="fa fa-check" aria-hidden="true"></i></a><span class="fitMapBtnText">Save</span></li>
                      </ul>
                   </div>
                </div>
                <!--div class="col-md-2 col-xs-12">
                   <div class="undo-div">
                       <ul class="list-group">
                           <li><a href="#"><i class="fa fa-undo"></i></a><span>Run</span></li>
                           <li><a href="#"><i class="fa fa-undo"></i></a><span>Cycle</span></li>
                       </ul>
                   </div>
                   </div-->
                <div class="col-md-2 col-xs-6 col-sm-2">
                   <div class="headerGradient">Workout <span class="caret"></span></div>
                   <div class="selectedWorkout" style="display: none">
                      <div>
                         <span>Workout</span>
                         <select id="selectedWorkout">
                            <option value="1">Running</option>
                            <option value="3">Walking</option>
                            <option value="8">Cycling</option>
                            <option value="4">Mountain Biking</option>
                            <option value="2">Swimming</option>
                            <option value="5">Kayaking</option>
                            <option value="6">Rowing</option>
                            <option value="7">Orienteering</option>
                            <option value="9">Other</option>
                         </select>
                      </div>
                      <div id = "selectedExerciseDiv">
                         <span>Exercise</span>
                         <select id="selectedExercise">
                            <option value="1">Easy Run</option>
                            <option value="3">Long Run</option>
                            <option value="8">Hill</option>
                            <option value="4">Speed</option>
                            <option value="2">Tempo Run</option>
                            <option value="5">Fartlek</option>
                         </select>
                      </div>
                      <div>
                         <span>Duration</span>
                         <input id="selectedDuration" type="text" data-wmt="Enter Duration" />
                      </div>
                   </div>
                </div>
                <div class="col-md-2 col-xs-6 col-sm-2">
                   <div id="fitMapContentWrapper" class="contentWrapper">
                      <div id="fitButtonPanel" class="buttonPanel">
                         <div class="buttonItem" id="ank">
                            <a id="toggleControl">
                            <img src="{{ asset('result/css/fitness-mapper/css/images/map/eye-1.png') }}" alt="Toggle Markers" />
                            </a>
                         </div>
                         <div class="buttonItem">
                            <a id="toggleUnit" href="#">
                            <img src="{{ asset('result/css/fitness-mapper/css/images/map/drawmode.png') }}" alt="Toggle Distance" /></a>
                         </div>
                         <div class="buttonItem" href="" id="both">
                            <a id="toggleRoad">
                            <img src="{{ asset('result/css/fitness-mapper/css/images/map/road.png') }}" alt="Toggle Road"/> 
                            </a>
                         </div>
                         <div class="buttonItem last">
                            <a id="toggleScreen">
                            <img src="{{ asset('result/css/fitness-mapper/css/images/map/max.png') }}" alt="Toggle Screen" /></a>
                         </div>
                      </div>
                      <div id="fitMapContentColumn" class="contentColumn">
                         <div class="fitMapInner">
                            <div id="fitMapCanvas" class="fitCanvas"></div>
                         </div>
                      </div>
                   </div>
                </div>
             </div>
             <div class="col-md-12 map-btn">
                <button class="btn mapp active changeMap" data-map="roadmap">Map</button><button class="btn satellite changeMap" data-map="satellite">Satellite</button>
             </div>
             <div id="fitControlPanel" class="">
                <!--controlPanel-->
                <div class="fitControlPanelMapInner">
                   <div class="minimizeDiv"></div>
                   <div id="accordion" class="accordian">
                      <!--div class="headerGradient">Routes</div-->
                      <!--div class="searchBar">
                         <input type="text" id="fitSearchAddress" class="searchInput" size="15" placeholder="Enter a location"/>
                         <span id="fitMapBtnSearch"></span>
                         <p id="fitSearchText" class="searchTextSpace">Enter address or zip postal code</p>
                         </div-->
                      <!--div id="fitMapCommands" class="mapCommand">
                         <a id="fitMapBtnUndo" class="fitMapBtn" href="#"></a>
                         <a id="fitMapBtnClear" class="fitMapBtn" href="#"><span class="fitMapBtnText"></a>
                         <a id="fitMapBtnPlotBack" class="fitMapBtn" href="#"></a>
                         <a id="fitMapBtnOutBack" class="fitMapBtn" href="#"></a>
                         <a id="fitMapBtnSave" class="fitMapBtn" href="#"></a>       
                         </div-->
                      <div class="displayPanel workoutGradient">
                         <div class="ab1">
                            <div class="distanceDiv">
                               <div><img src="{{ asset('result/css/fitness-mapper/css/images/Control/i-1.png') }}" alt="Distance"></div>
                               <div class="upperDisplayText">
                                  <span id="fitDistance">0</span>
                                  <span id="fitDistanceType">Kms</span>
                               </div>
                            </div>
                            <div class="elevationDiv">
                               <div><img src="{{ asset('result/css/fitness-mapper/css/images/Control/i-2.png') }} " alt="Elevation"></div>
                               <div class="upperDisplayText"><span id="map-altitude">0m</span></div>
                            </div>
                            <!--div class="clear"></div-->
                         </div>
                         <!--div class="clear"></div-->
                         <div class="ab2">
                            <div class="caloriesDiv">
                               <div><img src="{{ asset('result/css/fitness-mapper/css/images/Control/i-3.png') }}" alt="Calories"></div>
                               <div class="caloriesText"><span id="map-calories">0 calories</span></div>
                            </div>
                            <div class="timeDiv">
                               <div><img src="{{ asset('result/css/fitness-mapper/css/images/Control/i-4.png') }} " alt="Time"></div>
                               <div class="displayText"><span id="map-time">0:00</span></div>
                            </div>
                            <div class="clear"></div>
                         </div>
                         <div class="clear"></div>
                      </div>
                      <!--div class="headerGradient">Workout <span class="caret"></span></div>
                         <div class="selectedWorkout" style="display: none">
                             <div>
                                 <span>Workout</span>
                                 <select id="selectedWorkout">
                                     <option value="1">Running</option>
                                     <option value="3">Walking</option>
                                     <option value="8">Cycling</option>
                                     <option value="4">Mountain Biking</option>
                                     <option value="2">Swimming</option>
                                     <option value="5">Kayaking</option>
                                     <option value="6">Rowing</option>
                                     <option value="7">Orienteering</option>
                                     <option value="9">Other</option>
                                 </select>
                             </div>
                             <div id = "selectedExerciseDiv">
                                 <span>Exercise</span>
                                 <select id="selectedExercise">
                                     <option value="1">Easy Run</option>
                                     <option value="3">Long Run</option>
                                     <option value="8">Hill</option>
                                     <option value="4">Speed</option>
                                     <option value="2">Tempo Run</option>
                                     <option value="5">Fartlek</option>
                                 </select>
                             </div>
                             <div>
                                 <span>Duration</span>
                                 <input id="selectedDuration" type="text" data-wmt="Enter Duration" />
                             </div>
                         </div-->
                   </div>
                </div>
             </div>
             <div class="staticPanel" style="display:block">
                <div class="borderBottom"><span class="staticHeader">Create your Route</span></div>
                <div class="textDiv">
                   <div id="conte"><span><b>Enter your address</b> first</span></div>
                   <div id="conte"><span>Click the map to <b>Measure</b> distance &  altitude</span></div>
                   <div id="conte"><span><b>Estimate</b> exercise time & calories burned</span></div>
                   <div id="conte"><span><b>Save & share</b> your routes and workout statistics</span></div>
                </div>
                <div class="borderBottom"><span class="staticHeader">Get Started Videos</span></div>
                <div class="borderBottom">
                   <div class="leftDiv" id="m">
                      <div>
                         <a href="#">
                         <img src="{{ asset('result/css/fitness-mapper/css/images/Control/starter.png') }}" alt="Starter" /></a>
                         <div class="tagDiv">
                            <span>BEGINNER</span>
                         </div>
                      </div>
                   </div>
                   <div class="rightDiv" id="k">
                      <div>
                         <a href="#">
                         <img src="{{ asset('result/css/fitness-mapper/css/images/Control/power.png') }}" alt="Power" /></a>
                         <div class="tagDiv">
                            <span>POWER</span>
                         </div>
                      </div>
                   </div>
                   <div class="clear unpaddedDiv"></div>
                </div>
                <div class = "borderTop">
                   <a href="#">
                   <img src="{{ asset('result/css/fitness-mapper/css/images/Control/getstarted.png') }} " id="getStarted" alt="Get Started" /></a>
                </div>
             </div>
          </div>
       </div>
    </div>
    <div id="my-route" class="tab-pane fade {{  $my_route_div }}">
       <div class="row">
          <form action="{{ url('epic/train-gain/fitness-mapper') }}" method="get">
             <div class="col-md-3 col-sm-3 m-t-10">
                <input type="text" id="filter-map" name="search" class="form-control" value="{{ !empty($search) && $search != 'null' ? $search : '' }}" placeholder="Enter a keyword">
             </div>
             <div class="col-md-3 col-sm-3 m-t-10">
                <button type="submit" class="btn btn-primary filter">Search</button>
                <a href="javascript:void(0)" class="reset reset-filter">Reset</a>
             </div>
          </form>
          <div class="col-md-3 col-sm-3 m-t-10">
          </div>
          <div class="col-md-3 col-sm-3 m-t-10">
             <select class="form-control">
                <option>Most Recent </option>
             </select>
          </div>
       </div>
       <hr>
       <div class="table-responsive">
           <table id="data-table" class="table table-striped table-bordered table-hover m-t-10" >
          <thead>
             <tr>
                <th>S.No.</th>
                <th>Created</th>
                <th>Distance</th>
                <th>Name</th>
                <th>City</th>
                <th>Estimated Duration</th>
                <th>Actual Duration</th>
                <th>Options</th>
             </tr>
          </thead>
          <tbody>
             @foreach($data as $key=>$value)
             <tr>
                <td>{{ $key+1 }}</td>
                <td><span class="d-color">{{ date('m/d/Y',strtotime($value['created_at'])) }}</span></td>
                <td>{{ number_format((float)($value['km']/1000), 2, '.', '') }} km</td>
                <td><span class="d-color">{{ $value['name'] }}</span></td>
                <td>{{ $value['city'] }}</td>
                <td>{{ $value['duration'] }}</td>
                <td>{{ $value['actual_duration'] }}</td>
                <td>
                    <a href="{{url('details/routes/'.$value['id'])}}" class="action-btn btn-primary filter"><i class="fa fa-eye"></i></a>
                    <a class="action-btn editMap" data-id="{{$value['id']}}" data-action="copy" href="javascript:void(0)"><i class="fa fa-files-o"></i></a>
                    <a class="action-btn editMap" data-id="{{$value['id']}}" data-action="edit" href="javascript:void(0)"><i class="fa fa-edit"></i></a>
                    <a class="action-btn deleteMap" data-id="{{$value['id']}}" href="javascript:void(0)"><i class="fa fa-trash"></i></a>
                     <a class="action-btn create_challange" href="{{ url('fitness-mapper/'.$value['id'].'/create/challenge') }}"><img src="{{asset('assets/images/challenge-icon.png')}}" height="13px"></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
       </div>

</div>
<div id="my-challenges" class="tab-pane fade {{$myChallenges_div}}">
   <a href="{{ url('epic/train-gain/fitness-mapper/challenge') }}" class="btn btn-primary">Create New Route & Share Challenge</a>
      <div class="table-responsive">
         <table id="data-table1"  class="table table-striped table-bordered table-hover m-t-10" >
          <thead>
             <tr>
                <th>S.No</th>
                <th>Date</th>
                <th>Route City</th>
                <th>Challenge</th>
                <th>Activity Type</th>
                <th>Challenge Type</th>
                <th>Challenge Date</th>    
                <th>Total Participants</th>
                <th>Challenge By </th>
                <th>Challenge Completed Time </th>
                <th>Status</th>
                <th>Action</th>
             </tr>
          </thead>
          <tbody>
          @foreach($challenge as $key=>$value)
            <tr>
               <td>{{ $key+1 }}</td>
               <td>{{ date('m/d/Y',strtotime($value['created_at'])) }}</td>
               <td>{{$value['fitness_mapper_route']['name']}}</td>
               <td>{{$value['name']}}</td>
               <td>{{$value['challenge_type']['type']}}</td>
               <td>{{$value['activity_type']['type']}}</td>
               <td>{{ date('m/d/Y',strtotime($value['date'])) }}</td>
               <td><a data-toggle="modal" data-target="#challenge-status{{$value['id']}}">{{$value['challenge_friend']->count()}}</a></td>
               <td>
                  @if($value['client_id'] == Auth::user()->account_id)
                     My challenge
                  @else
                     Via invitation
                  @endif
               </td>
               <td> @foreach($value['challenge_friend'] as $val )
                        @if($val['client_id'] == Auth::user()->account_id)
                           {{$val['complete_time']}}
                        @endif
                    @endforeach
                </td>
               <td>
                  @if( $value['date'] >= now())
                      Active
                   @else
                      Expired
                  @endif
               </td>
               <td style="min-width: 93px;">
                  @php
                     if($value['client_id'] == Auth::user()->account_id){
                        $challenge_from = 'my-challenge';
                     }else{
                        $challenge_from = 'via-invitation';
                     }
                  @endphp
                  
                  @if($value['client_id'] == Auth::user()->account_id)
                     <a class="action-btn" href="{{ url('fitness-mapper/'.$value['fitness_mapper_route']['id'].'/update/challenge/'.$value['id']) }}"><i class="fa fa-edit"></i></a>
                     <a class="action-btn deleteChallenge" data-id="{{$value['id']}}" href="javascript:void(0)"><i class="fa fa-trash"></i></a>
                     <a class="action-btn" title="Enter time after complete your challenge." data-toggle="modal" data-target="#timer-open{{$value['id']}}"><i class="fa fa-clock"></i></a>
                  @endif
               </td>
            </tr>
            <!--timer popup-->
            <div class="modal fade" id="timer-open{{$value['id']}}" role="dialog">
               <div class="modal-dialog modal-md">
                  <div class="modal-content">
                  <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                     <h4 class="modal-title">Select Duration</h4>
                  </div>
                  <div class="modal-body">
               <div class="row">
                  <br>
                  <div class="form-group col-md-6">
                     <label>Duration</label>
                  <input readonly id="challengeDuration{{$value['id']}}" type="text"class="form-control challengeDuration" />
                  </div>
                  
               </div>
                  </div>
                  <div class="modal-footer">
                     <button class="btn btn-primary saveTime" data-id="{{$value['id']}}" data-client-id="{{$value['client_id']}}" data-challenge-from="{{ $challenge_from }}">Save</button>
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                  </div>
               </div>
            </div>
            @endforeach
          </tbody>
      </table> 
      </div>
    </div>

</div>

   <!-- Modal -->
   @foreach($challenge as $key=>$value)
   <div class="modal fade" id="challenge-status{{$value['id']}}" role="dialog">
      <div class="modal-dialog modal-md">
         <div class="modal-content bg-white">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Challange status</h4>
         </div>
         <div class="modal-body">
         <div class="table-responsive">
            <table  class="data-table2 table table-striped table-bordered table-hover m-t-10" >
            <thead>
               <tr>
                  <th>S.No</th>
                  <th>Name</th>
                  <th>status</th>
                  <th>challenge By</th>           
                  <th>Challenge Completed Time</th>            
               </tr>
            </thead>
            <tbody> 
            @foreach ($value['challenge_friend'] as $key => $item)
               <tr>
                  <td>{{ $key+1 }}</td>
                  <td> {{ucfirst($item['client']['firstname'])}} {{ucfirst($item['client']['lastname'])}}</td>
                  <td>
                      @if($item['status'] == 'No') 
                        No Action
                      @else
                      {{ $item['status'] }}
                      @endif
                  </td>  
                  <td>{{ $item['challenge_by'] }}</td>         
                  <td>{{ $item['complete_time'] }}</td>
  
               </tr> 
               @endforeach     
            </tbody>
         </table> 
         </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
         </div>
      </div>
   </div>
   @endforeach
<!-- end modal-->

<div class="clear"></div>
<div class="chartContainer">
    <div id="fitChartDiv">
        <div id="fitAltChart" class="chart" ></div>
    </div>
    <div class="clear"></div>
    <div class="chartControl">
        <!--<img alt="Toggle" src='/wordpress/wp-content/plugins/fitness-mapper/css/images/control/up.jpg' id="chartToggle" class="toggleImage" />-->
    </div>
</div>


<div id="fitSaveDialog" title="Save Route and Workout" style="display: none;">

    <div id="fitMappedActivity">
        <div class="saveInfo">
            <div id="fitRoute" class="form-horizontal">
                <input type="hidden" id="map_id">
                <input type="hidden" id="new_challenge" value="{{ !empty($new_challenge) ? $new_challenge : '' }}">
                <div class="form-group">
                    <label class="fitLabel control-label col-sm-2" for="fitRouteTitle">Title</label>
                    <div class="col-sm-10">
                        <input id="fitRouteTitle" class="form-control fitInput required" type="text" value="" data-wmt="Enter a short route time" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="fitLabel control-label col-sm-2" for="fitRouteDesc">Description</label>
                    <div class="col-sm-10">
                        <input id="fitRouteDesc" class="form-control fitInputDesc required" type="text" cols="30" rows="2" data-wmt="Enter a description of your route">
                    </div>
                </div>
                <div class="form-group">
                    <label class="fitLabel control-label col-sm-2" for="fitRouteKeywords">Keywords</label>
                    <div class="col-sm-10">
                        <input id="fitRouteKeywords" class="form-control fitInput required" type="text" value="" data-wmt="Optional tags, e.g. flat,hill,scenic,roads,footpaths,offroad,steep"  />
                    </div>
                </div>
            </div>        
            <!--table id="fitRoute" style="display: none;">
                <tr>
                    <td class="fitLabel">
                        <label for="fitRouteTitle">Title</label></td>
                    <td >
                        <input id="fitRouteTitle" class="fitInput required" type="text" value="" data-wmt="Enter a short route time" /></td>
                </tr>
                <tr>
                    <td class="fitRouteDesc">
                        <label for="fitRouteDesc">Description</label></td>
                    <td>
                        <input id="fitRouteDesc" class="fitInputDesc required" type="text" cols="30" rows="2" data-wmt="Enter a description of your route"></input></td>
                </tr>
                <tr>
                    <td class="fitLabel">
                        <label for="fitRouteKeywords">Keywords</label></td>
                    <td>
                        <input id="fitRouteKeywords" class="fitInput" type="text" value="" data-wmt="Optional tags, e.g. flat,hill,scenic,roads,footpaths,offroad,steep" /></td>
                </tr>
            </table-->
        </div>
        <hr />

        <div class="workoutWrapper fit-ui">
            <div id="workoutDiv" class="workoutContainer">
                <div class="workoutPlanner">
                    <div id="popupBoxDiv" class="popupBox">
                        <div class="">
                            <div class="form-group">
                                <label class=" control-label col-sm-2" for="">Workout</label>
                                <select class="form-control" id="workoutSelector">
                                    <option value="1">Running</option>
                                    <option value="3">Walking</option>
                                    <option value="8">Cycling</option>
                                    <option value="4">Mountain Biking</option>
                                    <option value="2">Swimming</option>
                                    <option value="5">Kayaking</option>
                                    <option value="6">Rowing</option>
                                    <option value="7">Orienteering</option>
                                    <option value="9">Other</option>
                                </select>
                            </div>
                            <div class="form-group" id = "exerciseSelectorDiv">
                                <label class=" control-label col-sm-2" for="">Exercise</label>
                                <select  class="form-control" id="exerciseSelector">
                                    <option value="1">Easy Run</option>
                                    <option value="3">Long Run</option>
                                    <option value="8">Hill</option>
                                    <option value="4">Speed</option>
                                    <option value="2">Tempo Run</option>
                                    <option value="5">Fartlek</option>
                                    {{-- <option value="6">Easy Run</option>
                                    <option value="7">Long Run</option>
                                    <option value="1">Hill</option>
                                    <option value="5">Speed</option>
                                    <option value="8">Tempo Run</option>
                                    <option value="20">Fartlek</option>
                                    <option value="21">Easy</option>
                                    <option value="22">Hill</option>
                                    <option value="23">Interval</option>
                                    <option value="24">Long</option>
                                    <option value="25">Tempo</option>
                                    <option value="26">Race</option>
                                    <option value="17">Freestyle</option>
                                    <option value="11">Backstroke</option>
                                    <option value="12">Breaststroke</option>
                                    <option value="13">Butterfly</option>
                                    <option value="18">Sidestroke</option>
                                    <option value="19">Mixed</option> --}}
                                </select>
                            </div>
                            <div class="clearDiv">
                            </div>

                            <div class="form-group">
                                <label class=" control-label col-sm-2" for="">Date</label>
                                <input id="dateSelector"  class="form-control" type="text" />
                            </div>
                            <div class="form-group">
                                <label class=" control-label col-sm-2" for="">Duration</label>
                                <input id="durationSelector" type="text" data-wmt="Enter Duration" class="form-control" />
                            </div>
                            <div class="clearDiv">
                            </div>
                        </div>
                    </div>
            <!-- <div class="workoutLoader">
                <img src="/wordpress/wp-content/plugins/fitness-workout-logger/css/wo_images/ajax-loader.gif" alt="loading" />
            </div> -->
        </div>

    </div>

</div>


@stop

@section('required-script')

<!-- <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script> -->
{!! Html::script('result/js/fitness-core/jquery.js?ver=1.12.4') !!}
{!! Html::script('result/js/fitness-core/jquery-migrate.min.js?ver=1.4.1') !!}
<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.min.js" integrity="sha256-Nnknf1LUP3GHdxjWQgga92LMdaU2+/gkzoIUO+gfy2M=" crossorigin="anonymous"></script>


<!--{!! Html::script('js/fitness-core/bootstrap.min.js') !!}-->
<!-- {!! Html::script('js/fitness-core/map.js') !!}  -->
{!! Html::script('result/js/fitness-core/arraylist.js') !!}
{!! Html::script('result/js/fitness-core/utility.js') !!}
{!! Html::script('result/js/fitness-core/jquery.tinymce.js') !!}
{!! Html::script('result/js/fitness-core/fitness-core-clean.js') !!}
{!! Html::script('result/js/fitness-core/jquery.livequery.js') !!}
{!! Html::script('result/js/fitness-core/jquery.json-2.4.min.js') !!}
<!--{!! Html::script('js/fitness-core/modernizr-2.5.2.min.js') !!}-->
{!! Html::script('result/js/fitness-core/jquery.cookie.js') !!}
{!! Html::script('result/js/fitness-core/raphael.js') !!}
{!! Html::script('result/js/fitness-core/g.Raphael.min.js') !!}
{!! Html::script('result/js/fitness-core/g.pie.js') !!}
{!! Html::script('result/js/fitness-core/jquery.ui.labeledslider.js') !!}
{!! Html::script('result/js/fitness-core/datetimepicker.min.js') !!}
{!! Html::script('result/js/fitness-mapper/jquery.watermark.min.js') !!}
{!! Html::script('result/js/fitness-core/jquery.ui.combobox.js') !!}
{!! Html::script('result/js/fitness-core/jquery.ui.touch-punc.js') !!}


<script src="//maps.googleapis.com"></script>
<!--<script src="//fonts.googleapis.com"></script>-->
<script src="https://www.google.com/jsapi"></script>

<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyBXlJaQ-UcLSOuUXSoNUCpOmsm5Pe1a11A&sensor=false&libraries=geometry'></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBXlJaQ-UcLSOuUXSoNUCpOmsm5Pe1a11A&#038;sensor=false&#038;&libraries=geometry"></script> -->



{!! Html::script('result/js/fitness-mapper/jquery.fullscreen.js') !!}
{!! Html::script('result/js/fitness-mapper/FitMapper.js?v='.time()) !!}
<script type='text/javascript'>

        // Set a callback to run when the Google Visualization API is loaded.
        //google.setOnLoadCallback(Fit.Mapper.Initialize);

        // Load the Visualization API and the piechart package.
        //google.load("visualization", "1", { packages: ["columnchart"] });

        jQuery(document).ready( function() {
		    Fit.Mapper.Start( '', null );
        });
	</script>
    <script type="text/javascript">
        $(".map-btn > .btn").click(function(){
    $(".map-btn > .btn").removeClass("active");
    $(this).addClass("active");
});
    </script>
    
  <script type="text/javascript">  
   jQuery(document).ready(function() {
      $('#data-table').DataTable({"info":false,searching: false});
      $('#data-table1').DataTable({"info":false,searching: false});
      $('.data-table2').DataTable({"info":false,searching: false});
      var challenge_status = $('#challenge_status').val();
        if(challenge_status == 'my_challenge'){
             var toaster_message = $('#toaster_message').val();
             if(toaster_message == 'Accepted'){
               //  $('#challenge-accept').modal('show'); 
               swal({
                        type: 'success',
                        title:'Challenge Accepted Sucessfully !',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        confirmButtonColor: '#ff4401',
                    }); 
              }

              if(toaster_message == 'Rejected'){
               //  $('#challenge-reject').modal('show'); 
               swal({
                        type: 'warning',
                        // timer: 2000,
                        title: 'Challenge has been Rejected !',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        confirmButtonColor: '#ff4401',
                    }); 
              }  

               if(toaster_message == 'Performed'){
               swal({
                        type: 'info',
                        title: 'Action has already been taken ',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        confirmButtonColor: '#ff4401',
                    }); 
              }          
        }

   });
   $("#getStarted").click(function(){
      $('.staticPanel').css('display','none');
   });
</script>
@stop