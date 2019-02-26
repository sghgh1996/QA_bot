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
                    <label for="question" class="col-xs-12 col-md-2 control-label">Question:</label>
                    <div class="col-xs-12 col-md-10">
                        <input id="question" type="text" class="form-control" name="question" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="choice1" class="col-xs-12 col-md-2 control-label">Choice 1:</label>
                    <div class="col-xs-12 col-md-4">
                        <input id="choice1" type="text" class="form-control" name="choice1" required autofocus>
                    </div>
                    <label for="choice2" class="col-xs-12 col-md-2 control-label">Choice 2:</label>
                    <div class="col-xs-12 col-md-4">
                        <input id="choice2" type="text" class="form-control" name="choice2" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="choice3" class="col-xs-12 col-md-2 control-label">Choice 3:</label>
                    <div class="col-xs-12 col-md-4">
                        <input id="choice3" type="text" class="form-control" name="choice3" required autofocus>
                    </div>
                    <label for="choice4" class="col-xs-12 col-md-2 control-label">Choice 4:</label>
                    <div class="col-xs-12 col-md-4">
                        <input id="choice4" type="text" class="form-control" name="choice4" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12 col-md-6 col-md-offset-1">
                        <label class="control-label">Your Choice:</label>
                        <select class="form-control" id="user_choice">
                            <option value="none">No Choice</option>
                            <option value="choice1">choice 1</option>
                            <option value="choice2">choice 2</option>
                            <option value="choice3">choice 3</option>
                            <option value="choice4">choice 4</option>
                        </select>
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
                    choice1: $('input[name="choice1"]').val(),
                    choice2: $('input[name="choice2"]').val(),
                    choice3: $('input[name="choice3"]').val(),
                    choice4: $('input[name="choice4"]').val(),
                    user_choice: $('select#user_choice').val()
                };
                const resultDiv = $('.result');
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
                    resultDiv.empty();
                    resultDiv.append(
                        '<div>' +
                        '<h3>Estimated Answer:   ' +
                        response.data.result.answer +
                        '</h3>' +
                        '</div>'
                    );
                    let choices = '';
                    Object.keys(response.data.result.choices).forEach(function (key) {
                        choices += '<tr>' +
                            '<td>' + key +
                            '</td>' +
                            '<td>' +
                            response.data.result.choices[key] +
                            '</td>' +
                            '</tr>';
                    });
                    resultDiv.append(
                        '<div class="table-responsive col-xs-12 col-md-6">' +
                        '<table class="table table-striped">' +
                        '<thead>' +
                        '<th>Answer</th>' +
                        '<th>Rank</th>' +
                        '</thead>' +
                        '<tbody>' + choices +
                        '</tbody>' +
                        '</table>' +
                        '</div>'
                    );

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
