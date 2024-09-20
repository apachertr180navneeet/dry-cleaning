<?php


namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index(Request $request)
    {

        $query = User::where(['is_deleted' => 0, 'role_id' => 2]);

        if ($request->ajax()) {
            $search = $request->input('search');
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('mobile', 'like', '%' . $search . '%');
                });
            }

            $clients = $query->orderBy('id', 'desc')->paginate(10);
            return response()->json([
                'clients' => $clients->items(),
                'pagination' => (string) $clients->links()
            ]);
        }

        $clients = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.client', compact('clients'));
    }

    public function addClient(Request $request)
    {
        try {
            // Custom validation messages
            $messages = [
                'name.required' => 'The name field is required.',
                'name.regex' => 'The name should only contain alphabetic characters and spaces, with a length of 4 to 20 characters.',
                'name.min' => 'The name must be at least 4 characters long.',
                'name.max' => 'The name may not be greater than 20 characters.',
                'mobile.required' => 'The mobile number field is required.',
                'mobile.numeric' => 'The mobile number should contain only numbers.',
                'mobile.digits_between' => 'The mobile number must be between 10 and 15 digits long.',
                'mobile.unique' => 'The mobile number has already been taken.',
            ];

            // Validate the incoming request data with custom messages
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'regex:/^[a-zA-Z\s]{4,20}$/', // Allows alphabetic characters and spaces only
                    'min:4', // Minimum length of 4 characters
                    'max:20', // Maximum length of 20 characters
                ],
                'mobile' => [
                    'required',
                    'numeric', // Ensures the mobile number contains only numbers
                    'digits_between:10,15', // Length must be between 10 and 15 digits
                    Rule::unique('users')->where(function ($query) {
                        return $query->where('is_deleted', 0);
                    }), // Ensures the mobile number is unique among non-deleted users
                ],
            ], $messages);

            // Redirect back with errors if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            // Check if a user with the same mobile number and is_deleted = 1 exists
            $existingUser = User::where('mobile', $request->mobile)->where('is_deleted', 1)->first();

            if ($existingUser) {
                // Update the existing user with the new data
                $existingUser->update([
                    'name' => $request->name,
                    'email' => $request->email ?? null,
                    'password' => $request->password ? bcrypt($request->password) : $existingUser->password, // Update password if provided
                    'is_deleted' => 0, // Restore the user by setting is_deleted to 0
                    'role_id' => 2, // Assign default role_id
                ]);
            } else {
                // Create a new user if no existing user is found
                User::create([
                    'name' => $request->name,
                    'email' => $request->email ?? null,
                    'mobile' => $request->mobile,
                    'password' => $request->password ? bcrypt($request->password) : null, // Set password if provided
                    'role_id' => 2, // Assign default role_id
                ]);
            }

            // Redirect to the client page with a success message
            return redirect()->route('clientpage')->with('success', 'Client added successfully');
        } catch (\Throwable $throwable) {
            // Log the error and redirect back with an error message
            \Log::error($throwable->getMessage());
            return redirect()->back()->with('error', 'An error occurred while adding the client.');
        }
    }

    public function editClient(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:20',
                'mobile' => [
                    'required',
                    'regex:/^[0-9()+-]+$/',
                    'min:4',
                    'max:15',
                ],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            } else {
                $client = User::findOrFail($id);

                $input = $request->all();

                $client->update([
                    'name' => $input['name'],
                    'email' => $input['email'] ?? $client->email,
                    'mobile' => $input['mobile'],
                    'password' => $input['password'] ?? $client->password,
                    // You can update other fields as needed
                ]);

                return redirect()->route('clientpage')->with('success', 'Client updated successfully');
            }
        } catch (\Throwable $throwable) {
            \Log::error($throwable->getMessage());
            // dd($throwable->getMessage());
        }
    }


    public function deleteClient($id)
    {
        try {
            $client = User::findOrFail($id);
            $client->update(['is_deleted' => 1]);
            return response()->json(['message' => 'Client deleted successfully']);
        } catch (\Throwable $throwable) {
            dd($throwable->getMessage());
        }
    }
}
