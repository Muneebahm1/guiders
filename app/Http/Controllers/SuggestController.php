<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use Illuminate\Http\Request;

class SuggestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:counselor');
    }

    public function index()
    {
        return view('suggest.index');
    }

    public function search(Request $request)
    {
        $term = $request->query('q', '');
        $budget = $request->query('budget');

        $opportunities = Opportunity::query()
            ->when($term, function ($query) use ($term) {
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('country', 'like', "%{$term}%")
                        ->orWhere('requirements', 'like', "%{$term}%");
                });
            })
            ->when($budget, fn ($query) => $query->where('cost', '<=', $budget))
            ->orderBy('cost')
            ->get();

        return view('suggest._results', compact('opportunities'));
    }
}
