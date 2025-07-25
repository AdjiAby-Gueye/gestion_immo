<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */

    'paths' => [
        resource_path('views'),
    ],
    'uploads' => [
        'users' => 'uploads/users',
        'appartements' => 'uploads/appartements',
        'categorieinterventions' => 'uploads/categorieinterventions',
        'compositions' => 'uploads/compositions',
        'etatlieu_pieces' => 'uploads/etatlieu_pieces',
        'etatlieus' => 'uploads/etatlieu_pieces',
        'contrats' => 'uploads/contrats',
        'demandeinterventions' => 'uploads/demandeinterventions',
        'documents' => 'uploads/documents',
        'contratprestations' => 'uploads/contratprestations',
        'versementloyers' => 'uploads/versementloyers',
        'versementchargecoproprietes' => 'uploads/versementchargecoproprietes',
        'obligationadministratives' => 'uploads/obligationadministratives',
        'assurances' => 'uploads/assurances',
        'locataires' => 'uploads/locataires',
        'factures' => 'uploads/factures',
        'cautions' => 'uploads/cautions',
        'demanderesiliations' => 'uploads/demanderesiliations',
        'societefacturations' => 'uploads/societefacturations',
        'entites' => 'uploads/entites',
        'bes' => 'uploads/bes',
        'produits' => 'uploads/produits',
        'categorieproduits' => 'uploads/categorieproduits',
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the storage
    | directory. However, as usual, you are free to change this value.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

];
