@extends('layouts.app')

@section('content')
    <div class="container">
        {{ Form::model($model, [
            'route' => $model->exists ? ['company.update', 'id' => $model->id] : ['company.store'],
            'method' => $model->exists ? 'PUT' : 'POST'
        ]) }}
        <h3 class="mb-4">{{ $model->exists ? 'Update Company' : 'Add New Company' }}</h3>

        <div class="form-group required">
            {{ Form::label('name', 'Company Name') }}
            {{ Form::text('name', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('devices', 'Select Devices') }}
            <multi-select
                    name="devices[]"
                    :items="{{ $devices->toJson() }}"
                    :height="300"
                    value-attribute="sn"
                    search-attribute="name"
                    sort-by="name"
                    sort-direction="asc"
                    class="box-shadow"
                    search-placeholder="Search device...."
                    :value="{{ $model->devices->map(function($item) { return $item->sn; }) }}"
            >
                <template #item="{ item }">
                    @{{item.name}}
                </template>
            </multi-select>
        </div>

        <div class="">
            <button type="submit" class="btn btn-primary">
                {{ $model->exists ? 'Update Company' : 'Add New Company' }}
            </button>
        </div>
        {{ Form::close() }}
    </div>
@endsection
