<div>
    <form wire:submit.prevent="saveSettings">
        @csrf

        <!-- Seleção de Protocolo -->
        <flux:field class="mt-4">
            <flux:label>Protocolo</flux:label>
            <flux:select wire:model="mailer" class="border border-gray-300 p-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="smtp">SMTP</option>
                <option value="pop3">POP3</option>
                <option value="imap">IMAP</option>
                <option value="mapi">MAPI</option>
                <option value="http">HTTP</option>
                <option value="xmpp">XMPP</option>
                <option value="jmap">JMAP</option>
            </flux:select>
        </flux:field>

        <!-- Campos para Host, Porta, Usuário, etc. -->
        <flux:field class="mt-4">
            <flux:label>Host</flux:label>
            <flux:input type="text" wire:model="host" class="border border-gray-300 p-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </flux:field>

        <flux:field class="mt-4">
            <flux:label>Porta</flux:label>
            <flux:input type="number" wire:model="port" class="border border-gray-300 p-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </flux:field>

        <flux:field class="mt-4">
            <flux:label>Usuário</flux:label>
            <flux:input type="text" wire:model="username" class="border border-gray-300 p-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </flux:field>

        <flux:field class="mt-4">
            <flux:label>Nome do Remetente</flux:label>
            <flux:input type="text" wire:model="from_name" class="border border-gray-300 p-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </flux:field>

        <flux:field class="mt-4">
            <flux:label>E-mail do Remetente</flux:label>
            <flux:input type="email" wire:model="from_address" class="border border-gray-300 p-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </flux:field>

        <flux:field class="mt-4">
            <flux:label>Senha de app</flux:label>
            <flux:input type="password" wire:model="password" class="border border-gray-300 p-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </flux:field>

        <!-- Botão de Salvar -->
        <flux:button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
            Salvar Configuração
        </flux:button>
    </form>

    
</div>


