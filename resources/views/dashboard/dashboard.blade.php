@extends('dashboard.dashboard_layout');

@section('content')
    <div>
        <h4>روش‌های مبتنی بر موتور جستجو</h4>
    </div>
    <div class="row p-3">
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">storage</i>
                    </div>
                    <p class="card-category">تعداد سوالات تست</p>
                    <h3 class="card-title">50
                        <small>سوال</small>
                    </h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">tv</i>
                        سوالات مسابقه&nbsp;<b>برنده باش</b>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">format_shapes</i>
                    </div>
                    <p class="card-category">تعداد الگوریتم ها</p>
                    <h3 class="card-title">3</h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">spellcheck</i> بهترین الگوریتم:&nbsp;<b>snippet</b>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">check</i>
                    </div>
                    <p class="card-category">دقت میانگین</p>
                    <h3 class="card-title">57%</h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">trending_up</i> بیشترین دقت: 76%
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr /> 
    <div>
        <h4>روش‌های مبتنی بر شبکه عصبی</h4>
    </div>
    <div>
        ... به زودی
    </div>
@endsection
