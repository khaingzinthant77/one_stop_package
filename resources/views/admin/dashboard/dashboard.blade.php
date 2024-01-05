@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>

@stop

@section('content')
<h6>Customer's by townships</h6>

    <div class="row">
    @foreach($townships as $key=>$tsh)

    <div class="card" style="background:{{$tsh->tsh_color}};">
      <div class="card__icon"><i class="fas fa-city"></i>&nbsp;{{$tsh->town_name}}</div>
      
      <div class="row card__title">
        <div class="col-md-6">
            <h6>New</h6>
            @if($tsh->new_count == 0)
            <p>{{$tsh->new_count}}</p>
            @else
            <a href="{{url('/customer?tsh_id='.$tsh->id.'&team_id=&from_date=&to_date=&survey_type=1')}}" target="_blank" style="color:white;">{{$tsh->new_count}}</a>
            @endif
        </div>
          
         <div class="col-md-6" style="text-align:right;">
            <h6>Services</h6>
            @if($tsh->service_count == 0)
            <p>{{$tsh->service_count}}</p>
            @else
            <a href="{{url('/ticket?is_solve=1&team_id=&from_date=&to_date=&tsh_id='.$tsh->id.'&issue_id=')}}" target="_blank" style="color:white;">{{$tsh->service_count}}</a>
            @endif
        </div>

          <!-- <div class="col-md-4">
            <h6>Total</h6>
            <p>{{$tsh->total_count}}</p>
        </div> -->
      </div>
    
    </div>
    @endforeach
</div>
<hr>
<h5>Technician Dashboard</h5>
        <div class="row">
            @foreach ($teams as $i=>$group)
            
               
                <div class="tech_card" style="background:{{$bg_color}}">
              <div class="card__icon"><i class="fas fa-users"></i>&nbsp;{{$group->group_name}}</div>
                
              <div class="row card__title">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="50%" style="text-align: center">
                                <a href="" class="text-white">
                                    Surveys
                                </a>
                            </th>
                            <th width="50%" style="text-align: center">
                                <a href="" class="text-white">
                                    Tickets
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span>New</span>
                                    </div>
                                    <!-- http://cctv.test/surveys?tsh_id=&team_id=26&assign_status=&solve_status=&from_date=&to_date= -->
                                    <div class="col-md-6">
                                        @if($group->cust_name == 0)
                                         <span>{{$group->cust_new}}</span>
                                        @else
                                            <a href="{{url('/surveys?tsh_id=&team_id='.$group->id.'&assign_status=&solve_status=&from_date=&to_date=')}}" style="color:white;" target="_blank">{{$group->cust_new}}</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span>Left</span>
                                    </div>
                                    <div class="col-md-6">
                                        <?php 
                                            $from_date = '01-01-1970';
                                            $to_date = \Carbon\Carbon::now()->subDay()->format('d-m-Y');
                                            // dd($current_date);
                                         ?>
                                         @if($group->cust_left == 0)
                                        <span>{{$group->cust_left}}</span>
                                        @else
                                        <a href="{{url('/surveys?tsh_id=&team_id='.$group->id.'&assign_status=&solve_status=&from_date='.$from_date.'&to_date='.$to_date)}}" style="color:white;" target="_blank">{{$group->cust_new}}</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span>Install</span>
                                    </div>
                                    <div class="col-md-6">
                                        @if($group->cust_install == 0)
                                        <span>{{$group->cust_install}}</span>
                                        @else
                                        <a href="{{url('/customer?tsh_id=&team_id='.$group->id.'&from_date=&to_date=&survey_type=1&warranty_status=')}}" style="color:white;" target="_blank">{{$group->cust_install}}</a>
                                        @endif
                                    </div>
                                </div>

                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span>New</span>
                                    </div>
                                    <div class="col-md-6">
                                        @if($group->new == 0)
                                        <span>{{$group->new}}</span>
                                        @else

                                        <?php 
                                            $from_date = '01-01-1970';
                                            $to_date = \Carbon\Carbon::now()->subDay()->format('d-m-Y');
                                            // dd($current_date);
                                         ?>

                                        <a href="{{url('/ticket?is_solve=&team_id='.$group->id.'&from_date='.$from_date.'&to_date='.$to_date.'&tsh_id=&issue_id=')}}" style="color:white;" target="_blank">{{$group->new}}</a>
                                        @endif
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-md-6">
                                        <span>Left</span>
                                    </div>
                                    <div class="col-md-6">
                                        @if($group->unsolve == 0)
                                        <span>{{$group->unsolve}}</span>
                                        @else
                                        <a href="{{url('/ticket?is_solve=0&team_id='.$group->id.'&from_date=&to_date=&tsh_id=&issue_id=')}}" style="color:white;" target="_blank">{{$group->unsolve}}</a>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <span>Solved</span>
                                    </div>
                                    <div class="col-md-6">
                                        @if($group->solved == 0)
                                            <span>{{$group->solved}}</span>
                                        @else
                                            <a href="{{url('/ticket?is_solve=1&team_id='.$group->id.'&from_date=&to_date=&tsh_id=&issue_id=')}}" style="color:white;" target="_blank">{{$group->solved}}</a>
                                        @endif
                                    </div>
                                </div>
                               
                            </td>
                        </tr>
                    </tbody>
                </table>
              </div>

            </div>
            @endforeach
        </div>

