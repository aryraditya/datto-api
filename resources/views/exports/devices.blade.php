<table>
    <thead>
    <tr>
        <th>Serial Number</th>
        <th>Name</th>
        <th>Model</th>
        <th>Last Seen</th>
        <th>Hidden</th>
        <th>Active Tickets</th>
        <th>Service Plan</th>
        <th>Registered</th>
        <th>Service Periode</th>
        <th>Warranty Expire</th>
        <th>Local Storage Used</th>
        <th>Local Storage Available</th>
        <th>Offsite Storage Used</th>
        <th>Internal IP</th>
        <th>Reseller Name</th>
        <th>Client Name</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data->items as $item)
        <tr>
            <td> {{ $item->serialNumber }} </td>
            <td> {{ $item->name }} </td>
            <td> {{ $item->model }} </td>
            <td> {{ $item->lastSeenDate }} </td>
            <td> {{ $item->hidden }} </td>
            <td> {{ $item->activeTickets }} </td>
            <td> {{ $item->servicePlan }} </td>
            <td> {{ $item->registrationDate }} </td>
            <td> {{ $item->servicePeriod }} </td>
            <td> {{ $item->warrantyExpire }} </td>
            <td> {{ $item->localStorageUsed->size }} {{ $item->localStorageUsed->units }} </td>
            <td> {{ $item->localStorageAvailable->size }} {{ $item->localStorageAvailable->units }}  </td>
            <td> {{ $item->offsiteStorageUsed->size }} {{ $item->offsiteStorageUsed->units }} </td>
            <td> {{ $item->internalIP }}</td>
            <td> {{ $item->resellerCompanyName }}</td>
            <td> {{ $item->clientCompanyName }} </td>
        </tr>
    @endforeach
    </tbody>
</table>