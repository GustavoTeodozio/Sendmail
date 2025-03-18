<x-layouts.app title="Enviar E-mail">
    <div class="flex h-screen justify-center items-center"> <!-- Centraliza o conteúdo na tela -->
        <!-- Formulário de Envio de E-mail -->
        <div
            class="w-full max-w-4xl p-6 bg-white dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700 rounded-xl">
            <h2 class="text-2xl font-semibold mb-4 text-center">Enviar E-mail</h2>
            <form action="{{ route('enviar.email') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Campo de E-mails -->
                <flux:field class="mb-4">
                    <flux:label>E-mails do destinatário (separados por vírgula)</flux:label>
                    <flux:input wire:model="emails" name="emails" type="text"
                        placeholder="exemplo1@gmail.com, exemplo2@gmail.com" class="w-full p-3 border rounded-md"
                        required />
                    <flux:error name="emails" />
                </flux:field>

                <!-- Campo de Assunto -->
                <flux:field class="mb-4">
                    <flux:label>Assunto</flux:label>
                    <flux:input wire:model="subject" name="subject" type="text" placeholder="Assunto do E-mail"
                        class="w-full p-3 border rounded-md" required />
                    <flux:error name="subject" />
                </flux:field>

                <!-- Campo de Mensagem -->
                <flux:field class="mb-4">
                    <flux:label>Mensagem</flux:label>
                    <flux:textarea wire:model="mensagem" name="mensagem" rows="6" class="w-full p-3 border rounded-md"
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
</x-layouts.app>