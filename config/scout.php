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

    'driver' => env('SCOUT_DRIVER', 'meilisearch'),

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
    | Meilisearch Configuration
    |--------------------------------------------------------------------------
    |
    | Qui definiamo i parametri di connessione e, soprattutto, le regole
    | di filtraggio e ordinamento per i tuoi Attori.
    |
    */

    'meilisearch' => [
        'host' => env('MEILISEARCH_HOST', 'http://localhost:7700'),
        'key' => env('MEILISEARCH_KEY', null),

        // Questo è il cuore della configurazione per i filtri avanzati
        'index-settings' => [

            \App\Models\Profile::class => [

                // CAMPI SU CUI PUOI FILTRARE (WHERE, IN, ecc.)
                // Devono corrispondere alle chiavi che restituisci in toSearchableArray() nel Model
                'filterableAttributes' => [
                    'id',
                    'gender',       // Uomo/Donna
                    'age',          // Età (calcolata)
                    'height',       // Altezza
                    'eye_color',    // Occhi
                    'hair_color',   // Capelli
                    'ethnicity',    // Etnia
                    'skills',       // Array di skills (es. Scherma)
                    'languages',    // Array di lingue
                    'city',         // Per filtro local hire
                    'country',
                    'is_represented', // Ha un agente?
                ],

                // CAMPI SU CUI PUOI ORDINARE (ORDER BY)
                'sortableAttributes' => [
                    'created_at',   // I più recenti
                    'age',          // Dal più giovane
                    'height',       // Dal più alto
                ],

                // (Opzionale) CAMPI IN CUI CERCARE IL TESTO
                // Meilisearch cerca in tutti i campi stringa di default,
                // ma puoi limitarlo per migliorare la pertinenza.
                'searchableAttributes' => [
                    'name',
                    'stage_name',
                    'skills',
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
