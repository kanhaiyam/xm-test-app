<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Mail\UserMail;
use App\Models\Symbol;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class HistoricalData extends Controller
{
    /**
     * Show the index page upon first request.
     */
    public function index(): View
    {
        return view('history');
    }

    /**
     * Get user input and show the historical
     * data according to the users query
     */
    public function show(Request $request): View
    {
        $validatedData = $request->validate([
            'symbol' => ['required', 'exists:sqlite.symbols,symbol', 'max:5', 'min:1'],
            's_date' => ['required', 'date_format:m/d/Y', 'before_or_equal:today', 'before_or_equal:e_date'],
            'e_date' => ['required', 'date_format:m/d/Y', 'after_or_equal:s_date', 'before_or_equal:today'],
            'email' => ['required', 'email'],
        ]);
        $request->flash();

        $symbol = Symbol::where('symbol', $validatedData['symbol'])->first();

        $startDate = Carbon::parse($validatedData['s_date'])->getTimestamp();
        $endDate = Carbon::parse($validatedData['e_date'])->getTimestamp();
        $responseFromAPI = Helper::getHistoricalData(['symbol' => strtoupper($validatedData['symbol'])]);
        $tableData = &$responseFromAPI['response'];
        if (200 === $responseFromAPI['http_response_code']) {
            Helper::formatDataForTable($responseFromAPI['response'], $startDate, $endDate);
        }

        //Send mail via queue
        $mailData = Helper::prepareMailContents($symbol, $validatedData);
        Mail::to($validatedData['email'])->queue(new UserMail($mailData));

        $firstLine = $symbol->s_name.' - '.$validatedData['s_date'].' to '.$validatedData['e_date'];

        return view('history', compact('firstLine', 'tableData'));
    }
}
