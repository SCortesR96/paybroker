<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Enums\LogChannelEnum;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Response as HttpResponse;

class UserController extends Controller
{
    use ApiResponse;

    private $repository;

    function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = $this->repository->index();

            if (!empty($user)) {
                return $this->Success(
                    'Users loaded successfully',
                    UserResource::collection($user)
                );
            }

            return $this->Error(
                'There is not user registered',
                array(),
                HttpResponse::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            return $this->Exception(
                $e,
                LogChannelEnum::USER,
                'UserController.Index'
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // There was an error with the custom file request and
            // because of time i have to validate information here
            $errors = $this->validateStore($request);
            if (!empty($errors)) {
                return $this->Validation(
                    'Please, validate the information',
                    $errors
                );
            }

            $user = $this->repository->store($request);
            if ($user['status']) {
                return $this->Success(
                    'Users created successfully',
                    new UserResource($user['data'])
                );
            }

            return $this->Error(
                'Error to create this user',
                array()
            );
        } catch (Exception $e) {
            return $this->Exception(
                $e,
                LogChannelEnum::USER,
                'UserController.Store'
            );
        }
    }

    /**
     * It validates the request data and returns an array of errors
     *
     * @param request The request object
     *
     * @return an array of errors.
     */
    private function validateStore($request): array
    {
        $errors = [];
        $rules = [
            'cpf' => 'required|min:11|max:11|unique:users,cpf',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
        ];
        $messages = [
            'required'  => "You must complete the field ':attribute'",
            'email'     => "You must type a right format for the :attribute",
            'min'       => "You must add at least 11 characters in the field :attribute",
            'max'       => "You must add maximum 11 characters in the field :attribute",
            'unique'    => "The :attribute already exists"
        ];
        $attr_names = [
            'cpf' => 'CPF',
            'name' => 'Name',
            'email' => 'Email',
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $attr_names);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $err_messages) {
                foreach ($err_messages as $message) {
                    array_push($errors, $message);
                }
            }
        }
        return $errors;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $cpf
     * @return \Illuminate\Http\Response
     */
    public function show($cpf)
    {
        try {
            $user = $this->repository->show($cpf);

            if ($user) {
                return $this->Success(
                    'User loaded successfully',
                    new UserResource($user)
                );
            }

            return $this->Error(
                'User not found',
                array(),
                HttpResponse::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            return $this->Exception(
                $e,
                LogChannelEnum::USER,
                'UserController.Show'
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $cpf
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cpf)
    {
        try {
            // There was an error with the custom file request and
            // because of time i have to validate information here
            $errors = $this->validateUpdate($request);
            if (!empty($errors)) {
                return $this->Validation(
                    'Please, validate the information',
                    $errors
                );
            }

            $user = $this->repository->update($request, $cpf);
            if (!$user['status'] && !empty($user['data'])) {
                return $this->Error(
                    $user['data']['error'],
                    array()
                );
            }

            if ($user['status']) {
                return $this->Success(
                    'Users updated successfully',
                    new UserResource($user['data'])
                );
            }

            return $this->Error(
                'Error to update this user',
                array()
            );
        } catch (Exception $e) {
            return $this->Exception(
                $e,
                LogChannelEnum::USER,
                'UserController.Update'
            );
        }
    }

    /**
     * It validates the request data and returns an array of errors
     *
     * @param request The request object
     *
     * @return an array of errors.
     */
    private function validateUpdate($request): array
    {
        $errors = [];
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email',
        ];
        $messages = [
            'required'  => "You must complete the field ':attribute'",
            'email'     => "You must type a right format for the :attribute",
            'min'       => "You must add at least 11 characters in the field :attribute",
            'max'       => "You must add maximum 11 characters in the field :attribute",
            'unique'    => "The :attribute already exists"
        ];
        $attr_names = [
            'name' => 'Name',
            'email' => 'Email',
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $attr_names);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $err_messages) {
                foreach ($err_messages as $message) {
                    array_push($errors, $message);
                }
            }
        }
        return $errors;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $cpf
     * @return \Illuminate\Http\Response
     */
    public function destroy($cpf)
    {
        try {
            $user = $this->repository->destroy($cpf);
            if (!$user['status']) {
                return $this->Error(
                    $user['data']['error'],
                    array()
                );
            }

            return $this->Success('User deleted successfully');
        } catch (Exception $e) {
            return $this->Exception(
                $e,
                LogChannelEnum::USER,
                'UserController.Destroy'
            );
        }
    }
}
