@extends('dashboard.dashboard_layout')

@section('style')
    <link href="{{ URL::asset('material-dashboard-assets/css/chartist.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <table class="table">
                <thead>
                    <th>الگوریتم</th>
                    <th>دقت</th>
                </thead>
                <tbody>
                    @foreach($algorithms as $algorithm)
                        <tr>
                            <td>{{ $algorithm->name }}</td>
                            <td>{{ $algorithm->accuracy }}</td>
                        </tr>
                    @endforeach 
                </tbody>
            </table>
        </div>
        <div class="col-md-8">
            <div class="ct-chart text-center" id="chart"></div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ URL::asset('material-dashboard-assets/js/plugins/chartist.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var algorithms = <?php echo json_encode($algorithms); ?>;

        let data = {
            labels: algorithms.map(algorithm => algorithm.name),
            series: [
                algorithms.map(algorithm => algorithm.accuracy)
            ]
        };

        let options = {
            seriesBarDistance: 10,
            height: '450px',
            width: '600px'
        };

        let responsiveOptions = [
            ['screen and (min-width: 641px) and (max-width: 1024px)', {
                seriesBarDistance: 10,
                axisX: {
                    labelInterpolationFnc: function (value) {
                        return value;
                    }
                }
            }],
            ['screen and (max-width: 640px)', {
                seriesBarDistance: 5,
                axisX: {
                    labelInterpolationFnc: function (value) {
                        return value[0];
                    }
                }
            }]
        ];

        let chart = new Chartist.Bar('#chart', data, options, responsiveOptions);
    </script>
@endpush
