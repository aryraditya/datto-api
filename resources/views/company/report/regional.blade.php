<table>
    <tr>
        <td>Backup Regional Report</td>
    </tr>
    <tr>
        <td style="font-weight: bold;"><b>{{ $model->name }}</b></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>PROPERTY NAME</td>
        <td>DEVICE</td>
        <td>SN</td>
        <td>USAGE</td>
        <td>TREND</td>
        <td>OFFSITE STORAGE USED</td>
        <td>PROTECTED</td>
        <td>SOLARWINDS OFFSITE</td>
        <td>OPEN TICKET</td>
    </tr>
    @foreach($model->devices as $device)
        <tr>
            <td></td>
            <td>{{ $device->name }}</td>
            <td>{{ $device->sn }}</td>
            <td>{{ $device->storage_capacity }}%</td>
            <td>{{ $device->storage_trend }}</td>
            <td>{{ $device->humanize_storage_used }}</td>
            <td>{{ $device->agents->count() }}</td>
            <td></td>
            <td>{{ $device->active_ticket }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="6">
                {{ $device->agents->implode('name', ', ') }}
            </td>
            <td colspan="2">

            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
    @endforeach
</table>