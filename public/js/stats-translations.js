new Chart(document.getElementById('translationsPerDay'), {
    type: 'bar',
    data: {
        labels: Object.keys(translationsPerDay),
        datasets: [{
            label: 'Number of strings',
            backgroundColor: '#39B54A',
            data: Object.values(translationsPerDay)
        }]
    },
});