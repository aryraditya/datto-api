@extends('layouts.app')

@section('content')
    <div class="container">
        {{ Form::model($model, [
            'route' => $model->exists ? ['user.update', 'id' => $model->id] : ['user.store'],
            'method' => $model->exists ? 'PUT' : 'POST'
        ]) }}
        <h3 class="mb-4">{{ $model->exists ? 'Update User' : 'New User' }}</h3>

        @component('@components.alert')
        @endcomponent

        <div class="row">
            <div class="col-md-2">
                <div class="form-group required">
                    {{ Form::label('title', 'Title') }}
                    {{ Form::text('title', null, ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group required">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', null, ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group required">
                    {{ Form::label('email', 'Email Address') }}
                    {{ Form::text('email', null, ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" :class="{ 'required' : @json(!$model->exists) }">
                    {{ Form::label('password', 'Password') }}
                    {{ Form::password('password', ['class' => 'form-control']) }}
                    @if($model->exists)
                        <div class="hint small">Keep blank if you won't to change the password</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="">
            <button class="btn btn-success">{{ $model->exists ? 'Update User' : 'Create User' }}</button>
        </div>
        {{ Form::close() }}
    </div>
@endsection