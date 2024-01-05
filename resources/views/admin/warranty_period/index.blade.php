@extends('adminlte::page')

@section('title', 'Warranty Period')

@section('content_header')
    <h5 style="color: blue;">Warranty Periods</h5>
    <style type="text/css">
      
    </style>
@stop
@section('content')
  	
    <form ction="{{ route('warranty_period.store')}}" method="post" enctype="multipart/form-data">
       @csrf
        <div class="row">
            <div class="col-md-2 {{ $errors->first('period', 'has-error') }} form-group">
               <input type="text" name="period" placeholder="6" value="{{ old('period') }}" class="form-control unicode"> 
                @if($errors->first('period'))
                    <span class="help-block">
                        <small>{{ $errors->first('period') }}</small>
                    </span>
                @endif
            </div>
            <div class="col-md-3">
            <button class="btn btn-success" type="submit"  style="height: 36px;font-size: 13px">
              Save
            </button>
           </div>
            
        </div>
    </form>


            
    <div class="page_body">

        <div class="table-responsive">
            <table class="table table-bordered styled-table">
              <thead>
                <tr>
                    <th >No</th>
                    <th>Period</th>
                </tr>
              </thead>
          	 @if($warranty_periods->count()>0)
             @foreach($warranty_periods as $warranty_period)
                <tr class="table-tr" data-url="{{route('warranty_period.show',$warranty_period->id)}}">
                    <td >{{++$i}}</td>
                   <td>{{$warranty_period->period}}</td>
                </tr>
           @endforeach
               @else
                <tr align="center">
                  <td colspan="10">No Data!</td>
                </tr>
              @endif
              
            </table>
             <div align="center">
                <p>Total -{{$count}}</p>
          </div>
       </div>
       {{ $warranty_periods->appends(request()->input())->links()}}
    </div>
@stop 

@section('css')
    <style type="text/css" media="screen">
      .error_msg{
        color: #DD4B39;
      }
      .has-error input{
        border-color: #DD4B39;
      }
      .help-block{
        color: #DD4B39;
      }

  </style>
@stop

@section('js')
 <script> 
   @if(Session::has('success'))
            toastr.options =
            {
            "closeButton" : true,
            "progressBar" : true
            }
            toastr.success("{{ session('success') }}");
        @endif
        
        @if(Session::has('error'))
          toastr.options =
          {
            "closeButton" : true,
            "progressBar" : true
          }
                toastr.error("{{ session('error') }}");
          @endif

        $(document).ready(function(){
            setTimeout(function(){
            $("div.alert").remove();
            }, 1000 ); 
            $(function() {
                $('#name').on('change',function(e) {
                this.form.submit();
            }); 
   
        });
          $(function() {
          $('table').on("click", "tr", function() {
            window.location = $(this).data("url");
          });
        });
        });
        
     </script>
@stop