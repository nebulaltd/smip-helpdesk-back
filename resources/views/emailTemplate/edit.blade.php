@extends('dashboard.master')
@inject('permission', 'App\Http\Controllers\PermissionController')
@section('title', __('lang.update_email_template'))
@section('style')
    
@stop

@section('main-section')
    <!-- Main content -->
    <div class="container-fluid">
        @if($permission->manageEmailTemplate() == 1)
        <h4 class="page-title">{{ __('lang.email_template') }} #{{ $template->template_type }}
            <a href="{{ route('email-template.index') }}" class="btn btn-primary btn-md pull-right">{{ __('lang.back') }}</a>
        </h4>
        <div class="row" id="eTemp" data-id="{{ $template->id }}">
            <div class="col-md-12">
                <div class="card">
                @include('includes.flash')
                    <!-- form start -->
                    <form role="form" class="" method="POST" action="{{ route('email-template.update',$template->id) }}">
                        {!! csrf_field() !!}
                        {{ method_field('put')}}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">{{ __('lang.title') }}</label>
                                <input id="title" type="text" class="form-control{{ $errors->has('template_subject') ? ' is-invalid' : '' }}" name="template_subject" value="{{ $template->template_subject }}" placeholder="{{ __('lang.enter_template_subject') }}" required>

                                @if ($errors->has('template_subject'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('template_subject') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group pb-1">
                                <label for="title">{{ __('lang.content') }}</label>
                                <div class="pb-1">
                                    <textarea class="textarea my-editor w-100" name="custom_content" placeholder="{{ __('lang.place_of_content') }}" required>{{ clean($template->custom_content ?? $template->default_content) }}</textarea>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block">{{ __('lang.update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @else
            <div class="callout callout-warning">
                <h4>{{ __('lang.access_denied') }}</h4>

                <p>{{ __("lang.don't_have_permission") }}</p>
            </div>
        @endif
    </div>

@endsection

@section('js')
    <script src="{{asset('tinymce/tinymce.min.js')}}"></script>
    <script src="{{asset('tinymce/script.js')}}"></script>
@stop
