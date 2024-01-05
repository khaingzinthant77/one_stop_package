<html>
    <head>
    </head>
    <body>
      <table class="table table-bordered styled-table ">
         <thead>
            <tr>
                <th>Name</th>
                <th>Phone No</th>
                <th>Issue Type</th>
                <th>Team</th>
                <th>Sub Total</th>
                <th>Install Charge</th>
                <th>Cloud Service</th>
                <th>Service Charge</th>
                <th>One Call Charge</th>
                <th>Discount</th>
                <th>Total Amount</th>
            </tr>
         </thead>
       
         <tbody>
            <?php 
                $sub_total = 0;
                $install_charge = 0;
                $cloud_charge = 0;
                $service_charge = 0;
                $on_call_charge = 0;
                $discount = 0;
                $total_amount = 0;
            ?>
            @forelse($amounts as $amount)
                <?php 

                    $sub_total += $amount->sub_total;
                    $install_charge += $amount->install_charge;
                    $cloud_charge += $amount->cloud_charge;
                    $service_charge += $amount->service_charge;
                    $on_call_charge += $amount->on_call_charge;
                    $discount += $amount->discount;
                    $total_amount += $amount->total_amt;
                    
                 ?>
            <tr>
                <td>{{$amount->name}}</td>
                <td>{{$amount->phone_no}}</td>
                <td>{{$amount->issue_type}}</td>
               
                <td>{{get_team_member($amount->ticket_id)}}</td>
                <td style="text-align:right;">{{number_format($amount->sub_total)}}</td>
                <td style="text-align:right;">{{number_format($amount->install_charge)}}</td>
                <td style="text-align:right;">{{number_format($amount->cloud_charge)}}</td>
                <td style="text-align:right;">{{number_format($amount->service_charge)}}</td>
                <td style="text-align:right;">{{number_format($amount->on_call_charge)}}</td>
                <td style="text-align:right;">{{number_format($amount->discount)}}</td>
                <td style="text-align:right;">{{number_format($amount->total_amt)}}</td>
            </tr>
            @empty
            @endforelse
         </tbody>
         <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;">Total Amount</td>
                <td style="text-align:right;">{{number_format($sub_total)}}</td>
                <td style="text-align:right;">{{number_format($install_charge)}}</td>
                <td style="text-align:right;">{{number_format($cloud_charge)}}</td>
                <td style="text-align:right;">{{number_format($service_charge)}}</td>
                <td style="text-align:right;">{{number_format($on_call_charge)}}</td>
                <td style="text-align:right;">{{number_format($discount)}}</td>
                <td style="text-align:right;">{{number_format($total_amount)}}</td>
            </tr>
        </tfoot>
      </table>
    </body>
</html>