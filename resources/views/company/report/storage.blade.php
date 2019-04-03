<table>
    <tr>
        <td>PROPERTY NAME</td>
        <td>DEVICE</td>
        <td>SN</td>
        <td>USAGE</td>
        <td>TREND</td>
        <td>OFFSITE STORAGE USED</td>
        <td>PROTECTED</td>
    </tr>
    @foreach($devices as $index => $group)
        <tr>
            <td>> {{ $index }} </td>
        </tr>
        @foreach($group as $device)
            <tr>
                <td></td>
                <td>{{ $device->name }}</td>
                <td>{{ $device->sn }}</td>
                <td>{{ $device->storage_capacity }}%</td>
                <td></td>
                <td>{{ $device->humanize_storage_used }}</td>
                <td>{{ $device->agents->count() }}</td>
            </tr>
        @endforeach

    @endforeach
</table>