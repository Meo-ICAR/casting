<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Policy - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white shadow-sm rounded-xl p-8 md:p-12 border border-gray-200">

            <header class="border-b border-gray-100 pb-8 mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Cookie Policy</h1>
                <p class="text-sm text-gray-500">Ultimo aggiornamento: {{ date('d/m/Y') }}</p>
            </header>

            <section class="space-y-6 leading-relaxed">

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Cosa sono i Cookie</h2>
                    <p>I cookie sono piccoli file di testo che i siti visitati dall'utente inviano al suo terminale, dove vengono memorizzati per essere poi ritrasmessi agli stessi siti alla visita successiva.</p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Cookie Tecnici (Necessari)</h2>
                    <p>Questa applicazione utilizza cookie tecnici per garantire il corretto funzionamento del sistema di autenticazione (Filament/Laravel) e la sicurezza della sessione. Senza questi cookie, il sito non potrebbe funzionare correttamente.</p>

                    <table class="min-w-full mt-4 text-sm border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 border-b text-left">Nome</th>
                                <th class="px-4 py-2 border-b text-left">Scopo</th>
                                <th class="px-4 py-2 border-b text-left">Durata</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-2 border-b font-mono">XSRF-TOKEN</td>
                                <td class="px-4 py-2 border-b">Protezione contro attacchi Cross-Site Request Forgery.</td>
                                <td class="px-4 py-2 border-b">Sessione</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 border-b font-mono">laravel_session</td>
                                <td class="px-4 py-2 border-b">Identificativo della sessione per mantenere l'accesso.</td>
                                <td class="px-4 py-2 border-b">2 ore (default)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Cookie di Terze Parti</h2>
                    <p>Al momento non utilizziamo cookie di profilazione o di tracciamento di terze parti (come Google Analytics o Facebook Pixel). Se dovessimo aggiungerli in futuro, questa informativa verrà aggiornata e ti verrà richiesto il consenso esplicito.</p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Come disabilitare i Cookie</h2>
                    <p>Puoi gestire le preferenze relative ai cookie direttamente all'interno del tuo browser. Disabilitando i cookie tecnici, tuttavia, non potrai effettuare il login al pannello amministrativo.</p>
                </div>

            </section>

            <footer class="mt-12 pt-8 border-t border-gray-100 text-center">
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-900 font-medium">&larr; Home</a>
                    <span class="hidden sm:inline text-gray-300">|</span>
                    <a href="{{ route('privacy') }}" class="text-blue-600 hover:text-blue-500 font-medium">Privacy Policy</a>
                </div>
            </footer>

        </div>
    </div>

</body>
</html>
