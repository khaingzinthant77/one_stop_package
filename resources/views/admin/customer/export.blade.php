<html>
    <head>
    </head>
    <body>
      <table class="table table-bordered styled-table ">
         <thead>
            <tr>
               <th>Name</th>
               <th>Phone No</th>
               <th>Township</th>
               <th>Address</th>
               <th>Latitude</th>
               <th>Longitude</th>
                <th>Team Name</th>
               <th>Issue Type</th>
               <th>Description</th>
               <th>Service Charge</th>
            </tr>
         </thead>
       
         <tbody>
             @if(count($tickets)>0)
                  @foreach($tickets as $ticket)
                        <tr>
                           <td>{{$ticket->name}}</td>
                           <td>{{$ticket->phone_no}}</td>
                           <td>{{$ticket->town_name}}</td>
                           <td>{{$ticket->address}}</td>
                           <td>{{$ticket->lat}}</td>
                           <td>{{$ticket->lng}}</td>
                           <td>{{$ticket->group_name}}</td>
                           <td>{{$ticket->issue_type}}</td>
                           <td>{{$ticket->description}}</td>
                           @if($ticket->service_charge != null)
                           <td>{{$ticket->service_charge}}</td>
                           @else
                           <td>0</td>
                           @endif
                        </tr>
                        
                     @endforeach
                  @else
                      <tr align="center">
                        <td colspan="10">No Data!</td>
                      </tr>
                  @endif
            
         </tbody>
      </table>
    </body>
</html>