<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <div class="container">
            <div class="card bg-white my-2 mx-2">
                <h1 class="text-center">Aplication Test</h1>
            </div>
            <div class="card bg-white my-2 mx-2">
                <div class="row">
                    <div class="col-6">
                        <h3 class="text-center">
                            User
                        </h3>

                        <div class="mx-2 my-2">
                            <label>Upload new file log user</label>
                            <input class="form-control" type="file" id="upload_file_user">
                        </div>

                        <div class="mx-2 my-3">
                            <label>Existing file log user : </label>
                            <a href="{{route('main.download_file_user')}}" target="_blank">Download</a>
                        </div>

                        <div id="data_user">
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
                                    @foreach($all_user as $item)
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
                        </div>
                    </div>
                    <div class="col-6">
                        <h3 class="text-center">
                            Transaction
                        </h3>

                        <div class="mx-2 my-2">
                            <label>Upload new file log transaction</label>
                            <input class="form-control" type="file" id="upload_file_transaction">
                        </div>

                        <div class="mx-2 my-3">
                            <label>Existing file log transaction : </label>
                            <a href="{{route('main.download_file_transaction')}}" target="_blank">Download</a>
                        </div>

                        <div id="data_transaction">
                            <table class="table table-bordered mx-2 my-2">
                                <thead>
                                    <th>trx_id</th>
                                    <th>op</th>
                                    <th>ts</th>
                                    <th>amount</th>
                                    <th>user_id</th>
                                </thead>
                                <tbody>
                                    @foreach($all_transaction as $item)
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
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <h3 class="text-center">
                        Result
                    </h3>

                    <div class="mx-2">
                        <label>Result file :</label>
                        <a href="{{route('main.download_file_result')}}" target="_blank">Download</a>
                    </div>

                    <div id="data_result">
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
                    </div>
                </div>
            </div>
        </div>
        
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        <script>
            $(document).ready(function(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $("#upload_file_user").on("change", function(){
                    let file = $(this).val();

                    if(file){

                        var fd = new FormData();

                        // console.log($("#upload_file_user"));
                        let file1 = $("#upload_file_user")[0].files[0];
                        fd.append('file', file1);

                        $.ajax({
                            url: "{{route('main.store_user')}}",
                            data:fd,
                            method: "POST",
                            processData: false,
                            contentType: false,
                            dataType: "JSON",
                            success: function (response) {
                                console.log(response)

                                $("#data_user").html(response.data);
                                $("#data_result").html(response.result);
                                $("#upload_file_user").prop("value","");
                                
                            },
                            error: function (response) {
                                console.log(response)
                            },
                        });
                    }
                });

                $("#upload_file_transaction").on("change", function(){
                    let file = $(this).val();

                    if(file){

                        var fd = new FormData();

                        // console.log($("#upload_file_transaction"));
                        let file1 = $("#upload_file_transaction")[0].files[0];
                        fd.append('file', file1);

                        $.ajax({
                            url: "{{route('main.store_transaction')}}",
                            data:fd,
                            method: "POST",
                            processData: false,
                            contentType: false,
                            dataType: "JSON",
                            success: function (response) {
                                console.log(response)

                                $("#data_transaction").html(response.data);
                                $("#data_result").html(response.result);
                                $("#upload_file_transaction").prop("value","");
                                
                            },
                            error: function (response) {
                                console.log(response)
                            },
                        });
                    }
                });
            });
        </script>

    </body>
</html>
