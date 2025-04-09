<x-layouts.app title="Dashboard">
    <div class="p-4 bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">E-mails enviados hoje</h2>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ $emailsSent->where('date', now()->toDateString())->first()->count ?? 0 }}
        </p>
    </div>

    <!-- Gráfico -->
    <div class="mt-4 p-4 bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">E-mails enviados nos últimos 7 dias</h2>
        <select id="chartType" class="mb-2 p-2 border rounded">
            <option value="line">Linha</option>
            <option value="bar">Barra</option>
            <option value="pie">Pizza</option>
        </select>
        <canvas id="emailChart" style="max-height: 300px;"></canvas>
    </div>

    <!-- Últimos e-mails enviados -->
    <ul class="space-y-4">
        @foreach ($latestEmails as $email)
            <li class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <p class="text-gray-900 dark:text-gray-100 font-semibold">
                    📩 <span class="text-blue-600 dark:text-blue-400">{{ $email->subject }}</span>
                </p>
                <p class="text-gray-700 dark:text-gray-300">
                    <strong>Email enviado:</strong> {{ $email->email }}
                </p>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    ⏳ <strong>Enviado em:</strong> {{ $email->formatted_date }}
                </p>

                <form action="{{ route('dashboard.delete', $email->id) }}" method="POST" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-3 py-1 border border-red-500 text-red-500 rounded-lg bg-white hover:bg-red-500 hover:text-white transition">
                        ❌ Excluir
                    </button>
                </form>
            </li>
        @endforeach
    </ul>




    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        let chartType = 'line';
        const ctx = document.getElementById('emailChart').getContext('2d');

        // Transformar os dados para o formato que o Chart.js espera
        const labels = @json($emailsSent->pluck('formatted_date'));
        const data = @json($emailsSent->pluck('count'));

        const chartData = {
            labels: labels,
            datasets: [{
                label: 'E-mails Enviados',
                data: data,
                backgroundColor: ['rgba(54, 162, 235, 0.5)'],
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        // Função para criar o gráfico
        function createChart(type) {
            return new Chart(ctx, {
                type: type,
                data: chartData,
                options: {
                    responsive: true,
                    scales: type === 'pie' ? {} : {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        // Criar o gráfico inicialmente
        let emailChart = createChart(chartType);

        // Mudar o tipo do gráfico ao selecionar uma opção no dropdown
        document.getElementById('chartType').addEventListener('change', function (event) {
            chartType = event.target.value;

            // Destruir o gráfico anterior e criar um novo com o novo tipo
            emailChart.destroy();
            emailChart = createChart(chartType);
        });

    </script>


</x-layouts.app>