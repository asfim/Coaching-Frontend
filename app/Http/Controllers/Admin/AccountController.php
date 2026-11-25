<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = Account::query()->latest('date');

        if ($request->filled('type') && in_array($request->type, ['income', 'expense'])) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('reference', 'like', "%{$request->search}%");
            });
        }

        $accounts = $query->paginate(15);

        $totalIncome = Account::where('type', 'income')->sum('amount');
        $totalExpense = Account::where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        $monthlyIncome = Account::where('type', 'income')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $monthlyExpense = Account::where('type', 'expense')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $monthlyNet = $monthlyIncome - $monthlyExpense;
        $totalCount = Account::count();
        $incomeCount = Account::where('type', 'income')->count();
        $expenseCount = Account::where('type', 'expense')->count();

        return view('admin.accounts.index', compact(
            'accounts', 'totalIncome', 'totalExpense', 'netBalance',
            'monthlyIncome', 'monthlyExpense', 'monthlyNet',
            'totalCount', 'incomeCount', 'expenseCount'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:50',
            'date' => 'required|date',
            'reference' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        Account::create($validated);

        return redirect()->route('admin.accounts.index')->with('success', 'Transaction recorded successfully!');
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()?->canEdit('accounts')) {
            return redirect()->back()->with('error', 'You do not have permission to edit transactions.');
        }

        $account = Account::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:50',
            'date' => 'required|date',
            'reference' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $account->update($validated);

        return redirect()->route('admin.accounts.index')->with('success', 'Transaction updated successfully!');
    }

    public function destroy($id)
    {
        if (!auth()->user()?->canDelete('accounts')) {
            return redirect()->back()->with('error', 'You do not have permission to delete transactions.');
        }

        $account = Account::findOrFail($id);
        $account->delete();

        return redirect()->route('admin.accounts.index')->with('success', 'Transaction deleted successfully!');
    }
}
