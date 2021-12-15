<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\User1;
use App\Transaction1;

use DB;

class MainController extends Controller
{
    public function index(){

        $all_user = User1::all();
        $all_transaction = Transaction1::all();

        if(!empty($all_user) && !empty($all_transaction)){
            $result_data = $this->get_result_1();
        } else {
            $result_data = [];
        }

        return view('welcome',compact('all_user','all_transaction','result_data'));
    }

    public function store_user(Request $request){

        // dd($request->all());

        if (isset($_FILES['file'])) {
            Storage::putFileAs('public/', $_FILES['file']["tmp_name"], 'user.txt');
        }

        $array = [];
        $contents = Storage::get('public/user.txt');
        
        $raw_content = explode(PHP_EOL,$contents);

        $content_1 = [];
        $content_result = [];

        foreach($raw_content as $rows){
            $elements = explode(' ', $rows);

            array_push($content_1, $elements);
        }

        $head = [];

        foreach($content_1 as $key => $value){
            if($key == 0){
                foreach($value as $item){
                    if($item){
                        array_push($head, $item);
                    }
                }

                $head_user_id = array_search('user_id', $head);
                $head_op = array_search('op', $head);
                $head_ts = array_search('ts', $head);
                $head_name = array_search('name', $head);
                $head_email = array_search('email', $head);
                $head_address = array_search('address', $head);
            }

            if(!empty($value[0]) && $key > 0){
                for($i=$head_name; $i<count($value);$i++){
                    if(str_contains($value[$i], '@') ){
                        if($head_name > $head_ts){
                            $name_length = $i - $head_name - 1;
                        } else {
                            $name_length = $i - $head_name;
                        }

                        $head_email = $i;
                        $head_address = $i+1;
                    }
                }

                $user_id = $value[$head_user_id];
                $op = $value[$head_op];
                $ts = $value[$head_ts].' '.$value[$head_ts+1];
                $name = '';
                for($i=0;$i<$name_length;$i++){
                    if($i!=0){
                        $name .= ' ';
                    }
                    $name .= $value[$head_name+1+$i];
                }
                $email = $value[$head_email];
                $address = '';
                for($i=0;$i<count($value) - $head_address;$i++){
                    if($i!=0){
                        $address .= ' ';
                    }
                    $address .= $value[$head_address + $i];
                }

                $array_input = [$user_id, $op, $ts, $name, $email, $address];

                array_push($content_result, $array_input);
            }
        }

        // dd($content_result);

        $delete_existing = User1::truncate();

        foreach($content_result as $value){
            $create_data = User1::firstOrCreate([
                'user_id' => $value[0],
                'op' => $value[1],
                'ts' => $value[2],
                'name' => $value[3],
                'email' => $value[4],
                'address' => $value[5],
            ]);
        }

        $data = User1::all();

        $view = view('table_user',compact('data'))->render();
        
        $result_data = $this->get_result_1();
        $view_result = view('table_result',compact('result_data'))->render();

        return response()->json([
            'message'   => 'success',
            'data'      => $view,
            'result'    => $view_result
        ]);
    }

    public function store_transaction(Request $request){

        if (isset($_FILES['file'])) {
            Storage::putFileAs('public/', $_FILES['file']["tmp_name"], 'transaction.txt');
        }

        $array = [];
        $contents = Storage::get('public/transaction.txt');
        
        $raw_content = explode(PHP_EOL,$contents);

        $content_1 = [];
        $content_result = [];

        foreach($raw_content as $rows){
            $elements = explode(' ', $rows);

            array_push($content_1, $elements);
        }

        $head = [];

        foreach($content_1 as $key => $value){
            if($key == 0){
                foreach($value as $item){
                    if($item){
                        array_push($head, $item);
                    }
                }

                $head_trx_id = array_search('trx_id', $head);
                $head_op = array_search('op', $head);
                $head_ts = array_search('ts', $head);
                $head_amount = array_search('amount', $head)+1;
                $head_user_id = array_search('user_id', $head)+1; 
            }

            if(!empty($value[0]) && $key > 0){

                $trx_id = $value[$head_trx_id];
                $op = $value[$head_op];
                $ts = $value[$head_ts].' '.$value[$head_ts+1];
                $amount = $value[$head_amount];
                $user_id = $value[$head_user_id];

                $array_input = [$trx_id, $op, $ts, $amount, $user_id];

                array_push($content_result, $array_input);
            }
        }

        $delete_existing = Transaction1::truncate();

        foreach($content_result as $value){
            $create_data = Transaction1::firstOrCreate([
                'trx_id' => $value[0],
                'op' => $value[1],
                'ts' => $value[2],
                'amount' => $value[3],
                'user_id' => $value[4],
            ]);
        }

        $data = Transaction1::all();

        $view = view('table_transaction',compact('data'))->render();

        $result_data = $this->get_result_1();
        $view_result = view('table_result',compact('result_data'))->render();
        
        return response()->json([
            'message'   => 'success',
            'data'      => $view,
            'result'    => $view_result
        ]);
    }

    public function get_result_1(){
        $all_user = User1::all();
        $all_transaction = Transaction1::all();

        $save_file = Storage::disk('local')->put('result.txt', 'trx_id trx_created_at amount user_id name email address');

        $result = DB::select(
            "
                select t2.trx_id, t2.trx_created_at, t2.amount, t2.user_id, t2.name, t2.email, t2.address
                from
                    (
                        select trx_id, t1.ts as trx_created_at, amount, t1.user_id, name, email, address, row_number() over(partition by trx_id order by u1.ts desc) AS row_num  
                        from transaction_1 t1
                            left join user_1 u1 on u1.user_id = t1.user_id
                                and u1.ts < t1.ts
                        group by trx_id, t1.user_id, name, email, address, amount, t1.ts
                        order by trx_id, u1.ts
                    ) as t2
                where t2.row_num = 1
            "
        );

        foreach($result as $row){
            $content = '';
            $content = $row->trx_id.' '.$row->trx_created_at.' '.$row->amount.' '.$row->user_id.' '.$row->name.' '.$row->email.' '.$row->address;

            $save_file = Storage::disk('local')->append('result.txt', $content);
        }

        return $result;
    }

    public function get_result_2(){

    }

    public function download_file_user(){
        $file= 'public/user.txt';
    
        return Storage::download($file);
    }

    public function download_file_transaction(){
        $file= 'public/transaction.txt';
    
        return Storage::download($file);
    }

    public function download_file_result(){
        $file= 'result.txt';
    
        return Storage::download($file);
    }
}
