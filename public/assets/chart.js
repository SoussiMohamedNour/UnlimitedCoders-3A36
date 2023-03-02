window.addEventListener('load', function() {
    const canvas = document.getElementById('appointments-chart');
    const id = canvas.getAttribute('data-id');
    const apiUrl = '/stat/' + id;

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            const appointmentsData = data.appointmentsData;

            const chartConfig = chartjs_chart_config({
                type: 'line',
                data: {
                    labels: appointmentsData.map(d => d.date),
                    datasets: [
                        {
                            label: 'Number of Appointments',
                            data: appointmentsData.map(d => d.numberOfAppointments),
                            borderColor: 'rgb(75, 192, 192)',
                            tension: 0.1
                        }
                    ]
                }
            });

            new Chart(canvas, chartConfig);
        })
        .catch(error => console.error('Error fetching data:', error));
});
