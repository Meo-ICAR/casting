<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white shadow-sm rounded-xl p-8 md:p-12 border border-gray-200">

            <header class="border-b border-gray-100 pb-8 mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Informativa sulla Privacy</h1>
                <p class="text-sm text-gray-500">Ultimo aggiornamento: {{ date('d/m/Y') }}</p>
            </header>

            <section class="space-y-6 leading-relaxed">

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">1. Titolare del Trattamento</h2>
                    <p>Il titolare del trattamento dei dati è <strong>{{ config('app.name') }}</strong>, con sede legale in [Tuo Indirizzo], Email: [Tua Email].</p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">2. Tipi di Dati raccolti</h2>
                    <p>Fra i Dati Personali raccolti da questa Applicazione, in modo autonomo o tramite terze parti, ci sono: Nome, Cognome, Email, Cookie e Dati di utilizzo.</p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">3. Modalità e luogo del trattamento</h2>
                    <p>Il Titolare adotta le opportune misure di sicurezza volte ad impedire l’accesso, la divulgazione, la modifica o la distruzione non autorizzate dei Dati Personali.</p>
                    <p class="mt-2">I Dati sono trattati presso le sedi operative del Titolare ed in ogni altro luogo in cui le parti coinvolte nel trattamento siano localizzate.</p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">4. Base giuridica del trattamento</h2>
                    <p>Il Titolare tratta Dati Personali relativi all’Utente in caso sussista una delle seguenti condizioni:</p>
                    <ul class="list-disc ml-6 mt-2 space-y-1">
                        <li>l’Utente ha prestato il consenso per una o più finalità specifiche;</li>
                        <li>il trattamento è necessario all’esecuzione di un contratto con l’Utente;</li>
                        <li>il trattamento è necessario per adempiere un obbligo legale.</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">5. Diritti dell’Utente (GDPR)</h2>
                    <p>Gli Utenti possono esercitare determinati diritti con riferimento ai Dati trattati dal Titolare, tra cui il diritto di:</p>
                    <ul class="list-disc ml-6 mt-2 space-y-1">
                        <li>revocare il consenso in ogni momento;</li>
                        <li>opporsi al trattamento dei propri Dati;</li>
                        <li>accedere ai propri Dati;</li>
                        <li>verificare e chiedere la rettificazione;</li>
                        <li>ottenere la cancellazione o rimozione dei propri Dati Personali.</li>
                    </ul>
                </div>

            </section>

            <footer class="mt-12 pt-8 border-t border-gray-100 text-center">
                <a href="{{ url('/') }}" class="text-primary-600 hover:text-primary-500 font-medium">
                    &larr; Torna alla Home
                </a>
            </footer>

        </div>
    </div>

</body>
</html>
