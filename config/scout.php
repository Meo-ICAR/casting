<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Search Engine
    |--------------------------------------------------------------------------
    |
    | Supported: "algolia", "meilisearch", "typesense",
    |            "database", "collection", "null"
    |
    */

    'driver' => env('SCOUT_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Index Prefix
    |--------------------------------------------------------------------------
    |
    | Qui puoi impostare un prefisso per i tuoi indici. Utile se usi la stessa
    | istanza di Meilisearch per più applicazioni (es: casting_prod_profiles).
    |
    */

    'prefix' => env('SCOUT_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Queue Data Syncing
    |--------------------------------------------------------------------------
    |
    | IMPORTANTE: Impostalo a true. In questo modo, quando salvi un profilo,
    | Laravel non aspetta Meilisearch, ma mette l'operazione in coda.
    | Rende l'app molto più veloce per chi inserisce i dati.
    |
    */

    'queue' => true,

    /*
    |--------------------------------------------------------------------------
    | Chunk Sizes
    |--------------------------------------------------------------------------
    |
    | Quanti record importare alla volta quando lanci "scout:import".
    |
    */

    'chunk' => [
        'searchable' => 500,
        'unsearchable' => 500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Soft Deletes
    |--------------------------------------------------------------------------
    */

    'soft_delete' => false,

    /*
    |--------------------------------------------------------------------------
    | Identify User
    |--------------------------------------------------------------------------
    */

    'identify' => env('SCOUT_IDENTIFY', false),

    /*
    |--------------------------------------------------------------------------
                    'languages',
                    'bio' // Se hai aggiunto una bio testuale
                ],
            ],

            // Se in futuro vorrai cercare anche tra i Progetti:
            // \App\Models\Project::class => [ ... ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Database / Collection / Algolia Drivers
    |--------------------------------------------------------------------------
    |
    | Configurazioni standard lasciate per compatibilità.
    |
    */

    'database' => [
        'table' => 'job_batches',
        'column' => 'batch',
    ],

    'algolia' => [
        'id' => env('ALGOLIA_APP_ID', ''),
        'secret' => env('ALGOLIA_SECRET', ''),
    ],

];
