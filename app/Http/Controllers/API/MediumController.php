<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediumRequest;
use App\Http\Requests\UpdateMediumRequest;
use App\Models\Contact;
use App\Models\Medium;

class MediumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contact $contact)
    {
        $this->authorize('viewAny', $contact);

        return $contact->media;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMediumRequest $request, Contact $contact)
    {
        $this->authorize('create', Medium::class);

        $data = $request->safe()->only(['category', 'value']);
        $data['contact_id'] = $contact->id;

        $medium = new Medium($data);
        $medium->save();

        return $medium->refresh();
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact, Medium $medium)
    {
        $this->authorize('view', $medium);

        return $medium;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMediumRequest $request, Contact $contact, Medium $medium)
    {
        $this->authorize('update', $medium);

        $data = $request->safe()->only(['category', 'value']);
        $medium->fill($data);
        $medium->save();

        return $medium->refresh();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact, Medium $medium)
    {
        $this->authorize('delete', $contact, $medium);

        $medium->delete();
    }
}
