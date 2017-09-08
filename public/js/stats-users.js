new Chart(document.getElementById('usersPerDay'), {
    type: 'bar',
    data: {
        labels: Object.keys(usersPerDay),
        datasets: [{
            label: 'Number of users',
            backgroundColor: '#39B54A',
            data: Object.values(usersPerDay)
        }]
    },
});