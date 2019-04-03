@extends('layouts.app')

@section('content')
    <div class="container">

        {{ Form::open([
            'method' => 'GET',
            'class' => 'mb-4 row'
        ]) }}
        <div class="col-12">
            <div class="input-group mb-3">
                {{ Form::text('q', Request::get('q'), ['placeholder' => 'Device name or serial number', 'class' => 'form-control']) }}
                <div class="input-group-append">
                    <button class="btn btn-primary has-icon" type="submit" id="button-addon2">
                        <i class="material-icons">search</i>
                    </button>
                </div>
            </div>
        </div>
        {{ Form::close() }}


        <div class="row d-flex justify-content-end mb-1 align-items-center">
            <div class="col-md-3 text-muted">
                Showing {{ $models->perPage() * ($models->currentPage() - 1) + 1 }}
                - {{ $models->count() + $models->perPage() * ($models->currentPage() - 1)  }} of {{ $models->total() }}
                results.
            </div>
            <div class="col-md-9 d-flex justify-content-end">
                {!! $models->appends(Request::all())->links() !!}
            </div>
        </div>

        @foreach($models as $model)
            <device sn="{{ $model->sn }}" url="{{ route('device.assets', ['sn' => $model->sn]) }}">
                <template #default="{ device, loading }">
                    <div class="card no-border box-shadow mb-5 card-primary">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-8">
                                    <a href="javascript:void(0)" class="text-white" data-toggle="modal"
                                       data-target="#device-detail" data-device="{{ json_encode($model) }}">
                                        <h2 class="card-title">{{ $model->name }}
                                            <small class=""> - {{ $model->sn }}</small>
                                        </h2>
                                    </a>
                                </div>
                                <div class="col-md-4 justify-content-end d-flex">
                                    <h5 class="mb-0 text-right text-success d-inline-flex" style="opacity: 1;"
                                        :class="{
                                    'text-danger': @json($model->storage_capacity > 90),
                                    'text-light-orange': @json($model->storage_capacity > 75 && $model->storage_capacity <= 90)
                                                }">
                                        {{ $model->storage_capacity }}%
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table mb-0 table-sm" style="font-size: 95%">
                                <colgroup>
                                    <col width="300">
                                    {{--<col width="100">--}}
                                    <col width="200">
                                    <col width="150">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    {{--<th>Type</th>--}}
                                    <th>Operating System</th>
                                    <th>Last 10 Backups</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-if="loading">
                                    <tr>
                                        <td colspan="4">Loading...</td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr v-for="asset in device.agents" :key="asset.name">
                                        <td>
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#asset-detail"
                                               class="text-primary" :data-asset="JSON.stringify(asset)"
                                               data-device-name="{{ $model->name }}" data-device-sn="{{ $model->sn }}">
                                                @{{ asset.name }}
                                            </a>
                                        </td>
                                        {{--<td>{{ $asset->type }}</td>--}}
                                        <td>@{{ asset.os }}</td>
                                        <td class="" style="vertical-align: middle">
                                            <div class="backup-status-indicators">
                                                    <span
                                                            v-for="backup in asset.backups"
                                                            :key="backup.date.date"
                                                            class="indicator"
                                                            :class="backup.model ? backup.model.summary_status || 'empty' : 'empty'"
                                                            data-toggle="popover"
                                                            data-trigger="hover click"
                                                            data-placement="bottom"
                                                            data-html="true"
                                                            :errors="backup.model ? backup.model.error_messages: 'No backups found on this date'"
                                                            :data-content="
                                                            '<div>' +  $moment(backup.model ? backup.model.timestamp * 1000 : backup.date.date).format('ddd, DD MMM  YYYY, h:mm:ss a Z') + '</div>' +
                                                            '<div class=\'mt-3\'>' + (backup.model ? Array.isArray(backup.model.error_messages) ? backup.model.error_messages.join('; ') : backup.model.error_messages : 'No backups found on this date') + '</div>'
                                                            "
                                                    >

                                            </span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="device.agents && !device.agents.length">
                                        <td colspan="4">No agents found.</td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>
            </device>
        @endforeach

        <div class="modal fade" id="device-detail">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title"></h5>
                        <button type="button" class="close" data-dismiss="modal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-sm">
                            <tr>
                                <th class="" width="40%">Name</th>
                                <td class="" id="name"></td>
                            </tr>
                            <tr>
                                <th class="">Serial Number</th>
                                <td class="" id="sn"></td>
                            </tr>
                            <tr>
                                <th class="">Model</th>
                                <td class="" id="model"></td>
                            </tr>
                            <tr>
                                <th class="">Last Seen</th>
                                <td class="" id="last_seen"></td>
                            </tr>
                            <tr>
                                <th class="">Service Plan</th>
                                <td class="" id="service_plan"></td>
                            </tr>
                            <tr>
                                <th class="">Service Period</th>
                                <td class="" id="service_period"></td>
                            </tr>
                            <tr>
                                <th class="">Registration Date</th>
                                <td class="" id="registration_date"></td>
                            </tr>
                            <tr>
                                <th class="">Warranty Expire</th>
                                <td class="" id="warranty_expire"></td>
                            </tr>
                            <tr>
                                <th class="">Local Storage Used</th>
                                <td class="" id="ls_used"></td>
                            </tr>
                            <tr>
                                <th class="">Local Storage Available</th>
                                <td class="" id="ls_available"></td>
                            </tr>
                            <tr>
                                <th class="">Internal IP</th>
                                <td class="" id="internal_ip"></td>
                            </tr>
                            <tr>
                                <th class="">Synchronized Time</th>
                                <td class="" id="synced_time"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="asset-detail">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title"></h5>
                        <button type="button" class="close" data-dismiss="modal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-sm">
                            <tr>
                                <th class="" width="40%">Name</th>
                                <td class="" id="name"></td>
                            </tr>
                            <tr>
                                <th class="">Volume</th>
                                <td class="" id="volume"></td>
                            </tr>
                            <tr>
                                <th class="">Type</th>
                                <td class="" id="type"></td>
                            </tr>
                            <tr>
                                <th class="">Device</th>
                                <td class="" id="device"></td>
                            </tr>
                            <tr>
                                <th class="">Local IP</th>
                                <td class="" id="local_ip"></td>
                            </tr>
                            <tr>
                                <th class="">Operating System</th>
                                <td class="" id="os"></td>
                            </tr>
                            <tr>
                                <th class="">Protected Volume</th>
                                <td class="" id="protected_volume"></td>
                            </tr>
                            <tr>
                                <th class="">Unprotected Volume</th>
                                <td class="" id="unprotected_volume"></td>
                            </tr>
                            <tr>
                                <th class="">Agent Version</th>
                                <td class="" id="agent_version"></td>
                            </tr>
                            <tr>
                                <th class="">FQDN</th>
                                <td class="" id="fqdn"></td>
                            </tr>
                            <tr>
                                <th class="">Is Archived</th>
                                <td class="" id="is_archived"></td>
                            </tr>
                            <tr>
                                <th class="">Is Paused</th>
                                <td class="" id="is_paused"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            {!! $models->appends(Request::all())->links() !!}
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#device-detail').on('show.bs.modal', function (e) {
                var button = $(e.relatedTarget);
                var modal = $(this);

                var device = button.data('device');

                modal.find('#title').text('Device ' + device.name);
                modal.find('#name').text(device.name);
                modal.find('#sn').text(device.sn);
                modal.find('#model').text(device.model);
                modal.find('#last_seen').text((new Date(device.last_seen_ts * 1000)).toUTCString());
                modal.find('#service_plan').text(device.service_plan);
                modal.find('#service_period').text(new Date(device.service_period_ts * 1000).toUTCString());
                modal.find('#registration_date').text(new Date(device.registration_date_ts * 1000).toUTCString());
                modal.find('#warranty_expire').text(new Date(device.warranty_expire_ts * 1000).toUTCString());
                modal.find('#ls_used').text(device.ls_used_size + ' ' + device.ls_used_unit);
                modal.find('#ls_available').text(device.ls_available_size + ' ' + device.ls_available_unit);
                modal.find('#internal_ip').text(device.internal_ip);
                modal.find('#synced_time').text(device.synced_at);
            });

            $('#asset-detail').on('show.bs.modal', function (e) {
                var button = $(e.relatedTarget);
                var modal = $(this);

                var asset = button.data('asset');
                var device = {
                    name: button.data('device-name'),
                    sn: button.data('device-sn')
                };

                modal.find('#title').text('Asset ' + asset.name);
                modal.find('#name').text(asset.name);
                modal.find('#volume').text(asset.volume);
                modal.find('#type').text(asset.type);
                modal.find('#local_ip').text(asset.local_ip);
                modal.find('#os').text(asset.os);
                modal.find('#protected_volume').text(asset.protected_volume_count);
                modal.find('#unprotected_volume').text(asset.unprotected_volume_count);
                modal.find('#agent_version').text(asset.agent_version);
                modal.find('#is_paused').html(checkMark(asset.is_paused));
                modal.find('#is_archived').html(checkMark(asset.is_archived));
                modal.find('#fqdn').text(asset.fqdn);
                modal.find('#device').text(device.name + ' - ' + device.sn);
            });

            function checkMark(value = 0) {
                var yes = '<i class="material-icons">check</i>';
                var no = '<i class="material-icons">close</i>';

                return value ? yes : no;
            }
        })
    </script>
@endpush