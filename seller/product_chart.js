const colors = [
    'rgba(255, 99, 132, 0.2)',
    'rgba(255, 159, 64, 0.2)',
    'rgba(255, 205, 86, 0.2)',
    'rgba(75, 192, 192, 0.2)',
    'rgba(54, 162, 235, 0.2)',
    'rgba(153, 102, 255, 0.2)',
    'rgba(201, 203, 207, 0.2)'
];

fetch('get_product_data.php')
    .then(response => response.json())
    .then(data => {
      
        const titles = data.map(item => item.title);
        const quantities = data.map(item => item.quantity);

        // Create the chart using the extracted data and predefined colors
        createChart(titles, quantities, colors);
    })
    .catch(error => console.error('Error fetching product data:', error));

// Function to create the Chart.js chart
function createChart(titles, quantities, colors) {
    const options = {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: titles,
            datasets: [{
                label: 'bar',
                data: quantities,
                backgroundColor: colors,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
            }]
        },
        options: options
    });
}
