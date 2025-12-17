<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termini e Condizioni - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white shadow-sm rounded-xl p-8 md:p-12 border border-gray-200">

            <header class="border-b border-gray-100 pb-8 mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Termini e Condizioni d'Uso</h1>
                <p class="text-sm text-gray-500">Ultimo aggiornamento: {{ date('d/m/Y') }}</p>
            </header>

            <section class="space-y-6 leading-relaxed">

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">1. Accettazione dei Termini</h2>
                    <p>L'accesso e l'utilizzo di <strong>{{ config('app.name') }}</strong> sono soggetti ai seguenti Termini e Condizioni. Registrandosi o utilizzando il servizio, l'utente conferma di aver letto, compreso e accettato di essere vincolato da questi termini.</p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">2. Descrizione del Servizio</h2>
                    <p>{{ config('app.name') }} fornisce una piattaforma gestionale basata su Laravel e Filament. Ci riserviamo il diritto di modificare, sospendere o interrompere il servizio in qualsiasi momento senza preavviso.</p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">3. Registrazione e Account</h2>
                    <p>L'utente è responsabile della protezione della propria password e di tutte le attività svolte tramite il proprio account. È vietato:</p>
                    <ul class="list-disc ml-6 mt-2 space-y-1">
                        <li>Fornire informazioni false o ingannevoli in fase di registrazione.</li>
                        <li>Utilizzare l'account di terzi senza autorizzazione.</li>
                        <li>Utilizzare il servizio per scopi illegali o non autorizzati.</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">4. Proprietà Intellettuale</h2>
                    <p>Tutti i contenuti, i marchi, il codice sorgente e la grafica presenti sulla piattaforma sono di proprietà esclusiva di <strong>{{ config('app.name') }}</strong> o dei suoi licenziatari e sono protetti dalle leggi sul diritto d'autore.</p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">5. Limitazione di Responsabilità</h2>
                    <p>Il servizio è fornito "così com'è" senza garanzie di alcun tipo. In nessun caso il Titolare sarà responsabile per danni diretti, indiretti o incidentali derivanti dall'uso o dall'impossibilità di usare il servizio.</p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">6. Legge Applicabile</h2>
                    <p>Questi termini sono regolati dalle leggi vigenti nella Repubblica Italiana. Qualsiasi controversia relativa a questi termini sarà soggetta alla giurisdizione esclusiva del Foro di Napoli.</p>
                </div>

            </section>

            <footer class="mt-12 pt-8 border-t border-gray-100 text-center">
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                        &larr; Home
                    </a>
                    <span class="hidden sm:inline text-gray-300">|</span>
                    <a href="{{ route('privacy') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                        Privacy Policy
                    </a>
                </div>
            </footer>

        </div>
    </div>

</body>
</html>
