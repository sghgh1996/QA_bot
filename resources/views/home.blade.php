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
                    <div class="col-xs-12 text-center">
                        <button type="submit" class="btn btn-primary" id="submitQuestion">
                            Try
                        </button>
                    </div>
                </div>
            </form>
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

                axios({
                    method: 'post',
                    url: 'api/answer',
                    data: answerRequest,
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    }
                }).then(function (response) {
                    console.log(response.data);
                }).catch(function (error) {
                    console.log(error);
                });
            });
        });
    </script>
</body>
</html>