window.addEventListener('load', function() {
    const canvas = document.getElementById('appointments-chart');
    const id = canvas.getAttribute('data-id');
    const apiUrl = '/stat/' + id;


    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            const appointmentsData = data.appointmentsData;

            const chartData = {
                labels: appointmentsData.map(d => d.date),
                datasets: [
                    {
                        label: 'Number of Appointments',
                        data: appointmentsData.map(d => d.count),
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }
                ]
            };

            const chartConfig = {
                type: 'line',
                data: chartData
            };

            const chartContainer = document.getElementById('appointments-chart');
            const appointmentsChart = new Chart(chartContainer, chartConfig);
        })
        .catch(error => console.error('Error fetching data:', error));


});