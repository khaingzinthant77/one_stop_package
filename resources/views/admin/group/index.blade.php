@extends('adminlte::page')

@section('title', 'Teams')

@section('content_header')
    <h5 style="color: blue;">Teams</h5>
@stop
@section('content')
    <?php
        $name = isset($_GET['name'])?$_GET['name']:'';   
    ?>
        <!-- <form action="{{ route('group.index') }}" method="post" accept-charset="utf-8" class="form-horizontal">
           
            <div class="row">
                <div class="col-md-10">
                    <div class="row form-group">
                        <div class="col-md-2">
                            <input type="text" name="name" id="name" value="{{ old('name',$name) }}" class="form-control" placeholder="Search...">
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-success unicode" href="{{route('group.create')}}"><i class="fas fa-users" /></i> Add New!</a>
                </div>
            </div>
        </form> -->
        <form action="{{ route('group.index') }}" method="get" accept-charset="utf-8" class="form-horizontal">
            <div class="row">
                <div class="col-md-10">
                    <div class="row form-group">
                        <div class="col-md-2">
                            <input type="text" name="name" id="name" value="{{ old('name',$name) }}" class="form-control" placeholder="Search...">
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-success unicode" href="{{route('group.create')}}"><i class="fas fa-user-cog" /></i> Add New!</a>
                </div>
            </div>
        </form>
    <div class="page_body">
       
        

        <div class="table-responsive">
            <table class="table table-bordered styled-table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Team Name</th>
                    <th>LoginId</th>
                </tr>
                </thead>
            @if(count($groups)>0)
              @foreach($groups as $group)
                <tr class="table-tr" data-url="{{route('group.edit',$group->id)}}">
                    <td>{{++$i}}</td>
                    <td>{{$group->group_name}}</td>
                    <td>{{$group->loginId}}</td>
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
       {{ $groups->appends(request()->input())->links()}}
    </div>
@stop 

@section('css')
 
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
               // $( "#form_id" )[0].submit();   
            }); 
        });
            $(function() {
              $('table').on("click", "tr.table-tr", function() {
                window.location = $(this).data("url");
              });
            });
        });
     </script>
@stop