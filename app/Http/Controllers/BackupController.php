<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Models\Auditoria;

class BackupController extends Controller
{
    public function index()
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403);
        }
        return view('respaldos.index');
    }

    public function create()
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403);
        }

        try {
            $filename = "backup-" . date('Y-m-d-H-i-s') . ".sql";
            $path = storage_path("app/public/" . $filename);
            

            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password'); 
            

            $mysqldumpPath = "C:/xampp/mysql/bin/mysqldump.exe";
            


            $passwordPart = $dbPass ? "-p\"$dbPass\"" : "";
            


            
            $command = "\"$mysqldumpPath\" --user=\"$dbUser\" $passwordPart \"$dbName\" > \"$path\"";


            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {

                $commandFallback = "mysqldump --user=\"$dbUser\" $passwordPart \"$dbName\" > \"$path\"";
                exec($commandFallback, $output, $returnVar);
                
                if ($returnVar !== 0) {
                    throw new \Exception("Error al generar el respaldo. Código de salida: $returnVar");
                }
            }


            Auditoria::create([
                'id_usuario' => Auth::id(),
                'accion' => 'Generación de Respaldo de Base de Datos',
                'ip' => request()->ip(),
            ]);

            return response()->download($path)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el respaldo: ' . $e->getMessage());
        }
    }
}
