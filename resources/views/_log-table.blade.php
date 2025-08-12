<table class="table table-bordered">
    <thead>
        <tr class="table-active">
            <th>Value</th>
            <th>Updated By</th>
            <th>Updated At</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($log as $item)
            <tr>
                <td>{{ $item->value() }}</td>
                <td>{{ $item->userName() }}</td>
                <td>{{ $item->UpdatedAt() }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center"> No Log Found </td>
            </tr>
        @endforelse
    </tbody>
</table>