@stop

@section('css')

    <style type="text/css">
        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }

   

        .main-container {
          padding: 30px;
        }

        /* HEADING */

        .heading {
          text-align: center;
        }

        .heading__title {
          font-weight: 200;
        }

        .heading__credits {
          margin: 10px 0px;
          color: #888888;
          font-size: 25px;
          transition: all 0.5s;
        }

        .heading__link {
          text-decoration: none;
        }

        .heading__credits .heading__link {
          color: inherit;
        }

        /* CARDS */

        .cards {
          display: flex;
          flex-wrap: wrap;
          justify-content: space-between;
        }

        .card {
          margin: 20px;
          padding: 20px;
          width: 300px;
          /*height: 150px;*/
          /*min-height: 100px;*/
         /* display: grid;
          grid-template-rows: 20px 50px 1fr 50px;*/
          border-radius: 10px;
          box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.25);
          transition: all 0.2s;
        }

        .tech_card{
              margin: 5px;
              padding: 10px;
              width: 330px;
              /*height: 160px;*/
              /*min-height: 100px;*/
             /* display: grid;
              grid-template-rows: 20px 50px 1fr 50px;*/
              border-radius: 10px;
              box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.25);
              transition: all 0.2s;
        }

        .tech_card:hover {
          box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.4);
          transform: scale(1.01);
        }

        .card:hover {
          box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.4);
          transform: scale(1.01);
        }

        .card__link,
        .card__exit,
        .card__icon {
          position: relative;
          text-decoration: none;
          color: rgba(255, 255, 255, 0.9);
        }

        .card__link::after {
          position: absolute;
          top: 25px;
          left: 0;
          content: "";
          width: 0%;
          height: 3px;
          background-color: rgba(255, 255, 255, 0.6);
          transition: all 0.5s;
        }

        .card__link:hover::after {
          width: 100%;
        }

        .card__exit {
          grid-row: 1/2;
          justify-self: end;
        }

        .card__icon {
          grid-row: 2/3;
          font-size: 20px;
        }

        .card__title {
          grid-row: 3/4;
          font-weight: 400;
          color: #ffffff;
        }

        .card__apply {
         
          align-self: center;
        }

        /* CARD BACKGROUNDS */

        .card-1 {
          background: radial-gradient(#1fe4f5, #3fbafe);
        }

        .card-2 {
          background: radial-gradient(#fbc1cc, #fa99b2);
        }

        .card-3 {
          background: radial-gradient(#76b2fe, #b69efe);
        }

        .card-4 {
          background: radial-gradient(#15d1ac,#009879);
        }

        .card-5 {
          background: radial-gradient(#009879, #c0a3e5);
        }

        /* RESPONSIVE */

        @media (max-width: 1600px) {
          .cards {
            justify-content: center;
          }
        }
    </style>
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop