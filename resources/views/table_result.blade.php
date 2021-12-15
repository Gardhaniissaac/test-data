<table class="table table-bordered mx-2 my-2">
    <thead>
        <th>trx_id</th>
        <th>trx_created_at</th>
        <th>amount</th>
        <th>user_id</th>
        <th>name</th>
        <th>email</th>
        <th>address</th>
    </thead>
    <tbody>
        @foreach ($result_data as $value)
            <tr>
                <td>{{$value->trx_id}}</td>
                <td>{{$value->trx_created_at}}</td>
                <td>{{$value->amount}}</td>
                <td>{{$value->user_id}}</td>
                <td>{{$value->name}}</td>
                <td>{{$value->email}}</td>
                <td>{{$value->address}}</td>
            </tr>
        @endforeach
    </tbody>
</table>