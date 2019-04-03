@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-left mb-3">
            <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm has-icon">
                <i class="material-icons">add</i>
                Add New User
            </a>
        </div>
        <div class="card box-shadow-sm no-border">
            <div class="card-table table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        {{--<td>ID</td>--}}
                        <th>Title</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Register date</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($models as $model)
                        <tr>
                            {{--<td>{{ $model->id }}</td>--}}
                            <td>{{ $model->title ?? '-' }}</td>
                            <td>{{ $model->name }}</td>
                            <td>{{ $model->email }}</td>
                            <td>{{ $model->created_at ? $model->created_at->toRssString() : '-' }}</td>
                            <td class="action-buttons text-right">
                                <a href="{{ route('user.edit', ['id' => $model->id]) }}" class="btn btn-sm"
                                   data-toggle="tooltip" data-title="Update"><i class="material-icons">edit</i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @if($models->lastPage() > 1)
                <div class="card-footer justify-content-end d-flex card-footer-pagination">
                    {!! $models->links() !!}
                </div>
            @endif
        </div>
    </div>
@endsection