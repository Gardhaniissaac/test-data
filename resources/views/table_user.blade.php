<table class="table table-bordered mx-2 my-2">
    <thead>
        <th>user_id</th>
        <th>op</th>
        <th>ts</th>
        <th>name</th>
        <th>email</th>
        <th>address</th>
    </thead>
    <tbody>
        @foreach($data as $item)
            <tr>
                <td>{{$item->user_id}}</td>
                <td>{{$item->op}}</td>
                <td>{{$item->ts}}</td>
                <td>{{$item->name}}</td>
                <td>{{$item->email}}</td>
                <td>{{$item->address}}</td>
            </tr>
        @endforeach
    </tbody>
</table>