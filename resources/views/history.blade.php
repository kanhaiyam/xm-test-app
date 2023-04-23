<!DOCTYPE html>
<html>
<head>
    <meta property="og:image" content="{{ asset('assets/img/XM-logo.jpg') }}"/>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/XM-logo.jpg') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery-ui.min.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>XM Practice Tests</title>
</head>
<body> 
    <section class="historical_form">
        <h3>Get Historical Data</h3>
        <form action="{{ route('history.show') }}" method="POST" id="historyForm" onsubmit="return validateForm()">
            @csrf
            <div class="common" id="divSymbol">
              <input for="symbol" value="{{ old('symbol') }}" name="symbol" id="symbol" type="text" style="text-transform:uppercase" required />
              <label>Symbol</label>
            </div>
            <div class="common" id="symbolError"><span class="error">{{ $errors->first('symbol') }}</span></div>
            <div class="common" id="divSdate">
              <input for="s_date" value="{{ old('s_date') }}" name="s_date" id="s_date" type="text" required />
              <label>Start Date</label>
            </div>
            <div class="common" id="sDateError"><span class="error">{{ $errors->first('s_date') }}</span></div>
            <div class="common" id="divEdate">
              <input for="e_date" value="{{ old('e_date') }}" name="e_date" id="e_date" type="text" required />
              <label>End Date</label>
            </div>
            <div class="common" id="eDateError"><span class="error">{{ $errors->first('e_date') }}</span></div>
            <div class="common" id="divEmail">
              <input for="email" value="{{ old('email') }}" name="email" id="email" type="email" required />
              <label>Email</label>
            </div>
            <div class="common" id="emailError"><span class="error">{{ $errors->first('email') }}</span></div>
            <button type="submit" form="historyForm" value="Submit">Submit</button>
        </form>
    </section>
@if(!empty($firstLine))
    <section class="historical_section" style="padding-top: 2.5em;">
        <hr><h3>Historical Data - {{ $firstLine }}</h3><hr>
        <div class="common">
            <table>
                <tr>
                    <th>Date</th>
                    <th>Open</th>
                    <th>High</th>
                    <th>Low</th>
                    <th>Close</th>
                    <th>Volume</th>
                </tr>
                @forelse($tableData as $data)
                <tr>
                    <td>{{ $data[0] }}</td>
                    <td>{{ $data[1] }}</td>
                    <td>{{ $data[2] }}</td>
                    <td>{{ $data[3] }}</td>
                    <td>{{ $data[4] }}</td>
                    <td>{{ $data[5] }}</td>
                </tr>
                @empty
                <tr colspan="6" style="text-align: center !important"><td>No Data Found</td></tr>
                @endforelse
            </table>
        </div>
        <div id="container" style="width: 100%; height: 500px;></div>
    </section>
@endif
</body>
<script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
@if(!empty($firstLine))
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
@php
krsort($tableData);
@endphp
var data = {!! Js::from(array_values($tableData)) !!}
let chartData = [];

data.forEach((element) => {
    temp = [];
    temp.push(new Date(element[6]).toISOString().substring(0, 10));
    temp.push(parseFloat(element[3]));
    temp.push(parseFloat(element[1]));
    temp.push(parseFloat(element[4]));
    temp.push(parseFloat(element[2]));
    chartData.push(temp);
});

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable(chartData, true);

    var options = {
        legend:'none',
        candlestick: {
            fallingColor: { strokeWidth: 0, fill: '#a52714', stroke: '#a52714' }, // red
            risingColor: { strokeWidth: 0, fill: '#0f9d58', stroke: '#0f9d58' }   // green
        }
    };

    var chart = new google.visualization.CandlestickChart(document.getElementById('container'));

    chart.draw(data, options);
  }


</script>
@endif
</html>