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
    <ul class="space-y-2">
        @foreach ($latestEmails as $email)
            <li class="text-gray-700 dark:text-gray-300">
                <strong>Assunto:</strong> {{ $email->subject }} <br>
                <strong>Email enviado:</strong> {{ $email->email }} <br>
                <strong>Enviado em:</strong> {{ $email->formatted_date }} <br>
                <!-- Adiciona o botão de exclusão -->
                <form action="{{ route('dashboard.delete', $email->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700">Excluir</button>
                </form>
            </li>
        @endforeach
    </ul>


    <!-- Chart.js -->
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
        });
    </script>

</x-layouts.app>