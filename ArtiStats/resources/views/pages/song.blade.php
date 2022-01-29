@extends('default.layout')
@section('title', $songName)
@section('metaOG')
@endsection
@section('content')

<div class="container my-5 text-montserrat">
    <div class="profile-body px-3 py-4">
        <div class="px-3">
            <div class="media mb-4">
                <div>
                    <img class="album-picture" src="{{$album->images[1]->url}}"/><br/>
                    <a href="" class="show-stats-button mt-3" type="button" data-toggle="collapse" data-target="#statsCollapse" aria-expanded="false" aria-controls="statsCollapse">
                        <i class="fas fa-chart-pie"></i>
                        Show stats
                    </a>
                </div>
                <div class="media-body pl-4">
                    <h1 class="text-bold">{{$songName}}</h1>
                    <h3>
                        By :
                        @foreach($album->artists as $artist)
                            <a href="{{url('format/profile/'.$artist->name)}}" class="album-artist-link">
                                {{$artist->name}}@if($loop->index!==sizeof($album->artists)-1), @endif
                            </a>
                        @endforeach
                    </h3>
                    <h4>
                        {{\Carbon\Carbon::parse($album->release_date)->isoFormat('LL')}}
                    </h4>
                </div>
            </div>

            <div class="collapse" id="statsCollapse">
                <div class="row">
                    <div class="col-lg-6">
                        <h4>Most used words</h4>
                        <div id="barChart"></div>
                    </div>
                    <div class="col-lg-6">
                        <h4>Percentage of swear words</h4>
                        <div id="pieChart"></div>
                    </div>
                </div>
                <br/>
            </div>

            @if($songDesc)
                <div class="song-desc-cont">
                    <h2 class="text-bold">Description</h2>
                    <div class="under-line-block mb-4"></div>
                    {!! $songDesc !!}
                </div>
            @endif
            <div>
                <h2 class="text-bold">Lyrics</h2>
                <div class="under-line-block mb-4"></div>
                <div class="mt-n4 pt-4">
                    {!! $lyrics !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            //Pie chart
            var pieData = new google.visualization.DataTable();
            pieData.addColumn('string', 'Word');
            pieData.addColumn('number', 'Repetition');
            pieData.addRows([
                ['Swear words', 1],
                ['Normal words', 3],
            ]);

            var pieOptions = {
                'width': 400,
                'height': 300,
                chartArea: {
                    left: 40,
                    top: 10,
                    width: 800,
                    height: 350
                },
                colors: ['#00d461', '#00B551'],
                is3D: true
            };

            var pieChart = new google.visualization.PieChart(document.getElementById('pieChart'));
            pieChart.draw(pieData, pieOptions);


            //Bar chart
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Word');
            data.addColumn('number', 'Repetition');

            data.addRows([
                [{v: "The", f: 'The'}, 1],
                [{v: "Fuck", f: 'Fuck'}, 2],
                [{v: "Test", f:'Test'}, 3],
            ]);

            var options = {
                hAxis: {
                    title: 'Word',
                },
                vAxis: {
                    title: 'Repetition'
                },
                legend: {
                    position: 'none'
                },
                colors: ['#00d461'],
            };

            var chart = new google.visualization.ColumnChart(
                document.getElementById('barChart'));

            chart.draw(data, options);
        }
    </script>
@endpush
