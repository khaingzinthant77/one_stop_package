<html>
    <head>
    </head>
    <body>
      <div class="table-responsive">
        <table class="table table-bordered styled-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Customer Name</th>
                <th>Phone Number</th>
                <th>Township</th>
                <th>Checked By</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @if(count($customer_list) != 0)
            @foreach($customer_list as $key=>$customer)
            <tr>
                <td>{{++$key}}</td>
                <td>{{$customer->name}}</td>
                <td>{{$customer->phone_no}}</td>
                <td>{{$customer->town_name}}</td>
                <td>{{$customer->cby}}</td>
                <td>{{date('d-m-Y h:i A',strtotime($customer->created_at))}}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="6" style="text-align: center;">No Data</td>
            </tr>
            @endif
        </tbody>
        </table>
   </div>
    </body>
</html>