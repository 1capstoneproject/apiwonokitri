<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

// use Symfony\Component\Console\Output\ConsoleOutput;

// note:
// for standaritation api request must have this response
// error: @boolean | status code is response error or not, make easy to detect response on client side, when forget add response code.
// messages: @string | response messages
// data: @any | data request response
//
// status code:
// 200: OK
// 400: Bad Request | error on users side like validation error issue.
// 500: Internal Server error | error on server side like something bad happen (ex: nuclear war)


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
                'data' => $e->getMessage(),
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
    // to update transaction status payment we need midtrans implementation webhooks
    // to update transaction status code automatically.
    Route::group(['prefix' => 'webhook'], function () {
        // create payment webhook's for midtrans.
        Route::post("/notif", function(Request $request){
            try{
                // validate midtrans webhooks transaction post request
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
                // search for transaction order_id
                $transaction = Models\Transaction::where('code', $form['order_id'])->first();
                // return error if transaction not found.
                if(!$transaction){
                    return response()->json([
                        'error' => true,
                        'messages' => 'cannot find transaction',
                        'data' => $transaction,
                    ], 400);
                }
                // update transaction status payment
                if(in_array($form['transaction_status'], ["capture", "settlement"]) && $form['fraud_status'] == "accept"){
                   $transaction->status = "paid";
                   $transaction->save();
                }elseif($form['transaction_status'] == "pending"){
                    // get user payment method
                    $transaction->payment_method = $form['payment_type'];
                    $transaction->status = "inprogress";
                    $transaction->save();
                }else{
                    $transaction->status = "cancel";
                    $transaction->save();
                }
                // save midtrans transaction data
                // like time they pay and transaction status
                $transaction->payment_status = $form['transaction_status'];
                $transaction->payment_time = $form['transaction_time'];
                $transaction->save();

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
    // this requst from atik, because she want
    // to validate the users session request.
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

    Route::post('/user/edit/{id}', function (Request $request, $id) {
        try {
            $credentials = $request->validate([
                'password' => 'nullable|min:8',
                'name' => 'nullable',
                'phone' => 'nullable',
                'address' => 'nullable',
                'description' => 'nullable',
                'profile' => 'nullable',
            ]);
            // if password available we rehash with new password
            if ($request->filled('password')) {
                $credentials['password'] = Hash::make($credentials['password']);
            }
            // need to handle profile update
            $user = Models\User::find($id);
            // need to handle fileupload to upload profile picture
            if($request->has('profile') && $credentials['profile'] != ""){
                // detected if previus file not a linked image
                // then delete
                if(!str_starts_with($user->profile, 'http')){
                    if($user->profile && file_exists(storage_path("app/public".str_replace("storage", "", $user->profile)))){
                        unlink(storage_path("app/public".str_replace("storage", "", $user->profile)));
                    }
                }
                // save file to storage
                // file upload from api is base64 file we need to convert to
                // binary file and save on filestorage
                $fileData = base64_decode($credentials['profile']);
                // set filename
                $filename = uniqid().'.png'; // set default file type is .png
                $filePath = "users/".$filename;

                Storage::disk('public')->put($filePath, $fileData);
                $profile = Storage::url($filePath);
                $credentials['profile'] = $profile;
            }else{
                // because profile doesn't exist we need remove profile from credentials
                // prevent updated profile with empty string
                unset($credentials['profile']);
            }
            $user->update($credentials);
            return response()->json([
                'error' => false,
                'messages' => "Success edit users",
                'data' => $user,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'messages' => "Failed to update user because error occured.",
                'data' => $e->validator->errors(),
            ], 400);
        } catch(\Exception $e){
            return response()->json([
                'error' => true,
                'messages' => 'Failed to update users because error occured.',
                'data' => $e->getMessage(),
            ], 500);
        }
    });

    // currently not used but in future whenever user
    // need to delete their account, they can request for account deletion.
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
            $transaction = Models\Transaction::where('user_id', $request->user()->id)->orderBy('code', 'desc')->get();
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
            $transaction->Product;
            $transaction->product->ImagesIds;

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
        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'messages' => "",
                'data' => $e->getMessage(),
            ], 500);
        }
    });

    Route::post("/transaction/edit/{id}", function (Request $request, $id) {
        try {
            $data = $request->validate([
                // 'code' => 'nullable|string|max:255',
                'tourism_id' => 'nullable|exists:users,id', // Assuming tourism_id refers to users table
                'user_id' => 'nullable|exists:users,id',
                'product_id' => 'nullable|exists:products,id',
                'price' => 'nullable|numeric|min:0',
                'quantity' => 'nullable|integer|min:1',
                'total' => 'nullable|numeric|min:0',
                'status' => 'nullable|string',
                'date' => 'nullable|date',
                'phone_number'=> 'nullable|string',
                'contact_name'=> 'nullable|string',
                'order_data'=> 'nullable|string',
            ]);

            $transaction = Models\Transaction::find($id);
            $transaction->update($data);

            return response()->json([
                'error' => false,
                'messages' => 'success update transaction',
                'data' => $transaction,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'messages' => 'Bad request',
                'data' => $e->validator->errors(),
            ], 400);
        } catch(\Exception $e){
            return response()->json([
                'error' => true,
                'messages' => 'Internal Server Error',
                'data' => $e->getMessage(),
            ], 500);
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

            if(!in_array($tx->status, ["draft", "inprogress"])){
                return response()->json([
                    'error' => false,
                    'messages' => '',
                    'data' => [
                        'token' => $tx->payment_id,
                        'redirect_url' => $tx->payment_url,
                    ],
                    'other' => $tx,
                ], 402);
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
                'other' => $dataMidtrans,
            ]);
        }catch(ValidationException $e){
            return response()->json([
                'error' => true,
                'messages' => "Failed to create transaction order.",
                'data' => $e->validator->errors(),
            ], 400);
        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'messages' => "Failed to create transaction order.",
                'data' => $e->getMessage(),
            ], 500);
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


Route::any('/', function () {
    return response()->json([
        'error' => false,
        'messages' => 'Wonokitri Tourisme Endpoint Api',
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
