<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = \Auth::user()->id;
        $user = \App\User::with('files')->find($userId);//passo arquivos associados ao usuario logado
        
        
        return view('home', compact('user'));
    }

    public function upload()
    {
        //Request related
        $file = \Request::file('arquivo');
        $date = date('Y-m-d H:i:s');
        $userId = \Request::get('userId');

        //Storage related
        $storagePath = storage_path().'/documentos/'.$userId;
        $fileName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileNameHash = md5($fileName.'_'.$userId.'_'.$date).'.'.$extension;

        //Database related
        $fileModel = new \App\File();
        $fileModel->name = $fileNameHash;

        $user = \App\User::find($userId);
        $user->files()->save($fileModel);

        return $file->move($storagePath, $fileNameHash);
    }

    public function download($userId, $fileId)
    {
        $file = \App\File::find($fileId);
        $storagePath = storage_path().'/documentos/'.$userId;
        return \Response::download($storagePath.'/'.$file->name);
    }

    public function destroy($userId, $fileId)
    {
        $file = \App\File::find($fileId);
        $storagePath = storage_path().'/documentos/'.$userId;

        $file->delete();//remove do banco de dados o nome do arquivo

        unlink($storagePath.'/'.$file->name);
        
        return redirect()->back()->with('success', 'Arquivo removido com sucesso!');
    }
}
