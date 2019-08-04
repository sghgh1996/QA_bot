@extends('dashboard.dashboard_layout')

@section('style')
    <link href="{{ URL::asset('material-dashboard-assets/css/chartist.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div class="mb-4">
        <h4>تحلیل سوال:  <b>{{ $question->text . ' ؟' }}</b></h4>
    </div>
    <div class="row mb-4">
        <div class="col-xs-12 col-md-1">
            گزینه‌ها:
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="row">
                @foreach($question->choices as $choice)
                    <div class="col-xs-12 col-md-3 text-center {{ $choice->is_answer ? 'text-success' : '' }}">
                        @if($choice->is_answer)
                            <b>{{ $choice->text }}</b>
                        @else
                            {{ $choice->text }}
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @foreach ($question->algorithms as $algorithm)
        <div class="row p-1 mb-2">
            <div class="col-xs-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">الگوریتم: <b>{{ $algorithm->name }}</b></h4>
                    </div>
                    <div class="card-body">
                        @if($algorithm->predicted)
                            <div class="row">
                                <div class="col-xs-12 col-md-5">
                                    <h3>
                                        پیش بینی: <b class="{{ $algorithm->predicted_id === $question->answer_id ? 'text-success' : 'text-danger' }}">{{ $algorithm->predicted_text }}</b>
                                    </h3>
                                    <div>
                                        <table class="table">
                                            <thead>
                                                <th>گزینه</th>
                                                <th>تعداد</th>
                                                <th>درصد</th>
                                            </thead>
                                            <tbody>
                                                @foreach($algorithm->choices as $choice)
                                                    <tr>
                                                        <td class="{{ $choice->is_answer ? 'text-success' : '' }}">{{ $choice->text }}</td>
                                                        <td>{{ $choice->value }}</td>
                                                        <td>{{ $choice->normalized_value }}</td>
                                                    </tr>
                                                @endforeach 
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-7">
                                    <div class="ct-chart text-center pt-5" id="chart{{ $algorithm->id }}"></div>
                                </div>
                            </div>
                        @else
                            <div>
                                <h3 class="text-danger">پیش بینی انجام نشده است.</h3>
                            </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script src="{{ URL::asset('material-dashboard-assets/js/plugins/chartist.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var question = <?php echo json_encode($question); ?>;
        function createChart (algorithm) {
            let data = {
                labels: algorithm.choices.map(choice => choice.text),
                series: [
                    algorithm.choices.map(choice => choice.normalized_value)
                ]
            };

            let options = {
                seriesBarDistance: 10,
                height: '400px',
                width: '500px'
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

            let chart = new Chartist.Bar('#chart' + algorithm.id, data, options, responsiveOptions);
        }

        question.algorithms.forEach(algorithm => {
            if(algorithm.predicted) {
                createChart(algorithm);
            }
        });
    </script>
@endpush