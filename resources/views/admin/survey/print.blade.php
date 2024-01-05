<!DOCTYPE html>
<html lang="en">

<head>


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="XL1LTaGOrRdrf1QmXiesCB25nAWG5NaXcncrTmpz">

    <title></title>
    <link rel="stylesheet" href='https://mmwebfonts.comquas.com/fonts/?font=pyidaungsu' />


    <style>
    body {
        font-family: Pyidaungsu, Yunghkio, 'Masterpiece Uni Sans' !important;
    }

    .unicode {
        font-family: Pyidaungsu, Yunghkio, 'Masterpiece Uni Sans' !important;
    }

    .row {
        letter-spacing: .5px;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: -7.5px;
        margin-left: -7.5px;
    }

    .col-md-3 {
        -ms-flex: 0 0 25%;
        flex: 0 0 25%;
        max-width: 25%;
    }

    .col-md-4 {
        -ms-flex: 0 0 33.333333%;
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }

    .col-md-6 {
        -ms-flex: 0 0 50%;
        flex: 0 0 50%;
        max-width: 50%;
    }

    .col-md-8 {
        -ms-flex: 0 0 66.666667%;
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
    }
        
   

    img {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    .groove {
        border-radius: 10px;
        border-style: groove;
        height: auto;
    }

    #logo {
        width: 100px;
        height: 100px;
    }

    #logo1 {
        width: 190px;
        height: 190px;
        margin:auto;
    }

    #tick {
        width: 190px;
        height: 190px;
    }

    .title {
        text-align: center;
        font-size: 18px;
    }

    .space {
        padding-left: 35px;
        padding-right: 35px;
    }

    .row {
        letter-spacing: .5px;
    }

    .main-footer {
        display: none !important;
    }

    #banner {
    height: 100px;
    /*width: 100%;*/
    /*padding-bottom: 10px;*/
    /*background-color: red;*/
    }

    #banner input {
        display: block;
        margin: 0 auto;
    }

    
        #line{
              background: rgba(0, 0, 0, 0);
              outline: 0;border-width: 5px 0 0;
              overflow-wrap: break-word;
              /*margin-top: 10px;*/
              width: 97%;
              /*padding: 10px;*/
              text-align: center;
              font-size: 20px;
              font-weight: bold;
              /*background-color: red;*/
          }
    input[type="text"]
          {
              background: rgba(0, 0, 0, 0);
              outline: 0;border-width: 1px 0 0;
              overflow-wrap: break-word;
              
             border-top-style: dashed;
              text-align: center;
              width: 200px;
          }
    
    th, td {
      border: 1px solid black;
    }
    .styled-table {
    border-collapse: collapse;
    /*margin: 25px 0;*/
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 400px;
    /*box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);*/
    }
    .styled-table thead tr {
        /*background-color: #009879;
        color: #ffffff;*/
        text-align: left;
    }
    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
    }

    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
    }

   
/*
    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #009879;
    }*/
        .container {
            position: relative;
            z-index: 1;
          }
        .bg {
            visibility: visible;
            position: absolute;
            z-index: -1;
            bottom: 0;
            left: 0;
            right: 0;
            background: url({{asset('linn.jpg')}}) center center;
            opacity: .3;
            width: 100%;
            height: 100%;
            background-repeat: no-repeat;
            background-position: center;
            background-size: 1000px 400px;
          }

          @page {
            size:A5;
            margin-left: 0px;
            margin-right: 0px;
            margin-top: 0px;
            margin-bottom: 0px;
            margin: 0;
            -webkit-print-color-adjust: exact;
        }
   
    </style>

</head>

