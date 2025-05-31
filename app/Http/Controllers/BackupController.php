<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Backup;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class BackupController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión primero.');
            return redirect('/Login');
        }
        $usuario = Auth::user();
        if (!in_array($usuario->tipo_user, ['Director General', 'Administrador'])) {
            session()->flash('error', 'Debes iniciar sesión como Director General o Administrador para acceder a los registros.');
            return redirect()->back();
        }
    
        // Si es Veterinario o Director General, continuar con la lógica
        $backups = Backup::orderBy('fecha_creacion', 'desc')->get();
        return view('backup.backup', compact('backups'));
    }
    public function fullbackup()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión primero.');
            return redirect('/Login');
        }
    
        // Obtener el usuario autenticado
        $usuario = Auth::user();
        $directorGeneral = DirectorGeneral::where('usuario_id', $usuario->id)->first();
        $admin = Administrador::where('usuario_id', $usuario->id)->first();
        if (!$directorGeneral && !$admin) {
            session()->flash('error', 'Debes iniciar sesión como Director General para acceder a los registros.');
            return redirect()->back(); // Redirigir a la página anterior
        }
    
        // Si es Veterinario o Director General, continuar con la lógica
        $databaseName = env('DB_DATABASE', 'default_database_name');
        $user = 'postgres';
        $password = '12345';
        $host = env('DB_HOST', '127.0.0.1');
    
        $dsn = "pgsql:host={$host};dbname={$databaseName}";
        $pdo = new \PDO($dsn, $user, $password);
    
        // Crear un archivo SQL para guardar el backup
        $backupFile = storage_path("app/{$databaseName}_backup_" . date('YmdHis') . ".sql");
        $handle = fopen($backupFile, 'w'); // Cambiado: ya no se utiliza $backupFilePath
    
        $tables = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'")->fetchAll(\PDO::FETCH_COLUMN);
    
        foreach ($tables as $table) {
            fwrite($handle, "DROP TABLE IF EXISTS \"{$table}\";\n");
    
            $createTableStmt = $this->getCreateTableStatement($pdo, $table);
            fwrite($handle, "{$createTableStmt}\n");
    
            $rows = $pdo->query("SELECT * FROM \"{$table}\"")->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $columns = implode(", ", array_map(function($col) { return "\"$col\""; }, array_keys($row)));
                $values = implode(", ", array_map([$pdo, 'quote'], array_values($row)));
                fwrite($handle, "INSERT INTO \"{$table}\" ({$columns}) VALUES ({$values});\n");
            }
            fwrite($handle, "\n");
        }
    
        fclose($handle);
    
        // Comprobar si el archivo se creó correctamente
        if (file_exists($backupFile)) {
            Backup::create([
                'nombre_archivo' => basename($backupFile),
                'ruta_archivo' => $backupFile,
                'tipo_respaldo' => 'Completo',
                'tamaño' => filesize($backupFile), // Cambiado: ahora se usa $backupFile directamente
                'fecha_creacion' => now(),
                'usuario_id' => session('user_id')
            ]);
            return back()->with('agregar', 'Se ha realizado el Full Backup Correctamente');
        } else {
            Log::error("El archivo de respaldo no se creó: {$backupFile}");
            return response()->json(['error' => 'No se pudo crear el archivo de respaldo'], 500);
        }
    }    

    private function getCreateTableStatement($pdo, $table)
    {
        $query = "SELECT column_name, data_type, is_nullable, column_default 
                  FROM information_schema.columns 
                  WHERE table_name = '{$table}' 
                  ORDER BY ordinal_position";
        
        $columns = $pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        $createTableStatement = "CREATE TABLE \"{$table}\" (";
        $columnDefs = [];

        foreach ($columns as $column) {
            $columnDef = "\"{$column['column_name']}\" {$column['data_type']}";

            if ($column['is_nullable'] === 'NO') {
                $columnDef .= " NOT NULL";
            }
            if (!is_null($column['column_default'])) {
                $columnDef .= " DEFAULT {$column['column_default']}";
            }

            $columnDefs[] = $columnDef;
        }

        $createTableStatement .= implode(", ", $columnDefs) . ");";

        return $createTableStatement;
    }
    public function backup()
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión primero.');
            return redirect('/Login'); // Redirigir sin abrir una nueva ventana
        }
    
        // Obtener el usuario autenticado
        $usuario = Auth::user();
    
        // Verificar si el usuario es un Director General
        $directorGeneral = DirectorGeneral::where('usuario_id', $usuario->id)->first();
        $admin = Administrador::where('usuario_id', $usuario->id)->first();
        // Si no es Director General, mostrar error
        if (!$directorGeneral && !$admin) {
            session()->flash('error', 'Debes iniciar sesión como Director General para acceder a los registros.');
            return redirect()->back(); // Redirigir a la página anterior
        }
    
        // Si es Veterinario o Director General, continuar con la lógica
        $databaseName = env('DB_DATABASE', 'default_database_name');
        $user = 'postgres';
        $password = '12345';
        $host = env('DB_HOST', '127.0.0.1');
        
        // Conectar a la base de datos
        $dsn = "pgsql:host={$host};dbname={$databaseName}";
        $pdo = new \PDO($dsn, $user, $password);
        
        // Crear un archivo SQL para guardar el backup
        $backupFile = storage_path("app/{$databaseName}_backup_" . date('YmdHis') . ".sql");
        $handle = fopen($backupFile, 'w');
    
        // Obtener todas las tablas de la base de datos
        $tables = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'")->fetchAll(\PDO::FETCH_COLUMN);
    
        // Filtrar tablas sin llaves foráneas
        $tablesWithoutForeignKeys = [];
        $tablesWithForeignKeys = [];
        
        foreach ($tables as $table) {
            $hasForeignKey = $pdo->query("
                SELECT COUNT(*) 
                FROM information_schema.table_constraints 
                WHERE table_name = '{$table}' AND constraint_type = 'FOREIGN KEY'
            ")->fetchColumn();
    
            if ($hasForeignKey) {
                $tablesWithForeignKeys[] = $table;
            } else {
                $tablesWithoutForeignKeys[] = $table;
            }
        }
    
        // Generar inserciones para tablas sin llaves foráneas
        foreach ($tablesWithoutForeignKeys as $table) {
            $rows = $pdo->query("SELECT * FROM \"{$table}\"")->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $columns = implode(", ", array_map(function($col) { return "\"$col\""; }, array_keys($row)));
                $values = implode(", ", array_map([$pdo, 'quote'], array_values($row)));
                fwrite($handle, "INSERT INTO \"{$table}\" ({$columns}) VALUES ({$values});\n");
            }
            fwrite($handle, "\n");
        }
    
        // Generar inserciones para tablas con llaves foráneas
        foreach ($tablesWithForeignKeys as $table) {
            $rows = $pdo->query("SELECT * FROM \"{$table}\"")->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $columns = implode(", ", array_map(function($col) { return "\"$col\""; }, array_keys($row)));
                $values = implode(", ", array_map([$pdo, 'quote'], array_values($row)));
                fwrite($handle, "INSERT INTO \"{$table}\" ({$columns}) VALUES ({$values});\n");
            }
            fwrite($handle, "\n");
        }
    
        fclose($handle);
    
        // Comprobar si el archivo se creó correctamente
        if (file_exists($backupFile)) {
            Backup::create([
                'nombre_archivo' => basename($backupFile),
                'ruta_archivo' => $backupFile,
                'tipo_respaldo' => 'Parcial',
                'tamaño' => filesize($backupFile),
                'fecha_creacion' => \Carbon\Carbon::now('America/La_Paz'),
                'usuario_id' => session('user_id')
            ]);  
            return back()->with('agregar', 'Se ha realizado el Backup Parcial Correctamente');                  
        } else {
            Log::error("El archivo de respaldo no se creó: {$backupFile}");
            return response()->json(['error' => 'No se pudo crear el archivo de respaldo'], 500);
        }
    }   
    public function download($id)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión primero.');
            return redirect('/Login'); // Redirigir sin abrir una nueva ventana
        }
    
        // Obtener el usuario autenticado
        $usuario = Auth::user();
    
        // Verificar si el usuario es un Director General
        $directorGeneral = DirectorGeneral::where('usuario_id', $usuario->id)->first();
        $admin = Administrador::where('usuario_id', $usuario->id)->first();
        // Si no es Director General, mostrar error
        if (!$directorGeneral && !$admin) {
            session()->flash('error', 'Debes iniciar sesión como Director General para acceder a los registros.');
            return redirect()->back(); // Redirigir a la página anterior
        }
    
        // Si es Veterinario o Director General, continuar con la lógica
        $backup = Backup::findOrFail($id);
        return response()->download($backup->ruta_archivo);
    }
    
}
