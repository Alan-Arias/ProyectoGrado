<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\especiecontroller;
use App\Http\Controllers\PropietarioController;
use App\Http\Controllers\VacunaController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HistorialSanitarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\RegistroCambioController;
use App\Http\Controllers\CensistaController;
use App\Http\Controllers\PanelController;

    Route::get('/Especies', 'App\Http\Controllers\especiecontroller@index');    
    Route::post('/Especies/RegistrarEspecie', [especiecontroller::class, 'store']);
    Route::post('/Especies/ActualizarEspecie/{id}', [especiecontroller::class, 'update'])->name('especies.update');
    Route::post('/Razas/ActualizarRaza/{id}', [especiecontroller::class, 'updateRaza'])->name('razas.update');    

    Route::get('/Razas/AgregarRaza/{id}', [especiecontroller::class, 'mostrarAgregarRaza']);
    Route::get('/CensoRazas/AgregarRaza/{id}', [especiecontroller::class, 'CensomostrarAgregarRaza']);
    Route::post('/Razas/RegistrarRaza', [especiecontroller::class, 'registrarRaza']);
    
    Route::get('/Propietarios', 'App\Http\Controllers\PropietarioController@index');
    Route::post('/Propietarios/RegistrarPropietario', [PropietarioController::class, 'store']);
    Route::get('/cambiar-propietario/{id}', [AnimalController::class, 'cambiarPropietario'])->name('cambiar.propietario');
    Route::post('/cambiar-propietario/{id}', [AnimalController::class, 'actualizarPropietario'])->name('actualizar.propietario');
    Route::get('/historial-propietario/{id}', [RegistroCambioController::class, 'verHistorial'])->name('historial.propietario');


    Route::get('/Vacunas', 'App\Http\Controllers\VacunaController@index');
    Route::get('/Vacunas/TipoVacunas', 'App\Http\Controllers\VacunaController@indexTipo');
    Route::get('/vacunas/tipo-vacuna/modal', [VacunaController::class, 'getTipoVacunaModal'])->name('modal.tipo_vacuna.page');
    Route::post('/Vacunas/RegistrarVacunas', [VacunaController::class, 'store']);
    Route::post('/Vacunas/RegistrarTipos', [VacunaController::class, 'storeTipo']);
    Route::get('/vacuna/create', [VacunaController::class, 'create'])->name('vacuna.create');
    Route::post('/vacuna/store', [VacunaController::class, 'storeVacuna'])->name('vacuna.store');


    Route::get('/Animales', 'App\Http\Controllers\AnimalController@index');
    Route::get('/Animales/HistorialPropietarios', 'App\Http\Controllers\AnimalController@indexHistorialPropietarios');
    Route::get('/Animales/TipoAnimal', 'App\Http\Controllers\AnimalController@indexTipoAnimal');
    Route::get('/Animales/FormaAdquisicion', 'App\Http\Controllers\AnimalController@indexFormaAdquisicion');
    Route::get('/Animales/Incapacidad', 'App\Http\Controllers\AnimalController@indexIncapacidad');
    Route::post('/Animales/RegistrarAnimal', [AnimalController::class, 'store']);
    Route::post('/Animales/RegistrarTipoAnimal', [AnimalController::class, 'storeTipoAnimal']);
    Route::post('/Animales/RegistrarFormaAdquisicion', [AnimalController::class, 'storeFormaAdquisicion']);
    Route::post('/Animales/RegistrarIncapacidad', [AnimalController::class, 'storeIncapacidad']);
    Route::get('/buscar', [AnimalController::class, 'buscar'])->name('buscarAnimales');


    Route::get('/CensoAnimales', [CensistaController::class, 'create']);
    Route::post('/CensoAnimales', [CensistaController::class, 'store']);
    Route::post('/logout-censista', [CensistaController::class, 'logoutCensista'])->name('logoutCensista');

    Route::get('/CensoAnimales/Animales', 'App\Http\Controllers\AnimalController@CensoAnimales');
    Route::get('/propietarios/ajax', [AnimalController::class, 'getPropietariosAjax']);
    Route::get('/CensoAnimales/CensoIndex', 'App\Http\Controllers\AnimalController@CensoIndex');
    Route::get('/CensoAnimales/Otb', 'App\Http\Controllers\AnimalController@CensoAnimalesOtb');    
    Route::get('/CensoAnimales/Especies', 'App\Http\Controllers\AnimalController@CensoAnimalesMostrarEspecie');
    Route::get('/CensoAnimales/Propietarios', 'App\Http\Controllers\AnimalController@CensoAnimalesPropietariosIndex');
    Route::get('/CensoAnimales/Enfermedades', 'App\Http\Controllers\AnimalController@CensoAnimalesEnfermedadesIndex');
    Route::get('/buscar-animales', [AnimalController::class, 'buscarAnimales'])->name('buscar.animales');
    Route::get('/CensoAnimales/ListaEnfermedades', 'App\Http\Controllers\AnimalController@CensoAnimalesListaEnfermedadesIndex');
    Route::post('/CensoAnimales/RegistrarAnimal', 'App\Http\Controllers\AnimalController@CensoAnimalesStore');
    Route::post('/CensoAnimales/RegistrarCenso', 'App\Http\Controllers\AnimalController@CensoStore');
    Route::post('/CensoAnimales/RegistrarOtb', 'App\Http\Controllers\AnimalController@CensoAnimalesOtbStore');
    Route::post('/CensoAnimales/RegistrarPropietario', 'App\Http\Controllers\AnimalController@CensoAnimalesPropietariosStore');    
    Route::post('/CensoAnimales/RegistrarEnfermedad', 'App\Http\Controllers\AnimalController@CensoAnimalesEnfermedadesStore');    
    Route::post('/CensoAnimales/ListaEnfermedadesRegistrar', 'App\Http\Controllers\AnimalController@CensoAnimalesListaEnfermedadesStore');


    Route::get('/HistorialSanitario', 'App\Http\Controllers\HistorialSanitarioController@index');
    Route::post('/HistorialSanitario/Registrar', [HistorialSanitarioController::class, 'store']);
    Route::get('/historial/{id}', [HistorialSanitarioController::class, 'show'])->name('historial.show');
    
    Route::get('/Tratamiento', 'App\Http\Controllers\TratamientoController@index');
    Route::post('/Tratamiento/RegistrarTratamiento', [TratamientoController::class, 'store']);

    Route::get('/Usuarios', 'App\Http\Controllers\UsuarioController@index');
    Route::put('/Usuarios/CambiarEstado/{id}', [UsuarioController::class, 'cambiarEstado']);
    Route::get('/Usuarios/Rol', 'App\Http\Controllers\UsuarioController@indexRol');
    Route::get('/Usuarios/BuscarRol', [UsuarioController::class, 'indexRol']);
    Route::post('/Usuarios/RegistrarUsuario', [UsuarioController::class, 'store']);
    Route::get('/Usuarios/AgregarRol/{id}', [UsuarioController::class, 'agregarRol'])->name('usuarios.agregarRol');
    Route::post('/Usuarios/GuardarRol/{id}', [UsuarioController::class, 'guardarRol'])->name('usuarios.guardarRol');

    Route::get('/panel/reintentos', [PanelController::class, 'index'])->name('panel.reintentos');
    Route::post('/panel/reintentos/reset', [PanelController::class, 'reset'])->name('panel.reintentos.reset');
    Route::post('/panel/reintentos/cambiar-estado', [PanelController::class, 'cambiarEstado'])->name('panel.reintentos.estado');

    Route::get('/Reportes', [ReporteController::class, 'reporteIndex']);
    Route::get('/Reportes/Animal', [ReporteController::class, 'reporteAnimal'])->name('reporteAnimal');
    //login and logout
Route::post('/authenticate', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/Login', function () {
    return view('login.login');
})->name('login');

Route::get('/backup', [BackupController::class, 'index']);
Route::get('/backup/full', [BackupController::class, 'fullbackup'])->name('backup.full');
Route::get('/backup/partial', [BackupController::class, 'backup'])->name('backup.partial');
Route::get('/backup/{id}/download', [BackupController::class, 'download'])->name('backup.download');
Route::get('/animales/exportar', [AnimalController::class, 'exportExcel'])->name('animales.exportar');