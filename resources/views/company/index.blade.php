@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-left mb-3">
            <a href="{{ route('company.create') }}" class="btn btn-primary has-icon btn-sm">
                <i class="material-icons">add</i>
                Add New Company
            </a>
        </div>
        <div class="card no-border box-shadow">
            <div class="card-table table-responsive-lg">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th width="125">Devices</th>
                        <th width="300"></th>
                    </tr>
                    </thead>
                    @foreach($models as $model)
                        <tr>
                            <td>{{ $model->name }}</td>
                            <td>{{ $model->devices()->count() }}</td>
                            <td class="action-buttons text-right">
                                {{--<span class="dropdown">--}}
                                {{--<button class="btn" data-toggle="dropdown"><i class="material-icons">print</i> Report</button>--}}
                                {{--<ul class="dropdown-menu">--}}
                                {{--<li class="dropdown-item">--}}
                                {{--<a href="{{ route('company.report.regional', ['id' => $model->id]) }}" class="">--}}
                                {{--Regional Report--}}
                                {{--</a>--}}
                                {{--</li>--}}
                                {{--<li class="dropdown-item">--}}
                                {{--<a href="{{ route('company.report.asset', ['id' => $model->id]) }}" class="">--}}
                                {{--Asset Report--}}
                                {{--</a>--}}
                                {{--</li>--}}
                                {{--<li class="dropdown-item">--}}
                                {{--<a href="{{ route('company.report.storage', ['id' => $model->id]) }}" class="">--}}
                                {{--Storage Report--}}
                                {{--</a>--}}
                                {{--</li>--}}
                                {{--</ul>--}}
                                {{--</span>--}}

                                <a href="{{ route('company.report', ['id' => $model->id]) }}" class="btn">
                                    <i class="material-icons">print</i> Report
                                </a>
                                <a href="{{ route('company.edit', ['id' => $model->id]) }}" class="btn"
                                   data-toggle="tooltip" data-original-title="Update">
                                    <i class="material-icons">edit</i>
                                </a>
                                <a href="{{ route('company.destroy', ['id' => $model->id]) }}" class="btn btn-danger"
                                   data-toggle="tooltip" data-original-title="Delete">
                                    <i class="material-icons">delete</i>
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    @if(!$models->count())
                        <tr>
                            <td colspan="3">No company found here.</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection