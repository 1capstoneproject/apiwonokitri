<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

Route::group(['prefix' => 'public'], function () {
    // auth login and register for users
    Route::post("/auth/login", function (Request $req) {
        // api authentication with username and password
        try{
            $credentials = $req->only('email', 'password');
            $oauth = $req->only('gid', 'email', 'name', 'profile');
            if (!empty($credentials) && empty($oauth)) {
                // do credentials generate token
                if (auth()->attempt($credentials)) {
                    $user = Models\User::where('email', $credentials['email'])->first();
                    if ($user) {
                        if(!in_array($user->roles->id, [3])){
                            return response()->json([
                                'error' => true,
                                'messages' => 'Invalid credentials.',
                                'data' => [],
                            ], 400);
                        }
                        $token = $user->createToken('mobile:auth');

                        // add token validity until +7 days
                        // $token->setExpiresAt(now()->addDays(7));
                        // $token->save();

                        return response()->json([
                            'error' => false,
                            'messages' => $token->plainTextToken,
                            'data' => $user,
                        ]);
                    }
                }
            }

            if (!empty($oauth)) {
                $user = Models\User::where('gid', $oauth['gid'])->first();
                if ($user) {
                    $token = $user->createToken('mobile:auth');
                    // add token validity until +7 days
                    // $token->setExpiresAt(now()->addDays(7));
                    // $token->save();

                    return response()->json([
                        'error' => false,
                        'messages' => $token->plainTextToken,
                        'data' => $user,
                    ]);
                } else {
                    // do create users and generate token.
                    // we will do latter

                    // create users
                    $user = Models\User::create([
                        'gid' => $oauth['gid'],
                        'email' => $oauth['email'],
                        'name' => $oauth['name'],
                        'roles_id' => 3,
                        'address' => '',
                        'password' => '',
                        'description' => '',
                        'profile' => $oauth['profile'],
                    ]);

                    // create token
                    $token = $user->createToken('mobile:auth');

                    // send json
                    return response()->json([
                        'error' => false,
                        'messages' => $token->plainTextToken,
                        'data' => $user,
                    ]);

                }
            }
            // if all of that not passed will be send this note.
            return response()->json([
                'error' => true,
                'messages' => 'Invalid credentials.',
                'data' => [],
            ], 400);
        }catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'messages' => 'Internal Server Error',
                'data' => $e,
            ], 500);
        }
    });
    // get product global
    Route::get('/product', function (Request $request) {
        try {
            $product = Models\Product::where('is_event', false)->where('is_package', false)->get();
            foreach($product as $key){
                $key->ImagesIds;
            }
            return response()->json([
                'error' => false,
                'messages' => '',
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'messages' => 'Internal Server Error',
                'data' => $e,
            ], 500);
        }
    });
    Route::post('/product/search', function(Request $request){
        try{
            $search = $request->validate([
                'search' => 'required|string|max:255',
            ]);
            // sould use ilike or like
            $product = Models\Product::where('name', 'like', '%'.$search['search'].'%')->get();
            foreach($product as $key){
                $key->ImagesIds;
            }
            // return response
            return response()->json([
                'error' => false,
                'messages' => '',
                'data' => $product,
            ]);
        }catch(ValidationException $e){
            return response()->json([
                'error' => true,
                'messages' => $e->validator->errors(),
                'data' => [],
            ], 500);
        }
    });
    // get product event
    Route::get('/product/event', function (Request $request) {
        try {
            $product = Models\Product::where('is_event', true)->get();
            foreach($product as $key){
                $key->ImagesIds;
            }
            return response()->json([
                'error' => false,
                'messages' => '',
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'messages' => 'Internal Server Error',
                'data' => $e,
            ], 500);
        }
    });
    Route::get('/product/package', function (Request $request) {
        try {
            $product = Models\Product::where('is_package', true)->get();
            foreach($product as $key){
                $key->ImagesIds;
            }
            return response()->json([
                'error' => false,
                'messages' => '',
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'messages' => 'Internal Server Error',
                'data' => $e,
            ], 500);
        }
    });
    // get product details
    Route::get('/product/details/{id}', function (Request $request, $id) {
        try {
            $product = Models\Product::where('id', $id)->first();
            $product->ImagesIds;
            $product->user;
            if (!$product) {
                return response()->json([
                    'error' => true,
                    'messages' => 'Product tidak tersedia.',
                    'data' => [],
                ], 400);
            }
            $product["other_product"] = Models\Product::where('users_id', $product->users_id)->where('id', '!=', $id)->get();
            foreach($product['other_product'] as $p){
                $p->ImagesIds;
            }
            return response()->json([
                'error' => false,
                'messages' => "get product with id $id",
                'req' => $request->method,
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'messages' => 'Internal Server Error',
                'data' => $e,
            ], 500);
        }
    });
    // get banner
    Route::get('/banner', function (Request $request) {
        try {
            $banner = Models\Banner::all();
            return response()->json([
                'error' => false,
                'messages' => '',
                'data' => $banner,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'messages' => 'Internal Server Error',
                'data' => $e,
            ], 500);
        }
    });
    // midtrans webhook endpoint
    Route::group(['prefix' => 'webhook'], function () {
        // create payment webhook's for midtrans.
        Route::post("/notif", function(Request $request){
            try{
                // validasi request dari server midtrans jika semua data
                // sudah terpenuhi maka akan di proses untuk update status transaksi menjadi paid.
                $form = $request->validate([
                    'transaction_time' => 'nullable|string',
                    'transaction_status' => 'nullable|string',
                    'transaction_id' => 'nullable|string',
                    'status_message' => 'nullable|string',
                    'status_code' => 'nullable|string',
                    'signature_key' => 'nullable|string',
                    'payment_type' => 'nullable|string',
                    'order_id' => 'nullable|string',
                    'merchant_id' => 'nullable|string',
                    'masked_card' => 'nullable|string',
                    'gross_amount' => 'nullable|numeric',
                    'fraud_status' => 'nullable|string',
                    'eci' => 'nullable|string',
                    'currency' => 'nullable|string',
                    'channel_response_message' => 'nullable|string',
                    'channel_response_code' => 'nullable|string',
                    'card_type' => 'nullable|string',
                    'bank' => 'nullable|string',
                    'approval_code' => 'nullable|string',
                ]);

                $transaction = Models\Transaction::where('code', $form['order_id'])->first();
                if(!$transaction){
                    return response()->json([
                        'error' => true,
                        'messages' => 'cannot find transaction',
                        'data' => $transaction,
                    ], 400);
                }
                if(in_array($form['transaction_status'], ["capture", "settlement"]) && $form['fraud_status'] == "accept"){
                   $transaction->status = "paid";
                   $transaction->save();
                }elseif($form['transaction_status'] == "pending"){
                    $transaction->status = "inprogress";
                    $transaction->save();
                }else{
                    $transaction->status = "cancel";
                    $transaction->save();
                }
                
                $transaction->status = "paid";
                return response()->json([
                    'error' => false,
                    'messages' => 'success update transaction status.',
                    'data' => $transaction,
                ]);

            }catch(ValidationException $e){
                return response()->json([
                    'error' => true,
                    'messages' => 'Internal Server Error',
                    'data' => $e->validator->errors(),
                ], 400);
            }catch(\Exception $e){
                return response()->json([
                    'error' => true,
                    'messages' => 'Internal Server Error',
                    'data' => $e->getMessage(),
                ], 500);
            }
        });
    });
});

Route::group(['prefix' => 'private', 'middleware' => 'auth:sanctum'], function () {
    Route::post("/session", function (Request $request) {
        return response()->json([
            'error' => false,
            'messages' => '',
            'data' => $request->user(),
        ]);
    });

    // cud users
    Route::post('/user/create', function (Request $request) {
        try {
            // validate users input
            $credentials = $request->validate([
                'email' => 'required|email|unique:users,email ',
                'password' => 'required|min:8',
                'name' => 'required',
                'phone' => 'required|min:10|max:16',
                'roles_id' => 'required|integer',
                'address' => 'nullable',
                'description' => 'nullable',
                'profile' => 'nullable',
            ]);
            // hasing user password
            if ($request->filled('password')) {
                $credentials['password'] = Hash::make($credentials['password']);
            }
            // todo add storage link for profile
            // and linked to profile
            $user = Models\User::create($credentials);

            return response()->json([
                'error' => false,
                'messages' => "Success create users $user->name",
                'data' => $user,
                'input' => $credentials,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'messages' => "Failed create user because error occured.",
                'data' => $e->validator->errors(),
            ], 500);
        }
    });

    Route::put('/user/edit/{id}', function (Request $request, $id) {
        try {
            $credentials = $request->validate([
                'password' => 'nullable|min:8',
                'name' => 'nullable',
                'phone' => 'nullable|min:10|max:16',
                'roles_id' => 'nullable|integer',
                'address' => 'nullable',
                'description' => 'nullable',
                'profile' => 'nullable',
            ]);
            // if password available we rehash with new password
            if ($request->filled('password')) {
                $credentials['password'] = Hash::make($credentials);
            }
            // need to handle profile update

            $user = Models\User::find($id)->update($credentials);
            return response()->json([
                'error' => false,
                'messages' => "Success edit users",
                'data' => $user,
                'input' => $credentials,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'messages' => "Failed create user because error occured.",
                'data' => $e->validator->errors(),
            ], 500);
        }
    });

    Route::delete("/user/delete/{id}", function (Request $request, $id) {
        try {

            $user = Models\User::find($id);

            if (!$user) {
                return response()->json([
                    'error' => true,
                    'messages' => "failed to delete users, because users doesn't exist",
                    'data' => [],
                ]);
                return back()->with('error', "failed to delte users, because users doesn't exist");
            }

            // do delete
            $user->delete();
            return response()->json([
                'error' => false,
                'messages' => "Success delete users",
                'data' => [],
            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'error' => true,
                'messages' => "Failed to delete users because error occured.",
                'data' => $e->validator->errors(),
            ], 500);
        }
    });
    // cud transactin
    Route::post("/transaction", function (Request $request) {
        try {
            $transaction = Models\Transaction::where('user_id', $request->user()->id)->get();
            foreach($transaction as $tx){
                $tx->Product;
                $tx->product->ImagesIds;

            }
            return response()->json([
                'error' => false,
                'messages' => '',
                'data' => $transaction,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'messages' => 'Failed to get transaction',
                'data' => [],
            ], 500);
        }
    });

    Route::post("/transaction/create", function (Request $request) {
        try {
            $data = $request->validate([
                // 'code' => 'required|string|max:255',
                'tourism_id' => 'required|exists:users,id', // Assuming tourism_id refers to users table
                'user_id' => 'required|exists:users,id',
                'product_id' => 'required|exists:product,id',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:1',
                'total' => 'required|numeric|min:0',
                'status' => 'nullable|string',
                'date' => 'nullable|date',
                'phone_number'=> 'nullable|string',
                'contact_name'=> 'nullable|string',
                'order_data'=> 'nullable|string',
            ]);

            $transaction = Models\Transaction::create($data);
            return response()->json([
                'error' => false,
                'messages' => 'success create transaction.',
                'data' => $transaction,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'messages' => "",
                'data' => $e->validator->errors(),
            ], 500);
        }
    });

    Route::post("/transaction/edit/{id}", function (Request $request, $id) {
        try {
            $data = $request->validation([
                // 'code' => 'nullable|string|max:255',
                'tourism_id' => 'nullable|exists:users,id', // Assuming tourism_id refers to users table
                'user_id' => 'nullable|exists:users,id',
                'product_id' => 'nullable|exists:products,id',
                'price' => 'nullable|numeric|min:0',
                'quantity' => 'nullable|integer|min:1',
                'total' => 'nullable|numeric|min:0',
                'status' => 'nullable|string',
                'date' => 'nullable|date',
            ]);

            $transaction = Models\Transaction::find($id)->update($data);


            return response()->json([
                'error' => false,
                'messages' => 'success update transaction',
                'data' => $transaction,
            ]);

            return back()->with('success', 'success edit transaction');
        } catch (ValidationException $e) {

            return response()->json([
                'error' => true,
                'messages' => '',
                'data' => $e->validator->errors(),
            ]);
        }
    });

    Route::post("/transaction/delete/{id}", function(Request $request, $id){
        try {

            $transaction = Models\Transaction::find($id);

            if (!$transaction) {
                return response()->json([
                    'error' => true,
                    'messages' => "failed to delete transaction, because transaction doesn't exist",
                    'data' => [],
                ]);
            }

            // do delete
            $transaction->delete();
            return response()->json([
                'error' => false,
                'messages' => "Success delete transaction",
                'data' => [],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'messages' => "Failed delete transaction because error occured.",
                'data' => $e->validator->errors(),
            ], 500);
        }
    });
    // transaksi ke midtrans server
    Route::post("/transaction/request/payment/{id}", function(Request $request, $id){
        try {
            $tx = Models\Transaction::find($id);

            if(!in_array($tx->status, ["draft"])){
                return response()->json([
                    'error' => false,
                    'messages' => '',
                    'data' => [
                        'token' => $tx->payment_id,
                        'redirect_url' => $tx->payment_url,
                    ]
                ]);
            }

            // mendapatkan env variable dari app configuration.
            $endpoint = Config::string("app.midtrans.endpoint_url");
            $auth = Config::string("app.midtrans.auth_key");

            // request untuk membuat order ke midtrans
            $dataMidtrans = [
                "transaction_details" => [
                    "order_id" => $tx->code,
                    "gross_amount" => $tx->total,
                ],
                "item_details" => [
                    [
                        "id" => "MID-".$tx->product->id,
                        "price" => $tx->price,
                        "quantity" => $tx->quantity,
                        "subtotal" => $tx->price * $tx->quantity,
                        "name" => $tx->product->name,
                    ]
                ],
                "customer_details" => [
                    "first_name" => $tx->customer->name,
                    "email" => $tx->customer->email,
                    "phone" => $tx->customer->phone,
                    "address" => $tx->address,
                ],
            ];
            $midtransRequest = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => "Basic $auth",
                ])->post("$endpoint/snap/v1/transactions", $dataMidtrans);

            $snapData = $midtransRequest->json();

            if(!array_key_exists("token", $snapData)){
                return response()->json([
                    'error' => true,
                    'messages' => 'Error on request midtrans backend api.',
                    'data' => [
                        'response' => $snapData,
                    ],
                ]);
            }
            $tx->payment_id = $snapData["token"];
            $tx->payment_url = $snapData["redirect_url"];
            // $tx->status = "inprogress";
            $tx->save();

            return response()->json([
                'error' => false,
                'messages' => "Success create transaction order.",
                'data' => $snapData,
                'other' => [
                    'endpoint' => $endpoint,
                    'auth' => $auth,
                ]
            ]);
        }catch(ValidationException $e){
            return response()->json([
                'error' => true,
                'messages' => "Failed to create transaction order.",
                'data' => $e->validator->errors(),
            ]);
        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'messages' => "Failed to create transaction order.",
                'data' => $e->getMessage(),
            ]);
        }
    });


    // cud settings

    // testing
    Route::any('/testing', function () {
        return response()->json([
            'error' => false,
            'messages' => 'Aunthenticated',
            'data' => [
                'version' => '1.0.0'
            ],
        ]);
    });
});


// jangan mengedit script di bawah ini
// dapat menyebabkan kehamilan masal :v

Route::any('/', function () {
    return response()->json([
        'error' => false,
        'messages' => 'Wonokitri Tourism Endpoint Api',
        'data' => [
            'version' => '1.0.0'
        ],
    ]);
});

Route::any('{all}', function () {
    return response()->json([
        'error' => true,
        'messages' => 'Endpoint not found.',
        'data' => [],
    ], 404);
})->where(['all' => '.*']);
