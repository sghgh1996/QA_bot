@extends('dashboard.dashboard_layout');

@section('content')
    <div id="loading" class="hidden">
        <div class="loading-container">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                <g transform="translate(50,50)">
                    <g transform="scale(0.8)">
                        <g transform="translate(-50,-50)">
                            <g transform="translate(-17.7419 -15.4839)">
                                <animateTransform attributeName="transform" type="translate" calcMode="linear" values="-20 -20;20 -20;0 20;-20 -20" keyTimes="0;0.33;0.66;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform>
                                <path d="M44.19,26.158c-4.817,0-9.345,1.876-12.751,5.282c-3.406,3.406-5.282,7.934-5.282,12.751 c0,4.817,1.876,9.345,5.282,12.751c3.406,3.406,7.934,5.282,12.751,5.282s9.345-1.876,12.751-5.282 c3.406-3.406,5.282-7.934,5.282-12.751c0-4.817-1.876-9.345-5.282-12.751C53.536,28.033,49.007,26.158,44.19,26.158z" fill="#5bc0de"></path>
                                <path d="M78.712,72.492L67.593,61.373l-3.475-3.475c1.621-2.352,2.779-4.926,3.475-7.596c1.044-4.008,1.044-8.23,0-12.238 c-1.048-4.022-3.146-7.827-6.297-10.979C56.572,22.362,50.381,20,44.19,20C38,20,31.809,22.362,27.085,27.085 c-9.447,9.447-9.447,24.763,0,34.21C31.809,66.019,38,68.381,44.19,68.381c4.798,0,9.593-1.425,13.708-4.262l9.695,9.695 l4.899,4.899C73.351,79.571,74.476,80,75.602,80s2.251-0.429,3.11-1.288C80.429,76.994,80.429,74.209,78.712,72.492z M56.942,56.942 c-3.406,3.406-7.934,5.282-12.751,5.282s-9.345-1.876-12.751-5.282c-3.406-3.406-5.282-7.934-5.282-12.751 c0-4.817,1.876-9.345,5.282-12.751c3.406-3.406,7.934-5.282,12.751-5.282c4.817,0,9.345,1.876,12.751,5.282 c3.406,3.406,5.282,7.934,5.282,12.751C62.223,49.007,60.347,53.536,56.942,56.942z" fill="#337ab7"></path>
                            </g>
                        </g>
                    </g>
                </g>
            </svg>
        </div>
    </div>
    <div class="card" id="main-card">
        <div class="card-header card-header-info">
            <h4>تست کردن سیستم پرسش و پاسخ چند گزینه‌ای</h4>
        </div>
        <div class="card-body">
            <div class="row mt-4">
                <div class="bmd-form-group col-md-6">
                    <input placeholder="سوال" type="text" class="form-control" name="question" required>
                </div>
            </div>
            <div class="row mt-5">
                @php
                    $choices = array(
                        '1' => 'اول',
                        '2' => 'دوم',
                        '3' => 'سوم',
                        '4' => 'چهارم',
                    )    
                @endphp
                @foreach ($choices as $index => $choice)
                    <div class="bmd-form-group col-md-2">
                        <input placeholder="گزینه {{ $choice }}" type="text" class="form-control" name="choice{{ $index }}" required>
                    </div>
                @endforeach   
            </div>
            <div class="row mt-5 pl-5">
                <div class="col-md-2">
                    انتخاب الگوریتم‌ها
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label><input type="checkbox" name="snippet" value="snippet" checked>&nbsp;&nbsp;Snippet</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label><input type="checkbox" name="count_normal" value="count_normal">&nbsp;&nbsp;Count Normal</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label><input type="checkbox" name="count_quote" value="count_quote">&nbsp;&nbsp;Count with Quote</label>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" id="submit-question">مشاهده نتایج</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ URL::asset('material-dashboard-assets/js/plugins/chartist.min.js') }}" type="text/javascript"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            const csrf_token = $('meta[name="csrf-token"]').attr('content');
            const submitQuestionBtn = $('#submit-question');
            const mainDiv = $('#main-card');
            const loading = $('#loading');

            submitQuestionBtn.on('click', function (e) {
                e.preventDefault();
                let answerRequest = {
                    question: $('input[name="question"]').val(),
                    choice1: $('input[name="choice1"]').val(),
                    choice2: $('input[name="choice2"]').val(),
                    choice3: $('input[name="choice3"]').val(),
                    choice4: $('input[name="choice4"]').val(),
                    algorithms: {
                        snippet: $('input[name="snippet"]').is(":checked"),
                        count_normal: $('input[name="count_normal"]').is(":checked"),
                        count_quote: $('input[name="count_quote"]').is(":checked")
                    }
                };
                showLoading();
                axios({
                    method: 'post',
                    baseURL: '/',
                    url: 'api/answer',
                    data: answerRequest
                }).then(function (response) {
                    hideLoading();
                    window.location.href = '/dashboard/search/questions/question/' + response.data.question_id;
                }).catch(function () {
                    alert('مشکلی رخ داده است.')
                });
            });
            function showLoading() {
                loading.removeClass('hidden');
                submitQuestionBtn.addClass('disabeld');
                submitQuestionBtn.attr('disabled', true);
                mainDiv.addClass('main-blur');
            }
            function hideLoading() {
                loading.addClass('hidden');
                submitQuestionBtn.removeAttr('disabled');
                submitQuestionBtn.removeClass('disabled');
                mainDiv.removeClass('main-blur');
            }
        });
    </script>
@endpush
