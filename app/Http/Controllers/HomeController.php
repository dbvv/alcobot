<?php

namespace App\Http\Controllers;

use AdminSection;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\TelegramUsers;
use App\ImportTrait;
use Log;
use Session;

class HomeController extends Controller
{
    use ImportTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        Log::info('HomeController');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function adminHome(Request $request) {
        $content = "Bla bla bla";
        return AdminSection::view($content, 'Dashboard');
    }

    public function import(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);
        Log::info("Beginn import");

        $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
        $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
        $this->importProducts(storage_path("app/public/$filePath") );
        Session::put("info_message", 'Импорт прошел успешно!');
        return back()->with(['success' => 'Файл загруже']);
    }

}
