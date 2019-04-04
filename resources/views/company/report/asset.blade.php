<table>
    <tr>
        <td>Asset Report</td>
    </tr>
    <tr>
        <td style="font-weight: bold;"><b>{{ $model->name }}</b></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    @foreach($model->devices as $device)
        <tr>
            <td>Device</td>
            <td>{{ $device->name }}</td>
        </tr>
        <tr>
            <td rowspan="2">AGENT NAME</td>
            <td rowspan="2">OS</td>
            <td colspan="10" align="center">Last 10 days backup</td>
        </tr>
        <tr>
            @for($i = 1; $i<=10; $i++)
                <td>{{ now()->subDay($i)->format('d/m/y') }}</td>
            @endfor
        </tr>
        @foreach($device->agents as $agent)
            @php
                $backups = $agent->getLastDailyBackups(10);
            @endphp
            <tr>
                <td>{{ $agent->name }}</td>
                <td>{{ $agent->os }}</td>
                @foreach($backups as $backup)
                    <td>
                        @if($backup['model'])
                            @if($backup['model']->summary_status === 'success')
                                <a href="{{ $backup['model']->screenshot_verify_image ?? '#' }}">success</a>
                            @elseif($backup['model']->summary_status === 'half')
                                NO SV
                            @else
                                failed
                            @endif
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
        <tr>
            <td></td>
        </tr>
    @endforeach
</table>