@extends('dashboard.dashboard_layout');

@section('content')
    <div>
        <h4>لیست سوالات</h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-info">
                    <h4 class="card-title ">لیست سوالات</h4>
                    <p class="card-category">سوالات انتخاب شده از مسابقه عصر جدید</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <th style="width: 52%" colspan="2" class="text-center">
                                    <b>سوال</b>
                                </th>
                                <th colspan="4" class="text-center">
                                    <b>گزینه‌ها</b>
                                </th>
                            </thead>
                            <tbody>
                                @foreach($questions as $index=>$question)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td style="width: 52%">
                                            <a href="{{ url('dashboard/search/questions/'.$question->id) }}">
                                                {{ $question->text .' ؟' }}
                                            </a>
                                        </td>
                                        @foreach($question->choices as $choice)
                                            <td style="width: 12%" class="{{ $choice->is_answer ? 'text-success' : ''}}">
                                                @if($choice->is_answer)
                                                    <b>{{ $choice->text }}</b>
                                                @else
                                                    {{ $choice->text }}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection