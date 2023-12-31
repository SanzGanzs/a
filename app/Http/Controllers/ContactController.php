<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ContactResource;
use App\Http\Requests\ContactUpdateRequest;
use App\Http\Requests\CreateContactRequest;

class ContactController extends Controller
{
    public function create(CreateContactRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();

        $contact = new Contact($data);
        $contact->user_id = $user->id;
        $contact->save();

        return (new ContactResource($contact))->response()->setStatusCode(201);
    }

    public function get(int $id): ContactResource
    {
        $user = Auth::user();
        $contact = Contact::where("id", $id)->first();
        if(!$contact){
            throw new HttpResponseExcepcion(response()->json([
                "errors" => [
                    "messages" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        return new ContactResource($contact);
    }

    public function update(int $id, ContactUpdateRequest $request): ContactResource
    {
        $user = Auth::user();

        $contact = Contact::where("id", $id)->first();
        if(!contact){
            throw new HttpResponseExcepcion(response()->json([
                "errors" => [
                    "messages" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        $data = $request->validated();
        $contact->fill($data);
        $contact->save();

        return new ContactResource($contact);
    }
}