<body>

    <div>
       
        <div class="row" style="visibility: hidden;">
            <div class="col-md-2">
                <img src="{{ asset('linn.jpg') }}" style="width: 250px;height: 100px;margin-top: 40px;" align="center">
            </div>
            <div class="col-md-7" style="margin-top: 20px;">
                <!-- <p style="font-weight: bold;"></p> -->
                <label style="font-weight: bold;">Computer Training, Sales, Service & Mobile Mart</label>
                    <div class="col-md-12" style="margin-left: 20px;">
                       <label style="font-size: 10px;">Head Office : No(14/585),4th Street,PaungLaung Quarter,Pyinmana,</label> 
                       <br>
                       <label style="font-size: 10px;margin-left: 80px;">06722884,23884,24884,25884</label><br>
                       <label style="font-size: 10px;">Center-(1): No(11/7),Bogyoke Road,Pyinmana,067-24488,26884</label><br>
                      
                    </div>
                     
                 
            </div>
        </div>
           
         <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
               <!-- <p>Customer Info</p>  -->
               <div class="row">
                   <label style="margin-left: 60px;" class="col-md-4">Invoice No:</label>
                    <label>{{$customer_data->voucher_no}}</label>
               </div>
               <div class="row">
                   <label style="margin-left: 60px;" class="col-md-4">Name:</label>
                    <label>{{$customer_data->name}}</label>
               </div>
               <div class="row">
                   <label style="margin-left: 60px;" class="col-md-4">Phone:</label>
                    <label>{{$customer_data->phone_no}}</label>
               </div>
               <div class="row">
                   <label style="margin-left: 60px;" class="col-md-4">Address:</label>
                    <label>{{$customer_data->town_name}}</label>
               </div>
            </div>
            <div class="col-md-1">
                <!-- <p>QR</p> -->
            </div>
            @if($assign != null)
            <div class="col-md-4">
                <div class="row" style="margin-right: 40px;">
                    <label class="col-md-4" style="margin-right: 40px;">Date:</label>
                    <label >{{date('d-m-Y', strtotime($assign->solved_date))}}</label>
                </div>
                <div class="row">
                    <label class="col-md-4" style="margin-right: 20px;">Technician:</label>
                    <label>{{$assign->group_name}}</label>
                </div>
                <div class="row" style="margin-right: 40px;">
                    <label class="col-md-4" style="margin-right: 30px;">Phone:</label>
                    <label>{{$assign->loginId}}</label>
                </div>
            </div>
            @endif
        </div>
        </div>
        <div class="row" style="margin-left: 30px;margin-right: 30px;">
            
           <div class="col-md-12" style="width: 100%;">
               <table class="table styled-table" style="width: 100%">
                <thead>
                    <tr>
                    <th>No</th>
                    <th style="width: 100%;">Item</th>
                    <th style="width: 100%;">Unit</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Amount</th>
                </tr>
                </thead>
                <tbody>
                    @if(count($survey_install_items)>0)
                    @foreach($survey_install_items as $key => $installItem)
                    <tr class="table-tr"> 
                        <td>{{++$key}}</td>
                        <td>{{$installItem->model}}</td>
                        <td>{{$installItem->unit}}</td>
                        <td>{{$installItem->qty}}</td>
                        <td style="text-align:right;">{{number_format($installItem->item_price)}}</td>
                        <td style="text-align: right;">{{number_format($installItem->amount)}}</td>
                      
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="6" style="text-align: center;">No Data</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="5" style="text-align: right;">Sub Total</td>
                        <td style="text-align: right;">
                            @if($amounts != null)
                                {{number_format($amounts->sub_total)}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: right;">Installation Charges</td>
                        <td style="text-align: right;">
                            @if($amounts != null)
                                {{number_format($amounts->install_charge)}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: right;">Cloud Regristration Service Fee</td>
                        @if($amounts != null)
                        <td style="text-align: right;">{{number_format($amounts->cloud_charge)}}</td>
                        @else
                        <td style="text-align: right;">0</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: right;">Cabling Charge</td>
                        <td style="text-align: right;">
                            @if($amounts != null)
                                {{number_format($amounts->cabling_charge)}}
                            @endif

                            </td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: right;">Service Charge</td>
                        <td style="text-align: right;">
                            @if($amounts != null)
                                {{number_format($amounts->service_charge)}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: right;">Discount</td>
                        <td style="text-align: right;">
                            @if($amounts != null)
                                {{number_format($amounts->discount)}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: right;">Total Amount</td>
                        <td style="text-align: right;">
                            @if($amounts != null)
                                {{number_format($amounts->total_amt)}}
                            @endif
                        </td>
                </tr>
              </tbody>
            </table>
           </div>
           
       
        </div><br><br>
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="" style="margin-left: 20px;" value="Customer's Sign">

            </div>
            <div class="col-md-6">
                <input type="text" name="" style="margin-left: 150px;margin-right: 30px;" value="Technician's Sign">
            </div>
        </div>
    </div>
       
    <script>
    window.print();
    </script>

</body>

</html>