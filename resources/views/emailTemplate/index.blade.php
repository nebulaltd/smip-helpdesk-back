@extends('dashboard.master')
@inject('permission', 'App\Http\Controllers\PermissionController')
@section('style')
    
@stop
@section('title', __('lang.email_template'))

@section('main-section')
    
    <!-- Main content -->
    <div class="container-fluid">
        @if($permission->manageEmailTemplate() == 1)
        <h4 class="page-title">{{ __('lang.email_template') }}</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    @include('includes.flash')
                    <div class="card-body table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('lang.subject') }}</th>
                                <th>{{ __('lang.custom_content') }}</th>
                                <th>{{ __('lang.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($templates->isEmpty())
                                <p>{{ __('lang.currently_no_knowledge_base_found') }}</p>
                            @else
                                @foreach($templates as $kb)
                            <tr>
                                <td>{{ $loop->index +1 }}</td>
                                <td>{{ $kb->template_subject }}</td>
                                <td>
                                    @if($kb->custom_content)
                                        {{ __('lang.yes') }}
                                    @else
                                        {{ __('lang.no') }}
                                    @endif
                                </td>
                                <td class="">
                                    <a href="{{ route('email-template.edit', $kb->id) }}" class="badge bg-primary text-white" title="Edit"><i class="fa fa-pencil"></i> </a>
                                    <form id="delete-form-{{ $kb->id }}" method="post" action="{{ route('email-template.destroy', $kb->id) }}" class="d-none">
                                        {{csrf_field()}}
                                        {{ method_field('DELETE') }}
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        @else
            <div class="callout callout-warning">
                <h4>{{ __('lang.access_denied') }}</h4>

                <p>{{ __("lang.don't_have_permission") }}</p>
            </div>
        @endif
    </div>
    <!-- /.content -->

@endsection