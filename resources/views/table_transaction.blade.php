<table class="table table-bordered mx-2 my-2">
    <thead>
        <th>trx_id</th>
        <th>op</th>
        <th>ts</th>
        <th>amount</th>
        <th>user_id</th>
    </thead>
    <tbody>
        @foreach($data as $item)
            <tr>
                <td>{{$item->trx_id}}</td>
                <td>{{$item->op}}</td>
                <td>{{$item->ts}}</td>
                <td>{{$item->amount}}</td>
                <td>{{$item->user_id}}</td>
            </tr>
        @endforeach
    </tbody>
</table>