<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Http\Requests\StoreUrlRequest;
use App\Http\Requests\UpdateUrlRequest;
use App\Services\UrlService;
use Exception;
use Inertia\Inertia;

class UrlController extends Controller
{
    public function __construct(
        private UrlService $service,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Inertia::render('Urls/Index', [
                'urls' => $this->service->getPaginatedByUrlable(auth()->user(), 15)
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUrlRequest $request)
    {
        try {
            if ($this->service->shortExists(auth()->user(), $request->url)) {
                throw new Exception('This url has already been shortened');
            }

            $this->service->createByUser(auth()->user(), [
                'original_url' => $request->url,
            ]);

            return redirect()
                ->route('urls.index')
                ->withSuccess('Url shortened successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['url' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Url $url)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Url $url)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUrlRequest $request, Url $url)
    {
        $this->authorize('update', $url);

        try {
            $this->service->update($url, [
                'original_url' => $request->url,
            ]);

            return redirect()
                ->route('urls.index')
                ->withSuccess('Url updated successfully');
        } catch (\Throwable $th) {
            return redirect()->back(500)->withErrors($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Url $url)
    {
        $this->authorize('delete', $url);
        try {
            $this->service->delete($url);

            return redirect()
                ->route('urls.index')
                ->withSuccess('Url deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->back(500)->withErrors($th->getMessage());
        }
    }
}
