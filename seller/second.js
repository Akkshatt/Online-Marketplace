<script>
document.addEventListener("DOMContentLoaded", function () {
    // Fetch data from the server (you may need to adjust the URL and data format based on your server response)
    fetch('end_point.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Data retrieved successfully:", data);

            if (!data || !data.dates || !data.quantities || data.dates.length !== data.quantities.length) {
                console.error("Invalid data format or missing data.");
                return;
            }

            // Create a Chart.js line chart
            var ctx = document.getElementById('ordersChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.dates,
                    datasets: [{
                        label: 'Number of Products Ordered',
                        data: data.quantities,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        x: [{
                            type: 'time',
                            time: {
                                unit: 'day',
                                displayFormats: {
                                    day: 'MMM D'
                                }
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Date'
                            }
                        }],
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Products'
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        })
});

</script>
