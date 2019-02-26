<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Question Answering Bot</title>

    <link rel="stylesheet" type="text/css" media="screen" href="{{ URL::asset('css/app.css') }}" />
    <link rel="stylesheet" type="text/css" media="screen" href="{{ URL::asset('css/main.css') }}" />

    <script src="{{ URL::asset('js/app.js') }}"></script>
    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
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
    <main class="main">
        <h1 class="text-center">Question Answering Bot</h1>
        <div class="question-form">
            <form class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="question" class="col-xs-12 col-md-1 control-label">Question:</label>
                    <div class="col-xs-12 col-md-11">
                        <input id="question" type="text" class="form-control" name="question" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="answer1" class="col-xs-12 col-md-1 control-label">Answer 1:</label>
                    <div class="col-xs-12 col-md-5">
                        <input id="answer1" type="text" class="form-control" name="answer1" required autofocus>
                    </div>
                    <label for="answer2" class="col-xs-12 col-md-1 control-label">Answer 2:</label>
                    <div class="col-xs-12 col-md-5">
                        <input id="answer2" type="text" class="form-control" name="answer2" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="answer3" class="col-xs-12 col-md-1 control-label">Answer 3:</label>
                    <div class="col-xs-12 col-md-5">
                        <input id="answer3" type="text" class="form-control" name="answer3" required autofocus>
                    </div>
                    <label for="answer4" class="col-xs-12 col-md-1 control-label">Answer 4:</label>
                    <div class="col-xs-12 col-md-5">
                        <input id="answer4" type="text" class="form-control" name="answer4" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-12 col-md-2 control-label">Your Choice</label>
                    <div class="col-xs-12 col-md-10">
                        <label class="radio-inline">
                            <input type="radio" name="user-choice" checked>
                            Answer 1
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="user-choice">
                            Answer 2
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="user-choice">
                            Answer 3
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="user-choice">
                            Answer 4
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12 text-center">
                        <button type="button" class="btn btn-primary" id="submitQuestion">
                            Try
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="result">

        </div>
    </main>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            const csrf_token = $('meta[name="csrf-token"]').attr('content');

            $('#submitQuestion').on('click', function (e) {
                e.preventDefault();
                let answerRequest = {
                    question: $('input[name="question"]').val(),
                    answer1: $('input[name="answer1"]').val(),
                    answer2: $('input[name="answer2"]').val(),
                    answer3: $('input[name="answer3"]').val(),
                    answer4: $('input[name="answer4"]').val()
                };

                showLoading();
                axios({
                    method: 'post',
                    url: 'api/answer',
                    data: answerRequest,
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    }
                }).then(function (response) {
                    hideLoading();
                    $('.result').append('<div>' + response.data + '</div>');
                    console.log(response.data);
                }).catch(function (error) {
                    console.log(error);
                });
            });
            function showLoading() {
                $('#loading').removeClass('hidden');
                $('.main').addClass('main-blur');
            }
            function hideLoading() {
                $('#loading').addClass('hidden');
                $('.main').removeClass('main-blur');
            }
        });
    </script>
</body>
</html>
