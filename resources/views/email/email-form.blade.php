<x-layouts.app title="Enviar E-mail">
    <div class="flex h-screen justify-center items-center">
        <!-- Formulário de Envio de E-mail -->
        <div
            class="w-full max-w-4xl p-6 bg-white dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700 rounded-xl">
            <h2 class="text-2xl font-semibold mb-4 text-center">Enviar E-mail</h2>
            <form id="emailForm" class="space-y-4">
                @csrf

                <!-- Campo de E-mails -->
                <flux:field class="mb-4">
                    <flux:label>E-mails do destinatário (separados por vírgula)</flux:label>
                    <flux:input id="emails" name="emails" type="text"
                        placeholder="exemplo1@gmail.com, exemplo2@gmail.com" class="w-full p-3 border rounded-md"
                        required />
                    <flux:error name="emails" />
                </flux:field>

                <!-- Campo de Assunto -->
                <flux:field class="mb-4">
                    <flux:label>Assunto</flux:label>
                    <flux:input id="subject" name="subject" type="text" placeholder="Assunto do E-mail"
                        class="w-full p-3 border rounded-md" required />
                    <flux:error name="subject" />
                </flux:field>

                <!-- Campo de Mensagem -->
                <flux:field class="mb-4">
                    <flux:label>Mensagem</flux:label>
                    <flux:textarea id="mensagem" name="mensagem" rows="6" class="w-full p-3 border rounded-md"
                        placeholder="Digite sua mensagem" required></flux:textarea>
                    <flux:error name="mensagem" />
                </flux:field>

                <!-- Botão de Enviar -->
                <flux:button type="submit" class="w-full bg-blue-500 text-white py-3 px-4 rounded-md hover:bg-blue-600">
                    Enviar
                </flux:button>
            </form>
        </div>
    </div>

    <!-- Modal de Spinner -->
    <div id="spinnerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-lg">
            <div class="flex items-center space-x-4">
                <!-- Spinner -->
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                <span class="text-lg">Enviando e-mails...</span>
            </div>
        </div>
    </div>

    <!-- Script para controlar o spinner e enviar o formulário via AJAX -->
    <script>
        document.getElementById('emailForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Impede o envio padrão do formulário

            // Mostra o spinner
            document.getElementById('spinnerModal').classList.remove('hidden');

            // Captura os dados do formulário
            const formData = new FormData(this);

            // Envia os dados via AJAX
            fetch("{{ route('enviar.email') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Adiciona o token CSRF
                },
            })
                .then(response => response.json()) // Converte a resposta para JSON
                .then(data => {
                    // Esconde o spinner após o envio ser concluído
                    document.getElementById('spinnerModal').classList.add('hidden');

                    // Exibe uma mensagem de sucesso ou erro
                    if (data.success) {
                        alert(data.message); // E-mails enviados com sucesso
                    } else {
                        alert(data.message + '\nErros: ' + (data.errors ? data.errors.join('\n') : ''));
                    }
                })
                .catch(error => {
                    // Esconde o spinner em caso de erro na requisição
                    document.getElementById('spinnerModal').classList.add('hidden');
                    alert('Erro na requisição: ' + error.message);
                });
        });
    </script>
</x-layouts.app>