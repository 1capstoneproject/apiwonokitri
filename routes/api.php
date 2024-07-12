<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

Route::group(['prefix' => 'public'], function () {
    // auth login and register for users
    Route::post("/auth/login", function (Request $req) {
        // api authentication with username and password
        $credentials = $req->only('email', 'password');
        $oauth = $req->only('gid');
        if ($credentials) {
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
        
        if (empty($oauth)) {
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
                
                // create token
                
                // send json
            }
        }
        // if all of that not passed will be send this note.
        return response()->json([
            'error' => true,
            'messages' => 'Invalid credentials.',
            'data' => [],
        ], 400);
    });
    // get product global
    Route::get('/product', function (Request $request) {
        try {
            $product = Models\Product::all();
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
    // get product event
    Route::get('/product/event', function (Request $request) {
        try {
            $product = Models\Product::where('is_event', true)->get();
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
            if (!$product) {
                return response()->json([
                    'error' => true,
                    'messages' => 'Product tidak tersedia.',
                    'data' => [],
                ], 400);
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
});

Route::group(['prefix' => 'private', 'middleware' => 'auth:sanctum'], function () {
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
                'messages' => "Failed create user because error occured.",
                'data' => $e->validator->errors(),
            ], 500);
        }
    });
    // cud transactin
    Route::post("/transaction", function (Request $request) {
        try {
            $transaction = Models\Transaction::where('user_id', $request->user()->id)->get();
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
        'messages' => 'WonokitriTourism Endpoint Api',
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
